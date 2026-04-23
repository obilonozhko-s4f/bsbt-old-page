<?php
require_once APPPATH . 'controllers/base/base_controller.php';

/**
 * Ajax_Controller.
 */
class Ajax_Controller extends Base_Controller {

  /** Libraries to load.*/
  protected $libraries = array('common/DoctrineLoader');


  /** Helpers to load.*/
  protected $helpers = array('common/itirra_language',
  							             'url',
                             'common/itirra_resources');

  /**
   *
   * Constructor
   */
  public function Ajax_Controller() {
    parent::Base_Controller();
    ManagerHolder::setLanguage($this->lang->lang());
    $this->layout->setLayout('ajax');
    $this->layout->setModule('ajax');
  }


  /**
   * features
   */
  public function features() {
    if (!isset($_GET['aId'])) show_404();
    $features = ManagerHolder::get('ObjectFeature')->getAllWhere(array('apartment_feature_rels.apartment_id' => $_GET['aId']), 'e.*, image.*, apartment_feature_rels.*');
    $this->layout->set('features', $features);
    $this->layout->view('object_features');
  }

  /**
   * index page -> recomendations
   */
  public function recomendations() {
    if (!isset($_GET['rId'])) show_404();
    if ($_GET['rId'] == "") {
      $rec = ManagerHolder::get('Apartment')->getAllWhere(array('is_published' => TRUE), 'e.*, city.*, image.*, objecttype.*', 6);
    } else {
      $rec = ManagerHolder::get('Apartment')->getAllWhere(array('is_published' => TRUE, 'city_id' => $_GET['rId']), 'e.*, city.*, image.*, objecttype.*', 6);
    }

    $this->layout->set('rec', $rec);
    $this->layout->view('index_recomend');
  }
  
  
/**
   * admin orders input -> persons
   */
  public function persons() {
    if (!isset($_GET['id'])) show_404();
    else {
      $obj = ManagerHolder::get('Apartment')->getOneWhere(array('id' => $_GET['id'], 'is_published' => TRUE), 'e.*');
    }
    if (isset($_GET['enp'])){
    	$enp = $_GET['enp'];
    	$this->layout->set('enp', $enp);
    }
    
    $this->layout->set('obj', $obj);
    $this->layout->view('orders_input_persons');
  }


  /**
   * index page -> objecttypeclass
   */
  public function typeclass() {
    if (!isset($_GET['otId'])) show_404();

    if (isset($_GET['otcId'])) {
      $otcId = $_GET['otcId'];
      $this->layout->set('otcId', $otcId);
    }

    $ot = array();
    if ($_GET['otId'] != "") {
      $ot = ManagerHolder::get('ObjectType')->getAllWhere(array('root_id' => $_GET['otId']), 'e.*');
    }

    $this->layout->set('ot', $ot);
    $this->layout->view('index_objecttypeclass');
  }


  /**
   * reservation page -> totalcost
   */
  public function totalcost() {
    $this->load->helper('common/itirra_date');

    if (isset($_GET['dateFrom'])) {
      $dateFrom = rstrptime($_GET['dateFrom'], '%d/%m/%Y');
      $dateTo = rstrptime($_GET['dateTo'], '%d/%m/%Y');
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

          $res_object = ManagerHolder::get('Apartment')->getOneWhere(array('id' => $_GET['id'], 'is_published' => TRUE), 'e.*');
          
          if ($_GET['breakfast'] == true && $_GET['added_person'] == true){
            $totalcost = $nights*($res_object['price_out']+$res_object['add_person_out']) + $nights*($_GET['persons'] + 1)*7;
          } elseif ($_GET['breakfast'] == true && $_GET['added_person'] == false) {
            $totalcost = $nights*$res_object['price_out'] + $nights*$_GET['persons']*7;
          } elseif ($_GET['breakfast'] == false && $_GET['added_person'] == true) {
            $totalcost = $nights*($res_object['price_out']+$res_object['add_person_out']);
          } elseif ($_GET['breakfast'] == false && $_GET['added_person'] == false) {
            $totalcost = $nights*$res_object['price_out'];
          }

          $this->layout->set('totalcost', $totalcost);
          $this->layout->view('reservation_totalcost');

        }
      }
    }



  }

  /**
   * reservation page -> datecheck
   */
  public function datecheck() {
    $this->load->helper('common/itirra_date');
     
    if ($_GET['dateFrom'] != "" || $_GET['dateTo'] != "") {

      $where = array('is_published' => TRUE);
      	
      if (isset($_GET['id'])) {
        $where['id'] = $_GET['id'];
      }

      if (isset($_GET['dateFrom'])) {
        $dateFrom = rstrptime($_GET['dateFrom'], '%d/%m/%Y');
        $dateTo = rstrptime($_GET['dateTo'], '%d/%m/%Y');
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
          }
        }
      }

      $ocheck = ManagerHolder::get('Apartment')->getOneWhere($where, 'e.*');
    } else {
      $ocheck = "";
    }

    $this->layout->set('ocheck', $ocheck);
    $this->layout->view('reservation_datecheck');
  }

}