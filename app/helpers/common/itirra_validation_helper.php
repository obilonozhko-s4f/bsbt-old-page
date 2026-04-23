<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * FRULE Definition
 * ---------------
 * Must be an array "FIELD_NAME" => array('required', 'maxLength' => 255....)
 * Rules can have 2 types:
 * 1) "name" - no value
 * 2) "name" => "value" - with value
 */

/**
 * Simple validate Post
 * SHOWS 404 on validation error
 * - if POST is empty
 * - if key not present in POST
 * @param array $keys
 */
if (!function_exists('simple_validate_post')) {
  function simple_validate_post($keys = array()) {
    if (empty($_POST)) show_404();
    if (!is_array($keys)) $keys = array($keys);
    foreach ($keys as $k) {
      if (!isset($_POST[$k])) {
        show_404();
      }
    }
  }
}

/**
 * prepare_array
 */
if (!function_exists('prepare_array')) {
  function prepare_array(&$array, $required = TRUE, $htmlEncode = TRUE) {
    if ($required && empty($array)) show_404();
    if ($htmlEncode) {
      foreach ($array as $k => &$v) {
        $v = htmlspecialchars(trim($v));
      }
    }
  }
}


/**
 * Validate post
 */
if (!function_exists('validate_post')) {
  function validate_post($frules, $error_message_prefix = 'common') {
    return validate_array($_POST, $frules, $error_message_prefix);
  }
}

/**
 * Validate array
 */
if (!function_exists('validate_array')) {
  function validate_array($array, $frules, $error_message_prefix = 'common') {
    if (empty($frules)) return;
    $postValid = TRUE;
    $postErrors = array();
    foreach ($frules as $fname => $rules) {
      if (is_numeric($fname) || (!is_numeric($fname) && empty($rules))) continue;
      $vres = validate_field($fname, isset($array[$fname])?$array[$fname]:null, $rules, $error_message_prefix, FALSE, $array);
      if ($postValid && !$vres['valid']) {
        $postValid = FALSE;
      }
      if (!empty($vres['errors'])) {
        $postErrors = array_merge($postErrors, $vres['errors']);
      }
    }
    return array('valid' => $postValid, 'errors' => $postErrors);
  }
}

/**
 *
 * Enter description here ...
 * @param unknown_type $field
 * @param unknown_type $value
 * @param unknown_type $rules
 * @param unknown_type $error_message_prefix
 */
if (!function_exists('validate_field')) {
  function validate_field($field, $value, $rules, $error_message_prefix = 'common', $fireAll = FALSE, $allData = array()) {
    $valid = TRUE;
    $errors = array();
    // Trim the value
    $value = trim($value);

    foreach ($rules as $rk => $rv) {
      $ruleName = $rk;
      if (is_numeric($ruleName)) {
        $ruleName = $rv;
      }
      switch (true) {
        // REQUIRED
        case ($ruleName == 'required'):
          if ((empty($value) && $value != '0')  || $fireAll) {
            $valid = FALSE;
            $errors[$field][] = array('error' => 'validation.error.' . $error_message_prefix . '.' . $field . '.' . $ruleName);
          }
          break;
          // MAXLENGTH
        case ($ruleName == 'maxLength'):
          if (mb_strlen($value, 'UTF-8') > $rv || $fireAll) {
            $valid = FALSE;
            $errors[$field][] = array('error' => 'validation.error.' . $error_message_prefix . '.' . $field . '.' . $ruleName, 'args' => array('maxLength' => $rv));
          }
          break;
          // EMAIL
        case ($ruleName == 'email'):
          if (!empty($value)) {
            if (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $value) || $fireAll) {
              $valid = FALSE;
              $errors[$field][] = array('error' => 'validation.error.' . $error_message_prefix . '.' . $field . '.' . $ruleName);
            }
          }
          break;
          // EQUALTO
        case ($ruleName == 'equalTo'):
          if (!isset($allData[$rv]) || (isset($allData[$rv]) && $allData[$rv] !== $value) || $fireAll) {
            $valid = FALSE;
            $errors[$field][] = array('error' => 'validation.error.' . $error_message_prefix . '.' . $field . '.' . $ruleName, 'args' => array('equalTo' => ucwords(str_replace('_', ' ', $rv))));
          }
          break;
          // EXACT
        case ($ruleName == 'exact'):
          if ($value != $rv || $fireAll) {
            $valid = FALSE;
            $errors[$field][] = array('error' => 'validation.error.' . $error_message_prefix . '.' . $field . '.' . $ruleName, 'args' => array('exact' => $rv));
          }
          break;

        default:
          show_error("UNKNOWN VALIDATION RULE: " . $ruleName);
      }

    }
    return array('valid' => $valid, 'errors' => $errors);
  }
}


