<?php
require_once APPPATH . 'controllers/admin/base/base_admin_lang_controller.php';

/**
 * Xadmin controller.
 * @author Itirra - http://itirra.com
 */
class xAdmin_Reservation extends Base_Admin_Lang_Controller {

  /** Print */
  protected $print = TRUE;

  /** Filter. Row example: "column_name" => default_value. Default value may be null. */
  protected $filters = array('apartment.id' => '', 'is_published' => '');

  /** Date Filters. Row example: array("created_at"). */
  protected $dateFilters = array('date_from', 'created_at');

  /** Search params. */
  protected $searchParams = array("code");

  /** Additional Actions. */
  protected $additionalActions = array('printlist');

  /**
   * Index.
   */
  public function index($page = "page1") {
    ManagerHolder::get($this->managerName)->setLanguage('en');
    if (empty($page)) {
      $page = pager_add_prefix(1);
    }
    $this->checkPermissions('_view');
    $manager = ManagerHolder::get($this->managerName);
    $this->setSearchAndOrderBy($manager);
    $this->setFilter();
    $where = $this->filters;
    if (!empty($this->extraWhere)) {
      $where = array_merge($where, $this->extraWhere);
    }

    if ($this->perPage != 'all') {
      $res = $manager->getAllWhereWithPager($where, pager_remove_prefix($page), $this->perPage, $this->preProcessParams(), $this->excludeIds);
    } else {
      $res = new stdClass();
      $res->data = $manager->getAllWhere($where, $this->preProcessParams());
      $res->pager = null;
    }

    // Count total price values (P-in, P-out, VAT)
    $priceArr = array('total_cost_in' => 0,
                      'total_cost' => 0,
                      'vat_summ' => 0);

    $allEnt = $manager->getAllWhere($where, $this->preProcessParams());
    if(!empty($allEnt)) {
      foreach ($allEnt as $e) {
        $priceArr['total_cost_in'] += $e['total_cost_in'];
        $priceArr['total_cost'] += $e['total_cost'];
        $priceArr['vat_summ'] += $e['vat_summ'];
      }
    }
    $this->layout->set('priceArr', $priceArr);

    $this->setViewParamsIndex($res->data, $res->pager, TRUE);
    $this->layout->view('res_entity_list');
  }

  /**
   * setViewParamsIndex
   * @param  $entities
   * @param  $pager
   * @param  $hasSidebar
   */
  protected function setViewParamsIndex(&$entities, &$pager, $hasSidebar) {
    $this->actions["invoice_user"] = $this->adminBaseRoute . '/' . strtolower($this->entityUrlName) . '/invoice/user';
    $this->actions["invoice_landlord"] = $this->adminBaseRoute . '/' . strtolower($this->entityUrlName) . '/invoice/landlord';
    $this->actions["invoice_bs"] = $this->adminBaseRoute . '/' . strtolower($this->entityUrlName) . '/invoice/bs';
    $this->actions["invoice_email"] = $this->adminBaseRoute . '/' . strtolower($this->entityUrlName) . '/invoice/email';
    $this->actions["invoice_email_vaucher"] = $this->adminBaseRoute . '/' . strtolower($this->entityUrlName) . '/invoice/email_vaucher';
    $this->actions["invoice_vaucher"] = $this->adminBaseRoute . '/' . strtolower($this->entityUrlName) . '/invoice/vaucher';
    parent::setViewParamsIndex($entities, $pager, $hasSidebar);
  }


