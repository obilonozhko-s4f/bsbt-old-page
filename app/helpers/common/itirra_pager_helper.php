<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Pager Helper
 *
 * Itirra - http://itirra.com
 *
 * @author  Alexei Chizhmakov
 * @link    http://itirra.com
 * @since   Version 1.0
 */

// ------------------------------------------------------------------------

/**
 * Get Prefix
 * @access  public
 * @return  string
 */
if (!function_exists('get_prefix')) {
  function get_prefix() {
    $CI = &get_instance();
    $prefix = $CI->config->item('page_prefix');
    if(!$prefix){
      $prefix = "page"; 
    }
    return $prefix;
  }
}

/**
 * Pager URL
 *
 * @access  public
 * @param string
 * @return  string
 */
if (!function_exists('pager_url')) {
  function pager_url($page) {
  	$requestUri = current_url();
    if (pager_has_prefix($requestUri)) {
      $requestUri = preg_replace("/" . get_prefix() . "[0-9]+/i", get_prefix() . $page, $requestUri);
    } else {
      if (substr($requestUri, -1) != "/") {
        $requestUri .= "/";
      }
      $requestUri .= get_prefix() . $page;
    }
    // Append $_GET parameters
    $params = get_get_params();
    if ($params) {
      $requestUri .= $params;
    }
    return $requestUri;
  }
}


/**
 * Pager Add Prefix
 * @access  public
 * @param int
 * @return  string
 */
if (!function_exists('pager_add_prefix')) {
  function pager_add_prefix($page) {
    return get_prefix() . $page;
  }
}

/**
 * Pager Get Page Number
 * @access  public
 * @param int
 * @return  string
 */
if (!function_exists('pager_get_page_number')) {
  function pager_get_page_number($str = null) {
    if (!$str) {
      $str = current_url();
    }    
    $page = 1;
    preg_match("/\/" . get_prefix() . "[0-9]+/i", $str, $matches);
    if (!empty($matches)) {
      $page = str_replace(get_prefix(), '', $matches[0]);
      $page = (int)str_replace('/', '', $page);
    }
    return $page;
  }
}

/**
 * Pager Remove from srt
 * @access  public
 * @param int
 * @return  string
 */
if (!function_exists('pager_remove_from_str')) {
  function pager_remove_from_str($str = null) {
    if (!$str) {
      $str = current_url();
    }
    return preg_replace("/(\/)?" . get_prefix() . "[0-9]+/i", '', $str);
  }
}

/**
 * Pager Remove Prefix
 * @access  public
 * @param int
 * @return  string
 */
if (!function_exists('pager_remove_prefix')) {
  function pager_remove_prefix($str = null) {
    if (!$str) {
      $str = current_url();
    }    
    return preg_replace("/(\/)?" . get_prefix() . "+/i", '', $str);
  }
}

/**
 * Pager has Prefix
 * @access  public
 * @param int
 * @return  string
 */
if (!function_exists('pager_has_prefix')) {
  function pager_has_prefix($str = null) {
    if (!$str) {
      $str = current_url();
    }   
    return preg_match("/(\/)?" . get_prefix() . "[0-9]+/i", $str) ? TRUE : FALSE;
  } 
}

