<?php
require_once APPPATH . 'controllers/admin/base/base_admin_controller.php';

/**
 * Xadmin controller.
 * @author Alexei Chizhmakov (Itirra - http://itirra.com)
 */
class Base_Admin_Settings_Controller extends Base_Admin_Controller {

  /** List view file. */
  protected $listView = 'edit_settings';
  
  /**
   * Constructor.
   * @return Base_Admin_Settings_Controller
   */
  public function Base_Admin_Settings_Controller() {
    parent::Base_Admin_Controller();
  }
  
  /**
   * Get Fields from manager.
   */
  protected function getFieldsFromManager() {
    $this->fields = ManagerHolder::get($this->managerName)->fields;
  }
  
  /**
   * Index Action.
   */
  public function index() {
    $settings = ManagerHolder::get($this->entityName)->getAllKVGrouped();
    // Images
    $images = array();
    foreach ($this->fields as $name => $params) {
      if ($params['type'] == 'image') {
        $img = ManagerHolder::get($this->entityName)->getOneWhere(array('k' => $name));
        if (isset($settings[$img['gr']][$name]) && !empty($settings[$img['gr']][$name])) {
          $settings[$img['gr']][str_replace('_id', '', $name)] = ManagerHolder::get('Image')->getById($settings[$img['gr']][$name]);
        }
      }
    }
    $this->setIndexDataAndShowView($settings);
  }
  
  /**
   * Set index data and show view.
   * @param $settings
   */
  protected function setIndexDataAndShowView($settings) {
    $this->layout->set("hasSidebar", TRUE);
    $this->layout->set("menuItems", $this->menuItems);
    $this->layout->set("backUrl", '');
    $this->layout->set("fields", $this->fields);
    $this->layout->set("processLink", $this->adminBaseRoute . '/' .  strtolower($this->entityUrlName) . '/add_edit_process');
    $this->layout->set("settings", $settings);
    $this->layout->view($this->listView);
  }
  
  /**
   * (non-PHPdoc)
   * @see Base_Admin_Controller::add_edit_process()
   */
  public function add_edit_process() {
    
    // Images
    $images = array();
    foreach ($this->fields as $name => $params) {
      if ($params['type'] == 'image') {
        $img = ManagerHolder::get($this->entityName)->getOneWhere(array('k' => $name));
        $images[$img['gr'] . '_' . $name] = array('gr' => $img['gr'], 'v' => $name);
      }
    }
    foreach ($images as $fName => $p) {
      $this->fileoperations->set_base_dir('./web/images');
      $this->fileoperations->add_folder_to_uploads_dir(strtolower($this->entityName));
      $_POST[$p['gr']][$p['v']] = $this->loadAndResizeImage($fName);
    }
    
    ManagerHolder::get($this->entityName)->updateKVGrouped($_POST);
    set_flash_notice('admin.messages.' . strtolower($this->entityName) . '.edit');
    $this->redirectToReffer();
  }
  
  /**
   * Delete_image.
   * @param integer $imageId
   */
  public function delete_image($imageId) {
    $image = $this->deleteImageFromDb($imageId);

    // Images
    $images = array();
    foreach ($this->fields as $name => $params) {
      if ($params['type'] == 'image') {
        $img = ManagerHolder::get($this->entityName)->getOneWhere(array('k' => $name));
        ManagerHolder::get($this->entityName)->updateWhere(array('k' => $img['k'], 'gr' => $img['gr']), 'v', '');
      }
    }
    
    set_flash_notice('admin.messages.image_delete');
    $this->redirectToReffer();
  }

}