<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * History library.
 * Uses session as storage.
 * @author Itirra - http://itirra.com
 * @property Session $session
 */
class History {
  
  /** Const. */
  const HISTORY_SESSION_KEY = "HISTORY_SESSION_KEY";
  
  /** Session class object. */
  private $session;
  
  /** Max depth. */
  private $maxDepth;  

  /** Unique. */
  private $unique = TRUE;    
  
  /**
   * Constructor.
   */
  public function History($maxDepth = null, $unique = TRUE) {
    $ci = &get_instance();
    $this->maxDepth = $maxDepth;
    $this->unique = $unique;
    $this->session = &$ci->session;
  }
  
  /**
   * Add item to history.
   * @param mixed $value
   * @param string $key
   */
  public function add($value, $key = 'history') {
    $data = $this->session->userdata(self::HISTORY_SESSION_KEY);
    if (!$data) {
      $data = array();
    }
    if (isset($data[$key]) && is_array($data[$key])) {
      if ($this->maxDepth && count($data[$key]) > $this->maxDepth) {
        array_shift($data[$key]);
      }
      if ($this->unique) {
        if (isset($value['id'])) {
          $ids = get_array_vals_by_second_key($data[$key], 'id');
          if (!in_array($value['id'], $ids)) {
            $data[$key][] = $value;    
          }
        } else {
          $data[$key][] = $value;
        }
      } else {
        $data[$key][] = $value;
      }
    } else {
      $data[$key] = array($value);
    }
    $this->session->set_userdata(self::HISTORY_SESSION_KEY, $data);
  }
  
  /**
   * Get Item from history.
   * @param string $key
   */
  public function get($key = 'history') {
    $result = null;
    $data = $this->session->userdata(self::HISTORY_SESSION_KEY);
    if ($data && isset($data[$key])) {
      $result = $data[$key];
    }
    return $result;
  } 
  
  /**
   * Get count.
   * @param unknown_type $key
   */
  public function get_count($key = 'history') {
    $result = 0;
    $data = $this->session->userdata(self::HISTORY_SESSION_KEY);
    if ($data && isset($data[$key])) {
      $result = count($data[$key]);
    }
    return $result;
  }
  
}