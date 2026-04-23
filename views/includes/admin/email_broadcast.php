<?=$this->load->view('includes/admin/parts/email_broadcast_entity_list', array('entities' => $entities, 
                                                                               'pager' => $pager,
                                                                               'params' => $params,
                                                                               'entityName' => $entityName))?>

   

<div class="content default-box">
  <h2 class="title">
    <?=$lang->line('admin.email_broadcast.' . $entityName . ".form_title");?>
  </h2>
  <?=html_flash_message();?>
  
  <div class="inner">
    <form action="<?=site_url($adminBaseRoute . '/email_broadcast_' . $entityName . '/broadcast');?>" method="post" class="form validate" autocomplete="off" enctype="multipart/form-data">

      <?if (isset($filters) && is_array($filters) && !empty($filters)) :?>
        <?foreach ($filters as $filter_name => $current_filter_value) :?>
          <?$current_filter_values = $filter_values[$filter_name]?>
          <?if (test($current_filter_values) && is_array($current_filter_values)) :?>
                <?foreach ($current_filter_values as $key => $value) :?>
                  <?if ($current_filter_value == $key && $current_filter_value !== '') :?>
                    <input type="hidden" name="filter_<?=$filter_name?>" value="<?=$key;?>" /> 
                  <?endif?> 
                <?endforeach?>
          <? endif; ?>
        <? endforeach; ?>
      <? endif; ?>
      <div class="group">
        <label for="subject" class="label"><?=$lang->line("admin.email_broadcast.subject");?></label>
        <input id="subject" type="text" class="text-field required" name="subject" value="<?=$subject?>" />
        <?if($message = $lang->line("admin.email_broadcast.subject.description")):?><span class="description"><?=$message?></span><?endif;?>
      </div>     
      
      <div class="group group-tinymce">      
        <label for="message" class="label"><?=$lang->line("admin.email_broadcast.message");?></label>
        <div class="editor-changer">
          <input value="editor" type="radio" class="editor" id="radio1message" name="radiomessage" checked="checked"/><label class="exclude" for="radio1message"><?= $lang->line("admin.editor")?></label>
          <input value="html" type="radio" class="html" id="radio2message" name="radiomessage" /><label class="exclude" for="radio2message"><?= $lang->line("admin.html")?></label>
        </div>               
        <textarea class="text-area required"  name="message" id="message"><?=$emessage;?></textarea>
        <?if($message = $lang->line("admin.email_broadcast." . $entityName . ".message.description")):?><span class="description"><?=$message?></span><?endif;?>
      </div> 
      
     <div class="group">
        <label for="test_email" class="label"><?=$lang->line("admin.email_broadcast.test.email");?></label>
        <input id="test_email" type="text" class="text-field email" name="email" value="" />
        <?if($message = $lang->line("admin.email_broadcast.test_email.description")):?><span class="description"><?=$message?></span><?endif;?>
      </div>     
    
      <div class="group">
        <span class="red"><b style="font-size: 1.2em;">*</b> - <?=$lang->line('admin.required_description');?></span>
      </div>
      
      <div class="group navform wat-cf">
        <button class="button" type="submit" name="test_email" value="1">
          <img src="<?=site_img("admin/icons/tick.png")?>" alt="<?=$lang->line('admin.email_broadcast.send_test_email');?>"/><?=$lang->line('admin.email_broadcast.send_test_email');?>
        </button>      
        <button class="button confirm" type="submit" name="save" value="1" title="<?=$lang->line('admin.email_broadcast.confirm_broadcast');?>">
          <img src="<?=site_img("admin/icons/tick.png")?>" alt="<?=$lang->line('admin.email_broadcast.broadcast');?>"/><?=$lang->line('admin.email_broadcast.broadcast');?>
        </button>
      </div>      
    </form>
  </div>
</div>
<div class="clear"></div>                                                                               