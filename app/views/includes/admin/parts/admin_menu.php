<?
  /**
   * Print menu.
   * @pa</#document>ram $items
   * @param $permissions
   */
   
  function print_menu($items, $permissions, $level = 0, $itemName = null, $last = false) {
    if (is_array($items)) {
      if ($level > 0) print("<li>");
      if (isset($itemName)) print('<p class="section-header">' . lang("admin.menu." . $itemName . ".name") . '</p>');
      print('<ul class="navigation' . ($level ? ' nested' : '') . '">');

      $i = 0;
      $count = count($items);
      foreach ($items as $name => $item) {
        if (!is_array($item)) $name = $item;
        print_menu($item, $permissions, $level + 1, $name, $i == $count - 1);
        $i++;
      }
      
      print('</ul>');
      if ($level > 0) print("</li>");
      return;
    }
    
    // If $items is a single item
    if (!in_array($itemName . "_view", $permissions)) return;
    print('<li class="' . (url_contains(surround_with_slashes($itemName)) ? ' selected ' : '') . ($last ? ' last ' : '') . '">');
    print('<a href="' . admin_site_url($itemName) .'">' . lang("admin.menu." . $itemName . ".name") . '</a>');
    print('</li>');
  }
?>

<div class="block">
  <a href="#" class="toggle-menu">
    <h3><?=lang("admin.menu.title")?></h3>
  </a>
  <?if (isset($loggedInAdmin["permissions"]) && isset($menuItems)) :?>
		<? $permissions = explode("|", $loggedInAdmin["permissions"]); ?>
	  <? print_menu($menuItems, $permissions); ?>
  <?endif?>
  
 <? $langArr = lang('admin.per_page_array'); ?>
</div>

<? if(is_array($langArr) && ((isset($_GET['per_page']) && $_GET['per_page'] == 'all' && count($entities) > 0) || (isset($pager) && $pager->getNumResults() > reset($langArr)))): ?>
  <div style="margin: 0px 0px 25px;">
    <form action="<?=current_url();?>" class="query-params" method="get" style="margin-left: 20px;">
      <label style="font-size: 11px;" for="per_page"><?=lang('admin.per_page');?>:</label>
      <select id="per_page" name="per_page">
        <? foreach($langArr as $k => $v): ?>
          <option value="<?=$k;?>"><?=$v;?></option>
        <? endforeach; ?>
      </select>
    </form>
  </div>
<? endif; ?>


<div class="block" id="slide-action-box" style="display: none;">
  <h3><?=lang('admin.actions_title');?></h3>
  <div class="a-box">
    <p class="all"><?=lang('admin.elements_title');?>:&nbsp;<b>0</b></p>
    <form method="post" action="<?=site_url($adminBaseRoute . '/' . strtolower($entityName) . '/batch_process')?>">
      <input type="hidden" name="ids"/>

    <? /* 
      <input type="hidden" name="field" value="published"/>      
      <div class="action">
        <p class="title">Опубликован:</p>
        <select name="value" class="chosen-ignore">
          <option>--Выбрать вариант--</option>
          <option value="1">Да</option>
          <option value="0">Нет</option>
        </select>
      </div>
      */ ?>
      
      <? if($batchUpdateFields): ?>
        <? foreach ($batchUpdateFields as $fieldName => $default_filter_value): ?>
          <? $current_filter_values = isset($filter_values[$fieldName])?$filter_values[$fieldName]:null; ?>
          <? if (isset($current_filter_values)
                 && is_array($current_filter_values)) :?>
            <input type="hidden" name="field[]" value="<?=$fieldName?>"/>
            <div class="action">
              <p class="title"><?=lang("admin.entity_list." . $entityUrlName . ".filter." . $fieldName . "_title")?>:</p>
              <select class="chosen-ignore" name="value[]">
                <? if(empty($default_values[$fieldName])): ?>
                  <option value=""><?=lang('admin.filter.all');?></option>
                <? endif; ?>
                <? foreach ($current_filter_values as $key => $value): ?>
                    <? $langValueKey = 'enum.' . strtolower($entityName) . '.' . $fieldName . '.' . $key; // product.type.PUBLISHED ?>
                    <? if(lang_exists($langValueKey)): ?>
                      <? $value = lang($langValueKey); ?>
                    <? endif; ?>
                  <option value="<?=$key?>"><?=$value;?></option>
                <? endforeach; ?>
              </select>
            </div>
          <? endif; ?>
        <? endforeach; ?>
      <? endif; ?>
      
      
      <div class="button-action">
        <? if($batchUpdateFields): ?>
          <button class="button" type="submit" name="update"><img src="<?=site_img("admin/icons/tick.png")?>" alt="<?=lang('admin.update');?>"/><?=lang('admin.update');?></button>
          <div class="clear"></div>
        <? endif; ?>
        
        <button class="button confirm" type="submit" name="delete" title="<?=lang('admin.confirm.entities_delete_batch')?>"><img src="<?=site_img("admin/icons/cross.png")?>" alt="<?=lang('admin.delete');?>"/><?=lang('admin.delete');?></button>
        <div class="clear"></div>
        <? if($export): ?>
          <button class="button" type="submit" name="export"><img src="<?=site_img("admin/icons/export.png")?>" alt="<?=lang('admin.export');?>"/><?=lang('admin.export');?></button>
          <div class="clear"></div>
        <? endif; ?>
      </div>
    </form>
  </div>
