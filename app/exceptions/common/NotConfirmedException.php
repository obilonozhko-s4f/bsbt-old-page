<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Exception.
 * @author Itirra - www.itirra.com
 */
class NotConfirmedException extends Exception {
  
  /** NOT_CONFIRMED_PHONE */
  const NOT_CONFIRMED_PHONE = 'NOT_CONFIRMED_PHONE';
  
  /** NOT_CONFIRMED_EMAIL */
  const NOT_CONFIRMED_EMAIL = 'NOT_CONFIRMED_EMAIL';
  
  /** NOT_CONFIRMED_EMAIL_PHONE */
  const NOT_CONFIRMED_EMAIL_PHONE = 'NOT_CONFIRMED_EMAIL_PHONE';  
  
  /** Not confirmed type  */
  private $type;

  /**
   * Constructor.
   */
  public function NotConfirmedException($type = self::NOT_CONFIRMED_EMAIL) {
    $this->type = $type;
  } 
  
  /**
   * GetType.
   * @return string type
   */
  public function getType() {
    return $this->type; 
  }
  
}