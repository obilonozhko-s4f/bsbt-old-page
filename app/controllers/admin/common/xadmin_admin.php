<?php
require_once APPPATH . 'controllers/admin/base/base_admin_controller.php';

/**
 * Xadmin controller.
 * @author Itirra - http://itirra.com
 */
class xAdmin_Admin extends Base_Admin_Controller {

  /** Entity view file. */
  protected $itemView = 'add_edit_admin';
  
  /** Extra entitites. */
  protected $extraEntities = array();
  
  
  /**
   * Add/edit entity.
   * @param integer $entityId
   * @return void
   */
  public function add_edit($entityId = null) {
    if ($entityId) {
      unset($this->fields["password"]);
    }
    $menuItems = array_merge($this->menuItems, $this->extraEntities);
    $this->layout->set("menuItems", $menuItems);
    $this->layout->set("permissionsProcessLink", $this->adminBaseRoute . '/' . strtolower($this->entityName) . '/permissions_process');
    parent::add_edit($entityId);  
  }
  
  /**
   * Add/edit process.
   * @return void
   */
  public function add_edit_process() {
  	if (!isset($_POST['id'])) {
  	  $data = $_POST;
  	  $data['login_url'] = site_url($this->adminBaseRoute . "/login");
  	  ManagerHolder::get("Email")->sendTemplate($data['email'], 'admin_new_administrator', $data);
      $_POST["password"] = md5($_POST["password"]);
  	}
    parent::add_edit_process();
  }
  
  /**
   * Permissions process.
   * @return void
   */
  public function permissions_process() {
    $adminId = $_POST["id"];
    unset($_POST["id"]);
    $permissions = array();
    foreach ($_POST as $k => $v) {
      $permissions[] = $k;
    }
    ManagerHolder::get($this->entityName)->updateById($adminId, 'permissions', implode('|', $permissions));
    set_flash_notice('admin.messages.' . strtolower($this->entityName) . '.edit');
    $admin = $this->session->userdata(self::LOGGED_IN_ADMIN_SESSION_KEY);
    if ($admin['id'] == $adminId) {
      $admin = ManagerHolder::get($this->entityName)->getById($adminId);
      $this->session->set_userdata(self::LOGGED_IN_ADMIN_SESSION_KEY, $admin);      
    }
    $this->redirectToReffer();
  }

  /**
   * Delete.
   * @param integer $entityId
   * @return void
   */
  public function delete($entityId) {
    if ($entityId == $this->loggedInAdmin['id']) {
      set_flash_warning("admin.messages.admin.cannot_delete_current");
      $this->redirectToReffer();
    }
    parent::delete($entityId);
  }

  /**
   * Delete all.
   * @return void
   */
  public function delete_all() {
    if (isset($_POST["d_id"])) {
      foreach ($_POST["d_id"] as $id) {
        if ($id == $this->loggedInAdmin['id']) {
          set_flash_warning("admin.messages.admin.cannot_delete_current");
          $this->redirectToReffer();
        }
      }
    }
    parent::delete_all();
  }

}