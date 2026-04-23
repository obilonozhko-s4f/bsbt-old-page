<div class="group">
  <label for="<?=$id?>" class="label"><?=$label?></label>

  <a href="<?=$entity[$key]?>" target="_blank"><?=lang("admin.add_edit." . $entityName . "." . $key . ".anchor")?></a> 
  <?if ( ! empty($message)) :?><span class="description"><?=$message?></span><?endif?>
</div>