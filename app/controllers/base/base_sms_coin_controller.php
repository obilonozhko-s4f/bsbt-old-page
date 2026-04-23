<?php
/**
 * Created by IntelliJ IDEA.
 * User: victor
 * Date: 2/6/12
 * Time: 11:31 AM
 * To change this template use File | Settings | File Templates.
 */
abstract class Base_Sms_Coin_Controller extends Controller {

  private $secret_code = "devnevrotic";
  private $sms_bank_id = 14031;
  private $gateway = "http://bank.smscoin.com/bank/";

  public function Base_Sms_Coin_Controller() {
    parent::Controller();
    $this->load->library("common/Layout");
    $this->load->helper("url");
    $this->load->helper("common/itirra_commons");
    $this->load->helper("common/itirra_resources");
    $this->load->helper("common/itirra_language");
    $this->load->helper("common/itirra_messages");

    $this->lang->load('message_properties', $this->config->item('language'));

    $this->layout->setLayout('main');
    $this->layout->setModule('liqpay');
  }

  public function index() {
    $purse        = $this->sms_bank_id;  // sms:bank id        ������������� ���:�����
    $order_id     = 1234;           // operation id       ������������� ��������
    $amount       = 0.01;            // transaction sum    ����� ����������
    $clear_amount = 0;              // billing algorithm  �������� �������� ���������
    $description  = "test"; // operation desc     �������� ��������
    $submit       = "submit";       // submit label       ������� �� ������ submit
    echo $this->print_form($purse, $order_id, $amount, $clear_amount, $description, $this->secret_code, $submit);
  }

  public function get_payment_button($paymentAction, $paymentEntityId) {
    // initializing variables
    $purse        = $this->sms_bank_id;  // sms:bank id        ������������� ���:�����
    $order_id     = 1234;           // operation id       ������������� ��������
    $amount       = 0.01;            // transaction sum    ����� ����������
    $clear_amount = 0;              // billing algorithm  �������� �������� ���������
    $description  = lang("sms.desc.$paymentAction"); // operation desc     �������� ��������
    $submit       = lang("sms.btn.$paymentAction");       // submit label       ������� �� ������ submit

    $customData = array("payment_action"=> $paymentAction,
                        "payment_entity_id" => $paymentEntityId);
    if (isset($_GET['redirect'])) $customData['redirect'] = $_GET['redirect'];
    if (isset($_POST['redirect'])) $customData['redirect'] = $_POST['redirect'];
    log_message("debug", "create button with custom data "  . print_r($customData, true));
    echo $this->print_form($purse, $order_id, $amount, $clear_amount, $description, $this->secret_code, $submit, $customData);
  }

  public function result() {
    log_message('debug', "SMS-COIN-RESULT");
    log_message('debug', "SMS-COIN-RESULT " . print_r($_GET, true));
    if (ENV == "DEV" && !isset($_GET["s_sign_v2"])) {
      $_GET["s_purse"]        = $this->sms_bank_id;
      $_GET["s_order_id"]     = 1234;
      $_GET["s_amount"]       = 0.01;
      $_GET["s_clear_amount"] = 0;
      $_GET["s_inv"] = 0;
      $_GET["s_phone"] = 0;
      $_GET["s_sign_v2"] = $this->ref_sign($this->secret_code, $_GET["s_purse"], $_GET["s_order_id"], $_GET["s_amount"], $_GET["s_clear_amount"], $_GET["s_inv"], $_GET["s_phone"]);

    }
    // collecting required data
    $purse        = $_GET["s_purse"];        // sms:bank id        èäåíòèôèêàòîð ñìñ:áàíêà
    $order_id     = $_GET["s_order_id"];     // operation id       èäåíòèôèêàòîð îïåðàöèè
    $amount       = $_GET["s_amount"];       // transaction sum    ñóììà òðàíçàêöèè
    $clear_amount = $_GET["s_clear_amount"]; // billing algorithm  àëãîðèòì ïîäñ÷åòà ñòîèìîñòè
    $inv          = $_GET["s_inv"];          // operation number   íîìåð îïåðàöèè
    $phone        = $_GET["s_phone"];        // phone number       íîìåð òåëåôîíà
    $sign         = $_GET["s_sign_v2"];      // signature          ïîäïèñü

    // making the reference signature
    $reference = $this->ref_sign($this->secret_code, $purse, $order_id, $amount, $clear_amount, $inv, $phone);

    log_message('debug', "GOT sign " . $sign);
    log_message('debug', "MY  sign " . $reference);

    // validating the signature
    if($sign == $reference) {
      log_message('debug', "SMS-COIN-RESULT sign ok" );
      if (!isset($_GET['payment_action']) || !isset($_GET['payment_entity_id'])) {
        log_message('debug', "SMS-COIN-RESULT custom params are absent" );
        $this->not_valid();
      } else {
        log_message('debug', "-------------------------------------------------------------");
        log_message('debug', "process payments");
        $this->processOrder($_GET['payment_action'], $_GET['payment_entity_id']);
        log_message('debug', "-------------------------------------------------------------");
      }

      set_flash_notice("smscoin.thank_you.message");

      if (isset($_GET['redirect'])) {
        redirect($_GET['redirect']);
      } else {
        redirect();
      }
    } else {
      log_message('debug', "SMS-COIN-RESULT sign ERROR" );
      $this->not_valid();
    }
  }

  public abstract function processOrder($payment_action, $payment_entity_id);

  public function not_valid() {
    set_flash_notice("smscoin.not_valid.message");

    if (isset($_GET['redirect'])) {
      redirect($_GET['redirect']);
    } else {
      redirect();
    }
  }

  public function success() {
    set_flash_notice("smscoin.thank_you.message");

    if (isset($_GET['redirect'])) {
      redirect($_GET['redirect']);
    } else {
      redirect();
    }
  }

  public function failure() {
    set_flash_notice("smscoin.failure.message");
    if (isset($_GET['redirect'])) {
      redirect($_GET['redirect']);
    } else {
      redirect();
    }
  }

  private function ref_sign() {
    $params = func_get_args();
    $prehash = implode("::", $params);
    return md5($prehash);
  }

  // the function prints a request form
  function print_form($purse, $order_id, $amount, $clear_amount, $description, $secret_code, $submit, $customData = array()) {
    // making signature
    $sign = $this->ref_sign($purse, $order_id, $amount, $clear_amount, $description, $secret_code);

    $customDataHtml = "";
    foreach ($customData as $name=>$cd) {
      $customDataHtml .= '<input type="hidden" name="'.$name.'" value="'.$cd.'" />';
    }

    $html = '<form action="'.$this->gateway.'" method="post">
              <p>
                <input name="s_purse" type="hidden" value="'.$purse.'" />
                <input name="s_order_id" type="hidden" value="'.$order_id.'" />
                <input name="s_amount" type="hidden" value="'.$amount.'" />
                <input name="s_clear_amount" type="hidden" value="'.$clear_amount.'" />
                <input name="s_description" type="hidden" value="'.$description.'" />
                <input name="s_sign" type="hidden" value="'.$sign.'" />'.
                '<button class="def-but"><b class="f"></b><b class="s">'.$submit.'</b><b class="t"></b></button>'.
                $customDataHtml.
              '</p>
            </form>';
    return $html;
  }


}