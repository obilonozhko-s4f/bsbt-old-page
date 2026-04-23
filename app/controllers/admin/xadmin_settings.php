<?php
require_once APPPATH . 'controllers/admin/base/base_admin_lang_controller.php';

/**
 * Xadmin controller.
 * @author Alexei Chizhmakov (Itirra - http://itirra.com)
 */
class xAdmin_Settings extends Base_Admin_Lang_Controller {

  /** List view file. */
  protected $listView = 'edit_settings';

  /**
   * to_db
   */
  public function to_db(){
    foreach($this->fields as $name => $field){
      if(!ManagerHolder::get('Settings')->existsWhere(array('k' => $name))){
        $s = new Settings();
        $s['k'] = $name;
        ManagerHolder::get('Settings')->insert($s);
      }
    }
    redirect(ADMIN_BASE_ROUTE . '/settings');
  }


  /**
   * Constructor.
   * @return Base_Admin_Settings_Controller
   */
  public function Base_Admin_Settings_Controller() {
    parent::Base_Admin_Lang_Controller();
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
    ManagerHolder::get($this->managerName)->setLanguage('en');
    $this->setIndexDataAndShowView($settings);
  }

  /**
   * Set index data and show view.
   * @param $settings
   */
  protected function setIndexDataAndShowView($settings) {
    $langs = config_item('languages');
    $this->layout->set("hasSidebar", TRUE);
    $this->layout->set("menuItems", $this->menuItems);
    $this->layout->set("backUrl", '');
    $this->layout->set("fields", $this->fields);
    $this->layout->set("processLink", $this->adminBaseRoute . '/' .  strtolower($this->entityUrlName) . '/add_edit_process');
    $this->layout->set("settings", $settings);
    $this->layout->set("languages", array_keys($langs));
    $this->layout->set("i18nFields", ManagerHolder::get($this->managerName)->i18nFields);
    $this->layout->view($this->listView);
  }

  /**
   * (non-PHPdoc)
   * @see Base_Admin_Controller::add_edit_process()
   */
  public function add_edit_process() {
    // preprocessing POST
    $resultPOST = array();
    
    //hack only for tinymce if $key = "seotext"
    if(isset($_POST['radioen_home_page_seotext'])){
      unset($_POST['radioen_home_page_seotext']);
      unset($_POST['radioru_home_page_seotext']);
      unset($_POST['radiode_home_page_seotext']);
    }
 
    foreach($_POST as $key => $settingsArray){
      
      $is_match = preg_match("/([\w]{2})_(.*)/", $key, $matches);
      if($is_match){
        $lang = $matches[1];
        $group = $matches[2];
        $translationArray = array();
                
        foreach($settingsArray as $keyName => $val){
          $translationArray = $val;
          $translationArray['language'] = $lang;
          $resultPOST[$group][$keyName][] = $translationArray;
        }
      }
    }

    ManagerHolder::get($this->entityName)->updateKVGrouped($resultPOST);
    set_flash_notice('admin.messages.' . strtolower($this->entityName) . '.edit');
    $this->redirectToReffer();
  }

}