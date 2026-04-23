<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Registry library
 * Itirra - http://itirra.com
 */
class Registry {
  
  /** Path to registry file. */
  private $filePath = "./lib/cache/registry.php";
  
  /** The registry data. */
  private $data = array();
  
  /** Access denied security. */
  const ACCESS_DENIED = '<?php die("Access Denied"); ?>';
  
  /**
   * Constructor.
   * @return Registry
   */
  public function Registry () {
    $this->loadData();
  }
  
  
  /**
   * Save a value to the registry.
   * @param string $key
   * @param mixed $value
   */
  public function set($key, $value, $saveToFile = TRUE) {
    $this->data[$key] = $value;
    if ($saveToFile) {
      $this->saveData();
    }
  }
  
  /**
   * Get a value from the registry.
   * @param string $key
   */
  public function get($key) {
    if ($this->exists($key)) {
      return $this->data[$key];
    }
  }
  
  /**
   * Gets an array with all of the key<=>value
   * @return array
   */
  public function getAll() {
    return $this->data;
  }
  
  /**
   * Check if a value is set in the registry.
   * @param string $key
   */
  public function exists($key) {
    if (isset($this->data[$key])) {
      return TRUE;
    }
    return FALSE;
  }
  
  /**
   * Remove a value AND key from registry.
   * @param string $key
   */
  public function remove($key) {
    if ($this->exists($key)) {
      unset($this->data[$key]);
      $this->saveData();
    }     
  }
  
  /**
   * Loads the data from file.
   */
  public function loadData() {
    $dataFile = $this->filePath;
    $fh = @fopen($dataFile, 'r');
    if ($fh) {
      $dataStr = fgets($fh);
      fclose($fh);
      $dataStr = str_replace(self::ACCESS_DENIED, "", $dataStr);
      $this->data = unserialize(base64_decode($dataStr));
    }
  }
  
  /**
   * Save data.
   */
  public function saveData() {
    $dataFile = $this->filePath;
    $fh = @fopen($dataFile, 'w') or die("Can't open registry file");
    $data = self::ACCESS_DENIED;
    $data .= base64_encode(serialize($this->data));
    fwrite($fh, $data);
    fclose($fh);
  }


}
?>