<div class="group multipleselectGroup">
  <div class="from_container select_container">
    <label class="label"><?=lang("admin.add_edit." . $entityName . "." . $key . ".from")?></label>
    <select class="select">
      <?foreach ($params['from'] as $k => $v) :?>
      <option value="<?=$k?>"><?=$v?></option>
      <?endforeach?>
    </select>
  </div>
  
  <div class="buttons_container">                
    <button class="button moveright" type="button">
      <img src="<?=site_img("admin/icons/right.png")?>" alt="<?=lang("admin.multipleselect.move_right")?>"/> <?=lang("admin.multipleselect.move_right")?>
    </button>
    <button class="button moveleft" type="button">
      <img src="<?=site_img("admin/icons/left.png")?>" alt="<?=lang("admin.multipleselect.move_left")?>"/> <?=lang("admin.multipleselect.move_left")?>
    </button>
  </div>
  
  <div class="to_container select_container">
    <label class="label"><?=lang("admin.add_edit." . $entityName . "." . $key . ".to")?></label>
    <select class="toSelectBox pagesSelect <?=isset($params['class']) ? $params['class'] : ''?>" multiple="multiple" name="<?=$name?>[]">
      <?if (!empty($entity[$key])) :?>
        <?foreach($entity[$key] as $ent):?>
          <option selected="selected" value="<?=$ent['id']?>"><?=$ent['name']?></option>
        <?endforeach?>
      <?endif?>
    </select>
    <div><a href="/" class="selectall"><?=lang("admin.multipleselect.select_all")?></a>&nbsp;|&nbsp;<a href="/" class="deselectall"><?=lang('admin.multipleselect.deselect_all')?></a></div>
  </div>
  <div class="clear"></div>
</div>