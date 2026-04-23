<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . "controllers/base_project_controller.php";

/**
 * Reservation_Controller
 */
class Reservation_Controller extends Base_Project_Controller {

  public function Reservation_Controller() {
    parent::Base_Project_Controller();
    $this->layout->setModule('reservation');
    $this->layout->setLayout('full');
  }

  public function index() {
    if (!isset($_GET['id']) || empty($_GET['id'])) {
      show_404();
    }
    $this->load->helper('common/itirra_validation');
    $res_object = ManagerHolder::get('Apartment')->getOneWhere(array('id' => $_GET['id'], 'is_published' => TRUE), 'e.*, city.*, objecttype.*, image.*, objectfeatures.*, images.*');
    if (empty($res_object)) {
      show_404();
    }
    $this->layout->set('res_object', $res_object);
    $this->layout->view('reserv');
  }

  public function reserv() {
    $this->load->helper('common/itirra_validation');
    save_post();
    $this->load->helper('common/itirra_date');
    simple_validate_post(array('utype', 'apartment_id', 'name', 'surname', 'phone', 'email', 'country', 'city', 'zip', 'street', 'home', 'office', 'date_from', 'date_to', 'time_h', 'time_m', 'persons', 'comments', 'terms', 'not_bot'));

    $dateFrom = rstrptime($_POST['date_from'], '%d/%m/%Y');
    $dateTo = rstrptime($_POST['date_to'], '%d/%m/%Y');
    if ($dateFrom && $dateTo) {
      $_POST['date_from'] = $dateFrom['tm_year'] . '-' . $dateFrom['tm_mon'] . '-' . $dateFrom['tm_mday'];
      $_POST['date_to'] = $dateTo['tm_year'] . '-' . $dateTo['tm_mon'] . '-' . $dateTo['tm_mday'];
    } else {
      show_404();
    }

    if (ManagerHolder::get('ApartmentReserv')->isReserved($_POST['apartment_id'], $_POST['date_from'], $_POST['date_to']) || ($_POST['date_from'] >= $_POST['date_to'])) {
      set_flash_error('error.reserved');
      redirect_to_referral();
    }

    $person_info = "";
    $person_arr_info = array();
    foreach ($_POST as $k => $v) {
      if (strpos($k, 'p_name_') === 0) {
        $n = str_replace('p_name_', '', $k);
        $person_info .= $_POST['p_name_' . $n] . ' ' . $_POST['p_surname_' . $n] . ' ' . $_POST['p_pas_' . $n] . "\n";
        array_push($person_arr_info, $_POST['p_name_' . $n] . ' ' . $_POST['p_surname_' . $n]);
        unset($_POST['p_name_' . $n]);
        unset($_POST['p_surname_' . $n]);
        unset($_POST['p_pas_' . $n]);
      }
    }
    $_POST['persons_info'] = $person_info;
    $_POST['persons_arr_info'] = $person_arr_info;

    $totalCost = "";
    $dates = date_interval($_POST['date_from'], $_POST['date_to']);
    $nights = count($dates) - 1;
    $apart = ManagerHolder::get('Apartment')->getOneWhere(array('id' => $_POST['apartment_id']), 'e.*');

    if (isset($_POST['breakfast']) && isset($_POST['added_person'])){
      $totalCostIn = $nights*($apart['price_in']+$apart['add_person_in']) + $nights*($_POST['persons'] + 1)*5;
      $totalCost = $nights*($apart['price_out']+$apart['add_person_out']) + $nights*($_POST['persons'] + 1)*7;
    } elseif (isset($_POST['breakfast']) && !isset($_POST['added_person'])) {
      $totalCostIn = $nights*$apart['price_in'] + $nights*$_POST['persons']*5;
      $totalCost = $nights*$apart['price_out'] + $nights*$_POST['persons']*7;
    } elseif (!isset($_POST['breakfast']) && isset($_POST['added_person'])) {
      $totalCostIn = $nights*($apart['price_in']+$apart['add_person_in']);
      $totalCost = $nights*($apart['price_out']+$apart['add_person_out']);
    } elseif (!isset($_POST['breakfast']) && !isset($_POST['added_person'])) {
      $totalCostIn = $nights*$apart['price_in'];
      $totalCost = $nights*$apart['price_out'];
    }
    $_POST['total_cost'] = $totalCost;
    $_POST['total_cost_in'] = $totalCostIn;

    if($_POST['utype'] == 'yur') {
      $_POST['vat_rate'] = '7_perc';
    }

    try {
      $entity = ManagerHolder::get('Reservation')->createEntityFromPOST();
      // ВОЗМОЖНО ИНВОЙС УХОДИТ ЗДЕСЬ (внутри метода insert)
      ManagerHolder::get('Reservation')->insert($entity); 
    } catch (Exception $e) {
      set_flash_error($e->getMessage());
      redirect_to_referral();
    }

    $ent = ManagerHolder::get('Reservation')->getOneWhere(array('id' => $entity['id']));

    $street = htmlentities($ent['apartment']['street'], ENT_QUOTES, "UTF-8");
    $house = htmlentities($ent['apartment']['house_num'], ENT_QUOTES, "UTF-8");
    $landlord_name = htmlentities($ent['apartment']['c_name'], ENT_QUOTES, "UTF-8");
    $flat_num = htmlentities($ent['apartment']['flat_num'], ENT_QUOTES, "UTF-8");
    $_POST['name'] = htmlentities($_POST['name'], ENT_QUOTES, "UTF-8");
    $_POST['surname'] = htmlentities($_POST['surname'], ENT_QUOTES, "UTF-8");
    if(isset($_POST['org']) && !empty($_POST['org'])){
      $_POST['org'] = htmlentities($_POST['org'], ENT_QUOTES, "UTF-8");
    }
    $_POST['country'] = htmlentities($_POST['country'], ENT_QUOTES, "UTF-8");
    $_POST['city'] = htmlentities($_POST['city'], ENT_QUOTES, "UTF-8");
    $_POST['zip'] = htmlentities($_POST['zip'], ENT_QUOTES, "UTF-8");
    $_POST['street'] = htmlentities($_POST['street'], ENT_QUOTES, "UTF-8");
    $_POST['home'] = htmlentities($_POST['home'], ENT_QUOTES, "UTF-8");

    $obj_city = ManagerHolder::get('City')->getOneWhere(array('id' => $ent['apartment']['city_id']));

    if(isset($_POST['added_person']) && !empty($_POST['added_person'])){
      $apn_cost = $nights*($apart['price_out']+$apart['add_person_out']);
      $apn_cost_in = $nights*($apart['price_in']+$apart['add_person_in']);
      $br_cost = ($ent['persons']+1)*$nights*7;
      $br_cost_in = ($ent['persons']+1)*$nights*5;
    } else {
      $apn_cost = $nights*$apart['price_out'];
      $apn_cost_in = $nights*$apart['price_in'];
      $br_cost = $ent['persons']*$nights*7;
      $br_cost_in = $ent['persons']*$nights*5;
    }

    $vat = number_format($totalCost/107*7, 2, '.', '');
    $created = substr($ent['created_at'], 0, 10);

    $data = array();
    $data['info'] = $_POST;
    $data['info']['nights'] = $nights;
    $data['info']['apn_cost'] = $apn_cost;
    $data['info']['apn_cost_in'] = $apn_cost_in;
    $data['info']['br_cost'] = $br_cost;
    $data['info']['br_cost_in'] = $br_cost_in;
    $data['info']['vat_nr'] = $_POST['vat'];
    $data['info']['vat'] = $vat;
    $data['order'] = $ent;
    $data['order']['created'] = $created;
    $data['order']['apartment']['city'] = $obj_city;
    $data['order']['apartment']['street'] = $street;
    $data['order']['apartment']['house'] = $house;
    $data['order']['apartment']['landlord_name'] = $landlord_name;
    $data['order']['apartment']['flat_num'] = $flat_num;

    // 1. Send request confirmation email to user
    $to_user = array($_POST['email']);
    $subject_user = 'We received your request ' . $ent['code'] . ' - availability confirmation within 24 hours';
    try {
      // ИСПРАВЛЕНО: возвращаем твой новый лейаут вместо старого 'email'
      ManagerHolder::get('Email')->setLayout('email_request'); 
      ManagerHolder::get('Email')->setUseAltMessage(false);
      ManagerHolder::get('Email')->sendTemplate($to_user, 'request_received', $data, $subject_user);
      ManagerHolder::get('Email')->setUseAltMessage(true);
    } catch (Exception $e) {
      log_message('error', 'User Email Error: ' . $e->getMessage());
    }

    // 2. Send email to landlord
    $to_admin = 'business@bs-travelling.com';
    $subject_admin = lang('bs_traveling_email_subject') . ' ' .  $ent['code'];
    try {
      ManagerHolder::get('Email')->setLayout('email_de');
      ManagerHolder::get('Email')->sendTemplate($to_admin, 'landlords', $data, $subject_admin);
    } catch (Exception $e) {
      log_message('error', 'Landlord Email Error: ' . $e->getMessage());
    }

    // 3. Send email to bs_traveling
    try {
      ManagerHolder::get('Email')->setLayout('email_de');
      ManagerHolder::get('Email')->sendTemplate($to_admin, 'bs_travelling', $data, $subject_admin);
    } catch (Exception $e) {
      log_message('error', 'Admin Email Error: ' . $e->getMessage());
    }

    redirect('reservation/thank_you');
  }

  public function thankyou() {
    $this->layout->setLayout('noparts');
    $this->layout->view('thank_you');
  }
}