<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Itirra language helper.
 * @author Itirra - http://itirra.com
 */


/**
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!  IMPORTANT  !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 * WHETHER A WEBSITE IS MULTILINUAGE OR NOT IS DEFINED BY THE ORDER OF LOADING HELPERS
 * ###################################################################################
 * url_helper, itirra_language_helper - NOT MULTILINUAGL
 * itirra_language_helper, url_helper  - MULTILINUAGL
 */

/**
 * Site url.
 *
 * @param $uri
 * @retunr string
 */
if ( ! function_exists('site_url')) {
  function site_url($uri = '', $disableLang = FALSE) {
    global $CFG;
    if (preg_match('/(.css$|.png$|.jpg$|.gif$|.js$|.ico$|.jpeg$)/i', $uri)) {
      return $CFG->site_url($uri);
    }
    
    // Get base url
    $CI =& get_instance();
    $baseUrl = $CI->config->slash_item('base_url');
    
    if (strstr($uri, $baseUrl) !== FALSE) {
      $uri = str_replace($baseUrl, '', $uri);
    }
    if (is_array($uri)) {
      $uri = implode('/', $uri);
    }
    $CI =& get_instance();
    if (!$disableLang) $uri = $CI->lang->localized($uri);
    return $CFG->site_url($uri);
  }
}

/**
 * Lang. A shortcut to $this->lang->line().
 * @param string $message
 * @param array $params
 * @return string
 */
if (!function_exists('lang')) {
  function lang($message, $params = array(), $maxChars = 200, $stripTags = TRUE) {
    $ci = get_instance();
    if (ENV == 'DEV') {
      if (!isset($ci->language->usedkeys)) $ci->language->usedkeys = array();
      if (!array_search($message, $ci->language->usedkeys))$ci->language->usedkeys[] = $message;
    }
    return $ci->lang->line($message, $params, $maxChars, $stripTags);
  }

  function get_lang_used_keys() {
    $ci = get_instance();
    $html = "";
    sort($ci->language->usedkeys);
    if (isset($ci->language->usedkeys) && is_array($ci->language->usedkeys)) {
        foreach ($ci->language->usedkeys as $ind) $html .= '$lang["'.$ind.'"] = "";<br/>';
    }

    return "<pre>$html</pre>";
  }
}

/**
 * Lang exists.
 * @param string $message
 * @return bool
 */
if (!function_exists('lang_exists')) {
  function lang_exists($message) {
    $ci = get_instance();
    return $ci->lang->has($message);
  }
}

if (!function_exists('google_translate')) {
  function google_translate($text, $fromLang, $intoLang, $sleep = null){
    $url = "http://ajax.googleapis.com/ajax/services/language/translate?v=1.0&q=".urlencode($text)."&langpair=".$fromLang.'|'.$intoLang;
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_REFERER, current_url());
    $response = curl_exec($curl);
    curl_close($curl);

    if ($sleep) {
      sleep($sleep);
    }

    $json = json_decode($response, true);
    if ($json['responseStatus'] != 200){
      return '';
    }
    return $json['responseData']['translatedText'];
  }
}

if (!function_exists('bing_translate')) {
  function bing_translate($text, $fromLang, $intoLang, $sleep = null){
    $url = "http://api.microsofttranslator.com/v2/Http.svc/Translate?from=$fromLang&to=$intoLang&text=".urlencode($text)."&appId=CC29908D870DF1B8A485A0908266852560C63325&contentType=text/html";
    
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_REFERER, current_url());
    $response = curl_exec($curl);
    curl_close($curl);

    if ($sleep) {
      sleep($sleep);
    }

    if (!empty($response)) {
      $response = str_replace('<string xmlns="http://schemas.microsoft.com/2003/10/Serialization/">', '', $response);
      $response = html_entity_decode($response);
    }
    
    return $response;
  }
}

/**
 * To translit.
 * @access  public
 * @return  string
 */
if (!function_exists('to_translit')) {
  function to_translit($string) {
    $string = str_replace("ю","yu",$string);
    $string = str_replace("ь","",$string);
    $string = str_replace("ц","ts",$string);
    $string = str_replace("у","u",$string);
    $string = str_replace("к","k",$string);
    $string = str_replace("е","e",$string);
    $string = str_replace("н","n",$string);
    $string = str_replace("г","g",$string);
    $string = str_replace("з","z",$string);
    $string = str_replace("х","h",$string);
    $string = str_replace("ъ","",$string);
    $string = str_replace("ф","f",$string);
    $string = str_replace("ы","y",$string);
    $string = str_replace("в","v",$string);
    $string = str_replace("а","a",$string);
    $string = str_replace("п","p",$string);
    $string = str_replace("р","r",$string);
    $string = str_replace("о","o",$string);
    $string = str_replace("л","l",$string);
    $string = str_replace("д","d",$string);
    $string = str_replace("с","s",$string);
    $string = str_replace("м","m",$string);
    $string = str_replace("и","i",$string);
    $string = str_replace("т","t",$string);
    $string = str_replace("б","b",$string);
    $string = str_replace("Ь","",$string);
    $string = str_replace("Ц","ts",$string);
    $string = str_replace("У","U",$string);
    $string = str_replace("К","K",$string);
    $string = str_replace("Е","E",$string);
    $string = str_replace("Н","N",$string);
    $string = str_replace("Г","G",$string);
    $string = str_replace("З","Z",$string);
    $string = str_replace("Х","H",$string);
    $string = str_replace("Ъ","",$string);
    $string = str_replace("Ф","F",$string);
    $string = str_replace("Ы","Y",$string);
    $string = str_replace("В","V",$string);
    $string = str_replace("А","A",$string);
    $string = str_replace("П","P",$string);
    $string = str_replace("Р","R",$string);
    $string = str_replace("О","O",$string);
    $string = str_replace("Л","L",$string);
    $string = str_replace("Д","D",$string);
    $string = str_replace("С","S",$string);
    $string = str_replace("М","M",$string);
    $string = str_replace("И","I",$string);
    $string = str_replace("Т","T",$string);
    $string = str_replace("Б","B",$string);
    $search = array( 'ж', 'ё', 'й','ю', 'я', 'э', 'ш', 'щ', 'ч', 'Ж', 'Ё', 'Й','Ю', 'Я', 'Э', 'Щ', 'Ш', 'Ч', 'І', 'і', 'Ґ', 'ґ', 'Є', 'є', 'Ї', 'ї');
    $replace = array('zh','e','j','yu','ya','e','sh','sch','ch','Zh','e','J','Yu','Ya','E','Sch','Sh','Ch','I', 'i', 'G', 'g', 'Ye', 'ye', 'Yi', 'yi');
    $string = str_replace($search, $replace, $string);
    return $string;
  }
}

