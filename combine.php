<?php
  error_reporting(E_ALL);
  ini_set('display_errors', TRUE);
  ini_set('display_startup_errors', TRUE);
  
  /**
   * Combine and compress for ALL ITIRRA PROJECTS.
   * @author Alexei Chizhmakov (Itirra - http://itirra.com)
   *
   * A php file that receives all requests to .css, .js and image files
   * by means of .htaccess rewrite rules. Combines, compresses and caches them.
   *
   * Path to js files start with "js/":
   *  js/jquery/jquery-1.3.js,jquery/jquery.validate.js,jquery/messages_ru.js
   *
   * Path to css files start with "css/":
   *  css/style.css,style2.css,style3.css
   *
   */
  

  //---------------------------------------------------------------
  //------------------------ THE CONFIG ---------------------------
  //---------------------------------------------------------------
  $cache 	          = FALSE;
  $doEncode               = FALSE;
  $sendModifiedHeaders    = FALSE;
  $cachedir               = dirname(__FILE__) . '/web/cache';
  $cssdir                 = dirname(__FILE__) . '/web/css';
  $jsdir                  = dirname(__FILE__) . '/web/js';
  $expiresPeriod          = 60 * 60 * 24 * 30; // = 1 month
                        /*  sec  min hour day   */  
  //-----------------------------------------------------------------
  //------------------------  THE CODE ------------------------------
  //-----------------------------------------------------------------
  
  /** Types. */
  $types = array("css", "js");
  if (!isset($_GET['type'])) {
    die("Error [combine.php]: Type not set.");
  }
  $type = $_GET['type'];
  if (!in_array($type, $types)) {
    die("Error [combine.php]: Type is not valid.");
  }
  $extension = null;
  $encoding = $doEncode ? getEncoding() : "none";
  $files = explode(',', $_GET['files']);

  switch($type) {
    case 'css': {
      $path = realpath($cssdir);
      break;
    };
    case "js": {
      $path = realpath($jsdir);
      break;
    }
  }
  
  $lastModified = get_last_modified($path, $files);
  $lastModifiedStr = date('Y_m_d_H_i_s', $lastModified);
  
  if ($cache) {
    $cacheFileName = $type . $encoding . md5($_GET['files']) . "mod_$lastModifiedStr.gzip";
    if (!file_exists(realpath($cachedir))) {
      die("Error [combine.php]: No Cache Dir.");
    }
    $cacheFilePath = realpath($cachedir) . '/' . $cacheFileName;
    if (file_exists($cacheFilePath)) {
      $contents = file_get_contents($cacheFilePath);
      setHeaders($type,  strlen($contents), $encoding, null, $sendModifiedHeaders, $lastModified, $expiresPeriod);
      die($contents);
    }
  }
  
  $contents = combine($path, $files);
  
  if ($type == 'css') {
    $regex = array("`^([\t\s]+)`ism" => '',
                   "`^\/\*(.+?)\*\/`ism" => "",
                   "`([\n\A;]+)\/\*(.+?)\*\/`ism" => "$1",
                   "`([\n\A;\s]+)//(.+?)[\n\r]`ism" => "$1\n",
                   "`(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+`ism" => "\n");
    $contents = preg_replace(array_keys($regex), $regex, $contents);
    
    $contents = str_replace(': ', ':', $contents);
    $contents = str_replace('; ', ';', $contents);
    $contents = str_replace('{ ', '{', $contents);
    $contents = str_replace(' }', '}', $contents); 


  }
  
  
  $contents = compress($contents, $encoding);
  if ($cache) {
    file_put_contents($cacheFilePath, $contents);
  }
  setHeaders($type,  strlen($contents), $encoding, $extension, $sendModifiedHeaders, $lastModified, $expiresPeriod);
  die($contents);
  
  
  //---------------------------------------------------------------
  //---------------------- THE FUNCTIONS --------------------------
  //---------------------------------------------------------------
  
  
  /**
   * Set headers function.
   * To set appropriate headers for different file types.
   * @param string $type
   * @param int $contentLength
   * @param string $encoding
   * @param string $extension
   */
  function setHeaders($type, $contentLength, $encoding, $extension = null, $sendModifiedHeaders, $lastModified, $expiresPeriod) {
    switch ($type) {
      case 'css':
        header("Content-Type: text/css; charset=UTF-8");
        break;
      case 'js':
        header("Content-Type: text/javascript; charset=UTF-8");
        break;
      default:
        die("Error [combine.php]: Type is not valid.");
    }
    header('Content-Length: ' . $contentLength);
    if ($encoding != "none") {
      header("Content-Encoding: " . $encoding);
    }
    
    if ($sendModifiedHeaders) {
      if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= $lastModified) {
        sendNotModifiedHeader();
      } else {
        $lastModifiedStr = gmdate("D, d M Y H:i:s \G\M\T", $lastModified);
        header('Last-Modified: ' . $lastModifiedStr);
        $expiresDate = $lastModified + $expiresPeriod;
        $expiresDateStr = gmdate("D, d M Y H:i:s \G\M\T", $expiresDate); 
        header('Expires: ' . $expiresDateStr);
      }
      header('Vary: Accept-Encoding');
    }
  }
  
  function sendNotModifiedHeader(){
    if (php_sapi_name()=='CGI') {
      header("Status: 304 Not Modified");
    } else {
      header("HTTP/1.0 304 Not Modified");
    }
  }
  
  /**
   * GetEncoding
   * Returns encoding supported by browser.
   * @return string
   */
  function getEncoding() {
    $encoding = "none";
    if (isset($_SERVER['HTTP_ACCEPT_ENCODING'])) {
      // Determine supported compression method
      $gzip = strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip');
      $deflate = strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'deflate');
      if ($gzip) {
        $encoding = "gzip";
      } else if ($deflate) {
        $encoding = "deflate";
      }
    }
    // Check for buggy versions of Internet Explorer
    if (isset($_SERVER['HTTP_USER_AGENT'])
    && !strstr($_SERVER['HTTP_USER_AGENT'], 'Opera')
    && preg_match('/^Mozilla\/4\.0 \(compatible; MSIE ([0-9]\.[0-9])/i', $_SERVER['HTTP_USER_AGENT'], $matches)) {
      $version = floatval($matches[1]);
      if ($version < 6) {
        $encoding = 'none';
      }
      if ($version == 6 && !strstr($_SERVER['HTTP_USER_AGENT'], 'EV1')) {
        $encoding = 'none';
      }
    }
    return $encoding;
  }
  
  /**
   * Compress.
   * Encodes data to needed encoding.
   * @param string $data
   * @param string $encoding
   * @return string
   */
  function compress($data, $encoding) {
    if ($encoding != "none") {
      if ($encoding == "gzip") {
        $data = gzencode($data, 9, FORCE_GZIP);
      } else {
        $data = gzencode($data, 9, FORCE_DEFLATE);
      }
    }
    return $data;
  }
  
  
  /**
   * get_last_modified
   * @param $basePath
   * @param $files
   * @return int time
   */
  function get_last_modified($basePath, $files){
    $maxDate = '';
    foreach($files as $file) {
      $path = realpath($basePath . '/' . trim($file));
      if (!$path) {
        $path = realpath(dirname(__FILE__) . '/' . trim($file));
      }
      if (!empty($path)) {
        $modifiedDate = filemtime($path);
        if($modifiedDate > $maxDate){
          $maxDate = $modifiedDate;
        }
      }
    }
    if(!$maxDate){
      $maxDate = time();
    }
    return $maxDate;
  }
  
  
  /**
   * Combines files into 1.
   * @param string $basePath
   * @param array $files
   * @return string
   */
  function combine($basePath, $files) {
    $result = "";
    foreach($files as $file) {
      $path = realpath($basePath . '/' . trim($file));
      if (!$path) {
        $path = realpath(dirname(__FILE__) . '/' . trim($file));
      }
      if (!empty($path)) {
        $contents = file_get_contents($path);
        // Remove empty lines
        $contents = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $contents);
        $result .= "\n" . $contents;
      }
    }
    return $result;
  }
  
  
  if (!function_exists('file_put_contents')) {
    function file_put_contents($filename, $data) {
      $f = @fopen($filename, 'w');
      if (!$f) {
        return false;
      } else {
        $bytes = fwrite($f, $data);
        fclose($f);
        return $bytes;
      }
    }
  }