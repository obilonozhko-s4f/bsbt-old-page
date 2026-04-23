<div class="content default-box">
  <h2 class="title">
    <?=lang('admin.add_edit.' . $entityName . ".form_title");?>
    <?if ($backUrl) :?>
      <a class="link" href="<?=site_url($backUrl);?>"><?=lang('admin.add_edit_back');?></a>
    <?endif;?>
  </h2>
  <?=html_flash_message();?>
  
  <div class="inner">
    <form action="<?=site_url($processLink);?>" method="post" class="form validate" autocomplete="off" enctype="multipart/form-data">
    
      <? if (empty($settings)): ?>
        <? $settings[str_replace(' ', '', lang("admin.menu." . $entityName . ".name"))] = ''; ?>
      <? endif; ?>
      
      <? if (isset($settings[''])): ?>
        <? $settings[str_replace(' ', '', lang("admin.menu." . $entityName . ".name"))] = $settings['']; ?>
        <? unset($settings['']); ?>
      <? endif; ?>
      
                  
    	<div id="tabs">
        <ul>
          <?foreach ($settings as $gr => $setting): ?>
            <li><a href="#websiteTabs-<?=$gr;?>"><?=lang("admin.settings.groups.$gr");?></a></li>
          <?endforeach;?>
        </ul>
        
        <?foreach ($settings as $gr => $entity): ?>
          <div id="websiteTabs-<?=$gr;?>">   
            <?foreach ($fields as $key => $params) :?>
            
              <? if (!in_array($key, array_keys($entity))): ?>
                <? continue; ?>
              <? endif; ?>
            
            	<? if (!isset($entity[$key])): ?>
                <? $entity[$key] = ""; ?>
              <? endif; ?>
              
              <?$attrs = "";?>   
              <?if (isset($params["attrs"])) {
                 foreach ($params["attrs"] as $n => $val) {
                 	  $attrs .= ' '. $n ;
                 	  if ($val) $attrs .= '="' . $val . '"';
                  }
                }
              ?>
               
              <?if (strstr($key, '.') !== FALSE) {
                  $tmpKey = explode('.', $key);
                  $inputVal = $entity;
                  foreach ($tmpKey as $tmpK) {
                    $inputVal = isset($inputVal[$tmpK]) ? $inputVal[$tmpK] : '';  
                  }
                  $entity[$key] = $inputVal;
                }
              ?>         

              <? if (isset($languages) && isset($i18nFields)): ?>
                <? $this->view("includes/admin/parts/lang_kv_tabs", array('languages' => $languages,
                                                                       'type' => $params['type'],
                                                                       'value' => isset($params['value']) ? $params['value'] : null,
                                                                       'key' => $key,
                                                                       'attrs' => $attrs, 
                                                                       'entity' => $entity,
                                                                       'params' => $params, 
                                                                       'entityName' => $entityName,
                                                                       'name' => $gr . "[" . $key . "]",
                                                                       'id' => $entityName . '_' . $key,
                                                                       'label' => isset($params['label']) ? $params['label'] : lang("admin.add_edit." . $entityName . "." . $key),
                                                                       'message' => lang("admin.add_edit." . $entityName . "." . $key . ".description"))); ?>     
                <? else: ?>

                  <?$this->view("includes/admin/parts/fields/" . $params['type'], array('group' => $gr,
                  																																			'key' => $key,
                                                                                        'name' => $gr . "[" . $key . "]",
                                                                                        'attrs' => $attrs, 
                                                                                        'entity' => $entity, 
                                                                                        'params' => $params,
                                                                                        'entityName' => $entityName,
                                                                                        'id' => $entityName . '_' . $gr . '_' . $key,
                                                                                        'label' => lang("admin.add_edit." . $entityName . "." . $key),
                                                                                        'message' => lang("admin.add_edit." . $entityName . "." . $key . ".description")))?>
                                                                                        
                                                                                        
              <? endif; ?>
            <?endforeach;?>
          </div> <!-- #websiteTabs -->
        <?endforeach;?>    
      </div> <!-- #tabs -->  
      
      <div class="group">
        <span class="red"><b style="font-size: 1.2em;">*</b> - <?=lang('admin.required_description');?></span>
      </div>
      
      <div class="group navform wat-cf" style="text-align: center;">
        <button class="button" type="submit" name="save" value="1">
          <img src="<?=site_img("admin/icons/tick.png")?>" alt="<?=lang('admin.save_settings');?>"/><?=lang('admin.save_settings');?>
        </button>
      </div>
    </form>
  </div>
</div>
<div class="clear"></div>


<script type="text/javascript">
  $(document).ready(function() {
    $('#tabs').tabs();
  });
</script>