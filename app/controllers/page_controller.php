<?php
require_once APPPATH . 'controllers/base_project_controller.php';

/**
 * Page_Controller.
 */
class Page_Controller extends Base_Project_Controller {

  /**
   *
   * Constructor
   */
  public function Page_Controller() {
    parent::Base_Project_Controller();
    $this->layout->setModule('page');
    $this->layout->setLayout('main');
  }


  /**
   * index
   */
  public function index() {
    
    // Get the URL for page_url
    $pageUrl = uri_string();
    
    // make array from string by "/"
    $pageUrlSegments = explode('/', trim($pageUrl, '/'));
    
    $firstSegment = $pageUrlSegments[0];
    
    $pageUrlNew = "";
    
    if ($firstSegment == 'ru' || $firstSegment == 'de') {
      $pageUrlArray = str_replace($firstSegment, "", $pageUrlSegments);
      
      for($cnt = 0; $cnt < sizeof($pageUrlArray); $cnt++)
      $pageUrlNew .= $pageUrlArray[$cnt] . '/';

    } else {
    $pageUrlNew = surround_with_slashes(uri_string());
    }
    
    $page = ManagerHolder::get('Page')->getOneWhere(array('page_url' => $pageUrlNew, 'is_published' => TRUE));
        
    if (!$page) {
      show_404();
    }
    
    ///////---- HEADERS START ----///////
    if(isset($page['header']['title']) && !empty($page['header']['title'])){
      $this->setHeaders($page);
    } else {
      $header = array();
      if($page['language'] == 'en'){
      	$header['title'] = $page['title'] . ' | BS Business Travelling - Business voyages in Hanover and Kiev, Ukraine and Germany';
      	$header['description'] = $page['title'] . ' - Business voyages in Hanover and Kiev, Ukraine and Germany';
      } elseif($page['language'] == 'de'){
      	$header['title'] = $page['title'] . ' | BS Business Travelling - Business Reisen in Hannover und Kiew, Ukraine und Deutschland';
      	$header['description'] = $page['title'] . ' - Business Reisen in Hannover und Kiew, Ukraine und Deutschland';
      } else {
      	$header['title'] = $page['title'] . ' | BS Business Travelling - бизнес путешевствия в Гановер и Киев, Германия и Украина';
      	$header['description'] = $page['title'] . ' | - бизнес путешевствия в Гановер и Киев, Германия и Украина';
      }
      $this->layout->set('header', $header);
    }
    ///////---- HEADERS END ----///////
    
    $this->layout->set('page', $page);
    
     // RECOMENDATIONS
    if (isset($_GET['city']) && $_GET['city'] != "") {
      $recomendations = ManagerHolder::get('Apartment')->getAllWhere(array('is_published' => TRUE, 'sale' => TRUE, 'city_id' => $_GET['city']), 'e.*, city.*, objecttype.*, image.*', 3);
    }
    if (isset($_GET['city']) && $_GET['city'] == "" || !isset($_GET['city'])) {
      $recomendations = ManagerHolder::get('Apartment')->getAllWhere(array('is_published' => TRUE, 'sale' => TRUE), 'e.*, city.*, objecttype.*, image.*', 3);
    }
    $this->layout->set('recomendations', $recomendations);
    
    $this->layout->view('view');
  }
  
  
  
  

}