<div id="box" class="box">
  <div class="block" id="block-login">
    <h2><?=$lang->line('admin.forgot_password.form_title');?></h2>
    <div class="content login">
     <?=html_flash_message();?>
      <form action="<?=site_url("$adminBaseRoute/forgot_password")?>" class="form validate" method="post">
        <div class="group wat-cf">
          <div class="left">
            <label class="label right"><?=$this->lang->line("admin.forgot_password.email_field");?></label>
          </div>
          <div class="right">
            <input type="text" class="text-field required email" name="email"/>
          </div>
        </div>        
        <div class="group navform wat-cf">
          <div class="right">
            <button class="button" type="submit">
              <img src="<?=site_img("admin/icons/key.png")?>" alt="<?=$this->lang->line("admin.forgot_password.send");?>" />&nbsp;<?=$this->lang->line("admin.forgot_password.send");?>
            </button>
            <a class="link" href="<?=site_url("$adminBaseRoute/login") ?>"><?=$lang->line('admin.forgot_password.back_to_login'); ?></a>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>