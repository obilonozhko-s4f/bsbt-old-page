<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


/**
 * truncate
 * @param str
 */
if (!function_exists('truncate')) {
  function truncate($str, $n = 500, $end_str = '') {
    $str = strip_tags($str);
    return character_limiter($str, $n, $end_str);
  }
}

/**
 * get_first_line
 * @param str
 */
if (!function_exists('get_first_line')) {
  function get_first_line($text) {
    $array = preg_split('#<\/?(br|p|div)\s?\/?>#', $text);
    foreach($array as $el){
      if(!empty($el)){
        return $el;
      }
    }
    return '';
  }
}


/**
 * truncate_around
 * @param str
 */
if (!function_exists('truncate_around')) {
  function truncate_around($str, $searchStr, $n = 500, $end_str = '...', $start_str = '...') {
    $str = strip_tags($str);
    $pos = strpos($str, $searchStr);
    if($pos === FALSE){
      return character_limiter($str, $n, $end_str);
    }
    if(strlen($str) < $n){
      return $str;
    }
    $searchLen = strlen($searchStr);
    $startPos = $pos - $n / 2 + $searchLen / 2;
    $length = $n;
    if($pos + $n / 2  + $searchLen / 2 > strlen($str)){
      $newLength = strlen($str) - $pos + $n / 2 - $searchLen / 2;
      $startPos -= ($n - $newLength);
      $end_str = '';
    }
    if($startPos < 0){
      $startPos = 0;
      $start_str = '';
    }
    return $start_str . substr($str, $startPos, $length) . $end_str;
  }
}


/**
 * highlight
 * @param str
 */
if (!function_exists('highlight')) {
  function highlight($str, $word) {
    return highlight_phrase($str, $word, '<span class="highlight">', '</span>');
  }
}
