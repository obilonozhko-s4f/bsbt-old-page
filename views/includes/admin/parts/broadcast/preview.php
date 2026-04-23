<div class="content" id="content">
  <div class="content default-box">
    <h2 class="title"><?=lang('admin.broadcast.preview.title');?><a href="<?=site_url($backUrl)?>" class="link"><?=$lang->line('admin.add_edit_back')?></a></h2>
    <h4><?=lang('admin.broadcast.preview.preview');?>:</h4>
    <div style="border: 1px solid #ccc;">
      <iframe src="<?=site_url($previewUrl)?>" width="100%" height=600></iframe>
    </div>
    <? if($entity['recipents']): ?>
      <h4><?=lang('admin.broadcast.preview.recipents');?>:</h4>
      <?=$this->view('includes/admin/parts/broadcast/recipents', array('entity' => $entity, 'readOnly' => TRUE), TRUE);?>
    <? endif; ?>
    <div class="clear" style="height: 30px;"></div>
    <div class="group navform wat-cf">
      <a class="button" href="<?=site_url($processUrl)?>">
        <img src="<?=site_img("admin/icons/tick.png")?>" alt="<?=lang('admin.save');?>"/><?=lang('admin.send');?>
      </a>
      <a href="<?=site_url($backUrl);?>" class="button">
        <img src="<?=site_img("admin/icons/cross.png")?>" alt="<?=lang('admin.cancel');?>"/><?=lang('admin.cancel');?>
      </a>
    </div>
  </div>
</div>
