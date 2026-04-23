<?php
require_once APPPATH . 'controllers/base_project_controller.php';

/**
 * Apartment_Controller.
 */
class Apartment_Controller extends Base_Project_Controller {

  /**
   *
   * Constructor
   */
  public function Apartment_Controller() {
    parent::Base_Project_Controller();
    $this->layout->setModule('apartments');
    $this->layout->setLayout('main');
  }


  /**
   * index
   */
  public function index($page = 'page1') {
    $where = array('is_published' => TRUE);
    
    $this->load->helper('common/itirra_pager');
    $this->load->helper('common/itirra_date');
    
    if (isset($_GET['id']) && !empty($_GET['id'])) {
      redirect('object/' . $_GET['id']);
    } else {
      if (isset($_GET['city']) && is_numeric($_GET['city'])) {
        $where['city_id'] = $_GET['city'];
      }

      if (isset($_GET['objecttypeclass']) && is_numeric($_GET['objecttypeclass'])) {
        $where['objecttype_id'] = $_GET['objecttypeclass'];
      }

      if (isset($_GET['persons']) && is_numeric($_GET['persons'])) {
        $where['persons_max'] = '>=' . $_GET['persons'];
      }
      
      if (isset($_GET['sale']) && is_numeric($_GET['sale'])) {
          $where['sale'] = $_GET['sale'];
        }
      
      
      if (isset($_GET['date_from'])) {
        $dateFrom = rstrptime($_GET['date_from'], '%d/%m/%Y');
        $dateTo = rstrptime($_GET['date_to'], '%d/%m/%Y');
        if ($dateFrom && $dateTo) {
          $dateFrom = $dateFrom['tm_year'] . '-' . $dateFrom['tm_mon'] . '-' . $dateFrom['tm_mday'];
          $dateTo = $dateTo['tm_year'] . '-' . $dateTo['tm_mon'] . '-' . $dateTo['tm_mday'];
          $dateFromTime = strtotime($dateFrom);
          $dateToTime = strtotime($dateTo);
          if ($dateFromTime <= $dateToTime && $dateFromTime && $dateToTime) {
            $dates = date_interval($dateFrom, $dateTo);
            $nights = count($dates) - 1;
            $this->layout->set('nights', $nights);
            $where['nights_min'] = '<=' . $nights;
            $freeApartmentIds = ManagerHolder::get('ApartmentReserv')->getAllReservedApartmentIds($dateFrom, $dateTo);
            $where['id NOT'] = $freeApartmentIds;
          } else if($dateFromTime > $dateToTime) {
          	$where['id'] = '99999999999999999';
          }
        }
      }
    }
    
        
    // SORTIROVKA
    if (isset($_GET['sort'])) {
      if ($_GET['sort'] == 'price_asc') {
        ManagerHolder::get('Apartment')->setOrderBy('price_out ASC');
      }
      if ($_GET['sort'] == 'price_desc') {
        ManagerHolder::get('Apartment')->setOrderBy('price_out DESC');
      }
    }
    
    
    $apartments = ManagerHolder::get('Apartment')->getAllWhereWithPager($where, pager_remove_prefix($page), 5, 'e.*, city.*, objectfeatures.*, objecttype.*, image.*');

    // UBRAT' SORTIROVKU
    ManagerHolder::get('Apartment')->setOrderBy(null);
    
    $this->layout->set('apartments', $apartments->data);
    $this->layout->set('pager', $apartments->pager);
    
    // RECOMENDATIONS
    if (isset($_GET['city']) && $_GET['city'] != "") {
      $recomendations = ManagerHolder::get('Apartment')->getAllWhere(array('is_published' => TRUE, 'sale' => TRUE, 'city_id' => $_GET['city']), 'e.*, city.*, objecttype.*, image.*', 3);
    }
    if (isset($_GET['city']) && $_GET['city'] == "" || !isset($_GET['city'])) {
      $recomendations = ManagerHolder::get('Apartment')->getAllWhere(array('is_published' => TRUE, 'sale' => TRUE), 'e.*, city.*, objecttype.*, image.*', 3);
    }
    $this->layout->set('recomendations', $recomendations);
    $this->setHeaders(null, 'apartments_page');    
    $this->layout->view('list');
  }
   
  

}