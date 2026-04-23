<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once LIBPATH . 'parser/BaseParser.php';

/**
 * BaseCompanyParser.
 * @author Misha Karpenko
 * @link http://itirra.com
 */
class BaseCompanyParser extends BaseParser {
  
  /**
   * Geo code libarary.
   * 
   * @var GeoCode $geoCode
   */
  private $geoCode;
  private $geoCodeCacheTtl = 86400; // 24 hours
  const GEOCODE_LIMIT = 2250;
  const GEOCODE_CACHE_KEY = 'geocode_cache_key';
  
	/**
   * Constructor.
   */
  public function BaseCompanyParser() {
    parent::BaseParser();
    
    // GeoCode
    $this->ci->load->library('GeoCode');
    $this->geoCode = &$this->ci->geocode;
  }
  
  /**
   * (non-PHPdoc)
   * @see src/app/libraries/parser/BaseParser::isFinished()
   */
  public function isFinished() {
  	$isGeoCodeLimitReached = $this->getGeoCodeRequests() >= self::GEOCODE_LIMIT;
  	if ($isGeoCodeLimitReached) log_message('error', get_class() . ': parser stopped because reached Google daily geocode limit.');
  	return parent::isFinished() || $isGeoCodeLimitReached;
  }
  
  /**
   * Get city name from coords.
   *
   * @param array $coords
   * @return string city name
   */
  protected function getCityNameFromCoords($coords) {
    if (empty($coords)) return null;
    $cityName = null;
    foreach ($coords['address'] as $item) {
      $isCityInfo = false;
      if (isset($item['types']) && !empty($item['types'])) {
        $hasLocality = false;
        $hasPolitical = false;
        foreach ($item['types'] as $type) {
          if ($type == 'locality') $hasLocality = true;
          if ($type == 'political') $hasPolitical = true;
        }
        if ($hasLocality && $hasPolitical && isset($item['long_name'])) {
          $cityName = $item['long_name'];
          break; 
        }
      }
    }
    return $cityName;
  }
  
  /**
   * Get geocode cache array.
   * @return array
   */
  protected function getGeoCodeCacheArray() {
    $cacheArray = null;
    $isCached = $this->cache->is_cached(self::GEOCODE_CACHE_KEY, $this->cacheGroup);
    if ($isCached) {
      $cacheArray = unserialize($this->cache->get(self::GEOCODE_CACHE_KEY, $this->cacheGroup));
    }
    if (!isset($cacheArray) || (time() - $cacheArray['time'] > $this->geoCodeCacheTtl)) {
      $cacheArray = array('requests' => 0, 'time' => time());
      $this->cache->save(self::GEOCODE_CACHE_KEY, serialize($cacheArray), $this->cacheGroup);
    }
    return $cacheArray;
  }
  
  /**
   * Get geo code requests.
   * @return int
   */
  protected function getGeoCodeRequests() {
    $cacheArray = $this->getGeoCodeCacheArray();
    return $cacheArray['requests'];
  }
  
  /**
   * Increment geo code requests.
   */
  protected function incrementGeoCodeRequests() {
    $cacheArray = $this->getGeoCodeCacheArray();
    $cacheArray['requests'] += 1;
    $this->cache->save(self::GEOCODE_CACHE_KEY, serialize($cacheArray), $this->cacheGroup);
    log_message('info', __METHOD__ . ' GeoCode requests today: ' . $cacheArray['requests'] . '.');
  }

	/**
   * Add geo code.
   * $cacheArray = array(requests, time).
   *
   * @param array $item
   */
  protected function addGeoCode(&$item) {
    if (!isset($this->geoCode) || empty($item['original_address'])) return;
    
    if ($this->getGeoCodeRequests() <= self::GEOCODE_LIMIT) {
      try {
        // Get coords from Google
        $coords = $this->geoCode->get_coords($item['original_address']);
        
        $this->incrementGeoCodeRequests();        
        
        // Get lat_lng
        if (isset($coords['lat_lng'])) {
          $item['latitude'] = $coords['lat_lng']['lat'];
          $item['longitude'] = $coords['lat_lng']['lng'];
        }
        // Get city name
        if (!isset($item['city'])) {
          $cityFromCoords = $this->getCityNameFromCoords($coords);
          if ($cityFromCoords != null) $item['city'] = $cityFromCoords;
        }
      } catch (Exception $e) {}
    }
  }
  
  /**
   * Add city id. 
   *
   * @param array $item
   */
  protected function addCityId(&$item) {
    if (isset($item['city'])) {
      $cityTitle = array_pop_by_key($item, 'city');
      $city = ManagerHolder::get('City')->getOneWhere(array('title' => $cityTitle));
      if (!empty($city)) $item['city_id'] = $city['id'];
    }
  }
  
  /**
   * (non-PHPdoc)
   * @see BaseParser::postProcessItem()
   */
  protected function postProcessItem(&$item) {
    parent::postProcessItem($item);
    $this->addGeoCode($item);
    $this->addCityId($item);
  }
  
	/**
   * (non-PHPdoc)
   * @see BaseParser::parseItemData()
   */
  protected function parseItemData(DOMXPath $itemXPath) {
    $item = parent::parseItemData($itemXPath);
    if (isset($this->params['category_id'])) $item['category_id'] = $this->params['category_id'];
    if (isset($this->params['subcategory_id'])) $item['subcategory_id'] = $this->params['subcategory_id'];
    if (isset($this->params['city_id']) && !isset($item['city_id'])) $item['city_id'] = $this->params['city_id'];
    if (isset($this->params['owner_id'])) $item['owner_id'] = $this->params['owner_id'];
    return $item;
  }
  
}