  /**
   * Invoice_user.
   */
  public function invoice($type, $id) {
    $this->layout->setLayout("invoice_email");

    $entity = ManagerHolder::get($this->managerName)->getByIdArray($id);
    $ent = array();
    foreach($entity as $e) {
    	$ent = $e;
    }

    // setting data array for invoice
    $dates = date_interval($ent['date_from'], $ent['date_to']);
    $nights = count($dates) - 1;

    if(isset($ent['added_person']) && !empty($ent['added_person'])){
      $apn_cost = $nights*($ent['apartment']['price_out']+$ent['apartment']['add_person_out']);
      $apn_cost_in = $nights*($ent['apartment']['price_in']+$ent['apartment']['add_person_in']);
      $br_cost = ($ent['persons']+1)*$nights*7;
      $br_cost_in = ($ent['persons']+1)*$nights*5;
    } else {
      $apn_cost = $nights*$ent['apartment']['price_out'];
      $apn_cost_in = $nights*$ent['apartment']['price_in'];
      $br_cost = $ent['persons']*$nights*7;
      $br_cost_in = $ent['persons']*$nights*5;
    }

    // counting VAT Rate
    if($ent['utype'] == 'fiz') {
      $vat_count = number_format($ent['total_cost']/107*7, 2, '.', '');
    } else {
      if(isset($ent['vat_rate']) && !empty($ent['vat_rate'])) {
        if($ent['vat_rate'] == '19_perc') {
          $vat_count = number_format($ent['total_cost']/107*7, 2, '.', '');
        } elseif($ent['vat_rate'] == 'reverse_charge') {
          $vat_count = 'Reverse Charge';
        } elseif($ent['vat_rate'] == 'zero') {
          $vat_count = number_format(0, 2, '.', '');
        }
      } else {
        $vat_count = '';
      }
    }

    $obj_city = ManagerHolder::get('City')->getOneWhere(array('id' => $ent['apartment']['city_id']));
    $flat_num = $ent['apartment']['flat_num'];

    foreach ($ent['apartment']['translations'] as $a) {
    	if($a['language'] == 'en'){
    		$street = $a['street'];
    		$house = $a['house_num'];
    		$landlord_name = $a['c_name'];
    		break;
    	}
    }

    $info = array();
    if(isset($ent['added_person']) && !empty($ent['added_person'])){
    	$info['added_person'] = 1;
    }
    $info['apartment_id'] = $ent['apartment']['id'];
    $info['name'] = $ent['name'];
    $info['surname'] = $ent['surname'];
    $info['org'] = $ent['org'];
    $info['street'] = $ent['street'];
    $info['home'] = $ent['home'];
    $info['office'] = $ent['office'];
    $info['zip'] = $ent['zip'];
    $info['city'] = $ent['city'];
    $info['country'] = $ent['country'];
    $info['vat'] = $ent['vat'];
    $info['total_cost'] = $ent['total_cost'];
    $info['total_cost_in'] = $ent['total_cost_in'];
    $info['nights'] = $nights;
    $info['apn_cost'] = $apn_cost;
    $info['apn_cost_in'] = $apn_cost_in;
    $info['br_cost'] = $br_cost;
    $info['br_cost_in'] = $br_cost_in;
    $info['vat_count'] = $vat_count;
    $info['utype'] = $ent['utype'];
    $info['vat_rate'] = $ent['vat_rate'];
    $info['breakfast'] = $ent['breakfast'];
    $info['persons_arr_info'] = $ent['persons_info'];
    $info['email'] = $ent['email'];
    $info['phone'] = $ent['phone'];
    $info['transfer'] = $ent['transfer'];

    $order = array();
    $order['created_at'] = $ent['created_at'];
    $order['created'] = $ent['created_at'];
    $order['persons'] = $ent['persons'];
    $order['code'] = $ent['code'];
    $order['date_from'] = $ent['date_from'];
    $order['date_to'] = $ent['date_to'];
    $order['apartment']['post_index'] = $ent['apartment']['post_index'];
    $order['apartment']['city'] = $obj_city;
    $order['apartment']['street'] = $street;
    $order['apartment']['house'] = $house;
    $order['apartment']['landlord_name'] = $landlord_name;
    $order['apartment']['flat_num'] = $flat_num;
    $order['apartment']['price_out'] = $ent['apartment']['price_out'];
    $order['apartment']['add_person_out'] = $ent['apartment']['add_person_out'];
    $order['apartment']['c_pr_phone'] = $ent['apartment']['c_pr_phone'];

    $this->layout->set('info', $info);
    $this->layout->set('order', $order);

    // Setting data for emails
    $data = array();
    $data['info'] = $info;
    $data['order'] = $order;

    // checking fields for de umlauts
    $data['order']['apartment']['street'] = htmlentities($data['order']['apartment']['street'], ENT_QUOTES, "UTF-8");
    $data['order']['apartment']['house'] = htmlentities($data['order']['apartment']['house'], ENT_QUOTES, "UTF-8");
    $data['order']['apartment']['landlord_name'] = htmlentities($data['order']['apartment']['landlord_name'], ENT_QUOTES, "UTF-8");
    $data['order']['apartment']['flat_num'] = htmlentities($data['order']['apartment']['flat_num'], ENT_QUOTES, "UTF-8");
    $data['info']['name'] = htmlentities($data['info']['name'], ENT_QUOTES, "UTF-8");
    $data['info']['surname'] = htmlentities($data['info']['surname'], ENT_QUOTES, "UTF-8");
    if(isset($data['info']['org']) && !empty($data['info']['org'])){
      $data['info']['org'] = htmlentities($data['info']['org'], ENT_QUOTES, "UTF-8");
    }
    $data['info']['country'] = htmlentities($data['info']['country'], ENT_QUOTES, "UTF-8");
    $data['info']['city'] = htmlentities($data['info']['city'], ENT_QUOTES, "UTF-8");
    $data['info']['zip'] = htmlentities($data['info']['zip'], ENT_QUOTES, "UTF-8");
    $data['info']['street'] = htmlentities($data['info']['street'], ENT_QUOTES, "UTF-8");
    $data['info']['home'] = htmlentities($data['info']['home'], ENT_QUOTES, "UTF-8");
    $data['info']['persons_arr_info'] = htmlentities($data['info']['persons_arr_info'], ENT_QUOTES, "UTF-8");

    switch ($type) {
    	case 'user': {
    		$this->layout->view('invoice/ty');
    		break;
    	}
      case 'landlord': {
    		$this->layout->view('invoice/admin_landlords');
    		break;
    	}
      case 'bs': {
    		$this->layout->view('invoice/admin_bs_travelling');
    		break;
    	}
      case 'email': {
		    // Send email to user
		    $to = array($info['email']);
		    $subject = lang('user_email_subject') . ' ' .  $ent['code'];
		    try {
		      ManagerHolder::get('Email')->sendTemplate($to, 'admin_ty', $data, $subject);
		    } catch (Exception $e) {
		      log_message('error', $e->getMessage());
		    }

		    // Send email to landlord
		    $to = 'business@bs-travelling.com';
		    $subject = lang('bs_traveling_email_subject') . ' ' .  $ent['code'];
		    try {
		      ManagerHolder::get('Email')->setLayout('email_de');
		      ManagerHolder::get('Email')->sendTemplate($to, 'admin_landlords', $data, $subject);
		    } catch (Exception $e) {
		      log_message('error', $e->getMessage());
		    }

		    // Send email to bs_traveling
		    try {
		      ManagerHolder::get('Email')->setLayout('email_de');
		      ManagerHolder::get('Email')->sendTemplate($to, 'admin_bs_travelling', $data, $subject);
		    } catch (Exception $e) {
		      log_message('error', $e->getMessage());
		    }
		    set_flash_message("notice", "Проверьте почту. Письма были успешно высланы на ваш email.");
		    redirect_to_referral();
    	}
      case 'email_vaucher': {
      	// Send email to user
		    $to = array($info['email']);
		    $subject = 'Conformation (Voucher) ' .  $ent['code'];
		    try {
					ManagerHolder::get('Email')->setAttachment('./web/gvh.pdf');
		      ManagerHolder::get('Email')->sendTemplate($to, 'admin_vaucher', $data, $subject);
		    } catch (Exception $e) {
		      log_message('error', $e->getMessage());
		    }
		    set_flash_message("notice", "Письмо было успешно выслано клиенту на " . $info['email']);
		    redirect_to_referral();
    	}
      case 'vaucher': {
    		$this->layout->view('invoice/admin_vaucher');
    		break;
    	}
    }
  }

