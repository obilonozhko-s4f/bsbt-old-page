<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'controllers/base/base_controller.php';
require_once APPPATH . 'exceptions/common/ValidationException.php';
require_once APPPATH . 'exceptions/common/UserExistsException.php';

/**
 * Base Auth Controller
 * @author Itirra - www.itirra.com
 */
class Base_Auth_Controller extends Base_Controller {
  
  /**
   * @var CI_Session
   */
  public $session;
  
  /**
   * @var Auth
   */
  public $auth;
  
  /** Libraries to load.*/
  protected $libraries = array('common/DoctrineLoader',
  														 'Session',
  														 'common/Auth');  
  
  /** Configs to load.*/
  protected $configs = array('auth');

  /** Helpers to load.*/
  protected $helpers = array('url', 'common/itirra_validation', 'common/itirra_messages', 'common/itirra_language', 'common/itirra_resources');
  
  /** Is logged In.*/
  protected $isLoggedIn;
  
  /** AuthEntity.*/
  protected $authEntity;  

  /** Auth config. */
  protected $authConfig;
  
  /**
   * Constructor.
   */
  public function Base_Auth_Controller() {
    parent::Base_Controller();
    $this->lang->load('validation_messages', $this->config->item('language'));
    $this->authConfig = $this->config->item('auth');
    $this->isLoggedIn = $this->auth->isLoggedIn();
    $this->authEntity = $this->auth->getAuthEntity();
    $this->layout->setLayout($this->config->item('layout', 'auth'));
    $this->layout->setModule($this->config->item('module', 'auth'));
    $this->layout->set('isLoggedIn', $this->isLoggedIn);    
    $this->layout->set('authEntity', $this->authEntity);
  }
  
  // -----------------------------------------------------------------------------------------
  // ------------------------------------- REGISTER ------------------------------------------
  // -----------------------------------------------------------------------------------------  
  
	/**
   * Register page.
   */
  public function register() {
    if ($this->isLoggedIn) show_404();
    $this->setRegisterViewData();
    $this->layout->view($this->authConfig['view_register']);
  }
  
  /**
   * Ajax register page.
   */
  public function ajax_register() {
    if ($this->isLoggedIn) show_404();
    $this->layout->setLayout('ajax');
    $this->layout->set('isAjax', TRUE);
    $this->setRegisterViewData();
    $this->layout->view($this->authConfig['view_register']);
  }

  /**
   * Register process action.
   */
  public function register_process() {
    try {
      $this->auth->register($_POST);
      $this->postRegisterProcess();
    } catch (ValidationException $e) {
      log_message('debug', 'auth - ValidationException');
      save_post(array('password', 'password_confirmation'));
      set_flash_validation_errors($e->getErrors());
      redirect($this->authConfig['url_register']);
    } catch (UserExistsException $e) {
      log_message('debug', 'auth - UserExistsException');
      save_post(array('password', 'password_confirmation'));
      set_flash_error('auth.error.user_exists');
      redirect($this->authConfig['url_register']);
    } catch (UserLoggedInException $e) {
      log_message('debug', 'auth - UserLoggedInException');
      set_flash_warning('auth.error.logout_first_to_register');
      redirect($this->authConfig['url_register']);
    } catch (EmailSendingException $e) {
      log_message('debug', 'auth - EmailSendingException');
      set_flash_error('error.email_sending');
      redirect($this->authConfig['url_register']);
    } catch (NotConfirmedException $e) {
      log_message('debug', 'auth - EmailNotConfirmedException ' . $e->getMessage());
      set_flash_warning('auth.error.email_not_confirmed');
      redirect($this->authConfig['url_email_confirm']);
    } catch (Exception $e) {
      log_message('debug', 'auth - Exception ' . $e->getMessage());
      set_flash_error($e->getMessage());
      redirect($this->authConfig['url_register']);
    }
  }
  
  /**
   * Post register_process.
   * Override this in your class.
   */
  protected function postRegisterProcess() {
    if ($this->authConfig['email_confirmation']) {
      set_flash_notice('auth.message.confirmation_email_sent');
      redirect($this->authConfig['url_email_confirm']);
    } else {
      redirect($this->authConfig['redirect_after_register']);
    }
  }
  
  /**
   * Set register view data.
   * Override this in your class.
   */
  protected function setRegisterViewData() {}
  
  // -----------------------------------------------------------------------------------------
  // ---------------------------------- EMAIL CONFIRM ----------------------------------------
  // -----------------------------------------------------------------------------------------    
  
	/**
   * Email confirm page.
   */
  public function email_confirm() {
    if (!$this->auth->isLoggedIn()) {
      set_flash_error("auth.error.message.login_first_to_confirm");
      redirect($this->authConfig['url_login']);
    } else if ($this->auth->isEmailConfirmed()) {
      set_flash_notice("auth.message.already_confirmed");
      redirect($this->authConfig['redirect_after_login']);
    } else {
      $this->layout->view('email_confirm');
    }
  }
  
	/**
   * Email confirm process action.
   * @param string $key
   */
  public function email_confirm_process($key = null) {
    if ($this->auth->isEmailConfirmed()) {
      set_flash_notice("auth.message.already_confirmed");
      redirect($this->authConfig['redirect_after_login']);
    }
    if ($key == null && isset($_POST['activation_key'])) {
      $key = $_POST['activation_key'];  
    }
    try {
      $this->auth->confirmEmail($key);
      $this->postConfirmEmailProcess();      
    } catch (ValidationException $e) {
      set_flash_validation_errors($e->getErrors());
      redirect($this->authConfig['url_email_confirm']);
    } catch (NoUserException $e) {      
      set_flash_error('auth.error.wrong_activation_key');
      redirect($this->authConfig['url_email_confirm']);
    } catch (EmailSendingException $e) {
      set_flash_error('error.email_sending');
      redirect($this->authConfig['url_email_confirm']);                 
    } catch (UserNotLoggedInException $e) {
      set_flash_error("auth.error.message.login_first_to_confirm");
      redirect($this->authConfig['url_login']);
    } catch (Exception $e) {
      set_flash_error($e->getMessage());
      redirect($this->authConfig['url_email_confirm']); 
    }
  }  
  
