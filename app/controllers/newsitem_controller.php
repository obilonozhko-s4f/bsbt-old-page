<?php
require_once APPPATH . 'controllers/base_project_controller.php';

/**
 * Newsitem_Controller.
 */
class Newsitem_Controller extends Base_Project_Controller {

  /**
   *
   * Constructor
   */
  public function Newsitem_Controller() {
    parent::Base_Project_Controller();
    $this->layout->setModule('news');
    $this->layout->setLayout('main');
  }


  /**
   * index
   */
  public function index($pageUrl) {
    
    $pageUrl = surround_with_slashes($pageUrl);
    $pageUrlFull = '/news' . $pageUrl ;
       
    $newsitem = ManagerHolder::get('NewsItem')->getOneWhere(array('page_url' => $pageUrlFull, 'is_published' => TRUE));

    if (!$newsitem) {
        show_404();
      }
    
    ///////---- HEADERS START ----///////
    if(isset($newsitem['header']['title']) && !empty($newsitem['header']['title'])){
      $this->setHeaders($newsitem);
    } else {
      $header = array();
      if($newsitem['language'] == 'en'){
      	$header['title'] = $newsitem['title'] . ' | BS Business Travelling - Business voyages in Hanover and Kiev, Ukraine and Germany';
      	$header['description'] = $newsitem['title'] . ' - Business voyages in Hanover and Kiev, Ukraine and Germany';
      } elseif($newsitem['language'] == 'de'){
      	$header['title'] = $newsitem['title'] . ' | BS Business Travelling - Business Reisen in Hannover und Kiew, Ukraine und Deutschland';
      	$header['description'] = $newsitem['title'] . ' - Business Reisen in Hannover und Kiew, Ukraine und Deutschland';
      } else {
      	$header['title'] = $newsitem['title'] . ' | BS Business Travelling - бизнес путешевствия в Гановер и Киев, Германия и Украина';
      	$header['description'] = $newsitem['title'] . ' | - бизнес путешевствия в Гановер и Киев, Германия и Украина';
      }
      $this->layout->set('header', $header);
    }
    ///////---- HEADERS END ----///////
    
    $this->layout->set('newsitem', $newsitem);
    
    // RECOMENDATIONS
    $recomendations = ManagerHolder::get('Apartment')->getAllWhere(array('is_published' => TRUE, 'sale' => TRUE), 'e.*, city.*, objecttype.*, image.*', 3);
    $this->layout->set('recomendations', $recomendations);
        
    $this->layout->view('news_item');
  }

}