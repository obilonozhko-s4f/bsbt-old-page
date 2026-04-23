<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Messages Helper
 * Itirra - http://itirra.com
 * @author  Alexei Chizhmakov
 * @link    http://itirra.com
 */

// ------------------------------------------------------------------------

/**
 * Html flash message. To be called from view.
 * Retrieve messages from session.
 * Messages can be an array.
 * meeages["notice"] = array("everything <strong>is</stromg> ok", "please proceed");
 * meeages["error"] = array("An error occured");
 * meeages["warning"] = Plese try not to do this";
 * @access  public
 * @return  html
 */
if (!function_exists('html_flash_message')) {
  function html_flash_message($message = null) {
    $html = "";
    if (!$message || count($message) < 1) {
      $CI =& get_instance();
      $session = $CI->session;
      $message = $session->flashdata('message');
    }
    if (!$message || count($message) < 1) {
      return $html;
    }
    foreach ($message as $type => $msgs) {
      $messageType = $type;
      if (strpos($msgs, '{') !== FALSE) {
        if (!$messageTexts = @unserialize($msgs)) {
          $messageTexts = $msgs;  
        }
      } else {
        $messageTexts = $msgs;  
      }
    }
    $html = '<div class="flash">' .
            '  <div class="message ' . $messageType .  '">';
    if (!is_array($messageTexts)) {
      $html .= '    <p>' . lang($messageTexts) . '</p>';
    } else {
      $html .= '    <ul>';
      foreach ($messageTexts as $msgText) {
        $html .= '      <li>' . lang($msgText) . '</li>';
      }
      $html .= '    </ul>';
    }
    $html .= '  </div>' .      
            '</div>';
    return $html;
  }
}


/**
 * Sets a flash message
 * @param string $type "notice","error","warning"  
 * @param string or array $messages
 * @param array $args
 */
if (!function_exists('set_flash_message')) {
  function set_flash_message($type, $messages, $args = array()) {
    if (empty($messages)) return;
    $CI =& get_instance();
    $session = $CI->session; 
    if (is_array($messages)) {
      foreach ($messages as &$msg) {
        if (is_array($msg)) {
          $key = implode('', array_keys($msg));
          $msg = lang($key, $msg[$key]);
        } else {
          $msg = lang($msg, $args);  
        }
      }
      $message[$type] = serialize($messages);
    } else {
      $message[$type] = lang($messages, $args);
    }
    $session->set_flashdata('message', $message);
  }
}

/**
 * Set flash notice
 * @param string $messages
 * @param array $args
 */
if (!function_exists('set_flash_notice')) {
  function set_flash_notice($messages, $args = array()) {
    set_flash_message('notice', $messages, $args);
  }
}

/**
 * Set flash error
 * @param string $messages
 * @param array $args
 */
if (!function_exists('set_flash_error')) {
  function set_flash_error($messages, $args = array()) {
    set_flash_message('error', $messages, $args);
  }
}

/**
 * Set flash warning
 * @param string $messages
 * @param array $args
 */
if (!function_exists('set_flash_warning')) {
  function set_flash_warning($messages, $args = array()) {
    set_flash_message('warning', $messages, $args);
  }
}

/**
 * Set flash validation errors
 * @param string $vErrors array returned from validation helper
 */
if (!function_exists('set_flash_validation_errors')) {
  function set_flash_validation_errors($vErrors) {
    if (empty($vErrors)) return;
    $ferrors = array();
    $args = array();
    foreach ($vErrors as $fKey => $errors) {
      $arg = array('field_name' => ucwords(str_replace('_', ' ', $fKey)));
      $errors = $errors[0];
      if (isset($errors['args'])) {
        $ferrors[$errors['error']] = array_merge($arg, $errors['args']);
      } else {
        $ferrors[$errors['error']] = $arg;
      }      
    }
    $nerrors = array();
    foreach ($ferrors as $k => $error) {
      if (!lang_exists($k)) {
        $nerrors[] = array('default.validation.error.' . array_pop(explode('.', $k)) => $error);
      } else {
        $nerrors[] = array($k => $error);
      }
    }
    set_flash_error($nerrors, $args);
  }
}

/**
* Set flash variable
* @param string $vErrors array returned from validation helper
*/
if (!function_exists('set_flash_var')) {
	function set_flash_var($key, $val = true) {
    $CI =& get_instance();
    $session = $CI->session->set_flashdata($key, $val);
  }
}

if (!function_exists('get_flash_var')) {
  function get_flash_var($key, $val = true) {
    $CI =& get_instance();
    return $CI->session->flashdata($key);
  }
}