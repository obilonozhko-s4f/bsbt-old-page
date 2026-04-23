<?php
/**
 * Created by IntelliJ IDEA.
 * User: victor
 * Date: 2/6/12
 * Time: 11:31 AM
 * To change this template use File | Settings | File Templates.
 */
abstract class Base_Liqpay_Controller extends Controller {

  public function Base_Liqpay_Controller() {
    parent::Controller();

    $this->load->helper("url");
    $this->load->library("common/Liqpay");
    $this->load->library("common/Layout");
    $this->load->helper("common/itirra_commons");
    $this->load->helper("common/itirra_resources");
    $this->load->helper("common/itirra_language");
    $this->load->helper("common/itirra_messages");

    $this->layout->setLayout('main');
    $this->layout->setModule('liqpay');

    $this->lang->load('message_properties', $this->config->item('language'));

  }


  public function test() {
    $this->load->config('liqpay');
    $this->cf = $this->config->item("liqpay");

    foreach($this->cf['forms'] as $key=>$form) {
      echo $this->liqpay->createLiqpayForm($key, array("ask_guru", 1, rand(0,10000)), "test");
    }
  }


  public function result() {
    // TEST YOUR BUSINESS LOGIC
    // CHANGE ENV on PRODUCTION!!!!!!
    if (ENV == "DEV" && !empty($_GET)) {
      $this->processOrder($_GET);
      return;
    }
    $xmlResult = (isset($_POST['operation_xml'])) ? $_POST['operation_xml'] : false;
    $signature = (isset($_POST['signature'])) ? $_POST['signature'] : false;
    log_message("debug", "recieve payment " . print_r($_POST, true));


    if (!$xmlResult || !$signature) {
      // try to get params from get (TEST CASE)
      $xmlResult = (isset($_GET['operation_xml'])) ? $_GET['operation_xml'] : false;
      $signature = (isset($_GET['signature'])) ? $_GET['signature'] : false;
      if (!$xmlResult || !$signature) show_404();
    }

    $liqPayResult = $this->liqpay->parseLiqpayResultXML($xmlResult, $signature);

    if ($liqPayResult && $liqPayResult->status . "" == "success") {
      log_message("debug", "process order");
      $params = $this->liqpay->parseOrderId($liqPayResult->order_id);
      log_message("debug", "pre process complete");
      try {
        $this->processOrder($params);
      } catch (Exception $e) {
        log_message("error", "--------------------------------------");
        log_message("error", "process error " . $e->getMessage());
        log_message("error", "--------------------------------------");
      }
      log_message("debug", "process complete");
    } else {
      $this->failed($liqPayResult);
    }
  }

  public abstract function thank_you();


  public function failed($liqPayResult) {
    if ($liqPayResult) {
      log_message("error", "status is not success " . print_r($liqPayResult, true));
    } else {
      log_message("error", "signature failed or parse response");
    }
    set_flash_error("liqpay.failed.message");
    redirect("/");
  }

}