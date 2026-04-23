<?php
require_once APPPATH . 'controllers/admin/base/base_admin_controller.php';

/**
 * xAdmin email broadcast controller.
 * @author Itirra - http://itirra.com
 */
class xAdmin_Email_Broadcast extends Base_Admin_Controller {
  
  /** Allowed file types. | is the delimeter. */
  protected $allowedFileTypes = "csv";
  
  /** Class Prefix to remove to get Entity name. */
  protected $classPrefix = "xAdmin_Email_";
  
  /** Filter. Row example: "column_name" => default_value. Default value may be null. */
  protected $filters = array('is_sent' => '');

  /** Search params. */
  protected $searchParams = array('subject');
  
  /** Date Filters. Row example: array("created_at"). */
  protected $dateFilters = array('sent_date');

  /**
   * Constructor.
   * @return
   */
  public function xAdmin_Email_Broadcast() {
    parent::Base_Admin_Controller();
  }

  /**
   * @see src/app/controllers/admin/base/Base_Admin_Controller::setViewParamsIndex()
   */
  protected function setViewParamsIndex(&$entities, &$pager, $hasSidebar){
    $this->actions['preview_broadcast'] = $this->adminBaseRoute . '/' . strtolower($this->entityUrlName) . '/preview_broadcast';
    $this->actions['view_results'] = $this->adminBaseRoute . '/' . strtolower($this->entityUrlName) . '/view_results';
    parent::setViewParamsIndex($entities, $pager, $hasSidebar);
  }
  
  /**
   * Add edit entity.
   * @param integer $entityId
   */
  public function add_edit($entityId = null) {
    if ($entityId) {
      $entity = ManagerHolder::get('Broadcast')->getById($entityId);
      if ($entity['is_sent']) {
        set_flash_error('admin.email_broadcast.cannot_edit_a_sent_broadcast');
        $this->redirectToReffer();
      }
    }
    parent::add_edit($entityId);
  }

  /**
   * @see src/app/controllers/admin/base/Base_Admin_Controller::add_edit_process()
   */
  public function add_edit_process(){
    $_POST['text'] = str_replace('&amp;', '&', $_POST['text']);
    $entity = $this->createEntityPOST();
    $this->loadAndResizeImages($entity);
    $this->loadFiles($entity);
    $this->loadVideos($entity);
    $entity = $this->addEditEntity($entity);
    if(isset($_POST['send'])){
      redirect($this->adminBaseRoute . '/' .  strtolower($this->entityUrlName) . '/preview_broadcast/' . $entity['id']);
    }
    if (isset($_POST['save_and_return_to_list'])) {
      redirect($this->adminBaseRoute . '/' .  strtolower($this->entityUrlName) . get_get_params());
    } else if (isset($_POST['save_and_add_new'])) {
      redirect($this->adminBaseRoute . '/' . strtolower($this->entityUrlName) . '/add_edit');
    }
    $this->redirectToReffer($entity['id']);
  }


  /**
   * Send Broadcast
   */
  public function send_broadcast($entityId){
    $entity = ManagerHolder::get('Broadcast')->getById($entityId);
    $recipents = $entity['recipents'];
    if (!$recipents) {
      set_flash_error('admin.email_broadcast.no_items_to_send_email');
      $this->redirectToReffer();
    }

    if ($entity['is_ajax_layout']) {
      ManagerHolder::get('Email')->setLayoutFile('ajax');
    }
    
    if ($entity['bcc_email']) {
      ManagerHolder::get('Email')->setBcc($entity['bcc_email']);
    }

    $sent = 0;
    foreach ($recipents as $recipent) {
      $message = $entity['text'];
      $message = ManagerHolder::get('Broadcast')->processLinks($message, $entityId, $recipent['id']);
      $message = ManagerHolder::get('Broadcast')->addStatusItem($message, $recipent['id']);
      $message = kprintf($message, $recipent['data']);
      $subject = kprintf($entity['subject'], $recipent['data']);
      try {
        ManagerHolder::get('Email')->sendTemplate($recipent['email'], 'admin_broadcast', array('message' => $message), $subject);
        $sent++;
        usleep(rand(1000, 5000));
      } catch (Exception $e) {
        log_message('error', 'Broadcast send error:' . $e->getMessage() . '; on email: ' . $recipent['email']);
      };
    }
    
    $entity['is_sent'] = TRUE;
    $entity['sent_date'] = date(DOCTRINE_DATE_FORMAT);
    ManagerHolder::get('Broadcast')->update($entity);
    if (count($recipents) == $sent) {
      set_flash_notice('admin.email_broadcast.broadcast_email_sent', array('count' => $sent));
    } else {
      set_flash_warning('admin.email_broadcast.broadcast_email_sent', array('count' => $sent));
    }
    redirect($this->adminBaseRoute . '/' .  strtolower($this->entityUrlName));
  }


  /**
   * preview_broadcast
   */
  public function preview_broadcast($entityId){
    $entity = ManagerHolder::get('Broadcast')->getById($entityId);
    if(!$entity){
      redirect($this->adminBaseRoute . '/' .  strtolower($this->entityUrlName));
    }
    if(empty($entity['recipents'])) {
      set_flash_warning('admin.email_broadcast.no_recipients');
      redirect($this->adminBaseRoute . '/' .  strtolower($this->entityUrlName));
    }
    $this->layout->set('entity', $entity);
    $this->layout->set('backUrl', $this->adminBaseRoute . '/' .  strtolower($this->entityUrlName));
    $this->layout->set('previewUrl', $this->adminBaseRoute . '/' .  strtolower($this->entityUrlName) . '/preview/' . $entityId);
    $this->layout->set('processUrl', $this->adminBaseRoute . '/' .  strtolower($this->entityUrlName) . '/send_broadcast/' . $entityId);
    $this->layout->view('parts/broadcast/preview');
  }


