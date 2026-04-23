<div class="content default-box">
  <h2 class="title">
    <?=$lang->line('admin.add_edit.' . $entityName . ".form_title");?>
    <? if (isset($prevUrl) && !empty($prevUrl)) :?>
      <a class="lin" href="<?=site_url($prevUrl);?>"><?=lang('admin.add_edit_prev');?></a>
    <? endif;?>
    <? if (isset($nextUrl) && !empty($nextUrl)) :?>
      |&nbsp;<a class="lin" href="<?=site_url($nextUrl);?>"><?=lang('admin.add_edit_next');?></a>
    <? endif;?>
    <?if ($backUrl) :?>
      <a class="link" href="<?=site_url($backUrl);?>"><?=lang('admin.add_edit_back');?></a>
    <?endif;?>
  </h2>
  <?=html_flash_message();?>
  <?=fill_form_with_saved_post('editForm');?>
  <div class="inner">
    <form action="<?=site_url($processLink) . get_get_params();?>" method="post" class="form validate" id="editForm" autocomplete="off" enctype="multipart/form-data">
    
      <div class="group navform wat-cf">
        <button class="button" type="submit" name="save" value="1">
          <img src="<?=site_img("admin/icons/tick.png")?>" alt="<?=lang('admin.save');?>"/><?=lang('admin.save');?>
        </button>
        <button class="button" type="submit" name="save_and_return_to_list" value="1">
          <img src="<?=site_img("admin/icons/tick.png")?>" alt="<?=lang('admin.save_return_to_list');?>"/><?=lang('admin.save_return_to_list');?>
        </button>
        <? if (isset($nextUrl) && !empty($nextUrl) && isset($entity['id'])) :?>
          <button class="button" type="submit" name="save_and_next" value="1">
              <img src="<?=site_img("admin/icons/tick.png")?>" alt="<?=lang('admin.save_and_next');?>"/><?=lang('admin.save_and_next');?>
          </button>
        <? endif;?>
        <? if(isset($actions['add']) && !isset($entity['id'])): ?>
          <button class="button" type="submit" name="save_and_add_new" value="1">
            <img src="<?=site_img("admin/icons/tick.png")?>" alt="<?=lang('admin.save_and_add_new');?>"/><?=lang('admin.save_and_add_new');?>
          </button>
        <? endif; ?>
        <a href="<?=site_url($backUrl);?>" class="button">
          <img src="<?=site_img("admin/icons/cross.png")?>" alt="<?=lang('admin.cancel');?>"/><?=lang('admin.cancel');?>
        </a>
        <img src="<?=site_img('admin/icons/down.png');?>" class="scroll-down">
        <? if(isset($entity['id']) && isset($print) && $print) :?>
          <a class="print" href="<?=admin_site_url($entityName . '/printpage/' . $entity['id']);?>"><img src="<?=site_img('admin/icons/print_icon.png');?>"/></a>
        <? endif;?>
      </div>
    
      <? if (isset($entity['id'])) :?>
        <input type="hidden" name="id" value="<?=$entity['id'];?>"/>
      <? endif;?>

      <? foreach ($fields as $key => $params) :?>
        
        <? if (isset($groups)): ?>
          <? foreach($groups as $gk => $gr) : ?>
            <? if (in_array($key, $gr) && array_key_by_value($key, $gr) == 0): ?>
              <div class="field-group-head"><a href="#" class="group-link" id="g<?=$gk;?>"><?=lang("admin.add_edit." . $entityName . ".group_" . $gk);?></a> <span>&darr;</span></div>
              <div class="fieldGroup" style="display: none;" id="group_<?=$gk;?>">
            <? endif; ?>
          <? endforeach; ?>
        <? endif; ?>
        
        <? if(!array_key_exists(0, $params)): ?>
          <? $params = array($params); ?>
        <? endif; ?>
        
        
        <? foreach ($params as $params): ?>
        
          <? $attrs = ""; ?>
          <? if (isset($params["attrs"])) {
              foreach($params["attrs"] as $n => $val) {
             	  $attrs .= ' '. $n ;
             	  if ($val) $attrs .= '="' . $val . '"';
              }
            }
          ?>
          
          <? if (strstr($key, '.') !== FALSE) {
              $tmpKey = explode('.', $key);
              $inputVal = $entity;
              foreach ($tmpKey as $tmpK) {
                $inputVal = isset($inputVal[$tmpK]) ? $inputVal[$tmpK] : '';
              }
              $entity[$key] = $inputVal;
            }
          ?>
                   
          <? if (isset($languages) && isset($i18nFields) && in_array($key, $i18nFields)): ?>
            <? $langKey = "admin.add_edit.$entityName.$key" ?>
            <? $this->view("includes/admin/parts/lang_tabs", array('languages' => $languages,
            																											 'type' => $params['type'],
            																											 'value' => isset($params['value']) ? $params['value'] : null,
                                                                   'key' => $key,
                                                                   'attrs' => $attrs,
                                                                   'entity' => $entity,
            																											 'params' => $params,
                                                                   'entityName' => $entityName,
                                                                   'name' => $key,
                                                                   'id' => $entityName . '_' . $key,
                                                                   'label' => isset($params['label']) ? $params['label'] : lang($langKey),
                                                                   'message' => lang("$langKey.description"),
                                                                   'langKey' => $langKey))?>
          <? else: ?>
            <? $langKey = "admin.add_edit.$entityName.$key" ?>
            <? $this->view("includes/admin/parts/fields/" . $params['type'], array('key' => $key,
                                                                                   'attrs' => $attrs,
            																																			 'value' => isset($params['value']) ? $params['value'] : null,
                                                                                   'entity' => $entity,
                                                                                   'params' => $params,
                                                                                   'entityName' => $entityName,
            																																			 'name' => $key,
                                                                                   'id' => isset($params['id']) ? $params['id'] : $entityName . '_' . $key,
                                                                                   'label' => isset($params['label']) ? $params['label'] : lang($langKey),
                                                                                   'message' => isset($params['message']) ? $params['message'] : lang("$langKey.description"),
                                                                   								 'langKey' => $langKey)); ?>
          <? endif; ?>
        <? endforeach; ?>
        <? if (isset($groups)): ?>
          <? foreach($groups as $gr) : ?>
            <? if (in_array($key, $gr) && array_key_by_value($key, $gr) == count($gr) - 1): ?>
              </div>
            <? endif; ?>
          <? endforeach; ?>
        <? endif; ?>
        
                
      <?endforeach;?>
      
      <div class="group">
        <span class="red"><b style="font-size: 1.2em;">*</b> - <?=lang('admin.required_description');?></span>
      </div>
      
      <div class="group navform wat-cf">
        <button class="button" type="submit" name="save" value="1">
          <img src="<?=site_img("admin/icons/tick.png")?>" alt="<?=lang('admin.save');?>"/><?=lang('admin.save');?>
        </button>
        <button class="button" type="submit" name="save_and_return_to_list" value="1">
          <img src="<?=site_img("admin/icons/tick.png")?>" alt="<?=lang('admin.save_return_to_list');?>"/><?=lang('admin.save_return_to_list');?>
        </button>
        <? if (isset($nextUrl) && !empty($nextUrl) && isset($entity['id'])) :?>
          <button class="button" type="submit" name="save_and_next" value="1">
              <img src="<?=site_img("admin/icons/tick.png")?>" alt="<?=lang('admin.save_and_next');?>"/><?=lang('admin.save_and_next');?>
          </button>
        <? endif;?>
        <? if(isset($actions['add']) && !isset($entity['id'])): ?>
          <button class="button" type="submit" name="save_and_add_new" value="1">
            <img src="<?=site_img("admin/icons/tick.png")?>" alt="<?=lang('admin.save_and_add_new');?>"/><?=lang('admin.save_and_add_new');?>
          </button>
        <? endif; ?>
        <a href="<?=site_url($backUrl);?>" class="button">
          <img src="<?=site_img("admin/icons/cross.png")?>" alt="<?=lang('admin.cancel');?>"/><?=lang('admin.cancel');?>
        </a>
        <img src="<?=site_img('admin/icons/top.png');?>" class="scroll-up">
        <? if(isset($entity['id']) && isset($print) && $print) :?>
          <a class="print" href="<?=admin_site_url($entityName . '/printpage/' . $entity['id']);?>"><img src="<?=site_img('admin/icons/print_icon.png');?>"/></a>
        <? endif;?>
      </div>
    </form>
  </div>
</div>
<div class="clear"></div>