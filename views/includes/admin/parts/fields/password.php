<?
  /**
   * Password is stored always encrypted.
   * So this logic was proposed:
   * 1. Field should be always empty (why would you need to display password hash?).
   * 2. If admin enters a new password, system changes it and at the next
   *    reload of the page the field would become empty again.
   * 3. Do not forget to insert similar code to your xAdmin_AuthEntity controller:
   * 
        protected function preProcessPost() {
          parent::preProcessPost();
          $this->load->library('common/Auth');
          if (isset($_POST['auth_info.password'])) {
            if (empty($_POST['auth_info.password'])) {
              unset($_POST['auth_info.password']);
            } else {
              $_POST['auth_info.password'] = $this->auth->preparePassword($_POST['auth_info.password']);
            }
          }
        }
   *
   * 4. Do not forget to insert similar message property lines:
   * 
		    $lang['admin.add_edit.authentity.auth_info.password'] = 'Password';
				$lang['admin.add_edit.authentity.auth_info.password.description'] = '<span style="color: red">Attention!</span> You can enter new password in this field. Leave this field blank to not change the user password.';
   * 
   */
?>
<div class="group">
  <label for="<?=$id?>" class="label"><?=$label?></label>
  
  <img alt="<?=lang('admin.generate_password')?>" class="passwordGenButton" style="cursor: pointer; position: absolute; top: 19px; right: 0px;" title="<?=lang('admin.generate_password')?>" src="<?=site_img('admin/icons/dice.png'); ?>"/>
  
  <input id="<?=$id?>"
         name="<?=$name?>" 
         type="text" 
         class="text-field passwordGen <?=isset($params['class']) ? $params['class'] : ''?>" 
         value=""
         <?=$attrs?> 
  />
  <?if (!empty($message)) :?><span class="description"><?=$message?></span><?endif?>
</div>