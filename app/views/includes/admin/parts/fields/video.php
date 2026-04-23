<div class="group">
  <?$fileField = str_replace("_id", "", $key)?>            
  <input type="hidden" name="<?=$key . "_entity"?>" value="<?=$params['entity']?>" />
  <label for="<?=$id?>" class="label"><?=$label?></label>
  
  <?if (isset($entity[$fileField]) && !empty($entity[$fileField])):?>
    <a style="display:block;width:520px;height:330px" href="<?=site_file_url($entity[$fileField])?>" id="player_<?=$fileField?>"></a><br/>
    <script type="text/javascript">
      flowplayer("player_<?=$fileField?>", "<?=site_flash('flowplayer-3.2.5.swf')?>", {clip:{autoPlay: false, autoBuffering: false}});
    </script>
    <a class="deleteLink confirm" title="<?=lang("admin.add_edit.video_confirm_delete")?>" href="<?=site_url('xadmin/' . $entityName . '/delete_file/' . $entity[$fileField]['id'] . '/' . $params['entity'])?>"><?=lang('admin.delete_video')?></a>
  <?else :?>
    <input type="file" name="<?=$key?>" id="<?=$id?>" />
  <?endif?>
  
  <?if ( ! empty($message)) :?><span class="description"><?=$message?></span><?endif?>
</div>