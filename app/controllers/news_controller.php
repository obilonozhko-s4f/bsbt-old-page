<?php
require_once APPPATH . 'controllers/base_project_controller.php';

/**
 * News_Controller.
 */
class News_Controller extends Base_Project_Controller {

  /**
   *
   * Constructor
   */
  public function News_Controller() {
    parent::Base_Project_Controller();
    $this->layout->setModule('news');
    $this->layout->setLayout('main');
  }


  /**
   * index
   */
  public function index() {
    $where = array('is_published' => TRUE);
    
    $allnews = ManagerHolder::get('NewsItem')->getAllWhere($where, 'e.*, image.*', 100);
    $this->layout->set('allnews', $allnews);
  
    // RECOMENDATIONS
    $recomendations = ManagerHolder::get('Apartment')->getAllWhere(array('is_published' => TRUE, 'sale' => TRUE), 'e.*, city.*, objecttype.*, image.*', 3);
    $this->layout->set('recomendations', $recomendations);
    
    $this->setHeaders(null, 'news_page'); 
    $this->layout->view('news');
  }
   
  

}