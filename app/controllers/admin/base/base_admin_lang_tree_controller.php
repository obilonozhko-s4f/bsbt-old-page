<?php
require_once APPPATH . 'controllers/admin/base/base_admin_tree_controller.php';

/**
 * xAdmin tree controller.
 * @author Itirra - http://itirra.com
 */
abstract class Base_Admin_Lang_Tree_Controller extends Base_Admin_Tree_Controller {
 
  /**
   * (non-PHPdoc)
   * @see Base_Admin_Controller::init()
   */
  protected function init() {
    parent::init();
    
    // Config
    $this->load->config('lang_config');
    
    ManagerHolder::setLanguage('en');
    ManagerHolder::get($this->managerName)->setLanguage(null);
  }
  
  /**
   * @see Base_Admin_Controller::index()
   */
  public function index($page = "page1") {
    ManagerHolder::get($this->managerName)->setLanguage('en');
    parent::index($page);
  }
  
  /**
   * Change Root order
   */
  public function change_root_order() {
    ManagerHolder::get($this->managerName)->setLanguage('en');
    parent::change_root_order();
  }

  /**
   * SetAddEditDataAndShowView.
   * Set all needed view data and show add_edit form.
   * @param object $entity
   */
  protected function setAddEditDataAndShowView($entity) {
    $langs = config_item('languages');
    if (!isset($entity['id']) || empty($entity['id'])) {
      foreach ($langs as $k => $l) {
        $lang = array('language' => $k);
        foreach (ManagerHolder::get($this->managerName)->i18nFields as $f) {
          $lang[$f] = "";
        }
        $entity[ManagerHolder::get($this->managerName)->translationTableAlias][] = $lang;
      }
    }
    $this->layout->set("languages", array_keys($langs));
    $this->layout->set("i18nFields", ManagerHolder::get($this->managerName)->i18nFields);
    parent::setAddEditDataAndShowView($entity);
  }
  
	/**
   * CreateEntityPOST.
   * Prepares POST.
   * Creates Entity From Post.
   * Validates Entity.
   *
   * @return Object
   */
  protected function createEntityPOST() {
    $this->preProcessPost();
    $langs = config_item('languages');
    $langRelTrans = array();
    foreach ($langs as $k => $l) {
      $lang = array('language' => $k);
      $langRel = array('language' => $k);
      $langRelAlias = "";
      foreach (ManagerHolder::get($this->managerName)->i18nFields as $f) {
        $key = $f;
        if (strstr($key, '.') !== FALSE) {
          $arr = explode('.', $key);
          $key = str_replace('.', '_', $key);
          $langRel[$arr[1]] = $_POST[$k . '_' . $key];
          $langRelAlias = $arr[0];
        } else {
          $lang[$f] = $_POST[$k . '_' . $key];
        }
        unset($_POST[$k . '_' . $key]);
      }
      $entity[ManagerHolder::get($this->managerName)->translationTableAlias][] = $lang;
      if (!empty($langRelAlias)) {
        $langRelTrans[$langRelAlias]['translations'][] = $langRel;
      }
    }
    $entity = array_merge($entity, $_POST);
    $entity = ManagerHolder::get($this->managerName)->createEntityFromArray($entity);
    
    if (!empty($langRelTrans)) {
      $rels = ManagerHolder::get($this->managerName)->getRelations();
      foreach ($langRelTrans as $alias => $transes) {
        if (isset($entity[$alias]['id']) && !empty($entity[$alias]['id'])) {
          $ent = $entity[$alias];
          $ent->synchronizeWithArray($transes);
          ManagerHolder::get($rels[$alias])->update($ent);
        } else {
          $ent = ManagerHolder::get($rels[$alias])->createEntityFromArray($transes);
          $ent['id'] = ManagerHolder::get($rels[$alias])->insert($ent);
          $entity->$alias = $ent;
        }
      }
    }
    
    $this->isValid($entity);
    
    return $entity;
  }

 /**
   * Add edit entity.
   * @param $entityId
   * @param $parentId
   */
  public function add_edit($entityId = null, $parentId = null) {
    $entity = new $this->entityName;
    $entity = $entity->toArray();
    if ($entityId) {
      $entity = ManagerHolder::get($this->entityName)->getById($entityId);
    }
    $options = array();
    $options[] = $this->lang->line("admin.add_edit." . strtolower($this->entityName) . ".default_parent_category_value");
    if ($entityId) {
      ManagerHolder::get($this->managerName)->setLanguage('en');
      $treeViewArray = ManagerHolder::get($this->entityName)->getTreeAsViewArrayWhithout($entityId);
      ManagerHolder::get($this->managerName)->setLanguage(null);
    } else {
      ManagerHolder::get($this->managerName)->setLanguage('en');
      $treeViewArray = ManagerHolder::get($this->entityName)->getTreeAsViewArray();
      ManagerHolder::get($this->managerName)->setLanguage(null);
    }
    
    
    if (!empty($treeViewArray)) {
      $options = $options + $treeViewArray;
    }
    
    $this->fields["parent_id"]['type'] = 'select';
    $this->fields["parent_id"]["options"] = $options;
    $entity["parent_id"] = "";
    if ($parentId) {
      $entity["parent_id"] = $parentId;
      if (isset($this->fields[$this->urlField]) && isset($this->fields[$this->urlField]['attrs']['startwith'])) {
        $parent = ManagerHolder::get($this->entityName)->getById($parentId);
        if (!empty($parent[$this->urlField])) {
          $this->fields[$this->urlField]['attrs']['startwith'] = $parent[$this->urlField];
          if (!$entityId) {
            $entity[$this->urlField]  = $parent[$this->urlField];
          }
        }
      }
    }
        
    if ($entityId) {
      $parent = ManagerHolder::get($this->entityName)->getParent($entity);
      if (isset($this->fields[$this->urlField]) && isset($this->fields[$this->urlField]['attrs']['startwith'])) {
        if (!empty($parent[$this->urlField])) {
          $this->fields[$this->urlField]['attrs']['startwith'] = $parent[$this->urlField];
        }
      }
      $entity["parent_id"] = $parent['id'];
      ManagerHolder::get($this->managerName)->setLanguage('en');
      $children = ManagerHolder::get($this->entityName)->getChildren($entity);
      ManagerHolder::get($this->managerName)->setLanguage(null);
      if ($children) {
        $childViewArray = array();
        foreach ($children as &$child) {
          $childViewArray[$child['id']] = $child[$this->nameField];
        }
        $this->fields["children"]['type'] = 'sortable';
        $this->fields["children"]['options'] = $childViewArray;
      }
    }
 
    $this->setAddEditDataAndShowView($entity);
  }
  

  
}