  /**
   * preview
   * @param $entityId
   */
  public function preview($entityId){
    $entity = ManagerHolder::get('Broadcast')->getById($entityId);
    if(!$entity){
      redirect($this->adminBaseRoute . '/' .  strtolower($this->entityUrlName));
    }
    $this->layout->set('subject', $entity['subject']);
    $this->layout->set('message', $entity['text']);
    $this->layout->setModule('email');
    if($entity['is_ajax_layout']){
      $this->layout->setLayout('ajax');
    } else {
      $this->layout->setLayout('email');
    }
    $this->layout->view('admin_broadcast');
  }


  /**
   * view_results
   */
  public function view_results($entityId){
    $entity = ManagerHolder::get('Broadcast')->getById($entityId);
    if(!$entity){
      redirect($this->adminBaseRoute . '/' .  strtolower($this->entityUrlName));
    }
    if(!$entity['is_sent']){
      set_flash_warning('admin.broadcast.view_results.not_sent');
      redirect($this->adminBaseRoute . '/' .  strtolower($this->entityUrlName));
    }
    $report = ManagerHolder::get('Broadcast')->getResultsReport($entity);
    $this->layout->set('report', $report);
    $this->layout->set('entity', $entity);
    $this->layout->set('backUrl', $this->adminBaseRoute . '/' .  strtolower($this->entityUrlName));
    $this->layout->view('parts/broadcast/view_results');
  }


  /**
   * @see src/app/controllers/admin/base/Base_Admin_Controller::postInsert()
   */
  protected function postSave(&$entity){
    if(isset($_FILES['recipents']['name'])){
      $this->fileoperations->set_upload_lib_config_value("allowed_types", $this->allowedFileTypes);
      $isUploaded = $this->fileoperations->upload('recipents', FALSE, './web');
      if($isUploaded){

        // Load CSV Library
        $this->load->library('common/csv');
        $fileInfo = $this->fileoperations->file_info;
        $this->csv->readFile($fileInfo['file_path'] . $fileInfo['file_name']);
        $header = $this->csv->readRow();

        $excludes = ManagerHolder::get('BroadcastExclude')->getAsViewArray(array(), array('email' => 'email'));
        $recCount = 0;
        while (($row = $this->csv->readRow()) !== FALSE) {
          $columns = count($row);
          $emptyColumns = 0;
          $rowData = array();
          foreach ($row as $k => &$v) {
            $v = trim($v);
            if (empty($v)) {
              $emptyColumns++;
            } else {
              $rowData[strtolower($header[$k])] = $v;
            }
          }
          
          if(is_not_empty($rowData['email']) && !$excludes || !in_array($rowData['email'], $excludes)){
            // adding recipent
            $recipent = new BroadcastRecipent();
            $recipent['email'] = $rowData['email'];
            $recipent['broadcast_id'] = $entity['id'];
            unset($rowData['email']);
            $recipent['data'] = $rowData;
            ManagerHolder::get('BroadcastRecipent')->insert($recipent);
            $recCount++;
          }

          if ($columns == $emptyColumns) {
            continue;
          }
        }
        ManagerHolder::get('Broadcast')->updateById($entity['id'], 'recipents_count', $recCount);
        $this->fileoperations->delete_file($fileInfo['file_name'], $fileInfo['file_path']);
      }
    }
    
    ManagerHolder::get('BroadcastLink')->deleteAllWhere(array('broadcast_id' => $entity['id']));
    ManagerHolder::get('Broadcast')->collectLinks($entity['text'], $entity['id']);
  }


  /**
   * @see src/app/controllers/admin/base/Base_Admin_Controller::postUpdate()
   */
  protected function postUpdate(&$entity){
    if(isset($_POST['del_rec'])){
      foreach($_POST['del_rec'] as $recipentId){
        ManagerHolder::get('BroadcastRecipent')->deleteById($recipentId);
      }
    }
    $recCount = ManagerHolder::get('BroadcastRecipent')->getCountWhere(array('broadcast_id' => $entity['id']));
    ManagerHolder::get('Broadcast')->updateById($entity['id'], 'recipents_count', $recCount);
  }

  /**
   * broadcast_link_redirect
   * gateway point for broadcast links
   */
  public function broadcast_link_redirect(){
    if(!isset($_GET['url'])) show_404();
    $url = prep_url($_GET['url']);
    if(isset($_GET['r']) && isset($_GET['l'])) {
      ManagerHolder::get('BroadcastLink')->setVisitedById($_GET['l'], $_GET['r']);
    }
    redirect($url);
  }


  /**
   * broadcast_read_callback
   */
  public function broadcast_read_callback(){
    if(isset($_GET['r'])) {
      ManagerHolder::get('BroadcastRecipent')->setIsRead($_GET['r']);
    }
    header('Content-Type: image/gif');
    readfile(realpath('web/images/admin/icons/transp.gif'));
  }


  /**
   * broadcast_unsubscribe
   */
  public function broadcast_unsubscribe($email = null){
    if($email) {
      ManagerHolder::get('BroadcastExclude')->add($email);
    }
    redirect();
  }

}