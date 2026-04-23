<?php
require_once APPPATH . 'controllers/admin/base/base_admin_controller.php';

/**
 * Xadmin controller.
 * @author Alexei Chizhmakov (Itirra - http://itirra.com)
 */
class xAdmin_Resource extends Base_Admin_Controller {
  
  /**
   * Constructor.
   * @return xAdmin_Header
   */
  public function xAdmin_Resource() {
    parent::Base_Admin_Controller();
    $this->config->load('thumbs');
    $this->fileoperations->set_base_dir('./web/images');
    $folder = $this->session->userdata(self::FOLDER_SESSION_KEY);
    if ($folder) {
      $this->fileoperations->add_folder_to_uploads_dir($folder);
      $this->layout->set("currentFolder", $folder);
    }    
  }
  
  /**
   * Overridden.
   * @see Base_Admin_Controller::index()
   */  
  public function index() {
    $this->load->helper('text');
    $entityName = "Image";
    $this->layout->set("folders", $this->fileoperations->get_folders());
    $where['web_path'] = add_slash($this->fileoperations->get_upload_dir());
    $entities = ManagerHolder::get($entityName)->getAllWhere($where);
    $this->layout->set("entities", $entities);
    $this->layout->setLayout("window");
    $this->layout->view("images");   
  }
  
  /**
   * Upload resource.
   */
  public function add_resource() {
    // Upload Image
    try {
      if ($this->fileoperations->upload('image', TRUE)) {
        $image = new Image();
        $image->fromArray($this->fileoperations->file_info);
        $thumbsAdmin = $this->config->item('_admin', 'all');
        $this->fileoperations->createImageThumb($image, '_admin', $thumbsAdmin['width'], $thumbsAdmin['height']);
        ManagerHolder::get('Image')->insert($image);
        set_flash_notice('Image successfully uploaded');
      }
    } catch (Exception $e) {
      set_flash_error('An error occurred: ' . $e->getMessage());
    }
    
    $this->load->library('user_agent');
    if ($this->agent->is_referral()) {
      redirect($this->agent->referrer());
    } else {
      redirect($this->adminBaseRoute . "/resource");
    }    
  }
  
  
  /**
   * Upload resource ajax.
   */
  public function add_image_resource_ajax() {
    $this->load->helper('common/itirra_ajax');
    // Upload Image
    try {
      if ($this->fileoperations->upload('image', TRUE)) {
        $image = new Image();
        $image->fromArray($this->fileoperations->file_info);
        $thumbsAdmin = $this->config->item('_admin', 'all');
        $this->fileoperations->createImageThumb($image, '_admin', $thumbsAdmin['width'], $thumbsAdmin['height']); 
        ManagerHolder::get('Image')->insert($image);
      }
    } catch (Exception $e) {
      die(array2json(array('success' => FALSE)));
    }
    
    $result = array('success' => TRUE, 'path' => site_img($image['web_path'] . $image['file_name']));
    die(array2json($result)); 
  }  
    
  
  /**
   * Create Folder.
   */
  public function create_folder() {
    if (count($_POST) < 1) show_404();
    $this->fileoperations->create_folder($_POST['name']);
    set_flash_notice('Folder created');
    redirect($this->adminBaseRoute . "/resource");
  }
  
  /**
   * Remove dir. 
   * @param string $dirName
   */
  public function remove_dir($dirName) {
    if ($this->fileoperations->remove_folder($dirName)) {
      set_flash_notice('Folder deleted');
    } else {
      set_flash_notice('Only empty folders can be deleted');
    }
    redirect_to_referral($this->adminBaseRoute . '/resource');    
  }
  
  /**
   * Change dir.
   * @param string $dirName
   */
  public function change_dir($dirName = null) {
    if ($dirName) {
      $folder = $this->session->userdata(self::FOLDER_SESSION_KEY);
      if ($folder) {
        $folder .= '/' . $dirName;  
      } else {
        $folder = $dirName; 
      }
      $this->session->set_userdata(self::FOLDER_SESSION_KEY, $folder);
    } else {
      $this->session->unset_userdata(self::FOLDER_SESSION_KEY);
    }
    redirect($this->adminBaseRoute . "/resource");
  }
  
  /**
   * Up dir.
   */
  public function up_dir() {
     $folder = $this->session->userdata(self::FOLDER_SESSION_KEY);
     if ($folder) {
       $folderArr = explode('/', $folder);
       array_pop($folderArr);
       $folder = implode('/', $folderArr);
       $this->session->set_userdata(self::FOLDER_SESSION_KEY, $folder);
     }
     redirect($this->adminBaseRoute . "/resource");
  }  
  
  /**
   *	Get Resource ajax.  
   */
  public function get_resource_info() {
    if (isset($_POST["image_id"]) && $_POST["image_id"]) {
      $entityName = "Image";
      $reslut = ManagerHolder::get($entityName)->getById($_POST["image_id"]);
      $reslut['main_image'] = site_image_url($reslut);
      $this->layout->setLayout("layouts/ajax");
      if (isset($_POST["html"])) {
        $this->layout->set("image", $reslut);
        die($this->layout->view('image_info', TRUE));
      } else {
        die(json_encode($reslut));
      }
    } else {
      if (isset($_POST["html"])) {
        $errorResult = 'ERROR';
      } else {
        $errorResult = '{"error": true}';
      }
      die($errorResult);
    }
  }

  /**
   * Delete resource.
   * @param integer $imageId
   */
  public function delete_resource($imageId) {
    $entityName = "Image";
    $image = ManagerHolder::get($entityName)->getById($imageId);
    @unlink($image["file_path"] . $image["file_name"]);
    ManagerHolder::get($entityName)->deleteById($imageId);
    set_flash_notice('Image successfully deleted');
    $this->load->library('user_agent');
    if ($this->agent->is_referral()) {
      redirect($this->agent->referrer());
    } else {
      redirect($this->adminBaseRoute . "/resource");
    }    
  }

  /**
   * Resize resource.
   */
  public function resize_resource() {
    if(isset($_POST["image_id"])) {
      $entityName = "Image";
      $oldImage = ManagerHolder::get($entityName)->getById($_POST["image_id"]);
      $this->fileoperations->set_image_lib_config_value("width", $_POST["width"]);
      $this->fileoperations->set_image_lib_config_value("height", $_POST["height"]);
      $this->fileoperations->set_image_lib_config_value("thumb_marker", FileOperations::THUMB_MARKER . "_" . $_POST["width"] . "x" . $_POST["height"]);
      try {
        $newFilePath = $this->fileoperations->image_resize($oldImage["file_path"], $oldImage["file_name"]);
      } catch (Exception $e) {
        set_flash_error('An error occurred: ' . $e->getMessage());
        redirect($this->adminBaseRoute . "/resource");
      }
      $image = new Image();
      $this->fileoperations->get_file_info($newFilePath);
      $image->fromArray($this->fileoperations->file_info);
      $image->id = NULL;
      ManagerHolder::get($entityName)->insert($image);
    }
    set_flash_notice('Image copied and resized successfully');
    
    $this->load->library('user_agent');
    if ($this->agent->is_referral()) {
      redirect($this->agent->referrer());
    } else {
      redirect($this->adminBaseRoute . "/resource");
    }
  }  
}