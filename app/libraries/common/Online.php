<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Online class.
 * 
 * I'm Fucking Amazing!
 * 
 * @version 1.1
 * @since 28.11.10
 * @author Alexei Chizhmakov (Itirra - www.itirra.com)
 */
class Online {

  /**
   * Online Class Options.
   * @var array
   */
  private $options = array(
						'cache_dir' => 'cache',
            'cache_file' => 'online.cache',   				 
						'session_time' => 5, // In minutes		
						'cache_ttl' => 1, // In minutes	
    			  'user_session_key' => 'auth_entity',
            'user_session_pk' => 'id',
            'user_session_data' => array()
  );

  /**
   * UsersOnline.
   * Array of users online.
   * @var array
   */
  private $users_online = array();
  
  /**
   * UsersPKOnline.
   * Array of online user PK .
   * @var array
   */
  private $users_pk_online = array();
    
  
  
  /**
   * Constructor
   * @param array - Options to override defaults
   */
  public function Online($options = null) {
    if (!is_null($options)) {
      $this->options = array_merge($this->options, $options);
    }
  }

  /**
   * GetUsersOnline.
   * Gets all users that are online.
   * Including the current user.
   *
   * Ex. Result:
   * Array([0] => ("id" => 1 ... other data),
   *       [1] => ("id" => 1 ... other data));
   * 
   * Note: Empty array if none found.
   * @return array
   */
  public function get_users_online() {
    $this->update_users_online();
    return $this->users_online;
  }

  /**
   * GetUsersPKOnline.
   * Gets all user PK that are online.
   * Including the current user.
   *
   * Ex. Result:
   * Array([0] => 1,
   *       [1] => 231);
   * 
   * Note: Empty array if none found.
   * @return array
   */
  public function get_users_pk_online() {
    $this->update_users_online();
    return $this->users_pk_online;
  }  

  /**
   * IsUserOnline.
   * Checks whether a user is online.
   * @param mixed $user_pk
   * @return bool
   */
  public function is_user_online($user_pk) {
    $this->update_users_online();
    if (in_array($user_pk, $this->users_pk_online)) {
      return TRUE;
    }
    return FALSE;
  }

  /**
   * GetUsersOnlineWhere.
   * Gets all users online where.
   * Can be used if an FK is stored in the session.
   * 
   * @param array $keyValueArray
   * @return array
   */
  public function get_users_online_where($keyValueArray) {
    $result = array();
    $this->update_users_online();
    foreach ($this->users_online as $online_user) {
      $ok = TRUE;
      foreach ($keyValueArray as $k => $v) {
        if ($online_user[$k] != $v) {
          $ok = FALSE;
          break;
        }
      }
      if ($ok) {
        $result[] = $online_user;
      }
    }    
    return $result;
  }
  
  /**
   * GetUsersReallyOnline.
   * Get the actual list of users online.
   * METHOD ONLY FOR IMPORTANT CASES ONLY.
   * NO CACHE USED, PERFORMANCE SUCKS.
   * Note: Empty array if none found.
   * 
   * @return array.
   */
  public function get_users_really_online() {
    $this->read_session_dir();
    return $this->users_online;
  }
  
  /**
   * GetUsersPKReallyOnline.
   * Get the actual list of user pk online.
   * METHOD ONLY FOR IMPORTANT CASES ONLY.
   * NO CACHE USED, PERFORMANCE SUCKS.
   * Note: Empty array if none found.
   * 
   * @return array.
   */
  public function get_users_pk_really_online() {
    $this->read_session_dir();
    return $this->users_pk_online;
  }  
  
  /**
   * ISUserReallyOnline.
   * Check if the users is actually online.
   * METHOD ONLY FOR IMPORTANT CASES ONLY.
   * NO CACHE USED, PERFORMANCE SUCKS.
   * 
   * @param mixed $user_pk
   * @return bool.
   */  
  public function is_user_really_online($user_pk) {
    $this->read_session_dir();
    if (in_array($user_pk, $this->users_pk_online)) {
      return TRUE;
    }
    return FALSE;
  }
  
  /**
   * AddUserToCache.
   * Adds a user to cache if he is not there.
   * Can be used for "afterLogin" methods.
   * 
   * @param array $user
   * @param string $id_key
   * @return bool - added or not
   */
  public function add_user_to_cache($user, $id_key = 'id') {
    if (empty($this->users_online)) {
      $this->update_users_online();
    }
    if (!$this->is_user_online($user[$id_key])) {
      $online_user = array();
      foreach ($this->options['user_session_data'] as $key) {
        $online_user[$key] = $user[$key];
      }
      $this->users_online[] = $online_user;
      $this->write_cache($this->users_online);
      return TRUE;
    }
    return FALSE;
  }
  
  /**
   * RemoveUserFromCache.
   * Removes user from cache.
   * Can be used for "logout" methods.
   * 
   * @param mixed $value
   * @param string $key
   * @return bool - removed or not
   */
  public function remove_user_from_cache($value, $key = 'id') {
    if (empty($this->users_online)) {
      $this->update_users_online();
    }
    $online_key = null;
    foreach ($this->users_online as $k => $online_user) {
      if ($online_user[$key] == $value) {
        $online_key = $k;
        break;    
      }
    }
    if (!is_null($online_key)) {
      unset($this->users_online[$online_key]);
      $this->write_cache($this->users_online);
      return TRUE;            
    }
    return FALSE; 
  }  
  
