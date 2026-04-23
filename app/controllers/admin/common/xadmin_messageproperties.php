<?php
require_once APPPATH . 'controllers/admin/base/base_admin_controller.php';

/**
 * Xadmin controller.
 * @author Itirra - http://itirra.com
 */
class xAdmin_MessageProperties extends Base_Admin_Controller {
    
  /**
   * All system languages
   * @var array
   */
  protected $languages = array();
  
  /**
   * Constructor.
   */
  public function  xAdmin_MessageProperties(){
    parent::Base_Admin_Controller();
    $this->load->helper('file');
    $this->load->config('lang_config');
    $this->languages = $this->config->item('languages');
    if(empty($this->languages)) throw new Exception();
  }
  
  /**
   * @see Base_Admin_Lang_Controller::init()
   */
  protected function init() {
    $this->entityName = str_replace($this->classPrefix, "", get_class($this));
    $this->entityUrlName = $this->entityName;
    
    // Uset folder for image
    $folder = $this->session->userdata(self::FOLDER_SESSION_KEY);
    if (strtolower($this->entityName) != 'resource') {
      $this->session->set_userdata(self::FOLDER_SESSION_KEY, strtolower($this->entityName));
    }
   
    $menuItems = $this->config->item("menu_items", "admin");
    if (is_array($menuItems) && !empty($menuItems)) $this->menuItems = $menuItems;

    $baseRoute = $this->config->item("base_route", "admin");
    if ($baseRoute !== FALSE) {
      $this->adminBaseRoute = $baseRoute;
    }
        
    $this->processListUrl = $this->adminBaseRoute . '/' . strtolower($this->entityUrlName) . '/process_list';

    $admin = $this->session->userdata(self::LOGGED_IN_ADMIN_SESSION_KEY);
    $segment = 2;
    if (empty($this->adminBaseRoute)) $segment = 1;
    if (!$admin
        && $this->uri->segment($segment) != "login"
        && $this->uri->segment($segment) != "forgot_password"
        && $this->uri->segment($segment > 1?$segment-1:1) != "email-image"
        && $this->uri->segment($segment>1?$segment-1:1) != "go-to-url"
        && $this->uri->segment($segment>1?$segment-1:1) != "unsubscribe") {
      if (uri_string() != '/' . $this->adminBaseRoute) {
        $this->session->set_userdata(self::SAVED_URL_SESSION_KEY, current_url());
        set_flash_warning('admin.need_to_login_message');
      }
      redirect($this->adminBaseRoute . '/login');
    }

    $this->loggedInAdmin = $admin;
    $header["title"] = $this->lang->line("admin.title");
    $this->layout->set("header", $header);
    $this->layout->setLayout("admin");
    $this->layout->setModule("admin");
    $this->layout->set("lang", $this->lang);
    $this->layout->set("loggedInAdmin", $admin);
    $this->layout->set("adminBaseRoute", $this->adminBaseRoute);
  }
  
  /**
   * Index.
   */
  public function index() {
    $this->checkPermissions('_view');
    try {
      $content = $this->getDataFromLaguageFiles();
    } catch (Exception $e) {
      log_message('error', $e->getTraceAsString());
      set_flash_error($e->getMessage());
    }
    $this->layout->set("processLink", $this->adminBaseRoute . '/' .  strtolower($this->entityUrlName) . '/add_edit_process');
    $this->layout->set("content", $content);
    $this->layout->set("menuItems", $this->menuItems);
    $this->layout->set("entityName", $this->entityName);
    $this->layout->set("hasSidebar", TRUE);
    $this->layout->view('parts/message_properties');
  }
  
  /**
   *
   * @see Base_Admin_Controller::add_edit_process()
   */
  public function add_edit_process() {
    $this->updateDataFromLaguageFiles($_POST);
    set_flash_notice('Message properties updated.');
    $this->redirectToReffer();
  }
  
  /**
   * GetDataFromLaguagesFile
   * @return string $content
   */
  protected function getDataFromLaguageFiles() {
    $content = array();
    try {
      $data = null;
      foreach ($this->languages as $key => $language) {
        $data = read_file($this->getFileLocation($language));
        $content[$key] = $data;
      }
    } catch (Exception $e) {
      log_message('error', $e->getTraceAsString());
      set_flash_error($e->getMessage());
    }
    return $content;
  }
  
  /**
   * UpdateDataFromLaguageFiles
   * @return boolean
   */
  protected function updateDataFromLaguageFiles($entity) {
    try {
      foreach ($entity as $key => $data) {
        if(!empty($this->languages[$key])) {
          write_file($this->getFileLocation($this->languages[$key]), $data, 'w');
        }
      }
    } catch (Exception $e) {
      log_message('error', $e->getTraceAsString());
      set_flash_error($e->getMessage());
      return FALSE;
    }
    return TRUE;
  }
  
  /**
   * GetFileLocation
   * @param string $lang_folder
   */
  protected function getFileLocation($lang_folder) {
    return APPPATH .'/language/' . $lang_folder . '/message_properties_lang.php'; //Do abstract
  }
  
}