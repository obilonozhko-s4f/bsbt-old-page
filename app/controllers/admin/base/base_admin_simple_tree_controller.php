<?php
require_once APPPATH . 'controllers/admin/base/base_admin_controller.php';

/**
 * xAdmin tree controller.
 * @author Itirra - http://itirra.com
 */
abstract class Base_Admin_Simple_Tree_Controller extends Base_Admin_Controller {
  
  /** Parameter for list page .*/
  protected $listParams = array();
  
  /** AddEdit entity fields. */
  protected $fields = array();

  /** Is list sortable. */
  protected $isListSortable = false;
  
  /** Delete all url. */
  protected $deleteAllUrl;
  
  /**
   * Constructor.
   * @return 
   */
  public function Base_Admin_Simple_Tree_Controller() {
    parent::Base_Admin_Controller();
  }
  
  /**
   * (non-PHPdoc)
   * @see Base_Admin_Controller::init()
   */
  public function init() {
    parent::init();
    $this->actions["add_child"] = $this->adminBaseRoute . '/' . strtolower($this->entityName) . '/add_child';
    $this->deleteAllUrl = $this->adminBaseRoute . '/' . strtolower($this->entityName) . '/delete_all';
  }
  
  /**
   * (non-PHPdoc)
   * @see Base_Admin_Controller::index()
   */
  public function index($page = "page1", $sortBy = null) {
    $this->checkPermissions('_view');
    $entities = ManagerHolder::get($this->entityName)->getAsArray();
    $this->setViewParamsIndex($entities, true);
    $this->layout->view("tree_list");
  }
  
  /**
   * (non-PHPdoc)
   * @see Base_Admin_Controller::setViewParamsIndex()
   */
  protected function setViewParamsIndex(&$entities, $hasSidebar) {
    $this->layout->set("processListUrl", $this->processListUrl);
    $this->layout->set("deleteAllUrl", $this->deleteAllUrl);
    $this->layout->set("isListSortable", $this->isListSortable);
    $this->layout->set("actions", $this->actions);
    $this->layout->set("hasSidebar", $hasSidebar);
    $this->layout->set("menuItems", $this->menuItems);
    $this->layout->set("params", $this->listParams);
    $this->layout->set("entities", $entities);
    $this->layout->set("name_field", ManagerHolder::get($this->entityName)->getNameField());
  }
  
  /**
   * Add child.
   * @param $entityId
   * @param $parentId
   */
  public function add_child($parentId, $entityId = null) {
    if ($entityId) {
      redirect($this->adminBaseRoute . '/' . strtolower($this->entityName) . '/add_edit/' . $entityId);
    }
    $this->add_edit(null, $parentId);
  }
  
