<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'logic/common/ManagerHolder.php';

/**
 * Base_Controller.
 * @author Itirra - http://itirra.com
 *
 * @property CI_Loader $load
 * @property Base_Project_Controller $ci
 * @property Layout $layout
 * @property BaseManager $manager
 */
class Base_HMVC_Controller {
  
  const C_DEFAULT_PER_PAGE = 2;
  
  protected $ci;
  protected $layout;
  protected $entityName;
  protected $manager;
  public $authEntity;
  public $isLoggedIn;
  protected  $module = "";
  
  protected $viewData = array();
	
  /**
   * Constructor.
   */
  public function Base_HMVC_Controller(Controller $ci = null) {
    $this->ci = $ci;    
    $className =  get_class($this);
    $matches = array();
    preg_match("/HMVC_([a-zA-Z0-9_]+)_Controller/", get_class($this), $matches);
    $this->entityName = $matches[1];
    $this->entityName = ucfirst($this->entityName);
    $this->manager = ManagerHolder::get($this->entityName);
        
    $this->layout = new Layout();
    if ($this->module == "") $this->module = strtolower($this->entityName);
    $this->layout->setModule($this->module);
    if ($this->ci->authEntity) $this->authEntity = $this->ci->authEntity;
    if ($this->ci->isLoggedIn) $this->isLoggedIn = $this->ci->isLoggedIn;
    if ($this->ci->settings) $this->setViewData("settings", $this->ci->settings);

    $this->setViewData("isLoggedIn", $this->isLoggedIn);
    $this->setViewData("authEntity", $this->authEntity);

    $this->setViewData("layout", $this->layout);
    
  }

  
  public function item($id, $type = HMVCViewType::HTML) {    
    $item = $this->manager->getOneWhere(array("id" => $id));
    if (!$item) return FALSE;
    
    $this->setViewData("item", $item);
    $view = HMVCViewType::getView("item", $type);
  	return $this->renderView($view);
  }

  public function search($data, $type = HMVCViewType::HTML) {
    //params validation
    if (!isset($data['where'])) $data['where'] = array();
    if (isset($data['page']) && !isset($data['perPage'])) $data['perPage'] = self::C_DEFAULT_PER_PAGE;

    $viewData = array();
    $all = $this->manager->getAllLike($data["where"]['fields'], $data['where']['like'], $data["page"], $data["perPage"]);
    $this->setViewData("data", $all->data);
    $this->setViewData("pager", $all->pager);

    if (isset($data['viewData'])) $viewData = array_merge($viewData, $data['viewData']);

    //get view php
    $view = HMVCViewType::getView("all", $type);
    return $this->renderView($view);
  }


  public function all($data, $type = HMVCViewType::HTML) {
    //params validation
    if (!isset($data['where'])) $data['where'] = array();
    if (isset($data['page']) && !isset($data['perPage'])) $data['perPage'] = self::C_DEFAULT_PER_PAGE;
    
    
    $viewData = array();

    if (isset($data['orderby'])) $this->manager->setOrderBy($data['orderby']);

    if (isset($data['page']) && isset($data['perPage'])) {
      $all = $this->manager->getAllWhereWithPager($data["where"], $data["page"], $data["perPage"]);
      $this->setViewData("data", $all->data);
      $this->setViewData("pager", $all->pager);
    } else {
      $all = $this->manager->getAllWhere($data["where"]);
      $this->setViewData("data", $all);
    }
    
    if (isset($data['viewData'])) $this->viewData = array_merge($this->viewData, $data['viewData']);


    //get view php
    $view = HMVCViewType::getView("all", $type);
  	return $this->renderView($view);  
  }  

  public function add_edit($id = null) {
    die ('not implemented');
  }
  
  public function add_edit_process($dataArray) {
    $entity =  $this->manager->createEntityFromArray($dataArray);
    $this->manager->insert($entity);
    return $entity;
  }
  
  public function emptyResults($data = array(), $type = HMVCViewType::HTML) {
    
    $this->setViewData("item", $data);
    $view = HMVCViewType::getView("empty_results", $type);
  	return $this->renderView($view);
  }
  
  
  public function delete() {
  }
  
  protected function renderView($view) {    
    $html = "";    
    if (!$this->layout->hasViewInModule($view)) {
      $this->layout->setModule("hmvc");
    }
    $html = $this->layout->renderInModule($view, $this->viewData, TRUE);
    $this->layout->setModule($this->module); //set back module to entity
    return $html;
  }
  
  public function setViewData($key, $data = false) {
    if (is_array($key)) {
      $this->viewData = array_merge($this->viewData, $key);
    } else {
      $this->viewData[$key] = $data;
    }
  }
  
}

class HMVCViewType {
  const HTML = 1;
  const JSON = 2;
  private static $views = array(
    "all" => array (self::HTML => "all.php", self::JSON => "all_json.php"),
    "item" => array (self::HTML => "one.php", self::JSON => "one_json.php"),
  	"empty_results" => array (self::HTML => "empty_results.php", self::JSON => "empty_results_json.php")
  );
  
  static function getView($action, $type) {
    if (isset(self::$views[$action][$type])) return self::$views[$action][$type];
    return $type;
  }
}

class HMVC {
  static $instances = array();
  
  /**
   * 
   * lazy load hmvcs
   * @param $entityName
   * @param Controller $ci
   * @return Base_HMVC_Controller controller
   */
  static function get($entityName, Controller $ci) {
    if (!isset(self::$instances[$entityName])) {
      require_once APPPATH . "controllers/hmvc/hmvc_". strtolower($entityName)  ."_controller.php";
      $className = "HMVC_".$entityName."_Controller";
      $inst = new $className($ci);
      self::$instances[$entityName] = $inst;
    }
    return self::$instances[$entityName];
  }
}