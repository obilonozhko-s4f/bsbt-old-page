<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  /**
   * User online
   */
  if (!function_exists('online')) {
    function online($user_pk) {
      $CI =& get_instance();
      if ($CI->online->is_user_online($user_pk)) {
        return '<span style="color: #AAAAAA; font-size: 11px;">Онлайн</span>';
      }
      return '';
    }
  }
  
  
  /**
   * IS User online
   */
  if (!function_exists('is_online')) {
    function is_online($user_pk) {
      $CI =& get_instance();
      return $CI->online->is_user_online($user_pk);
    }
  }  