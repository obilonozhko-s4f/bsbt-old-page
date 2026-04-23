<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Text Helper
 *
 * Itirra - http://itirra.com
 *
 * @author  Yuriy Manoylo
 * @author  Alexei Chizhmakov
 * @link    http://itirra.com
 * @since   Version 1.0
 * WARNING: THIS HELPER REQUIRES CODEIGNITER'S TEXT_HELPER !
 */

// ------------------------------------------------------------------------

/**
 * Truncate
 * Strips tags from a string and truncates it
 * @param string $str
 * @return string
 */
if (!function_exists('truncate')) {
  function truncate($str, $n = 500, $end_str = '') {
    $str = strip_tags($str);
    return character_limiter($str, $n, $end_str);
  }
}



/**
 * truncate_around
 * Truncates text around the search string
 * @param string str
 * @param string $searchStr
 * @param int $n
 * @param string $end_str
 * @param string $start_str
 * @return string
 */
if (!function_exists('truncate_around')) {
  function truncate_around($str, $searchStr, $n = 500, $end_str = '...', $start_str = '...') {
    $str = strip_tags($str);
    $str = fix_white_spaces($str);    
    $pos = mb_strpos($str, $searchStr, null, 'UTF-8');
    if($pos === FALSE){
      return character_limiter($str, $n, $end_str);
    }
    if(mb_strlen($str, 'UTF-8') < $n){
      return $str;
    }
    $searchLen = mb_strlen($searchStr, 'UTF-8');
    $startPos = $pos - $n / 2 + $searchLen / 2;
    $length = $n;
    if($pos + $n / 2  + $searchLen / 2 > mb_strlen($str, 'UTF-8')){
      $newLength = mb_strlen($str, 'UTF-8') - $pos + $n / 2 - $searchLen / 2;
      $startPos -= ($n - $newLength);
      $end_str = '';
    }
    if($startPos < 0){
      $startPos = 0;
      $start_str = '';
    }
    return $start_str . mb_substr($str, $startPos, $length, 'UTF-8') . $end_str;
  }
}



/**
 * Fix white spaces
 * Replaces all 2 and more spaces chars (spaces, tabs) in a row to a single space  
 * @param string $text
 * @param boolean $removeLinebreaks - indicates whether to remove all linebreake characters
 * @return string
 */
if (!function_exists('fix_white_spaces')) {
  function fix_white_spaces($text, $removeLinebreaks = false) {
    if($removeLinebreaks){
      $pattern = "([ \t]{2,})|[\r\n]";
    } else {
      $pattern = "[ \t]{2,}";
    }
    $text = preg_replace("/$pattern/", ' ', $text);
    return $text;
  }
}


/**
 * Remove linebreaks
 * Removes all both linebreakes characters (\r & \n) from a string  
 * @param string $string
 * @return string
 */
if ( ! function_exists("remove_linebreaks")) {
  function remove_linebreaks($string) {
    return preg_replace("/\n|\r/", "", $string);
  }
}