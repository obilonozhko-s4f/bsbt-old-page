<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ActionWatcher library.
 * Tracks your actions for them not to be called too often.
 * Works with UNIX timestamps.
 * Uses session as storage for last invoke timestamps.
 * Define specific intervals in a config actionwatcher_config.php.
 * @author Itirra - http://itirra.com
 */
class ActionWatcher {
  
  /** Const. */
  const SESSION_KEY_PREFIX = "action_watcher_session_key_";
  const DEFAULT_INTERVAL = 15; // in seconds
  const CONFIG_FILE_NAME = "actionwatcher";
  
  /** Errors. */
  private $errors = array(
    "invalid_key" => "actionwatcher.error.invalid_key"
  );
  
  /** Config. */
  private $config = array();
  
  /** Session class object. */
  private $session;

  /**
   * Constructor.
   */
  public function ActionWatcher() {
    $ci = &get_instance();
    
    // Config
    // No need to load config - just create actionwatcher.php in config dir
    // $ci->config->load(self::CONFIG_FILE_NAME, true);
    $this->config = $ci->config->item(self::CONFIG_FILE_NAME);
    
    // Session
    $this->session = &$ci->session;
  }
  
  /**
   * Action name getter.
   * @param string $key
   * @return action name
   */
  private function getActionCallName($key) {
    return self::SESSION_KEY_PREFIX . $key;
  }
  
  /**
   * Updates action's timestamp in session.
   * @param string $key
   */
  private function updateActionCall($key) {
    $this->session->set_userdata($this->getActionCallName($key), time());
  }
  
  /**
   * Get action timestamp from session.
   * @param string $key
   */
  private function getActionCall($key) {
    return $this->session->userdata($this->getActionCallName($key));
  }
  
  /**
   * Is action allowed.
   * Would return true if key is not found in session.
   * @param string $key
   * @throws InvalidArgumentException
   * @return boolean
   */
  public function isAllowed($key) {
    if (!isset($key) || empty($key)) throw new InvalidArgumentException($this->errors["invalid_key"]);
    
    // Get action call from session
    $call = $this->getActionCall($key);
    if ($call === false) return true;
    
    // Get interval
    $interval = isset($this->config[$key]) ? $this->config[$key] : self::DEFAULT_INTERVAL;
    
    return time() - $call > $interval; 
  }
  
  /**
   * Call this method if action was invoked.
   * @param string $key
   * @throws InvalidArgumentException
   */
  public function invoked($key) {
    if (!isset($key) || empty($key)) throw new InvalidArgumentException($this->errors["invalid_key"]);
    
    if ($this->isAllowed($key)) $this->updateActionCall($key);
  }
  
}
