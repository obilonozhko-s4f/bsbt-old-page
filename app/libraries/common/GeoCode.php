<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');


/**
 * GeoCode class.
 *
 * I'm Fucking Amazing!
 *
 * Documentation:
 * http://code.google.com/apis/maps/documentation/geocoding/
 *
 * @version 1.0
 * @since 16.04.11
 * @author Alexei Chizhmakov (Itirra - www.itirra.com)
 */
class GeoCode {

  /**
   * GeoCode Class Options.
   * @var array
   */
  private $options = array(
    'key' => '',
    'url' => 'https://maps.googleapis.com/maps/api/geocode/',
    'responce_type' => 'json',
    'region' => '',
    'language' => '',
    'sensor' => 'false',
    'allowed_responce_location_types' => '',
    'find_one' => true
  );

  /**
   * Constructor.
   * @param array $options
   */
  public function GeoCode($options = array()) {
    if (!empty($options)) {
      $this->options = array_merge($this->options, $options);
    }
  }

  /**
   * Get coords.
   * @param string $address
   * @throws Exception
   */
  public function get_coords($address) {
    $geocoderes = array();
    $url = $this->options['url'] . $this->options['responce_type'] . '?address=' . urlencode($address);
    if ($this->options['region']) {
      $url .= '&region=' . $this->options['region'];
    }
    if ($this->options['language']) {
      $url .= '&language=' . $this->options['language'];
    }
    if ($this->options['key']) {
      $url .= '&key=' . $this->options['key'];
    }
    $url .= '&sensor=' . $this->options['sensor'];
    $ch = curl_init();
    
    log_message('error', 'GEO CODE URL = ' . $url);
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $curlout = curl_exec($ch);
    curl_close($ch);
    $response = json_decode($curlout, true);
    log_message('error', 'GEO CODE RESPONCE = ' . print_r($response, TRUE));
    $msg = $this->check_status($response['status']);
    if (empty($msg)) {
      $results = $response['results'];
      foreach ($results as $result) {
        if (!empty($this->options['allowed_responce_location_types'])) {
          $found = FALSE;
          foreach ($this->options['allowed_responce_location_types'] as $loc_type) {
            if (in_array($loc_type, $result['types'])) {
              $found = TRUE;
              break;
            }   
          }
          if (!$found) {
            continue;
          }
        }
        if ($this->options['find_one'] && count($results) == 1) {
          $geocoderes['address'] = $result['address_components'];
          $geocoderes['lat_lng'] = $result['geometry']['location'];
        } else if($this->options['find_one']) {
          $geocoderes['address'] = $result['address_components'];
          $geocoderes['lat_lng'] = $result['geometry']['location'];
          break;          
        }
      }
    } else {
      throw new Exception($msg[0], $msg[1]);
    }
    return $geocoderes;
  }

  /**
   * Set options.
   * @param array $options
   */
  public function set_options($options) {
    $this->options = array_merge($this->options, $options);
  }


  /**
   * Function to check the status
   * @param string $status
   * @return empty string if OK, if not the error message
   */
  private function check_status($status) {
    if (empty($status)) return 'The status variable is emtpy!';
    if (strtoupper($status) == "OK") {
      return array();
    }
    if (strtoupper($status) == "ZERO_RESULTS") {
      return array("Geocode was successful but returned no results. This may occur if the geocode was passed a non-existent address or a latlng in a remote location.", 100);
    }
    if (strtoupper($status) == "OVER_QUERY_LIMIT") {
      return array("You are over your quota.", 200);
    }
    if (strtoupper($status) == "REQUEST_DENIED") {
      return array("Your request was denied, generally because of lack of a sensor parameter.", 300);
    }
    if (strtoupper($status) == "INVALID_REQUEST") {
      return array("The query (address or latlng) is missing.", 400);
    }
  }

}
?>