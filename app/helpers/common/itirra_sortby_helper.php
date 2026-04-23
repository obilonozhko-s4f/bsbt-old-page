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
if (!function_exists('get_sort_prefix')) {
  function get_sort_prefix() {
    return "sort_by_";
  }
}

/**
 * Pager URL
 *
 * @access  public
 * @param string
 * @return  string
 */
if (!function_exists('sort_by_url')) {
  function sort_by_url($fieldName) {
  	$requestUri = current_url();
    if (strstr($requestUri, get_sort_prefix()) !== FALSE) {
      if (strstr($requestUri, get_sort_prefix() . $fieldName . '_asc') !== FALSE) {
        $requestUri = preg_replace("/" . get_sort_prefix() . "[0-9a-zA-Z_\.]+_asc/i", get_sort_prefix() . $fieldName . '_desc', $requestUri);
      } else if (strstr($requestUri, get_sort_prefix() . $fieldName . '_desc') !== FALSE) {
        $requestUri = preg_replace("/" . get_sort_prefix() . "[0-9a-zA-Z_\.]+_desc/i", get_sort_prefix() . $fieldName . '_asc', $requestUri);
      } else {
        $requestUri = preg_replace("/" . get_sort_prefix() . "[0-9a-zA-Z_\.]+/i", get_sort_prefix() . $fieldName . '_asc', $requestUri);
      }
    } else {
      if (substr($requestUri, -1) != "/") {
        $requestUri .= "/";
      }
      $requestUri .= get_sort_prefix() . $fieldName . '_asc';
    }  

    // Remove page
    $requestUri = preg_replace("/\/page[0-9]+/i", '', $requestUri);
    return $requestUri;
  }
}

/**
 * Pager Remove Prefix
 * @access  public
 * @param int
 * @return  string
 */
if (!function_exists('sort_by_remove_prefix')) {
  function sort_by_remove_prefix($str) {
    $sortBy = str_replace(get_sort_prefix(), '', $str);
    if (strstr($sortBy, '_asc') !== FALSE) {
      $sortBy = str_replace('_asc', '', $sortBy);
      $sortBy .= ' ASC';
    }
    if (strstr($sortBy, '_desc') !== FALSE) {
      $sortBy = str_replace('_desc', '', $sortBy);
      $sortBy .= ' DESC';      
    }
    return $sortBy;
  }
}

/**
 * Pager Remove Prefix
 * @access  public
 * @param int
 * @return  string
 */
if (!function_exists('sort_by_get_class')) {
  function sort_by_get_class($field) {
    $requestUri = current_url();
    if (strstr($requestUri, get_sort_prefix() . $field . '_desc') !== FALSE) {
      return "desc";
    }
    if (strstr($requestUri, get_sort_prefix() . $field . '_asc') !== FALSE) {
      return "asc";
    }    
    return "";
  }
}


/**
 * Sort By has Prefix
 * @access  public
 * @param int
 * @return  string
 */
if (!function_exists('sort_by_has_prefix')) {
  function sort_by_has_prefix($str) {
    $result = strstr($str, get_sort_prefix());
    return $result;
  }
}