  /**
   * Implementation of PRE_INSERT event callback
   * @param Object $entity reference
   * @return Object
   */
  protected function preInsert(&$entity) {
    $apart = ManagerHolder::get('Apartment')->getOneWhere(array('id' => $entity['apartment_id']), 'e.*');
    if (ManagerHolder::get('ApartmentReserv')->isReserved($entity['apartment_id'], $entity['date_from'], $entity['date_to']) || ($entity['date_from'] >= $entity['date_to'])) {
      save_post();
      set_flash_error('error.reserved');
      redirect_to_referral();
    } elseif ($entity['added_person'] == true && (empty($apart['add_person_in']) || empty($apart['add_person_out']))) {
      save_post();
      set_flash_error('error.add_person.not_set');
      redirect_to_referral();
    }
  }

   /**
   * Implementation of PRE_UPDATE event callback
   * @param Object $entity
   * @return Object
   */
  protected function preUpdate($entity){
  	$order = ManagerHolder::get('Reservation')->getOneWhere(array('id' => $entity['id']));
    $res = ManagerHolder::get('ApartmentReserv')->getOneWhere(array('apartment_id' => $order['apartment_id'], 'date_from' => $order['date_from'], 'date_to' => $order['date_to']));
    ManagerHolder::get('ApartmentReserv')->deleteById($res['id']);

  	$apart = ManagerHolder::get('Apartment')->getOneWhere(array('id' => $entity['apartment_id']), 'e.*');
    if (ManagerHolder::get('ApartmentReserv')->isReserved($entity['apartment_id'], $entity['date_from'], $entity['date_to']) || ($entity['date_from'] >= $entity['date_to'])) {
      save_post();
      set_flash_error('error.reserved');
      redirect_to_referral();
    } elseif ($entity['added_person'] == true && (empty($apart['add_person_in']) || empty($apart['add_person_out']))) {
      save_post();
      set_flash_error('error.add_person.not_set');
      redirect_to_referral();
    }
		ManagerHolder::get('ApartmentReserv')->insert(array('date_from' => $entity['date_from'], 'date_to' => $entity['date_to'], 'apartment_id' => $entity['apartment_id']));
  }

