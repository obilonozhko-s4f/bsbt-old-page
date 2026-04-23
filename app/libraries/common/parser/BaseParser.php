<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * BaseParser.
 * @author Misha Karpenko
 * @link http://itirra.com
 */
class BaseParser {

  /**
   * CodeIgniter.
   *
   * @var CI_Base
   */
  protected $ci;

  /** Curl config. */
  protected $curlConfig = array(
    CURLOPT_TIMEOUT => 30,
    CURLOPT_RETURNTRANSFER => true
  );
  protected $curlResourceConfig = array(
    CURLOPT_TIMEOUT => 30
  );
  
  /** Tidy config. See http://tidy.sourceforge.net/docs/quickref.html for details. */
  protected $tidyConfig = array(
   	'clean' => true,
    'bare' => true,
    'output-encoding' => 'utf-8',
    'output-xhtml' => true,
    'wrap' => 0,
    'hide-comments' => true
  );

  /**
   * Cache.
   *
   * @var Cache $cache
   */
  protected $cache;
  protected $cacheGroup = 'Parser';
  protected $cacheTtl = 3600; // In seconds

  /** Urls. */
  protected $baseUrl;
  
  /** Xpath queries. */
  protected $listQueries = array(
  	'item_container' => '',
  	'item_data' => array()
  );
  protected $itemQueries = array();
  protected $itemImageFields = array();
  
  /** Pagination. */
  const PAGE_MARKER = 'page';
  protected $listCurrentPage = 1;
  protected $listPageMax = 1;
  protected $listPagePattern;
  protected $listPageUrlSuffix;
  
  /** Finished flags. */
  protected $listIsEmpty = false;
  
  /**
   * Contains function names, for example:
   * 'key' => array('function1' => array('param2', 'param3'), 'function2').
   */
  protected $itemPostProcess = array();
  
  /** Upload dir. Don't forget trailing slash. */ 
  protected $uploadDir = 'parser/';

  /** Params (received from controller). */
  protected $params = array();
  
  /**
   * Constructor.
   */
  public function BaseParser() {
    $this->ci = &get_instance();

    // Cache
    $this->ci->load->library('base/Cache');
    $this->cache = &$this->ci->cache;
    
    // Fix upload dir
    $this->uploadDir = ManagerHolder::get('Resource')->getContentServerName() . '/' . $this->uploadDir;
  }
  
  
  //####################### URLS HANDLING AND DATA FETCHING ################################
  
	/**
   * Fix url.
   * 
   * @param string $url
   * @return string url
   */
  protected function fixUrl($url) {
    if (strpos($url, 'http://') === false) {
      $url = trim($url, '/');
      $url = $this->baseUrl . $url;
    }
    return $url;
  }

  /**
   * Prepare raw data.
   * Manage encoding, normalize and cleanup raw data.
   *
   * @param string $rawData
   */
  protected function prepareRawData(&$rawData) {
    // Remove javascript
    $rawData = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $rawData);
    
