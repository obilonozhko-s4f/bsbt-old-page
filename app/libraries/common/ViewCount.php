<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ViewCount library
 * To count "views" of an entity (page)
 * Itirra - http://itirra.com
 * @author Alexei Chizhmakov (Itirra - www.itirra.com) 
 */
class ViewCount {

  /** Key for data storage. */
  const DATA_KEY = "VIEW_COUNT_DATA";

  /** View Data Array. */
  protected $data = array();
  
  /** Data storage object. */
  protected $dataStorageObj;

  
  /**
   * Constructor.
   * @return ViewCount
   */
  public function ViewCount () {
    $CI =& get_instance();
    $this->dataStorageObj = $CI->session;
    $this->read();
  }
  
  // -----------------------------------------------------------------------------------------
  // ------------------------------------ PUBLIC METHODS -------------------------------------
  // -----------------------------------------------------------------------------------------    

  /**
   * Add header to CSV string.
   * @param array $values
   */
  public function count($key, $id) {
    $result = TRUE;
    $kData = array();
    if (isset($this->data[$key])) {
      $kData = $this->data[$key];
    } 
    
    if (!isset($kData[$id])) {
      $kData[$id] = TRUE;
      $result = FALSE;
    }
    
    $this->data[$key] = $kData;
    $this->save();
    
    return $result;
  }
  
  /**
  * Add header to CSV string.
  * @param array $values
  */
  public function isVisitCountedReadOnly($key, $id) {    
    return isset($this->data[$key]) && isset($this->data[$key][$id]);
  }
  
  /**
  * Add header to CSV string.
  * @param array $values
  */
  public function isVisitCounted($key, $id) {
    return $this->count($key, $id);
  }

  
  // -----------------------------------------------------------------------------------------
  // --------------------------------- INTERNALL METHODS -------------------------------------
  // -----------------------------------------------------------------------------------------    
  
  /**
   * Save data. 
   */
  private function save() {
    $this->dataStorageObj->set_userdata(self::DATA_KEY, $this->data);    
  }
  
  /**
   * Read data. 
   */
  private function read() {
   $this->data = $this->dataStorageObj->userdata(self::DATA_KEY);        
  }  

}
?>