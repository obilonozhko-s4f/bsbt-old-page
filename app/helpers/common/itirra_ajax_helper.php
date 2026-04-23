<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Ajax Helper
 * Itirra - http://itirra.com
 */

define('C_AJAX_CODE_STD',                   0);
define('C_AJAX_CODE_NOT_LOGGED',            1);
define('C_AJAX_CODE_WRONG_PARAMS',          2);
define('C_AJAX_CODE_ERROR_STD',             3);
define('C_AJAX_CODE_ERROR_NOT_IMPLEMENTED', 4);
define('C_AJAX_CODE_NOT_ALLOWED',           5);
define('C_AJAX_CODE_RELOAD',              111);

/**
 * Makes json from array
 * @access public
 * @return $json
 */
if ( ! function_exists('array2json')) {
  function array2json($arr) {
    $parts = array();
    $is_list = false;
    if ( ! is_array($arr)) return;
    if (count($arr) < 1) return '{}';

    // Find out if the given array is a numerical array
    $keys = array_keys($arr);
    for ($i = 0; $i < count($keys); $i++) { //See if each key correspondes to its position
      $is_list = true;
      if ( ! is_numeric($keys[$i])) { // A key fails at position check.
        $is_list = false; // It is an associative array.
        break;
      }
    }
    
    foreach ($arr as $key=>$value) {
      if (is_array($value)) { //Custom handling for arrays
        if ($is_list) $parts[] = array2json($value); /* :RECURSION: */
        else $parts[] = '"' . $key . '":' . array2json($value); /* :RECURSION: */
      } else {
        $str = '';
        if ( ! $is_list) $str = '"' . $key . '":';

        //Custom handling for multiple data types
        if (!is_string($value) && is_numeric($value)) {
          if (is_double($value)) {
            $str .= '"' . $value . '"';
          } else {
            $str .= $value;
          }
        }
        elseif ($value === false) $str .= 'false'; // The booleans
        elseif ($value === true) $str .= 'true';
        else $str .= '"' . addslashes(remove_newlines($value)) . '"'; // All other things
        // :TODO: Is there any more datatype we should be in the lookout for? (Object?)

        $parts[] = $str;
      }
    }
    $json = implode(',', $parts);

    if ($is_list) return '[' . $json . ']'; // Return numerical JSON
    return '{' . $json . '}'; // Return associative JSON
  }
}

if ( ! function_exists("remove_newlines")) {
  function remove_newlines($string) {
    return preg_replace("/\n|\r/", "", $string);
  }
}

if ( ! function_exists('ajax_ok_result')) {
  function ajax_ok_result($code = C_AJAX_CODE_STD, $msg = "No message", $data = array()) {
    return array("success" => 'true', "code" => $code, "msg" => $msg, "data" => $data);
  }
}

if ( ! function_exists('ajax_error_result')) {
  function ajax_error_result($code = C_AJAX_CODE_STD, $msg = "No message", $data = array()) {
    return array("success" => 'false', "code" => $code, "msg" => $msg, "data" => $data);
  }
}

if ( ! function_exists('ajax_result_redirect')) {
  function ajax_result_redirect($url) {
    return json_encode(array("redirect" => site_url($url)));
  }
}

if ( ! function_exists('ajax_result_redirect_top')) {
  function ajax_result_redirect_top($url) {
    return json_encode(array("redirect" => site_url($url), 'top' => TRUE));
  }
}