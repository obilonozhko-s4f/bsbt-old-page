<h2 class="title">
  <span class="fl"><?=$lang->line('admin.entity_list.' . $entityName . '.list_title');?></span>
  <?if (isset($actions) && isset($actions["add"]) && !empty($actions["add"])): ?>
    <a class="link" href="<?=site_url($actions["add"])?>"><?=$lang->line('admin.add');?></a>
    <? unset($actions["add"]); ?>    
  <?endif;?>
  <? if(!empty($additionalActions)): ?>
    <ul class="act-list">
    <? foreach($additionalActions as $addAction): ?>
      <li>
        <a href="<?=site_url($adminBaseRoute . '/' . $entityUrlName . '/' . $addAction) . get_get_params()?>"><?=$lang->line('admin.' . $addAction);?></a>
      </li>
    <? endforeach; ?>
    </ul>
  <? endif; ?>
</h2>

<?=html_flash_message();?>
<div class="inner">
  
  <div style="margin-bottom: 5px;">
    <? $first = TRUE; ?>
    <? foreach($params as $p): ?>
      <span style="float: left; width:<? if($first): ?>400px; margin-left: 10px;<? else: ?>100px; text-align: center;<? endif; ?>">
        <? if(is_array($p)): ?>
          <? $tmp = ""; ?>
          <? foreach ($p as $pk => $pv): ?>                
          <? $tmp .= $pk . '.' . $pv; ?>
          <? endforeach; ?>          
          <?=$lang->line("admin.entity_list." . $entityName . '.' . $tmp);?>
        <? else: ?>
          <?=$lang->line("admin.entity_list." . $entityName . '.' . $p);?>
        <? endif; ?>
      </span>
      <? $first = FALSE; ?>
    <? endforeach; ?>
    <div class="clear"></div>
  </div>
  
  <div class="clear"></div>
  <? nested_array_loop_2($hmtl, $entities, $actions, $this->lang, TRUE, $params, $maxLevel); ?>
  <? function nested_array_loop_2(&$html = null, $tree, $actions, $lang, $first = FALSE, $params, $maxLevel = null) { ?> 
    <? if($first): ?>
      <ul id="holder" class="holder-tree"> 
    <?else:?> 
      <ul style="display: none;">
    <?endif; ?>
    <?$count = 0;
      foreach($tree as $item){ 
        $count++; ?>
        <li id="item_<?=$item['id'];?>">
          <div class="listCat">
            <table cellpadding="0" cellspacing="0" border="0" width="100%"> 
              <tr>
                <? $count1 = 0; ?>
                <? foreach ($params as $lp): ?>
                  <? if (is_array($lp)): ?>
                    <td style="width: <? if ($count1 == 0): ?><?=400 - ($item['level'] * 35);?>px<? else: ?>100px; text-align: center;<? endif; ?>">
                      <? foreach($lp as $k => $v): ?>
                        <? $count = 1; ?>
                        <? foreach($item[$k] as $it): ?>
                          <?=$it[$v];?><?=($count < count($item[$k]))?',':'';?>
                          <? $count++; ?>
                        <? endforeach; ?>
                      <? endforeach; ?>
                    </td>
                  <? elseif (is_bool($item[$lp])): ?>
                    <td style="width: <? if ($count1 == 0): ?><?=400 - ($item['level'] * 35);?>px<? else: ?>100px; text-align: center;<? endif; ?>" ><? if ($item[$lp]): ?><?=lang('admin.yes')?><? else: ?><?=lang('admin.no')?><? endif; ?></td>
                  <? else: ?>
                    <td style="width: <? if ($count1 == 0): ?><?=400 - ($item['level'] * 35);?>px<? else: ?>100px; text-align: center;<? endif; ?>" >
                      <? if ($count1 == 0 && sizeof($item['__children']) > 0): ?>
                        <span class="plus-minus">+</span>                      
                      <? endif; ?>
                     <?=htmlspecialchars($item[$lp]);?>
                    </td>
                  <? endif; ?>
                  <? $count1++; ?>
                <? endforeach; ?>
                <td class="actions tar">
                  <?$countActions = 0;?>
                  <? foreach($actions as $k => $v): ?>
                    <?$countActions++;?>
                    <? if ($k == "add_child"): ?>
                      <? if (!isset($maxLevel) || ($maxLevel && $maxLevel > (int)$item['level'])): ?>
                        	<a href="<?=site_url($v . "/" . $item['id']);?>"><?=$lang->line("admin." . $k)?></a><?if ($countActions < count($actions)):?>&nbsp;|<?endif;?>
                      <? endif; ?>
                      <? else: ?>
                       <? if ($k == "delete"): ?>
                         <? if (!isset($item['can_be_deleted']) || (isset($item['can_be_deleted']) && $item['can_be_deleted'] == TRUE)): ?>
                        	<a href="<?=site_url($v . "/" . $item['id']);?>" title="<?=lang('admin.confirm.entity_delete')?>" class="deleteLink confirm"><?=$lang->line("admin." . $k)?></a><?if ($countActions < count($actions)):?>&nbsp;|<?endif;?>
                         <? endif;?>
                        <? else: ?>
        	                <a href="<?=site_url($v . "/" . $item['id']);?>" ><?=$lang->line("admin." . $k)?></a><?if ($countActions < count($actions)):?>&nbsp;|<?endif;?>
        	              <? endif; ?>   
                    <? endif; ?>
                  <? endforeach; ?>
                </td>
              </tr>
            </table>
          </div>
        <?
          if(isset($item['__children']) && sizeof($item['__children']) > 0){
            nested_array_loop_2($html, $item['__children'], $actions, $lang, FALSE, $params, isset($maxLevel)?$maxLevel:null);
          }
        ?>
        </li>
    <? } ?>
    <? if ($count == 0):?>
      <li class="noItems"><?=$lang->line("admin.no_items");?></li>
    <? endif; ?>
    </ul> 
  <?}?>
  <? if (count($entities) > 0):?> 
    <p><a href="#" class="show-all"><?=lang("admin.show_all");?></a>&nbsp;&nbsp;&nbsp;<a href="#" class="hide-all"><?=lang("admin.hide_all");?></a></p>
  <? endif; ?>
</div>

<!-- Показать все -->

<script type="text/javascript">
  $(document).ready(function() {
    $('.plus-minus').click(function(){
       if ($(this).html() == '+') {
         $(this).parents('.listCat:first').next('ul').show();
         $(this).html('-');
       } else {
         $(this).parents('.listCat:first').next('ul').hide();
         $(this).html('+');         
       }
    });

    $('.show-all').click(function() {
      $('#holder ul').show();
      $('.plus-minus').html('-');
      return false;
    });
   

    $('.hide-all').click(function() {
      $('#holder ul').hide();
      $('.plus-minus').html('+');
      return false;
    });
    
  });
</script>