</div>

<script type="text/javascript">
  var entityName = "<?=$entityName;?>";
  $(document).ready(function() {

//    $.cookie('<?=strtolower($entityName);?>_batch_update_ids', null);

    function initSlide() {
      // Sliding logic
      var offset = $("#slide-action-box").offset();
      var topPadding = 35;
      $(window).scroll(function() {
        if ($(window).scrollTop() > offset.top) {
          $("#slide-action-box").stop().animate({
              marginTop: $(window).scrollTop() - offset.top + topPadding
          });
        } else {
          $("#slide-action-box").stop().animate({
              marginTop: 0
          });
        };
      });
    }


    function hideMenu() {
      $('ul.top-navigation').slideUp();
      $('.toggle-menu').toggleClass('closed-menu');
    }

    function showMenu() {
      $('ul.top-navigation').slideDown();
      $('.toggle-menu').toggleClass('closed-menu');
    } 
    
    function renderSideActionBlock(eIds) {
      var block = $('#slide-action-box');
      if (eIds.length > 0) {
        hideMenu();
        block.find('p.all b:first').html(eIds.length);
        block.find('input[name=ids]').val(eIds.join(','));
        block.show();
        initSlide();
      } else {
        block.find('p.all b:first').html(eIds.length);
        block.hide();
        showMenu();
      }
    }

    $('.toggle-menu').click(function(){
      $('ul.top-navigation').slideToggle();
      $(this).toggleClass('closed-menu');
      return false; 
    });


    $('ul.navigation:first').addClass('top-navigation');
    
    
    if ($('.table').length > 0) {
      // Get the array of ids from cookie
      var ids = $.cookie('<?=strtolower($entityName);?>_batch_update_ids');
      if (!ids) {
        ids = [];
      } else {
        ids = ids.split(',');
      }
      // Check the checkoxes
      for (var i = 0; i < ids.length; i++) {
        $('.checkbox[value=' + ids[i] + ']').attr('checked', 'checked');
        $('.checkbox[value=' + ids[i] + ']').change();
      }

      // Render the block
      renderSideActionBlock(ids);
      
      // Checkbox change event for editing cookies
      $('.table').find('.checkbox').change(function() {
        if ($(this).is(':checked')) {
          if (ids.indexOf($(this).val()) == -1 && $(this).val() != 'on') {
            ids[ids.length] = $(this).val();
            $.cookie('<?=strtolower($entityName);?>_batch_update_ids', ids.join(','), {path: '/'});
          }
        } else {
          var idx = ids.indexOf($(this).val());
          if(idx != -1) {
            ids.splice(idx, 1);
            $.cookie('<?=strtolower($entityName);?>_batch_update_ids', ids.join(','), {path: '/'});
          }
        }
        renderSideActionBlock(ids);
      });
    }

  });
</script>
