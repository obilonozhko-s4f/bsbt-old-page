<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'controllers/base/base_controller.php';

/**
 * BasePilController;
 */
class Base_Project_Controller extends Base_Controller {
  
  /** Libraries to load.*/
  protected $libraries = array('common/DoctrineLoader', 'Session');
  

  /** Helpers to load.*/
  protected $helpers = array('common/itirra_language',
  							             'url',
                             'text',
                             'project',
                             'common/itirra_resources',
                             'common/itirra_date',
                             'common/itirra_pager',
                             'common/itirra_messages');

  /**
   * Constructor
   */
  public function Base_Project_Controller() {
    parent::Base_Controller();
    
    ManagerHolder::setLanguage($this->lang->lang());

    $cities = ManagerHolder::get('City')->getAsViewArray(array(), 'title');
    $this->layout->set('cities', $cities);
  
    $objecttypes = ManagerHolder::get('ObjectType')->getAsArray();
    $this->layout->set('objecttypes', $objecttypes);

    $pages = ManagerHolder::get('Page')->getWhere(array('is_in_nav' => TRUE, 'is_published' => TRUE));
    $this->layout->set('pages', $pages);

    $settings = ManagerHolder::get('Settings')->getAllKVGrouped();
    $this->layout->set('settings', $settings);
  }
  
  /**
   * Load language.
   */
  protected function loadLanguage() {
    global $gLangs;
    $this->lang->load('message_properties', $gLangs[$this->lang->lang()]);
  }
  
  
  /**
   * Set headers
   * @param array $entity
   * @param string $pageName
   */
  protected function setHeaders($entity = null, $pageName = null) {
    if ($entity && isset($entity['header'])) {
      $this->layout->set('header', $entity['header']);
    }
    if ($pageName) {
      $settings = ManagerHolder::get('Settings')->getAllKV();
      $header = array();
      if (isset($settings[$pageName . '_title'])) {
        $header['title'] = $settings[$pageName . '_title'];
      }
      if (isset($settings[$pageName . '_description'])) {
        $header['description'] = $settings[$pageName . '_description'];
      }
      $this->layout->set('header', $header);
    }
  }
  
}