/**
 * Save Post
 * Saves $_POST array to flashmessage
 */
if (!function_exists('save_post')) {
  function save_post($exclude = array(), $foralong = false) {
    $CI =& get_instance();
    $session = $CI->session;
    if ($foralong) {
      $session->set_userdata('saved_post', array_diff_key($_POST, assoc_array_from_values($exclude)));
    } else {
      $session->set_flashdata('saved_post', array_diff_key($_POST, assoc_array_from_values($exclude)));
    }
    
  }
}

/**
 * Get Saved Post
 * returns the saved $_POST array
 */
if (!function_exists('get_saved_post')) {
  function get_saved_post() {
    $CI =& get_instance();
    $session = $CI->session;
    $post = $session->flashdata('saved_post');
    if (!$post) $post = $session->userdata('saved_post');
    return $post;
  }
}

/**
 * Fill form with saved post
 * returns JS that prefilss the form with the saved $_POST array
 */
if (!function_exists('fill_form_with_saved_post')) {
  function fill_form_with_saved_post($formId) {
    $js = '';
    $post = get_saved_post();
    if (!empty($post)) {
      $js .= '<script type="text/javascript"> ';
      $js .= '$(document).ready(function() { ';
      $js .= 'var el; ';
      foreach ($post as $k => $v) {
        if (!is_array($v)) {
          $js .= 'el = $("#' . $formId . '").find(\'[name="' . $k . '"]:first\');';
          $js .= 'if(el && el[0] && el[0].nodeName.toLowerCase() == "input" && el.attr("type") == "text"){el.val("' . addslashes($v) . '");}';
          $js .= 'if(el && el[0] && el[0].nodeName.toLowerCase() == "textarea"){el.html("' . htmlspecialchars($v) . '");}';
          $js .= 'if(el && el[0] && el[0].nodeName.toLowerCase() == "select"){el.val("' . str_replace(array('<', '>'), '', addslashes($v)) . '");}';
          $js .= 'if(el && el[0] && el[0].nodeName.toLowerCase() == "input" && el.attr("type") == "radio"){
          	$("#' . $formId . '").find(\'[name="' . $k . '"][value="'.addslashes($v).'"]\').attr("checked","checked");
          }';
          if ($v == 1) {
            $js .= 'if(el && el[0] && el[0].nodeName.toLowerCase() == "input" && el.attr("type") == "checkbox"){el.attr("checked","checked");}';
          }
        }
      }
      $js .= '}); ';
      $js .= '</script>';
    }
    return $js;
  }
}

/**
 *
 * Enter description here ...
 * @param unknown_type $field
 * @param unknown_type $value
 * @param unknown_type $rules
 * @param unknown_type $error_message_prefix
 */
if (!function_exists('generate_messages')) {
  function generate_messages($frules, $error_message_prefix = 'common') {
    if (empty($frules)) return;
    $messages = array();
    foreach ($frules as $fname => $rules) {
      if (is_numeric($fname) || (!is_numeric($fname) && empty($rules))) continue;
      $vres = validate_field($fname, null, $rules, $error_message_prefix, TRUE);
      $messages = array_merge($messages, $vres['errors']);
    }
    $lines = array();
    $lines[] = '//------------------------ VALIDATION MESSAGES FOR ' . strtoupper($error_message_prefix) . ' > START -----------------------------------';
    $CI =& get_instance();
    $CI->lang->load('validation_messages', $CI->config->config['language']);
    foreach ($messages as $k => $m) {
      foreach ($m as $m) {
        $key = $m['error'];
        $marr = explode('.', $key);
        $ruleName = $marr[count($marr) - 1];
        $fieldName = $marr[count($marr) - 2];
        $params = array('field_name' => ucwords(str_replace('_', ' ', $fieldName)));
        if (isset($m['args'])) {
          $params = array_merge($params, $m['args']);
        }
        $message = kprintf(lang('default.validation.error.' . $ruleName), $params);
        $lines[] = '$lang[\'' . $key . '\'] = \''. $message . '\';';
      }
    }
    $lines[] = '//------------------------ VALIDATION MESSAGES FOR ' . strtoupper($error_message_prefix) . ' > END -----------------------------------';
    die(implode('<br/>', $lines));
  }
}