  public function postConfirmEmailProcess() {
    redirect($this->authConfig['redirect_after_register']);
  }
  
  /**
   * Resend confirmation action.
   */
  public function resend_email_confirm() {
    if ($this->auth->isEmailConfirmed()) show_404();
    try {
      $this->auth->sendEmailConfirmation();
      set_flash_notice('auth.message.confirmation_code_resent');
      redirect($this->authConfig['url_email_confirm']);
    } catch (EmailSendingException $e) {
      set_flash_error('error.email_sending');
      redirect($this->authConfig['url_email_confirm']);
    } catch (UserNotLoggedInException $e) {
      set_flash_error('auth.error.not_logged_in');
      redirect($this->authConfig['url_email_confirm']);
    } catch (Exception $e) {
      set_flash_error($e->getMessage());
      redirect($this->authConfig['url_email_confirm']);
    }
  }
  
  // -----------------------------------------------------------------------------------------
  // --------------------------------------- LOGIN -------------------------------------------
  // -----------------------------------------------------------------------------------------      
  
	/**
   * Login.
   */
  public function login() {
    if ($this->isLoggedIn) show_404();
    $this->setLoginViewData();
    $this->layout->view($this->authConfig['view_login']);
  }
  
  /**
   * Ajax login.
   */
  public function ajax_login() {
    if ($this->isLoggedIn) show_404();
    $this->layout->setLayout('ajax');
    $this->layout->set('isAjax', TRUE);
    $this->setLoginViewData();
    $this->layout->view($this->authConfig['view_login']);
  }
  
	/**
   * Login action.
   */
  public function login_process() {  	
    //if ($this->isLoggedIn) show_404();
    try {
      log_message('debug', 'auth - login_process ' . print_r($_POST, true));
      $this->auth->login($_POST);
      log_message('debug', 'auth - postLoginProcess');
      $this->postLoginProcess();
    } catch (NoUserException $e) {
      log_message('debug', 'auth - NoUserException ' . $e->getMessage());
      set_flash_error('auth.error.wrong_email_password');
      redirect($this->authConfig['url_login']);      
    } catch (ValidationException $e) {
      log_message('debug', 'auth - ValidationException ' . $e->getMessage());
      set_flash_validation_errors($e->getErrors());
      redirect($this->authConfig['url_login']);
    } catch (NotConfirmedException $e) {
      log_message('debug', 'auth - EmailNotConfirmedException ' . $e->getMessage());
      set_flash_warning('auth.error.email_not_confirmed');
      redirect($this->authConfig['url_email_confirm']);
    } catch (UserLoggedInException $e) {
      log_message('debug', 'auth - UserLoggedInException ' . $e->getMessage());
    	set_flash_warning('auth.error.logout_first_to_login');
      redirect($this->authConfig['url_login']);
    } catch (UserBannedException $e) {
      log_message('debug', 'auth - UserBannedException ' . $e->getMessage());
      $bannedMessage = $e->getMessage();
      $bannedMessage = empty($bannedMessage) ? lang('auth.error.banned') : lang('auth.error.banned_because') . $bannedMessage;
      set_flash_error($bannedMessage);
      redirect($this->authConfig['url_login']);
    } catch (Exception $e) {
      log_message('debug', 'auth - Exception ' . $e->getMessage());
      set_flash_error($e->getMessage());
      redirect($this->authConfig['url_login']);
    }
  }
  
  /**
   * Set login view data.
   * Override this in your class.
   */
  protected function setLoginViewData() {}
  
  /**
   * Post login process
   * Override this in your class.
   */
  protected function postLoginProcess() {
    redirect($this->authConfig['redirect_after_login']);
  }

  // -----------------------------------------------------------------------------------------
  // -------------------------------------- FORGOT PASSWORD ----------------------------------
  // -----------------------------------------------------------------------------------------        
  
	/**
   * Forgot password page.
   */
  public function forgot_password() {
    if ($this->isLoggedIn) show_404();
    $this->layout->view($this->authConfig['view_forgot_password']);
  }
  
  /**
   * Ajax forgot password page.
   */
  public function ajax_forgot_password() {
    if ($this->isLoggedIn) show_404();
    $this->layout->setLayout('ajax');
    $this->layout->set('isAjax', TRUE);
    $this->layout->view($this->authConfig['view_forgot_password']);
  }

  /**
   * Forgot password action.
   */
  public function forgot_password_process() {
  	if ($this->isLoggedIn) show_404();
  	try {
  		$this->auth->forgotPassword($_POST['email']);
  		set_flash_notice('auth.message.new_password_sent');
  		redirect($this->authConfig['url_login']);
  	} catch (NoUserException $e) {
  		set_flash_error('auth.error.wrong_email');
  		redirect($this->authConfig['url_forgot_password']);
  	} catch (Exception $e) {
  		set_flash_error($e->getMessage());
  		redirect($this->authConfig['url_forgot_password']);
  	}
  }
  
  // -----------------------------------------------------------------------------------------
  // -------------------------------------- LOGOUT -------------------------------------------
  // -----------------------------------------------------------------------------------------
  
	/**
   * Logout action.
   */
  public function logout() {
    $this->auth->logout();
    redirect($this->authConfig['redirect_after_logout']);
  }

}