if (!function_exists('from_translit')) {
  if (!function_exists('from_translit')) {
    function from_translit($string) {
      $string = str_replace("yu","ю",$string);      
      $string = str_replace("ts","ц",$string);
      $string = str_replace("u","у",$string);
      $string = str_replace("k","к",$string);
      $string = str_replace("e","е",$string);
      $string = str_replace("n","н",$string);
      $string = str_replace("g","г",$string);
      $string = str_replace("z","з",$string);
      $string = str_replace("h","х",$string);      
      $string = str_replace("f","ф",$string);
      $string = str_replace("y","ы",$string);
      $string = str_replace("v","в",$string);
      $string = str_replace("a","а",$string);
      $string = str_replace("p","п",$string);
      $string = str_replace("r","р",$string);
      $string = str_replace("o","о",$string);
      $string = str_replace("l","л",$string);
      $string = str_replace("d","д",$string);
      $string = str_replace("s","с",$string);
      $string = str_replace("m","м",$string);
      $string = str_replace("i","и",$string);
      $string = str_replace("t","т",$string);
      $string = str_replace("b","б",$string);      
      $string = str_replace("ts","Ц",$string);
      $string = str_replace("U","У",$string);
      $string = str_replace("K","К",$string);
      $string = str_replace("E","Е",$string);
      $string = str_replace("N","Н",$string);
      $string = str_replace("G","Г",$string);
      $string = str_replace("Z","З",$string);
      $string = str_replace("H","Х",$string);      
      $string = str_replace("F","Ф",$string);
      $string = str_replace("Y","Ы",$string);
      $string = str_replace("V","В",$string);
      $string = str_replace("A","А",$string);
      $string = str_replace("P","П",$string);
      $string = str_replace("R","Р",$string);
      $string = str_replace("O","О",$string);
      $string = str_replace("L","Л",$string);
      $string = str_replace("D","Д",$string);
      $string = str_replace("S","С",$string);
      $string = str_replace("M","М",$string);
      $string = str_replace("I","И",$string);
      $string = str_replace("T","Т",$string);
      $string = str_replace("B","Б",$string);            
      $search = array('zh','e','j','yu','ya','e','sh','sch','ch','Zh','e','J','Yu','Ya','E','Sch','Sh','Ch','I', 'i', 'G', 'g', 'Ye', 'ye', 'Yi', 'yi');
      $replace = array( 'ж', 'ё', 'й','ю', 'я', 'э', 'ш', 'щ', 'ч', 'Ж', 'Ё', 'Й','Ю', 'Я', 'Э', 'Щ', 'Ш', 'Ч', 'І', 'і', 'Ґ', 'ґ', 'Є', 'є', 'Ї', 'ї');      
      $string = str_replace($search, $replace, $string);
      return $string;
    }
  } 
}

if (!function_exists('lang_url')) {
  function lang_url($string, $startsWith = null) {
    $string = trim(strip_tags($string));
    $string = to_translit($string);
    $string = strtolower($string);
    $string = preg_replace("/[^a-zA-Z0-9\s]/", "", $string); //remove all non-alphanumeric characters except the hyphen
    $string = str_replace("\n", "", $string);
    $string = str_replace("\r", "", $string);
    $string = str_replace(' ', '-', $string);
    $string = preg_replace("/[-]{2,}/", "", $string);  //replace multiple instances of the hyphen with a single instance
    if ($startsWith) {
      $string = $startsWith . $string;
    }
    $string = surround_with_slashes($string);
    return $string;
  }
}

/**
 * Choose correct lang line according to number.
 * Add these lines to message properties:
 * $lang['yourkey.1'] = 'день';
 * $lang['yourkey.2'] = 'дня';
 * $lang['yourkey.5'] = 'дней';
 * @param $number
 * @param $langKey
 * @param $addNumberBefore
 * @return string
 */
if (!function_exists('number_noun')) {
  function number_noun($number, $langKey, $addNumberBefore = TRUE) {
    $n = $number;
    $number = $number % 100;
    if ($number >= 11 && $number <= 19) {
      $endingKey = '5';
    } else {
      $i = $number % 10;
      switch ($i) {
        case (1):
          $endingKey = '1';
          break;
        case (2):
        case (3):
        case (4):
          $endingKey = '2';
          break;
        default:
          $endingKey = '5';
          break;
      }
    }
    $result = $addNumberBefore ? $n . ' ' : '';
    $result .= lang($langKey . '.' . $endingKey);
    return $result;
  }
}