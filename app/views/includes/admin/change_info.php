<div id="box" class="box">
  <div class="block">
    <h2><?=$this->lang->line("admin.change_info.form_title");?></h2>
    <div class="content">
      <?=html_flash_message();?>
      <form action="<?=site_url("$adminBaseRoute/change_info")?>" class="form validate" method="post" autocomplete="off">
        <div class="group">
          <label class="label" for="name"><?=$lang->line('admin.change_info.name');?></label>
          <input type="text" id="name" class="text-field required" name="name" value="<?=$loggedInAdmin['name'];?>"/>
        </div>
        <div class="group">
          <label class="label" for="email"><?=$lang->line('admin.change_info.email');?></label>
          <input type="text" id="email" class="text-field required" name="email" value="<?=$loggedInAdmin['email'];?>"/>
        </div>
        <div class="group">
          <label class="label" for="old_password"><?=$lang->line('admin.change_info.old_password');?></label>
          <input type="password" id="password" class="text-field" name="password"/>
        </div>
        <div class="group">
          <label class="label" for="new_password"><?=$lang->line('admin.change_info.new_password');?></label>
          <input type="password" id="new_password" class="text-field" name="new_password"/>
        </div>
        <div class="group">
          <label class="label" for="password1"><?=$lang->line('admin.change_info.confirm_password');?></label>
          <input type="password" id="password1" equalTo="#new_password" class="text-field" name="password1"/>
        </div>
        <div class="group navform wat-cf">          
          <button class="button" type="submit">
            <img src="<?=site_img("admin/icons/key.png")?>" alt="<?=$lang->line('admin.save');?>"/> <?=$lang->line('admin.save');?>
          </button>
          <a class="link" href="<?=site_url($skipStepUrl)?>"><?=$lang->line('admin.skip_step');?></a>                    
        </div>
      </form>
    </div>
  </div>
</div>