  /**
   * Add edit entity.
   * @param $entityId
   * @param $parentId
   */
  public function add_edit($entityId = null, $parentId = null) {
    $entity = new $this->entityName;
    if ($entityId) {
      $entity = ManagerHolder::get($this->entityName)->getFullById($entityId);
    }
    
    $options = array();
    $root = ManagerHolder::get($this->entityName)->getRoot();
    if (!$root || $entity->getNode()->isRoot()) {
      $options[] = $this->lang->line("admin.add_edit." . strtolower($this->entityName) . ".default_parent_category_value");
    } else {
      if ($entityId) {
        $without = ManagerHolder::get($this->entityName)->getDescendantIds($entity);
        $without[] = $entityId;
        $options = ManagerHolder::get($this->entityName)->getAsViewArray($without);
      } else {
        $options = ManagerHolder::get($this->entityName)->getAsViewArray();
      }
    }
    $this->fields["parent_id"]['type'] = 'select';
    $this->fields["parent_id"]["options"] = $options;
    
    $newFields = array("parent_id" => $this->fields["parent_id"]);
    unset($this->fields["parent_id"]);
    foreach ($this->fields as $k => $v) {
      $newFields[$k] = $v;
    }
    $this->fields = $newFields;    

    
    if ($entityId) {
      if ($entity->getNode()->hasParent() || $entity->getNode()->isRoot()) {
        ManagerHolder::get($this->entityName)->getTree()->resetBaseQuery();
        $children = $entity->getNode()->getChildren();
        if ($children) {
          $this->fields["children"]['type'] = 'sortable';
          $childViewArray = array();
          foreach ($children as &$child) {
            $childViewArray[$child['id']] = $child[ManagerHolder::get($this->entityName)->getNameField()]; 
          }
          $this->fields["children"]['options'] = $childViewArray;
        }
      }
    }
    
    $parent = $entity->getNode()->getParent();
    if ($entity['id']) {
      $entity = ManagerHolder::get($this->entityName)->getById($entity['id']);
    } else {
      $entity = $entity->toArray();
    }
    $entity["parent_id"] = '';
    if ($parent) {
      $entity["parent_id"] = $parent['id'];
    } else if ($parentId) {
      $entity["parent_id"] = $parentId;  
    }
    
    $this->layout->set("fields", $this->fields);
    $this->layout->set("entity", $entity);
    $this->layout->set("backUrl", $this->adminBaseRoute . '/' .  strtolower($this->entityName));
    $this->layout->set("processLink", $this->adminBaseRoute . '/' .  strtolower($this->entityName) . '/add_edit_process');
    $this->layout->view("add_edit_entity");
  }    
    
          
  /**
   * Add edit process.
   */
  public function add_edit_process() {
    if (!empty($_POST["id"])) {
      $entity = ManagerHolder::get($this->entityName)->getFullById($_POST["id"]);
    }        
    $this->preProcessPost();
    
    $entity = ManagerHolder::get($this->entityName)->createEntityFromPOST();
    $this->isValid($entity);
    
    $this->loadAndResizeImages(&$entity);
    
    if (empty($_POST["id"])) {
      
      try {
        $id = ManagerHolder::get($this->entityName)->insert($entity);
      } catch (Exception $e) {
        trace($e->getCode());
        die();
      }
      if (empty($_POST["parent_id"])) {
        ManagerHolder::get($this->entityName)->getTree()->createRoot($entity);
      } else {
        $parent = ManagerHolder::get($this->entityName)->getFullById($_POST["parent_id"]);
        $entity->getNode()->insertAsLastChildOf($parent);
      }
      set_flash_notice('admin.messages.' . strtolower($this->entityName) . '.add');
      if (isset($_POST['save_and_return_to_list'])) {
        redirect($this->adminBaseRoute . '/' .  strtolower($this->entityUrlName));
      } else if (isset($_POST['save_and_add_new'])) {
        redirect($this->adminBaseRoute . '/' . strtolower($this->entityUrlName) . '/add_edit');
      }       
      
      if (empty($_POST["parent_id"])) {
        $this->redirectToReffer($id);
      } else {
        redirect($this->adminBaseRoute . '/' . strtolower($this->entityUrlName) . '/add_edit/' . $id);
      }
    } else {
      ManagerHolder::get($this->entityName)->update($entity);
      if (empty($_POST["parent_id"])) {
        if (!$entity->getNode()->isRoot()) {
          $entity->getNode()->makeRoot($entity->id);
        } 
      } else {
        
        $parentId = null;
        $parent = $entity->getNode()->getParent();
        if ($parent) {
          $parentId = $parent->id;   
        }
        if ($_POST["parent_id"] != $entity['id'] && $_POST["parent_id"] != $parentId) {
         $parent = ManagerHolder::get($this->entityName)->getFullById($_POST["parent_id"]);
         $entity->getNode()->moveAsLastChildOf($parent);
        } 
      }
      
      if (isset($_POST["children"])) {
        foreach ($_POST["children"] as $chldId) {
          $chld = ManagerHolder::get($this->entityName)->getFullById($chldId);
          $chld->getNode()->moveAsLastChildOf($entity);
        }
      }
      set_flash_notice('admin.messages.' . strtolower($this->entityName) . '.edit');
      
      if (isset($_POST['save_and_return_to_list'])) {
        redirect($this->adminBaseRoute . '/' .  strtolower($this->entityUrlName));
      } else if (isset($_POST['save_and_add_new'])) {
        redirect($this->adminBaseRoute . '/' . strtolower($this->entityUrlName) . '/add_edit');
      } 
      $this->redirectToReffer();
    }
  }
  
  /**
   * Get Page Url By Id.
   */
  public function get_page_url() {
    if (isset($_POST["id"])) {
      if ($_POST["id"] === 0) {
        $entity["page_url"] = $this->fields['page_url']['attrs']['startwith'];
      } else {
        $entity = ManagerHolder::get($this->entityName)->getById($_POST["id"], "page_url, level");
      }
      die("{\"error\": false, \"page_url\": \"" . $entity["page_url"] . "\", \"level\": \"" . $entity["level"] . "\"}");  
    }
    die("{\"error\": true}");
  }  
  
}
