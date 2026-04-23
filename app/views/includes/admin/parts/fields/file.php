<div class="group">
  <?$fileField = str_replace("_id", "", $key)?>            
  <input type="hidden" name="<?=$key . "_entity"?>" value="<?=$params['entity']?>" />
  <label for="<?=$id?>" class="label"><?=$label?></label>

  <?if (isset($entity[$fileField]) && ! empty($entity[$fileField])) :?>
    <a href="<?=site_file_url($entity[$fileField])?>"><?=lang("admin.add_edit." . $entityName . "." . $key . ".download")?></a><br/>
    <a class="deleteLink" href="<?=site_url('xadmin/' . $entityName . '/delete_file/' . $entity[$fileField]["id"] . '/' . $params['entity'])?>"><?=lang('admin.delete_file')?></a>
  <?else :?>
    <input type="file" name="<?=$name?>" id="<?=$id?>"/>
  <?endif?>
  <?if ( ! empty($message)) :?><span class="description"><?=$message?></span><?endif?>
</div>