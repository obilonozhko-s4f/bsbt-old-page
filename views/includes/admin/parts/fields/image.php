<div class="group">      
  <label for="<?=$id?>" class="label"><?=$label?></label>
  <?if (isset($entity[$key]) && ! empty($entity[$key])) :?>
    <a class="zoom" href="<?=site_image_url($entity[$key])?>"><img src="<?=site_image_thumb_url('_admin', $entity[$key])?>"/></a><br/>
    <a class="confirm" title="<?=lang("admin.add_edit.image_confirm_delete")?>" href="<?=site_url($adminBaseRoute . '/' . $entityName . '/delete_image/' . $entity[$key]["id"])?>"><?=lang('admin.delete_image')?></a>
  <? else: ?>
    <input type="file"
           name="<?=$name?>"
           id="<?=$id?>"
           class="<?=isset($params['class']) ? $params['class']:""?>"
    />
  <? endif; ?>
  <?if ( ! empty($message)) :?><span class="description"><?=$message?></span><?endif?>
</div>   