<? $totalCount = isset($pager) ? $pager->getNumResults() : count($entities);?>

<table class="admin-h-table" cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td><span class="head-title"><?=lang('admin.entity_list.' . $entityName . '.list_title');?></span></td>
    <td class="filter-td">
      <div class="rel-box">
        <? if(!empty($additionalActions)): ?>
          <ul class="act-list">
           <? foreach($additionalActions as $addAction): ?>
              <li>
                <? if($addAction == 'printlist'):?>
                  <a href="<?=site_url($adminBaseRoute . '/' . $entityUrlName . '/' . $addAction . '/' . $pager_page) . get_get_params()?>"><?=lang('admin.' . $addAction);?></a>
                <? else:?>
                  <a href="<?=site_url($adminBaseRoute . '/' . $entityUrlName . '/' . $addAction) . get_get_params()?>"><?=lang('admin.' . $addAction);?></a>
                <? endif;?>
              </li>
            <? endforeach; ?>
          </ul>
        <? endif; ?>
        <? if (isset($loggedInAdmin['permissions']) && strstr($loggedInAdmin['permissions'], $entityName . "_add") !== FALSE): ?>
          <? if (isset($actions) && isset($actions["add"]) && !empty($actions["add"])): ?>
            <? if (empty($maxLines) || $maxLines > $totalCount || $totalCount == 0): ?>
              <a class="link" style="<?=(isset($searchParams)) || !empty($additionalActions) ? "" : "top:-8px;" ?>" href="<?=site_url($actions["add"]) . get_get_params()?>"><?=lang('admin.add');?></a>
            <? endif;?>
          <? endif;?>
        <? endif; ?>
        <? unset($actions["add"]); ?>
      </div>

      <?if (isset($searchParams)) :?>
        <div class="search-box search-bar" style="<?=!empty($additionalActions) ? "" : "margin-right:100px;" ?>">
          <div class="float-left search-container">
            <!-- Search bar -->
            <form action="<?=current_url()?>" method="get" class="query-params">
              <div class="float-left">
                <div class="input-row">
                  <!-- Search string -->
                  <img id="search_loader" class="search_loader" src="<?=site_img('admin/icons/small_back_loader.gif');?>" alt="Загружаем..." style="display: none;"/>
                  <label for="q"><?=lang('admin.search.search_string');?></label>
                  <input id="q" type="text" name="q" value=""/><br/>
                  <span class="description"><?=lang('admin.search.' . $entityName . '.description');?></span>
                </div>
                <div class="input-row button-box">
                  <!-- Submit -->
                  <button class="button" type="submit"><?=lang('admin.search.search_action');?></button><br/>
                </div>
              </div>
              
            </form>
          </div>
          <div class="clear"></div>
        </div>
        
        <script type="text/javascript">
          $(document).ready(function() {
            var options, a;
            jQuery(function() {
              var reEscape = new RegExp('(\\' + ['/', '.', '*', '+', '?', '|', '(', ')', '[', ']', '{', '}', '\\'].join('|\\') + ')', 'g');
              options = {serviceUrl: '<?=site_url($adminBaseRoute . '/' . strtolower($entityName) . '/search_autocomplete');?>',
                         fnFormatResult: function(value, data, currentValue) {
                           var pattern = '(' + currentValue.replace(reEscape, '\\$1') + ')';
                           return value.replace(new RegExp(pattern, 'gi'), '<span class="red-color">$1<\/span>') + '<br/>' + '<span class="fade-text"></span>' + '<span class="details">' + data.replace(new RegExp(pattern, 'gi'), '<span class="red-color">$1<\/span>') + '</span>';
                         },
                         preloader: $('#search_loader')};
              a = $('input[name=q]').ajaxautocomplete(options);
            });
          });
        </script>
      <?endif?>
    
      
    </td>
  </tr>
</table>
<div class="clear"></div>

<? if (isset($loggedInAdmin['permissions']) && strstr($loggedInAdmin['permissions'], $entityName . "_delete") === FALSE): ?>
  <? $isDeleteAllAllowed = false; ?>
<? endif; ?>

<? $this->view("includes/admin/parts/before_entity_list.php"); ?>