  public function printlist($page = "") {
    $this->layout->setLayout("ajax");
    if (empty($page)) {
      $page = pager_add_prefix(1);
    }
    $manager = ManagerHolder::get($this->managerName);

    $this->setSearchAndOrderBy($manager);
    $this->setFilter();
    $where = $this->filters;
    if (!empty($this->extraWhere)) {
      $where = array_merge($where, $this->extraWhere);
    }

    if ($this->perPage != 'all') {
      $res = $manager->getAllWhereWithPager($where, pager_remove_prefix($page), $this->perPage, $this->preProcessParams(), $this->excludeIds);
    } else {
      $res = new stdClass();
      $res->data = $manager->getAllWhere($where, $this->preProcessParams());
      $res->pager = null;
    }

    $newArr = array();
    foreach ($res->data as $rd) {
      foreach ($rd['apartment']['translations'] as $at) {
        if($at['language'] == 'en'){
          $rd['apartment']['street'] = $at['street'];
          array_push($newArr, $rd);
        }
      }
    }
    $res->data = $newArr;

    $this->setViewParamsIndex($res->data, $res->pager, TRUE);
    $this->layout->view('parts/printlist');
  }

  public function vat_update_all() {
    $reservations = ManagerHolder::get('Reservation')->getAll('e.*');
    foreach ($reservations as $entity) {
      // Counting VAT summ
      $vat_summ = NULL;
      if($entity['utype'] == 'fiz') {
        $vat_summ = number_format($entity['total_cost']/107*7, 2, '.', '');
      } else {
        if($entity['vat_rate'] == '7_perc') {
          $vat_summ = number_format($entity['total_cost']/107*7, 2, '.', '');
        } elseif($entity['vat_rate'] == 'reverse_charge') {
          $vat_summ = 'RC';
        } elseif($entity['vat_rate'] == 'zero') {
          $vat_summ = '0';
        }
      }
      ManagerHolder::get('Reservation')->updateById($entity['id'], 'vat_summ', $vat_summ);
    }
    die('Ok');
  }

  /**
   * SetAddEditDataAndShowView.
   * Set all needed view data and show add_edit form.
   * @param object $entity
   */
  protected function setAddEditDataAndShowView($entity) {
    $this->preProcessFields($entity);
    if (isset($entity['apartment']) && !empty($entity['apartment'])) {
      $apart = ManagerHolder::get('Apartment')->getOneWhere(array('id' => $entity['apartment']), 'e.*');
      $this->fields['apartment']['type'] = 'input';
      $this->fields['apartment']['class'] .= ' readonly';
      $this->fields['apartment']['attrs']['readonly'] = ' readonly';
      if(empty($apart['add_person_in']) || empty($apart['add_person_out'])){
        unset($this->fields['added_person']);
      }
    }

    $this->layout->set("fields", $this->fields);
    $this->layout->set("backUrl", $this->adminBaseRoute . '/' .  strtolower($this->entityUrlName . get_get_params()));
    if (!empty($entity['id'])) {
      $this->layout->set("nextUrl", $this->adminBaseRoute . '/' .  strtolower($this->entityUrlName) . '/next/' . $entity['id'] . '/' . get_get_params());
      $this->layout->set("prevUrl", $this->adminBaseRoute . '/' .  strtolower($this->entityUrlName) . '/prev/' . $entity['id'] . '/' . get_get_params());
    }
    $this->layout->set("processLink", $this->adminBaseRoute . '/' .  strtolower($this->entityUrlName) . '/add_edit_process');
    $this->preAddEditView($entity);
    $this->layout->set("entity", $entity);
    $this->layout->set("actions", $this->actions);
    $this->layout->set("print", $this->print);
    $this->layout->view($this->itemView);
  }

}
