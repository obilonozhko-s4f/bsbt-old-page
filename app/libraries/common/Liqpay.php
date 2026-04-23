<?php
/**
 * Created by IntelliJ IDEA.
 * User: victor
 * Date: 2/8/12
 * Time: 1:20 PM
 * To change this template use File | Settings | File Templates.
 */
class Liqpay {

  private static $ORDER_DELIMETR = "::";

  private $CI;

  private $config;

  public function Liqpay() {
    $this->CI =& get_instance();

    $this->CI->load->config("liqpay");
    $this->config = $this->CI->config->item("liqpay");
  }

  protected function getMerchantId($type) {
    return $this->config['cfg'][$type]['merchant_id'];
  }

  protected function getMerchantSignature($type) {
    return $this->config['cfg'][$type]['signature'];
  }

  protected function sign($xml, $type) {
    $mercSign = $this->getMerchantSignature($type);
    $sign = base64_encode(sha1($mercSign . $xml . $mercSign, 1));
    return $sign;
  }

  /**
  $xml=”<response>
  <version>1.2</version>
  <merchant_id></merchant_id>
  <order_id> ORDER_123456</order_id>
  <amount>1.01</amount>
  <currency>UAH</currency>
  <description>Comment</description>
  <status>success</status>
  <code></code>
  <transaction_id>31</transaction_id>
  <pay_way>card</pay_way>
  <sender_phone>+3801234567890</sender_phone>
  <goods_id>1234</goods_id>
  <pays_count>5</pays_count>
  </response>";
   *
   *
   */
  public function parseLiqpayResultXML($xml, $signature) {
    $xml_decoded = base64_decode($xml);
    log_message('debug', $xml_decoded);
    $oXml = false;

    try {
      $oXml = simplexml_load_string($xml_decoded);
    } catch (Exception $e) {
      return FALSE;
    }

    $type = $oXml->pay_way . "";

    log_message('debug', "pay way: " . $type);
    $localSignature = $this->sign($xml_decoded, $type);

    log_message('debug', "comparing signatures:");
    log_message('debug', "local : $localSignature");
    log_message('debug', "remote: $signature");
    return ($localSignature == $signature) ? $oXml : FALSE;
  }

  /**
   * <request>
        <version>1.2</version>
        <merchant_id></merchant_id>
        <result_url>result_url</result_url>
        <server_url>server_url</server_url>
        <order_id>ORDER_123456</order_id>
        <amount>1.01</amount>
        <currency>UAH</currency>
        <description>Comment</description>
        <default_phone></default_phone>
        <pay_way>card</pay_way>
        <goods_id>1234</goods_id>
     </request>
   * @param $type
   * @return string
   */
  protected function createLiqpayRequestXML($formName, $orderId, $redirectUrl) {
    $formData = $this->config['forms'][$formName];
    $version = $this->config['cfg']['version'];

    $merchantId = $this->getMerchantId($formData['pay_way']);
    $resultUrl  = (isset($formData['result_url'])) ? $formData['result_url'] : $this->config['cfg']['result_url'];
    $resultUrl  = site_url($resultUrl);
    if ($redirectUrl) {
      $resultUrl .= "?redirect=" . urlencode($redirectUrl);
    }
    $serverUrl = (isset($formData['server_url'])) ? $formData['server_url'] : $this->config['cfg']['server_url'];
    $serverUrl = site_url($serverUrl);
    $amount  = $formData['amount'];
    $currency  = $formData['currency'];
    $description  = $formData['description'];
    $defaultPhone  = $formData['default_phone'];

    $payWay  = $formData['pay_way'];

    return "<request>
              <version>$version</version>
              <merchant_id>$merchantId</merchant_id>
              <result_url>$resultUrl</result_url>
              <server_url>$serverUrl</server_url>
              <order_id>$orderId</order_id>
              <amount>$amount</amount>
              <currency>$currency</currency>
              <description>$description</description>
              <default_phone>$defaultPhone</default_phone>
              <pay_way>$payWay</pay_way>
              <goods_id>1</goods_id>
           </request>";
  }

  public function createLiqpayForm($formName, array $order, $redirectUrl = false) {
    if (!isset($this->config['forms'][$formName])) return "";

    $orderId = $this->createOrderId($order);
    $xml = $this->createLiqpayRequestXML($formName, $orderId, $redirectUrl);
    log_message("debug", "prepare: " . $xml);
    $payWay = $this->config['forms'][$formName]['pay_way'];
    $sign = $this->sign($xml, $payWay);
    $xmlEncoded = base64_encode($xml);
    $formData = $this->config['forms'][$formName];
    return $this->CI->layout->render("includes/liqpay/pay_button", array("signature" => $sign, "operation_xml" => $xmlEncoded, "buttonName" => $formData['button_name']), true);
  }

  public function createOrderId($params) {
    log_message("debug", "create order_id " . implode(self::$ORDER_DELIMETR, $params));
    return implode(self::$ORDER_DELIMETR, $params);
  }

  public function parseOrderId($orderId) {
    log_message("debug", "parse order_id " . print_r(explode(self::$ORDER_DELIMETR, $orderId), true));
    return explode(self::$ORDER_DELIMETR, $orderId);
  }

}