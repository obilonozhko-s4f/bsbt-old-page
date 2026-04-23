<?php
require_once APPPATH . 'controllers/admin/base/base_admin_controller.php';

/**
 * Base Top Admin Controller.
 * @author Alexei Chizhmakov (Itirra - http://itirra.com)
 */
abstract class Base_Top_Admin_Controller extends Base_Admin_Controller {
  
  /** Is entity list sortable. */  
  protected $isListSortable = TRUE;
  
  /** Prefix for Enitity. */
  protected $topPrefix = 'Top';
  
  /**
   * Constructor
   */
  public function Base_Top_Admin_Controller() {
    parent::Base_Admin_Controller();
    $this->entityName = str_replace("xAdmin_" . $this->topPrefix . '_', $this->topPrefix, get_class($this));
    unset($this->actions['edit']);
  }
  
  /**
   * overridden
   * @see src/app/controllers/admin/base/Base_Admin_Controller::add_edit_process()
   */
  public function add_edit_process() {
    try {
      parent::add_edit_process();
    } catch(Exception $ex) {
      set_flash_error($ex->getMessage());
    }
    $this->redirectToReffer();
  }
  
  
}

?>