<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'logic/common/ManagerHolder.php';

/**
 * Base_Controller.
 * @author Itirra - http://itirra.com
 *
 * @property CI_Loader $load
 * @property Layout $layout
 */
class Base_Controller extends Controller {
  
  /** Configs to load. */
  protected $configs = array();

	/** Default configs to load. */
  private $defaultConfigs = array('app_constants');
  
  /** Helpers to load.*/
  protected $helpers = array();

	/** Default helpers to load. */
  private $defaultHelpers = array('common/itirra_commons');
  
  /** Libraries to load.*/
  protected $libraries = array();

	/** Default libraries to load. */
  private $defaultLibraries = array('common/Layout');
  
  /** Is ajax request. */
  protected $is_ajax_request = false;
  
  /**
   * Constructor.
   */
  public function Base_Controller() {
    parent::Controller();
    header('Content-Type: text/html; charset=UTF-8');
    $this->is_ajax_request = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'));
      
    $this->loadConfigs();
    $this->loadLanguage();
    $this->loadHelpers();
    $this->loadLibraries();

    // Push GET to view
    $get = array();
    if (!empty($_GET)) {
      foreach ($_GET as $k => $v) {
        $get[$k] = urldecode($v);
      }
    }
    $this->layout->set('get', $get);
    
    $this->layout->set('isAjaxRequest', $this->is_ajax_request);
  }
  
  /**
   * Load configs.
   */
  protected function loadConfigs() {
    $cnfgs = array_merge($this->defaultConfigs, $this->configs);
    foreach ($cnfgs as $c) {
      $this->load->config($c);
    }
  }
  
  /**
   * Load language.
   */
  protected function loadLanguage() {
    $this->lang->load('message_properties', $this->config->item('language'));
  }
  
  /**
   * Load libraries.
   */
  protected function loadLibraries() {
    $libs = array_merge($this->defaultLibraries, $this->libraries);
    foreach ($libs as $l) {
      $this->load->library($l);
    }
  }
  
  /**
   * Load helpers.
   */
  protected function loadHelpers() {
    $hlprs = array_merge($this->defaultHelpers, $this->helpers);
    foreach ($hlprs as $h) {
      $this->load->helper($h);
    }
  }
  
  /**
   * Add configs.
   * @param array $array
   * @param bool $after
   */
  protected function addConfigs($array, $after = TRUE) {
  	if ($after) {
  		$this->configs = array_merge($this->configs, $array);
  	} else {
  		$this->configs = array_merge($array, $this->configs);
  	}
  }
  
	/**
   * Add libraries.
   * @param array $array
   * @param bool $after
   */
  protected function addLibraries($array, $after = TRUE) {
    if ($after) {
      $this->libraries = array_merge($this->libraries, $array);
    } else {
      $this->libraries = array_merge($array, $this->libraries);
    }
  }
  
  /**
   * Add helpers.
   * @param array $array
   * @param bool $after
   */
  protected function addHelpers($array, $after = TRUE) {
    if ($after) {
      $this->helpers = array_merge($this->helpers, $array);
    } else {
      $this->helpers = array_merge($array, $this->helpers);
    }
  }

}