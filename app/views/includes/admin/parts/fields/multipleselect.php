<div class="group multipleselectGroup">
  <div class="from_container select_container">
    <label class="label"><?=lang("admin.add_edit." . $entityName . "." . $key . ".from")?></label>
    <? if(isset($params['relation']['search']) && $params['relation']['search']): ?>
      <input type="text" style="width: 88%; float: left;" id="<?=$key?>_search"/>
      <img id="<?=$key?>_search_button" style="cursor: pointer; float: left;" alt="Search" src="<?=site_img('admin/icons/search.gif');?>" />
      <div class="clear" style="height: 3px;"></div>
    <? endif; ?>
    <select class="fromSelectBox pagesSelect" multiple="multiple" size="10">
      <?foreach ($params['from'] as $k => $v) :?>
        <option value="<?=$k?>"><?=htmlspecialchars($v)?></option>
      <?endforeach?>
    </select>
    <div><a href="/" class="selectall"><?=lang("admin.multipleselect.select_all")?></a>&nbsp;|&nbsp;<a href="/" class="deselectall"><?=lang('admin.multipleselect.deselect_all')?></a></div>
  </div>
  <div class="buttons_container">
    <button class="button moveright" type="button">
      <img src="<?=site_img("admin/icons/right.png")?>" alt="<?=lang("admin.multipleselect.move_right")?>"/> <?=lang("admin.multipleselect.move_right")?>
    </button>
    <button class="button moveleft" type="button">
      <img src="<?=site_img("admin/icons/left.png")?>" alt="<?=lang("admin.multipleselect.move_left")?>"/> <?=lang("admin.multipleselect.move_left")?>
    </button>
  </div>

  <? $name_field = isset($params['name_field']) ? $params['name_field'] : 'name' ?>
  <div class="to_container select_container">
    <label class="label"><?=lang("admin.add_edit." . $entityName . "." . $key . ".to")?></label>
    <select class="toSelectBox pagesSelect <?=isset($params['class']) ? $params['class'] : ''?>" multiple="multiple" name="<?=$name?>[]"  <? if(isset($params['relation']['search']) && $params['relation']['search']): ?>style="margin-top: 25px;"<? endif; ?>>
      <?if (!empty($entity[$key])) :?>
        <?foreach($entity[$key] as $ent):?>
        	<?if (is_array($ent) && isset($ent['id'])) : // For a relation?>
          	<option selected="selected" value="<?=$ent['id']?>"><?=htmlspecialchars($ent[$name_field])?></option>
          <?else : // For an array?>
            <option selected="selected" value="<?=$ent?>"><?=htmlspecialchars($ent)?></option>
          <?endif?>
        <?endforeach?>
      <?endif?>
    </select>
    <div><a href="/" class="selectall"><?=lang("admin.multipleselect.select_all")?></a>&nbsp;|&nbsp;<a href="/" class="deselectall"><?=lang('admin.multipleselect.deselect_all')?></a></div>
  </div>
  
  <? if(isset($params['relation']['sort']) && $params['relation']['sort']): ?>
    <div class="ascdes_container">
      <button class="button moveup" type="button" >
        &nbsp;<img src="<?=site_img("admin/icons/top.png")?>" alt="Move up"/>
      </button>
      <button class="button movedown" type="button">
        &nbsp;<img src="<?=site_img("admin/icons/down.png")?>" alt="Move down"/>
      </button>
    </div><!-- To Container [Close] -->
  <? endif; ?>
  <div class="clear"></div>
</div>

<? if(isset($params['relation']['search']) && $params['relation']['search']): ?>
  <script type="text/javascript">
    $(document).ready(function() {
      var searchInput = "#<?=$key?>_search";
      var searchButton = "#<?=$key?>_search_button";
      var fromSelectBox = ".fromSelectBox:first";
      var toSelectBox = ".toSelectBox:first";
      var name = "<?=$name?>";
      var url = "<?=admin_site_url($entityName)?>/get_field_values_ajax/" ;
      var searchIcon = "<?=site_img('admin/icons/search.gif');?>";
      var loadingIcon = "<?=site_img('admin/icons/small_loader.gif');?>";
      var isSearching = false;
      
      searchInput = $(searchInput);
      searchButton = $(searchButton);
      fromSelectBox =  searchButton.nextAll(fromSelectBox);
      toSelectBox = searchButton.parents('.group:first').find(toSelectBox);
      searchButton.click(function() {
        search();
        return false;
      });
      searchInput.keypress(function(e) {
        if (e.which == 13) {
          search();
          return false;
        }
      });

      function search() {
        if (isSearching) {
          return;
        }
        isSearching = true;
        searchInput.focus();
        fromSelectBox.attr('disabled', 'disabled');
        searchInput.attr('disabled', 'disabled');
        searchButton.attr('src', loadingIcon);
        
        $.get(url + name + '?q=' + searchInput.val() + '&not=' + toSelectBox.val(), function(data) {
          fromSelectBox.html(data);
          fromSelectBox.removeAttr('disabled');
          searchInput.removeAttr('disabled');
          searchButton.attr('src', searchIcon);
          searchInput.blur();
          setTimeout(function(){searchInput.focus();}, 10);
          isSearching = false;
        });
        
      };
      
    });
  </script>
<? endif; ?>