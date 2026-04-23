<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Exception.
 * @author Itirra - www.itirra.com
 */
class ValidationException extends Exception {
  
  /** Errors  */
  private $errors;

  /**
   * Constructor.
   */
  public function ValidationException($errors) {
    $this->errors = $errors;
  } 
  
  /**
   * GetErrors.
   * @return string type
   */
  public function getErrors() {
    return $this->errors; 
  }
  
}