  /**
   * Clear Cache.
   */  
  public function clear_cache() {
    $cache_file = BASEPATH . $this->options['cache_dir'] . '/' . $this->options['cache_file'];
    if (file_exists($cache_file)) {
      @unlink($cache_file);
    }
  }

  
  // ############################################################################ //
  // #############################  CACHE METHODS #########$##################### //
  // ############################################################################ //

  /**
   * ReadCache.
   * Reads the array of users online from cache.
   */
  private function read_cache() {
    $cache_file = BASEPATH . $this->options['cache_dir'] . '/' . $this->options['cache_file'];
    if (file_exists($cache_file)) {
      $cache_life_time = time() - filemtime($cache_file);
      if ($cache_life_time > $this->options['cache_ttl'] * 60) {
        return FALSE;
      }
      $fh = fopen($cache_file, 'r');
      $users_online = null;
      if (filesize($cache_file) > 0) {
        $users_online = fread($fh, filesize($cache_file));
      }
      fclose($fh);
      if (!is_null($users_online)) {
        $result = unserialize($users_online);
        $this->users_online = $result['users_online'];
        $this->users_pk_online = $result['users_pk_online'];
        return TRUE;        
      }
    }
    return FALSE;
  }
  

  /**
   * WriteCache.
   * Writes the array of users to cache.
   */
  private function write_cache() {
    $cache_file = BASEPATH . $this->options['cache_dir'] . '/' . $this->options['cache_file'];
    $fh = fopen($cache_file, 'w');
    flock($fh, LOCK_EX);
    $online_users = array();
    $online_users['users_online'] = $this->users_online;
    $online_users['users_pk_online'] = $this->users_pk_online;
    fwrite($fh, serialize($online_users));
    flock($fh, LOCK_UN);
    fclose($fh);
  }


  // ############################################################################ //
  // #############################  INTERNAL METHODS ############################ //
  // ############################################################################ //

  /**
   * ReadSessionDir.
   * Reads all files in /tmp/session dir.
   * Takes the data from these files and returns it.
   *
   * Ex. Result:
   * Array([0] => ("id" => 1 ... other data),
   *       [1] => ("id" => 1 ... other data));
   *
   * @return array
   */
  private function read_session_dir() {
    $users_online_array = array();
    $users_online_pk_array = array();
    
    $session_dir = session_save_path();
    $dh = @opendir($session_dir);
    if ($dh) {
      while (FALSE !== ($file = readdir($dh))) {
        if($file != '.' && $file != '..' && strpos($file, 'sess_') === 0) {
          $s_id = substr($file, strlen('sess_'), strlen($file));
          if (isset($_COOKIE[session_name()]) && $s_id == $_COOKIE[session_name()]) {
            continue;
          }
          $file = $session_dir . '/' . $file;
          $last_update_time = time() - filemtime($file);
          if($last_update_time < $this->options['session_time'] * 60) {
            $sess_data = null;
            $fh = fopen($file, 'r');
            if (filesize($file) > 0) {
              $sess_data = fread($fh, filesize($file));
            }
            fclose($fh);
            if ($sess_data) {
              $sess_data = $this->parse_sess_data_str($sess_data);
              if ($sess_data && isset($sess_data[$this->options['user_session_key']])) {
                $sess_data = $sess_data[$this->options['user_session_key']];
                $users_online_pk_array[] = $sess_data[$this->options['user_session_pk']];
                if (count($this->options['user_session_data']) > 0) {
                  $online_user = array();
                  foreach ($this->options['user_session_data'] as $key) {
                    $online_user[$key] = $sess_data[$key];
                  }
                  $users_online_array[] = $online_user;
                }
              }
            }
          }
        }
      }
    }

    // Add the current user to "online" list
    if (isset($_SESSION[$this->options['user_session_key']])) {
      $sess_data = $_SESSION[$this->options['user_session_key']];
      $users_online_pk_array[] = $sess_data[$this->options['user_session_pk']];
      if (count($this->options['user_session_data']) > 0) {
        $online_user = array();
        foreach ($this->options['user_session_data'] as $key) {
          $online_user[$key] = $sess_data[$key];
        }
        $users_online_array[] = $online_user;
      }
    }
    
    $this->users_online = $users_online_array;
    $this->users_pk_online = $users_online_pk_array;
  }

  /**
   * Updates the private array with users
   * from cache or fresh users.
   */
  private function update_users_online() {
    if (empty($this->users_online) || empty($this->users_pk_online)) {
      if (!$this->read_cache()) {
        $this->read_session_dir();
        $this->write_cache();
      }
    }
  }

  /**
   * ParseSessDataStr.
   * Creates an array from the session data string.
   * Session data string "entity|a:23{....}".
   * @param string $sess_data_str
   * @return array
   */
  private function parse_sess_data_str($sess_data_str) {
    $result = FALSE;
    $old = $_SESSION;
    $_SESSION = array();
    $ret = session_decode($sess_data_str);
    if (!$ret) {
      $_SESSION = array();
      $_SESSION = $old;
      return $result;
    }
    $result = $_SESSION;
    $_SESSION = array();
    $_SESSION = $old;
    return $result;
  }

}
?>