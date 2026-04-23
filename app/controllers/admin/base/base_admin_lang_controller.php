<?php
require_once APPPATH . 'controllers/admin/base/base_admin_controller.php';

/**
 * Xadmin controller.
 * @author Alexei Chizhmakov (Itirra - http://itirra.com)
 */
class Base_Admin_Lang_Controller extends Base_Admin_Controller {

  /**
   * Constructor.
   */
  public function Base_Admin_Lang_Controller() {
    parent::Base_Admin_Controller();
  }
  
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
   * Index.
   */
  public function index($page = "page1") {
    ManagerHolder::get($this->managerName)->setLanguage('en');
    parent::index($page);
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
   * CreateEntityId.
   * Creates Entity By Id;
   * @param integer $entityId
   * @return Object
   */
  protected function createEntityId($entityId = null) {
    $langs = config_item('languages');
    $entity = new $this->managerName;
    $entity = $entity->toArray();
    if ($entityId) {                                  
      $entity = ManagerHolder::get($this->managerName)->getById($entityId, '*');
      if (empty($entity)) {
        redirect($this->adminBaseRoute . '/' .  strtolower($this->entityUrlName));
      }
    } else {
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
    return $entity;
  }
  
  /**
   * preProcessParams
   */
  protected function preProcessParams(){
    $what = 'e.*';
    
    foreach ($this->listParams as $k => $v) {
      if (is_array($v)) {
        foreach ($v as $kk => $vv) {
          $what .= ', ' . $kk . '.*';
        }
      }
    }
    $what = rtrim($what, ',');
    return $what;
  }
  
}