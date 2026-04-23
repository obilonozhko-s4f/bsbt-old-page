<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . "controllers/base_project_controller.php";

/**
 * Object_Controller
 * @author Алексей Бойко
 */
class Object_Controller extends Base_Project_Controller {

  /**
   *
   * Constructor
   */
  public function Object_Controller() {
    parent::Base_Project_Controller();
    $this->layout->setModule('object');
    $this->layout->setLayout('main');
  }


  /**
   * index
   */
  public function index($id) {
    $object = ManagerHolder::get('Apartment')->getOneWhere(array('id' => $id, 'is_published' => TRUE), 'e.*, city.*, objecttype.*, image.*, objectfeatures.*, images.*, header.*'); 
    if (!$object) {
      show_404();
    }
    ///////---- HEADERS START ----///////
    if(isset($object['header']['title']) && !empty($object['header']['title'])){
      $this->setHeaders($object);
    } else {
      $header = array();
      if($object['language'] == 'en'){
      	$header['title'] = 'Reserv ' . $object['objecttype']['name'] . ' in ' . $object['city']['title'] . ', street ' . $object['street'] . ', price: ' . $object['price_out'] . '/night | BS Business Travelling - Rent apartment in Hannover';
      	$header['description'] = $object['objecttype']['name'] . $object['city']['title'] . ' | BS Business Travelling - Business voyages in Hanover and Kiev, Ukraine and Germany';
      } elseif($object['language'] == 'de'){
      	$header['title'] = 'Buchen Sie ' . $object['objecttype']['name'] . ' in ' . $object['city']['title'] . ', Straße ' . $object['street'] . ', Preis: ' . $object['price_out'] . '/Nacht | BS Business Travelling - Messewohnung Hannover';
      	$header['description'] = $object['objecttype']['name'] . $object['city']['title'] . ' | BS Business Travelling - Business Reisen in Hannover und Kiew, Ukraine und Deutschland';
      } else {
      	$header['title'] = 'Забронировать ' . $object['objecttype']['name'] . ' в городе ' . $object['city']['title'] . ', улица ' . $object['street'] . ', цена: ' . $object['price_out'] . '/ночь | BS Business Travelling - аренда квартир в Ганновере посуточно';
      	$header['description'] = $object['objecttype']['name'] . $object['city']['title'] . ' | BS Business Travelling - бизнес путешевствия в Гановер и Киев, Германия и Украина';
      }
      $this->layout->set('header', $header);
    }
    ///////---- HEADERS END ----///////
    
    $this->layout->set('object', $object);
     
    // RECOMENDATIONS
    $recomendations = ManagerHolder::get('Apartment')->getAllWhere(array('is_published' => TRUE, 'sale' => TRUE, 'city_id' => $object['city_id']), 'e.*, city.*, objecttype.*, image.*', 3);
    
    $this->layout->set('recomendations', $recomendations);

    $this->layout->view('single_object');
  }


}
?>