<?=html_flash_message();?>

<div class="inner inner-table">
  <?if (isset($entities) && sizeof($entities) > 0):?>
    <? $colSpan = sizeof($params) + 2;?>
    <? $rowNumStart = isset($pager) ? $pager->getFirstIndice() - 1 : 0; ?>
    <? if($processListUrl || $isListSortable): ?>
      <form action="<?=site_url($processListUrl)?>" class="form" autocomplete="off" method="post">
    <? endif; ?>
      <table id="list_<?=$entityName;?>" class="table">
        <thead>
          <tr>
            <? if($isDeleteAllAllowed): ?>
              <th class="first"><input type="checkbox" class="checkbox toggle checkbox-all" /></th>
              <th>№</th>
            <? else: ?>
              <th class="first">№</th>
            <? endif; ?>
                      
            <?foreach($params as $param):?>
              <? if(is_array($param)): ?>
                <? $tmp = ""; ?>
                <? foreach ($param as $pk => $pv): ?>
                <? $tmp .= $pk . '.' . $pv; ?>
                <? break; ?>
                <? endforeach; ?>
                <th class="<?=sort_by_get_class($tmp);?>"><a class="sort-by" href="javascript:void(0)" id="<?=$tmp;?>"><?=lang("admin.entity_list." . $entityName . "." . $tmp)?></a></th>
              <? else: ?>
                <? $ordArr = explode(' ', $defOrderBy); ?>
                <? if ($ordArr[0] == $param && sort_by_has_prefix(current_url()) === FALSE): ?>
                <? $sClass = (strpos($defOrderBy, 'DESC') !== FALSE)?'desc':'asc'; ?>
                  <th class="<?=$sClass?>"><a class="sort-by" href="javascript:void(0)" id="<?=$param;?>"><?=lang("admin.entity_list." . $entityName . "." . $param)?></a></th>
                <? else: ?>
                  <th class="<?=sort_by_get_class($param);?>"><a class="sort-by" href="javascript:void(0)" id="<?=$param;?>"><?=lang("admin.entity_list." . $entityName . "." . $param)?></a></th>
                <? endif; ?>
              <? endif; ?>
            <?endforeach;?>
            <? if ($actions && count($actions) > 0): ?>
            <th class="last"><a id="sort_clear" href="javascript:void(0)"><?=lang('admin.clear_sort');?></a></th>
            <? endif; ?>
          </tr>
        </thead>
        <?$rowNum = 1;?>
        <tbody  <?= $isListSortable ? 'class="sortable"' : '' ?>>
        <?foreach($entities as $el):?>
          <? $oel = $el; ?>
          <? $el = array_make_plain_with_dots($el); ?>
          <tr class="<?=($rowNum % 2 == 0)?"even":"odd"?>">
            <? if($isDeleteAllAllowed): ?>
              <td><input type="checkbox" class="checkbox exclude" name="d_id[]" value="<?=$el['id'];?>"/></td>
            <? endif; ?>
            <?$rowNumber = $rowNum + $rowNumStart; ?>
            <td>
              <? if($isListSortable): ?>
                <input type="hidden" name="s_id[]" value="<?=$el['id'];?>"/>
              <? endif; ?>
              <?=$rowNumber?>
            </td>
            <?foreach($params as $param):?>
              <? $image = FALSE; ?>
              <? if(is_array($param)): ?>
                <? foreach ($param as $pk => $pv): ?>
                  <? if (isset($oel[$pk]['id'])): ?>
                    <? $viewVal = $oel[$pk][$pv]; ?>
                    <? if (!in_array($pk, $listViewIgnoreLinks)): ?>
                      <? if(isset($oel[$pk]['type']) && $oel[$pk]['type'] == 'image'): ?>
                        <? $image = TRUE; ?>
                        <? $viewVal = $oel[$pk]; ?>
                      <? else: ?>
                        <? $url = $pk; ?>
                        <? if(isset($listViewLinksRewrite[$pk])): ?>
                          <? $url = $listViewLinksRewrite[$pk]; ?>
                        <? endif; ?>
                        <? $link = site_url("$adminBaseRoute/" . $url . "/add_edit/" . $oel[$pk]['id']); ?>
                      <? endif; ?>
                    <? endif; ?>
                    <? break; ?>
                  <? else: ?>
                    <? $viewVal = array(); ?>
                    <? $link = array(); ?>
                    <? if ($el[$pk]): ?>
                      <? foreach($el[$pk] as $ent): ?>
                      <? $viewVal[] = $ent[$pv]; ?>
                      <? if (!in_array($pk, $listViewIgnoreLinks)): ?>
                        <? $url = $pk; ?>
                        <? if(isset($listViewLinksRewrite[$pk])): ?>
                          <? $url = $listViewLinksRewrite[$pk]; ?>
                        <? endif; ?>
                        <? $link[] = site_url("$adminBaseRoute/" . $url . "/add_edit/" . $ent['id']); ?>
                      <? endif; ?>
                      <? endforeach; ?>
                    <? endif; ?>
                  <? endif; ?>
                <? break; ?>
                <? endforeach; ?>
                <? if (is_array($viewVal) && !$image) : ?>
                  <td>
                  <? for($ii = 0; $ii < count($viewVal); $ii++): ?>
                    <? if(isset($link[$ii])): ?><a href="<?=$link[$ii];?>"><?endif;?><?=trim($viewVal[$ii]);?><? if(isset($link[$ii])): ?></a><?endif;?><? if ($ii != count($viewVal) - 1): ?>, <? endif; ?>
                  <? endfor; ?>
                  </td>
                <? else: ?>
                  <? if($image): ?>
                    <td><img src="<?=site_image_thumb_url('_admin', $viewVal); ?>"/></td>
                  <? else: ?>
                    <td><?if(isset($link)):?><a href="<?=$link;?>"><?endif;?><?=trim($viewVal);?><?if(isset($link)):?></a><?endif;?></td>
                  <? endif; ?>
                <? endif; ?>
              <? else: ?>
              	<? if (isset($el[$param])): ?>
                  <? if (is_bool($el[$param])): ?>
                  	<td><? if ($el[$param]): ?><?=lang('admin.yes')?><? else: ?><?=lang('admin.no')?><? endif; ?></td>
                  <? else: ?>
                    <? if(isset($listViewValuesRewrite[$param]) && isset($listViewValuesRewrite[$param][$el[$param]])): ?>
                      <? $el[$param] = $listViewValuesRewrite[$param][$el[$param]]; ?>
                    <? endif; ?>
                    <? $langValueKey = 'enum.' . strtolower($entityName) . '.' . $param . '.' . $el[$param]; // poduct.type.PUBLISHED ?>
                    <? if(lang_exists($langValueKey)): ?>
                      <? $el[$param] = lang($langValueKey); ?>
                    <? endif; ?>
                  	<td><? if(isset($search["search_string"]) && in_array($param, explode(',', $search['search_in']))): ?><?=highlight_phrase(htmlspecialchars($el[$param]), $search["search_string"],'<span style="color:#F00">', '</span>')?><?else:?><?=character_limiter(htmlspecialchars($el[$param]), Base_Admin_Controller::ENTITY_LIST_STRING_LIMIT)?><? endif; ?></td>
                  <? endif; ?>
                <? else : ?>
                	<td></td>
                <? endif; ?>
                
              <? endif; ?>
            <?endforeach;?>
                
            <? if ($actions && count($actions) > 0): ?>
              <td class="last">
              <?$countActions = 0;?>
              <? foreach($actions as $key => $value): ?>
                <? if (isset($loggedInAdmin['permissions']) && in_array(strtolower($entityName) . '_' . $key, explode('|', $loggedInAdmin['permissions']))): ?>
                    <?$countActions++;?>
                    <? if($key == "delete" && (!isset($el["can_be_deleted"]) || (isset($el["can_be_deleted"]) && $el["can_be_deleted"] == "Y"))): ?>
                      <a <? if($key == "delete"): ?>class="deleteLink confirm" title="<?=lang('admin.confirm.entity_delete')?>"<? endif; ?> href="<?=site_url($value . '/' . $el['id']) . get_get_params()?>"><?=lang("admin." . $key)?></a><?if ($countActions < count($actions)):?>&nbsp;|<?endif;?>
                    <? endif; ?>
                    <? if($key != "delete"): ?>
                      <a href="<?=site_url($value . '/' . $el['id']) . get_get_params()?>"><?=lang("admin." . $key)?></a><?if ($countActions < count($actions)):?>&nbsp;|<?endif;?>
                    <? endif;  ?>
                <? else: ?>
                  <?$countActions++;?>
                  <a href="<?=site_url($value . '/' . $el['id']) . get_get_params()?>"><?=lang("admin." . $key)?></a><?if ($countActions < count($actions)):?>&nbsp;|<?endif;?>
                <? endif; ?>
              <? endforeach; ?>
              </td>
            <? endif; ?>
          </tr>
          <?$rowNum++; ?>
        <?endforeach;?>
        </tbody>
        <tfoot>
          <tr>
            <td style="width: 55%;" colspan="<?=++$colSpan-3?>"><?=lang("admin.total")?>: <?=$totalCount;?></td>
            <td style="width: 15%;">Total IN: <?=$priceArr['total_cost_in'];?></td>
            <td style="width: 15%;">Total OUT: <?=$priceArr['total_cost'];?></td>
            <td style="width: 15%;">Total VAT: <?=$priceArr['vat_summ'];?></td>
          </tr>
        </tfoot>
      </table>
      
      <? if($isListSortable): ?>
        <script type="text/javascript">
          $(function() {
            $(".sortable").sortable({ items: 'tr' });
            $(".sortable").disableSelection();
          });
        </script>
      <? endif; ?>
      
      <div class="actions-bar wat-cf">
        <? /*
        <? if($isDeleteAllAllowed): ?>
        <div class="actions">
          <a class="button confirm" title="<?=lang('admin.confirm.entities_delete')?>" href="<?=site_url($adminBaseRoute . '/' . strtolower($entityName) . '/delete_all_total');?>">
            <img src="<?=site_img("admin/icons/cross.png")?>" /><?=lang('admin.delete_all')?>
          </a>
        </div>
        <? endif; ?>
        */ ?>
        
        <? if($isListSortable): ?>
        <div class="actions">
          <button class="button saveOrder" type="submit" name="save_order" value="<?=lang('admin.save_order')?>">
            <img src="<?=site_img("admin/icons/tick.png")?>" alt="<?=lang('admin.save_order')?>" /><?=lang('admin.save_order')?>
          </button>
        </div>
        <? endif; ?>
        
        <?if(isset($pager)):?>
          <?=$this->view("includes/admin/parts/pager", array("pager" => $pager), TRUE);?>
        <?endif;?>
      </div>
    <? if($isDeleteAllAllowed): ?>
    </form>
    <? endif; ?>
  <? else: ?>
    <table class="table">
      <thead>
        <tr>
          <th class="first">№</th>
          <?foreach($params as $param):?>
            <? if(is_array($param)): ?>
              <? $tmp = ""; ?>
              <? foreach ($param as $pk => $pv): ?>
              <? $tmp .= $pk . '.' . $pv; ?>
              <? break; ?>
              <? endforeach; ?>
              <th class="<?=sort_by_get_class($tmp);?>"><?=lang("admin.entity_list." . $entityName . "." . $tmp)?></th>
            <? else: ?>
              <th class="<?=sort_by_get_class($param);?>"><?=lang("admin.entity_list." . $entityName . "." . $param)?></th>
            <? endif; ?>
          <?endforeach;?>
          <th class="last">&nbsp;</th>
        </tr>
      </thead>
      <tbody>
        <?$colSpan = sizeof($params) + 2;?>
        <tr><td class="notFound" colspan="<?=$colSpan?>"><?=lang("admin.no_items")?></td></tr>
      </tbody>
      <tfoot>
        <tr><td colspan="<?=$colSpan?>"><? if($totalCount > 0): ?><?=lang("admin.total")?>:<?=$totalCount;?><? endif; ?></td></tr>
      </tfoot>
    </table>
  <? endif; ?>
</div>
<div class="clear"></div>