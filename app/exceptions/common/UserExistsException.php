<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Exception.
 * @author Itirra - www.itirra.com
 */
class UserExistsException extends Exception {
  
  /** DUPLICATE_PHONE */
  const DUPLICATE_PHONE = 'DUPLICATE_PHONE';
  
  /** DUPLICATE_EMAIL */
  const DUPLICATE_EMAIL = 'DUPLICATE_EMAIL';
  
  /** DUPLICATE_EMAIL_PHONE */
  const DUPLICATE_EMAIL_PHONE = 'DUPLICATE_EMAIL_PHONE';  
  
  /** Not confirmed type  */
  private $type;

  /**
   * Constructor.
   */
  public function UserExistsException($type = self::DUPLICATE_EMAIL) {
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