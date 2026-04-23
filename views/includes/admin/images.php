<ul class="list">

 <? if (isset($currentFolder)): ?>
      <li>
        <div class="thumb">
          <table class="vertical-middle" cellspacing="0" cellpadding="0" border="0">
            <tr>
              <td>
                <a href="<?=site_url($adminBaseRoute . '/resource/up_dir/');?>"><img src="<?=site_img('window/up_folder.png');?>"/></a>    
              </td>
            </tr>
          </table>
        </div>
        <span><?=$lang->line('admin.window.up');?></span>
      </li>  
  <? endif; ?>

  <? if(count($folders) > 0): ?>
    <? foreach($folders as $folder): ?>
      <li title="<?=$folder;?>">
        <div class="thumb">
          <table class="vertical-middle" cellspacing="0" cellpadding="0" border="0">
            <tr>
              <td>
                <a href="<?=site_url($adminBaseRoute . '/resource/change_dir/' . $folder);?>"><img src="<?=site_img('window/folder.png');?>" alt="<?=$folder;?>" title="Folder - <?=$folder;?>"/></a>
                <a class="deleteFolder confirm" title="<?=$this->lang->line('delete_confirm')?>" href="<?=site_url($adminBaseRoute . '/resource/remove_dir/' . $folder);?>"><img src="<?=site_img('admin/icons/cross.png');?>" alt="Delete - <?=$folder;?>" title="Delete - <?=$folder;?>"/></a>    
              </td>
            </tr>
          </table>
        </div>
        <span><?=$folder;?></span>
      </li>  
    <? endforeach; ?>
  <? endif; ?>


  <?if($entities && sizeof($entities) > 0): ?>
    <?foreach($entities as $image): ?>
    <li class="thumbLi" id="<?=$image['id']?>" title="<?=$image['file_name'];?>">
      <div class="thumb">
        <table class="vertical-middle" cellspacing="0" cellpadding="0" border="0">
          <tr>
            <td>
              <img src="<?=site_image_thumb_url('_admin', $image);?>" alt="<?=$image['file_name'];?>" title="<?=$image['file_name'];?>"/>    
            </td>
          </tr>
        </table>
      </div>
      <span><?=character_limiter($image['file_name'], 20)?></span>
    </li>
    <?endforeach;?>       
  <?endif; ?>
</ul>

<script type="text/javascript">
  var refresh = "<?=site_img("refresh.gif");?>";
  var get_info = "<?=site_url($adminBaseRoute . "/resource/get_resource_info");?>"; 
</script>