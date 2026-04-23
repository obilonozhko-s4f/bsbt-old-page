<?php
require_once APPPATH . 'controllers/base_project_controller.php';

/**
 * Home_Controller.
 */
class Home_Controller extends Base_Project_Controller {

  /**
   *
   * Constructor
   */
  public function Home_Controller() {
    parent::Base_Project_Controller();
    $this->layout->setLayout('index');
  }


  /**
   * index
   */
  public function index() {
    $newsitems = ManagerHolder::get('NewsItem')->getAllWhere(array('is_published' => TRUE), 'e.*, image.*', 3);
    $this->layout->set('newsitems', $newsitems);

    $apartments = ManagerHolder::get('Apartment')->getAllWhere(array('is_published' => TRUE, 'sale' => TRUE), 'e.*, city.*, objecttype.*, image.*', 6);
    $this->layout->set('apartments', $apartments);
    
    $set = ManagerHolder::get('Settings')->getAllKVGrouped();
    foreach ($set as $s) {
      if($s['k'] == "home_page_seotext"){
        $seo_text = $s['v'];
        $this->layout->set('seo_text', $seo_text);
        break;
      }
    }
    
    $this->setHeaders(null, 'home_page');
    $this->layout->renderLayout();
  }
  

}