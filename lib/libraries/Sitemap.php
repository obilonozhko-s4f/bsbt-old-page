<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sitemap {

  const SITEMAP_XML_HEADER = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
  const SITEMAP_XML_FOOTER = '</urlset>';
  
  const SITEMAP_INDEX_XML_HEADER = '<?xml version="1.0" encoding="UTF-8"?><sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
  const SITEMAP_INDEX_XML_FOOTER = '</sitemapindex>';
  
  /* sitemap config file */
  private $config;
  
  /**
   * Sitemap library constructor
   */
  public function Sitemap(){
    log_message('debug', "Sitemap Class Initialized");

    $this->CI =& get_instance();
    $this->CI->load->helper('file');
    $this->CI->load->helper('url');
    $this->CI->load->config('sitemap');
    $this->config = $this->CI->config->item('sitemap');
  }
  
  /**
   * Generates sitemap.xml and children maps for entities, specified in config
   * @return site url of sitemap.xml
   */
  public function generate() {
    $entities = $this->config['entities'];
    if(empty($entities)) {
    	//do not create any files
    	return NULL;
    }

    $xml = self::SITEMAP_INDEX_XML_HEADER;
    foreach ($entities as $e) {
      $urls = $this->generateForEntity($e['name']);
      foreach ($urls as $u) {
        $xml .= '<sitemap>';
        $xml .= '<loc>' . $u . '</loc>';  
        $xml .= '</sitemap>';
      }
    }
    $xml .= self::SITEMAP_INDEX_XML_FOOTER;
    return $this->saveSitemapIndex($xml); 
  }
  
  /**
   * Generate sitemap file for specified entity
   * @param $entityName - entity (manager) name
   * @param $defaults - default values for lastmod, changefreq, etc (not implemented)
   * @return aray of generated files names
   */
  public function generateForEntity($entityName, $defaults = NULL) {
    $entities = ManagerHolder::get($entityName)->getAllForSitemap();
    if(empty($entities)) {
    	//do not create any files  
    	return Array();
    }
    
    // Generated files names array
    $filesNames = Array();
    
    // base size of xml file
    $baseSize = strlen(self::SITEMAP_XML_HEADER) + strlen(self::SITEMAP_XML_FOOTER);

    // file constraints counters 
    $recordNum = 1;
    $fileSize = $baseSize; 
    
    // file sequencial number
    $fileNum = 1; 

    // generating XML 
    $xmlUrls = '';
    foreach($entities as $e) {
      $xmlUrl  = '<url>'; 
      $xmlUrl .= '<loc>' . htmlspecialchars(site_url($e['loc'])) . '</loc>';
      $xmlUrl .= '</url>';

      //TODO implement lastmod, changefreq, etc
      
      if ($recordNum >= $this->config['max_url_count'] ||
          $fileSize  >= $this->config['max_file_size']) {
        // if file constraints met, save data to file an start new one
        $xml = self::SITEMAP_XML_HEADER . $xmlUrls . self::SITEMAP_XML_FOOTER;
        $filesNames[] = $this->saveEntitySitemap($xml, $entityName, $fileNum);
        $recordNum = 1;
        $fileNum++;
        $xmlUrls = $xmlUrl;
        $fileSize = $baseSize; 
      } else {
        // concat new record to list
        $xmlUrls .= $xmlUrl;
        $fileSize += strlen($xmlUrl);
        $recordNum++;
      }    
    }
    // save remainng records
    $xml = self::SITEMAP_XML_HEADER . $xmlUrls . self::SITEMAP_XML_FOOTER;
    $filesNames[] = $this->saveEntitySitemap($xml, $entityName, $fileNum);
    
    return $filesNames;
  }
  
  // ------------------------------ File operations ------------------------------------------

  /**
   * Save entity sitemap. and return it's URL
   * Save location is $children_location/sitemap-$entityName-$number.xml
   * @param $xml - xml string to save
   * @param $entityname - entity name to form file name
   * @param $number - sequence number to form file name 
   * @return file's site url
   */
  private function saveEntitySitemap($xml, $entityName, $number) {
    $file_name = $this->config['children_path'] . '/' . "sitemap-$entityName-$number.xml";
    
    if ( ! write_file($file_name, $xml) ) {
      throw new Exception("cannot open file " . $file_name);
    }
    return site_url($file_name);
  }
  
  /**
   * Save sitemap index file and return it's URL
   * @param $xml - file contents
   * @return file's site URL
   */
  private function saveSitemapIndex($xml) {
    $file_name = "sitemap.xml";
    if ( ! write_file($file_name, $xml) ) {
      throw new Exception("cannot open file " . $file_name);
    }
    return site_url($file_name);
  }

	
  // ------------------------------------ Misc -----------------------------------------------
  
  /** 
   * Notify search engine for new sitemap.xml
   * Sends GET request, specific to search engine to notify it that sitemap was changed and ust be reread.
   * @param $sitemap - full url of sitemap.xml file on site
   * @param $service - search engine name: 'bing', 'ask' or 'google'
   * 
   */
  public function pingSE($sitemap,$service){

    switch ($service) {
      case 'bing':
        $ping = "http://www.bing.com/webmaster/ping.aspx?siteMap=$sitemap";
        break;
      case 'ask':
        $ping = "http://submissions.ask.com/ping?sitemap=$sitemap";
        break;
      case 'google':
        $ping = "http://www.google.com/webmasters/sitemaps/ping?sitemap=$sitemap";
        break;      
      default:
        return false;
    }
    
    $curl_handle=curl_init();
    curl_setopt($curl_handle,CURLOPT_URL,$ping);
    curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
    curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
    $buffer = curl_exec($curl_handle);
    curl_close($curl_handle);

    trace($buffer);
    die();


    if (empty($buffer))
    {
      echo "<p>Sorry, submission failed for $service.<p>";
      die();
    }
    else
    {
      echo "<p>$service success</p>";
      trace($buffer);
    }

  }
  
}
// END Sitemap

/* End of file Sitemap.php */
/* Location: ./system/application/libraries/Sitemap */