    // Tidy
    $tidy = new tidy;
    $tidy->parseString($rawData, $this->tidyConfig, 'utf8');
    $tidy->cleanRepair();
    $htmlTidyNode = $tidy->html();
    $rawData = $htmlTidyNode->value;
  }

  /**
   * Fetch data from url.
   *
   * @param string $url
   * @return string data
   */
  protected function fetchDataFromUrl($url) {
    if (empty($url)) return;
    $rawData;
    if ($this->cache->is_cached($url, $this->cacheGroup)) {
      // Get data from cache
      $rawData = $this->cache->get($url, $this->cacheGroup);
      log_message('info', "Received data for '$url' from cache.");
    } else {
      try {
        // Fetch data from url
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt_array($curl, $this->curlConfig);
        $rawData = curl_exec($curl);
        curl_close($curl);
        log_message('info', "Fetched and cached data from '$url'.");
        $this->prepareRawData($rawData);
        $this->cache->save($url, $rawData, $this->cacheGroup, $this->cacheTtl);
      } catch (Exception $e) {
        log_message('error', __METHOD__ . ' ' . $e->getMessage());
      }
    }
    return $rawData;
  }
  
  /**
   * Fetch resource from url.
   * 
   * @param string $url
   */
  protected function fetchResourceFromUrl($url) {
    if (empty($url)) return;
    $fileName = '';
    try {
      // Get extension      
      $matches = array();
      preg_match('/(?<extension>\.\w+)($|\?)/', $url, $matches);
      $extension = isset($matches['extension']) ? $matches['extension'] : '';
      
      $fileName = 'web/uploads/' . $this->uploadDir . random_string('unique') . $extension;
      $fileHandle = fopen($fileName, 'w'); 
      // Fetch resource from url
      $curl = curl_init();
      curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt($curl, CURLOPT_FILE, $fileHandle);
      curl_setopt_array($curl, $this->curlResourceConfig);
      curl_exec($curl);
      curl_close($curl);
      fclose($fileHandle); 
      log_message('info', "Fetched resource from '$url'.");
    } catch (Exception $e) {
      log_message('error', __METHOD__ . ' ' . $e->getMessage());
    }
    return $fileName;
  }
  
	/**
   * Pre process list url.
   * For example, add page parameter.
   *
   * @param string $listUrl
   */
  protected function preProcessListUrl(&$listUrl) {
    if (strpos($listUrl, $this->listPageUrlSuffix) === false) {
      $listUrl .= $this->listPageUrlSuffix . $this->listCurrentPage;
    }
    log_message('info', 'Current page: ' . $this->listCurrentPage);
  }
  
  
  //################################ XPATH PARSING ########################################
  
	/**
   * Get data from DOMNode.
   * May return html or plain string.
   *
   * @param DOMNode $node
   * @return string html/string
   */
  protected function getDataFromDOMNode(DOMNode $node) {
    $result = '';
    if (isset($node->childNodes)) {
      $children = $node->childNodes;
      if ($children !== false && $children->length > 0) {
        foreach ($children as $child) {
          $tempDoc = new DOMDocument('1.0', 'UTF-8');
          $tempDoc->appendChild($tempDoc->importNode($child, true));
          $result .= $tempDoc->saveHTML();
        }
      }
    } else {
      $result = $node->nodeValue;
    }
    return $result;
  }

  /**
   * Parse list.
   * 
   * @param string $listRawData
   * @return array list
   */
  protected function parseList($listRawData) {
    $this->setListPageMax($listRawData);
    if ($this->isFinished()) return array();
    
    $list = array();
    try {
      $dom = new DOMDocument('1.0', 'UTF-8');
      $dom->loadHTML($listRawData);
      if (isset($this->listQueries['item_container'])) {
        $itemXPath = new DOMXPath($dom);
        $itemNodes = $itemXPath->query($this->listQueries['item_container']);
        if (!empty($itemNodes) &&
            !empty($this->listQueries['item_data'])) {
          foreach ($itemNodes as $itemNode) {
            $item = $this->parseListItemData($itemXPath, $itemNode);
            if (!empty($item)) $list[] = $item; 
          }
        }
      }
    } catch (Exception $e) {
      log_message('error', __METHOD__ . ' ' . $e->getMessage());
    }
    return $list;
  }
  
  /**
   * Parse list item data.
   * Override this in your class.
   *
   * @param DOMXPath $itemXPath
   * @param DOMNode $itemNode
   * @return array item
   */
  protected function parseListItemData(DOMXPath $itemXPath, DOMNode $itemNode) {
    $item = array();
    foreach ($this->listQueries['item_data'] as $key => $query) {
      $nodes = $itemXPath->query($query, $itemNode);
      if ($nodes !== false && $nodes->length > 0) {
        $item[$key] = $nodes->item(0)->nodeValue;
      }
    }
    return $item;
  }
  
  /**
   * Parse item.
   * 
   * @param string $itemRawData
   * @return array item
   */
  protected function parseItem($itemRawData) {
    $item = array();
    try {
      $dom = new DOMDocument('1.0', 'UTF-8');
      $dom->loadHTML($itemRawData);
      if (!empty($this->itemQueries)) {
        $itemXPath = new DOMXPath($dom);
        $item = $this->parseItemData($itemXPath);
      }
    } catch (Exception $e) {
      log_message('error', __METHOD__ . ' ' . $e->getMessage());
    }
    return $item;
  }
  
  /**
   * Parse item data.
   * Override this in your class.
   * 
   * @param DOMXPath $itemXPath
   * @return array data
   */
  protected function parseItemData(DOMXPath $itemXPath) {
    $item = array();
    foreach ($this->itemQueries as $key => $query) {
      $nodes = $itemXPath->query($query);
      if ($nodes !== false && $nodes->length > 0) {
        $node = $nodes->item(0);
        $item[$key] = $this->getDataFromDOMNode($node);
      }
    }
    return $item;
  }
  
  
  //############################### DATA PROCESSING #######################################
  
  /**
   * Process list.
   * 
   * @param array $list
   */
  protected function processList(&$list) {
    if (empty($list)) $this->listIsEmpty = true;
    if ($this->isFinished()) return; 
    
    foreach ($list as &$item) {
      if (isset($item['details_url'])) {
        $item['details_url'] = $this->fixUrl($item['details_url']);
        $itemRawData = $this->fetchDataFromUrl($item['details_url']);
        $itemToAppend = $this->parseItem($itemRawData);
        $item = array_merge($item, $itemToAppend);
        $this->postProcessItem($item);
        $this->processItemImages($item);
      }
    }
    
    $this->increaseListCurrentPage();
  }
  
  /**
   * Post process item.
   * Calls the function list from $this->itemPostProcess for each field.
   * Override this in your class.
   *
   * @param array $item
   */
  protected function postProcessItem(&$item) {
    if (empty($item) || empty($this->itemPostProcess)) return;
    foreach ($item as $field => $fieldValue) {
      if (isset($this->itemPostProcess[$field]) && !empty($this->itemPostProcess[$field])) {
        foreach ($this->itemPostProcess[$field] as $key => $value) {
          // Get function name depending on config
          $functionName = is_array($value) ? $key : $value;
          // Get params
          $params = is_array($value) ? $value : array();
          if (function_exists($functionName)) {
            // Push item element as first parameter
            array_unshift($params, $item[$field]);
            $item[$field] = call_user_func_array($functionName, $params);
          }
        }
      }
    }
  }
  
	/**
   * Process item images.
   * Override this in your class.
   *
   * @param array $item
   */
  protected function processItemImages(&$item) {
    if (empty($this->itemImageFields)) return;
    
    foreach ($this->itemImageFields as $fieldName) {
      if (isset($item[$fieldName])) {
        $item[$fieldName] = $this->fetchResourceFromUrl($item[$fieldName]);
      }
    }
  }
  
  
  //#################################### MISC #############################################
  
	/**
   * Set list page count.
   */
  protected function setListPageMax($listRawData) {
    $matches;
    preg_match_all($this->listPagePattern, $listRawData, $matches);
    if (isset($matches[self::PAGE_MARKER]) &&
        !empty($matches[self::PAGE_MARKER])) {
      $currentPageMax = max($matches[self::PAGE_MARKER]);
      if ($currentPageMax > $this->listPageMax) $this->listPageMax = $currentPageMax;
    }
    log_message('info', 'Current page max: ' . $this->listPageMax);
  }
  
  /**
   * Increase list current page.
   */
  protected function increaseListCurrentPage() {
    $this->listCurrentPage++;
  }
  
  /**
   * Is finished.
   * 
   * @return bool
   */
  public function isFinished() {
    return $this->listIsEmpty ||
           $this->listCurrentPage > $this->listPageMax;
  }
  
  
  //#################################### PARSE ############################################
  
  /**
   * Parse.
   * 1. Pre processes url (usually adds a domain and pagination).
   * 2. Gets the list HTML.
   * 3. Gets item list from it, with details urls.
   * 4. Gets each items HTML by details url.
   * 5. Parses desired values for each item.
   *
   * @param string $listUrl
   * @param array $params
   * @return array data
   */
  public function parse($listUrl, $params = array()) {
    $this->params = array_merge($this->params, $params);
    $this->preProcessListUrl($listUrl);
    $listRawData = $this->fetchDataFromUrl($listUrl);
    $list = $this->parseList($listRawData);
    $this->processList($list);
    return $list;    
  }

}