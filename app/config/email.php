<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['email']['from_email'] = "info@bs-travelling.com";
$config['email']['from_name'] = "BS Business Travelling";

$config['email']['encode_subject'] = "";
$config['email']['encode_message'] = "";

$config['email']['settings'] = array(
  'useragent'      => 'BS-Business-Travelling',
  'protocol'       => 'smtp',
  'smtp_host'      => 'ssl://smtp.gmail.com',
  'smtp_port'      => '465',
  'smtp_user'      => 'business@bs-travelling.com', 
  'smtp_pass'      => 'vibvjrisqraqcitx', 
  'smtp_timeout'   => 20, 

  'mailtype'       => 'html',
  'charset'        => 'utf-8',
  'wordwrap'       => FALSE,  // Строго FALSE
  'validate'       => FALSE,
  'priority'       => 3,
  
  // === ЗОЛОТОЙ КЛЮЧ К РЕШЕНИЮ БАГА ===
  'newline'        => "\r\n", // Удовлетворяет Гугл: команды проходят, зависаний НЕТ
  'crlf'           => "\n",   // Удовлетворяет CodeIgniter: заголовки собираются ровно, разрывов НЕТ
  // ===================================

  'bcc_batch_mode' => TRUE,
  'bcc_batch_size' => 200
);