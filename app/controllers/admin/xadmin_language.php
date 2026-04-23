<?php
require_once APPPATH . 'controllers/admin/base/base_admin_controller.php';

/**
 * Xadmin controller.
 * @author Itirra - http://itirra.com
 */
class xAdmin_Language extends Base_Admin_Controller {
  
  
  /** Is delete all action allowed */
  protected $isDeleteAllAllowed = FALSE;
  
  /**
   * SetAddEditDataAndShowView.
   * Set all needed view data and show add_edit form.
   * @param object $entity
   */
  protected function setAddEditDataAndShowView($entity) {
    if ($entity['url_name'] == 'en') {
      $this->fields['name']['attrs'] = array('disabled' => 'disabled' );
      $this->fields['url_name']['attrs'] = array('disabled' => 'disabled' );
    }
    parent::setAddEditDataAndShowView($entity);
  }
  
  /**
   * Implementation of POST_INSERT event callback
   * @param Object $entity reference
   * @return Object
   */
  protected function postInsert(&$entity) {
    $this->load->helper('file');
    if (!is_dir('./app/language/' . $entity['name'])) {
      @mkdir('./app/language/' . $entity['name'], 0777, true);
      @copy('./app/language/english/message_properties_lang.php', './app/language/' . $entity['name'] . '/message_properties_lang.php');
    }
  }
  
  
  
  
  
  
  /**
   * DeleteFromDb.
   * Deletes entity from DB and its images.
   * @param integer $entityId
   * @return Object (the deleted entity)
   */
  protected function deleteFromDb($entityId) {
    $entity = ManagerHolder::get($this->managerName)->getById($entityId);
    if ($entity) {
      if ($entity['url_name'] == 'en') {
        set_flash_error('You can\'t delete the english language');
        $this->redirectToReffer();
      }
      $this->preDelete($entityId);
      ManagerHolder::get($this->managerName)->deleteById($entityId);
      $this->postDelete($entityId);
    }
    return $entity;
  }
  
  
}