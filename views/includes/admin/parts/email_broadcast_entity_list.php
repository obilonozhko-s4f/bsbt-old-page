<? $totalCount = isset($pager) ? $pager->getNumResults() : 0;?>
<h2 class="title">
  <?=$lang->line('admin.email_broadcast_entity_list.' . $entityName . '.list_title');?>
</h2>

<? /* if (isset($filters) || isset($searchParams)) :?>
  <div class="search-box filter-bar">
  
    <?if (isset($filters) && is_array($filters) && !empty($filters)) :?>
      <div class="float-left filter-container">
        <form name="filter-type" class="remove-hidden-on-submit" action="<?=site_url($adminBaseRoute . '/email_broadcast_' . $entityName)?>" method="get">
    			<?foreach ($filters as $filter_name => $current_filter_value) :?>
    				<?$current_filter_values = $filter_values[$filter_name]?>
            
    				<?if (test($current_filter_values) &&
    				      is_array($current_filter_values)) :?>
    					<div class="input-row">
              	<label for="<?=$filter_name?>" class="float-left"><?=lang("admin.entity_list." . $entityUrlName . ".filter." . $filter_name . "_title")?>:</label>
                <select class="filter" name="<?=$filter_name?>">
                	<option <?if ($current_filter_value === '') :?>selected="selected"<?endif?> value="">-- All --</option>
                	<?foreach ($current_filter_values as $key => $value) :?>
                		<option <?if ($current_filter_value == $key && $current_filter_value !== '') :?>selected="selected"<?endif?> value="<?=$key?>"><?=$value;?></option>
                	<?endforeach?>
                </select>
                  
              </div>
    				<?endif?>
    			<?endforeach?>
        </form>
      </div>
    <?endif?>
    
    
    <?if (isset($searchParams)) :?>
      <div class="float-left search-container">
        <!-- Search bar -->
        <form action="<?=site_url($adminBaseRoute . '/email_broadcast_' . $entityName . '/search')?>" method="post">
          <div class="float-left">
            <div class="input-row">
              <!-- Search string -->
              <label for="search_string"><?=lang('admin.search.search_string');?></label>
              <input id="search_string" class="required" type="text" name="search_string" value="<?=isset($search) ? $search["search_string"] : ""?>"/><br/>
              <span class="description"><?=lang('admin.search.' . $entityName . '.description');?></span>
            </div>
            <div class="input-row button-box">
              <!-- Submit -->
              <button class="button" type="submit">Search</button><br/>
              <a class="link clear-search" href="<?=site_url($adminBaseRoute . '/email_broadcast_' . $entityName . '/search_clear')?>"><?=lang('admin.search.search_clear');?></a>
            </div>
          </div>
          <div class="clear"></div>
        </form>
      </div>
    <?endif?>
        
    <script type="text/javascript">
    	$(document).ready(function() {
        var thisUrl = "<?=site_url($adminBaseRoute . '/email_broadcast_' . $entityName); ?>";
    		$("form.remove-hidden-on-submit select").change(function() {
    		    if ($(this).val() == '') {
              var reload = true;
    		      $('form.remove-hidden-on-submit select').each(function() {
                if ($(this).val() != '') {
                  reload = false;
                }
    		      });
              if (reload) {
    					  window.location.href = thisUrl;
                return;
              }
            }
    				$(this).parents("form").submit();
    			return false;
    		});
    	});
    </script>
    <div class="clear"></div>
  </div>
<? endif;*/ ?>

<? $this->view("includes/admin/parts/before_entity_list.php"); ?>

<?=html_flash_message();?>

<div class="inner inner-table">
  <?if (isset($entities) && sizeof($entities) > 0):?>
    <? $colSpan = sizeof($params) + 2;?>
    <? $rowNumStart = isset($pager) ? $pager->getFirstIndice() - 1 : 0; ?>
    <form action="<?=site_url($adminBaseRoute . '/email_broadcast_' . $entityName . '/exclude')?>" class="form" autocomplete="off" method="post">
     <table id="list_<?=$entityName;?>" class="table">
        <thead>
          <tr>
            <? if($isDeleteAllAllowed): ?>
              <th class="first"><input type="checkbox" class="checkbox toggle" /></th>
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
                  	<td><? if(isset($search["search_string"]) && in_array($param, explode(',', $search['search_in']))): ?><?=highlight_phrase(htmlspecialchars($el[$param]), $search["search_string"],'<span style="color:#F00">', '</span>')?><?else:?><?=htmlspecialchars($el[$param])?><? endif; ?></td>
                  <? endif; ?>
                <? else : ?>
                	<td></td>
                <? endif; ?>
                
              <? endif; ?>
            <?endforeach;?>
            <td class="last">
            </td>
          </tr>
          <?$rowNum++; ?>
        <?endforeach;?>
        </tbody>
        <tfoot>
          <tr><td colspan="<?=++$colSpan?>"><?=lang("admin.total")?>: <?=$totalCount;?></td></tr>
        </tfoot>
      </table>
      
      <div class="actions-bar wat-cf">
        <div class="actions">
          <button class="button" type="submit">
            <img src="<?=site_img("admin/icons/cross.png")?>" /><?=$lang->line('admin.remove_from_broadcast')?>
          </button>
          <? if(!empty($exclude)): ?>
            <span><?=$lang->line("admin.email_broadcast.number_excluded", array('count' => count($exclude)));?><br/><a href="<?=site_url($adminBaseRoute . '/email_broadcast_' . $entityName . '/add_back')?>"><?=$lang->line("admin.email_broadcast.return_exluded")?></a></span>
          <? endif ?>
        </div>
        
        <?if(isset($pager)):?>
          <?=$this->view("includes/admin/parts/pager", array("pager" => $pager), TRUE);?>
        <?endif;?>
      </div>
    </form>
    
    
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
              <th class="<?=sort_by_get_class($tmp);?>"><?=$lang->line("admin.entity_list." . $entityName . "." . $tmp)?></th>
            <? else: ?>
              <th class="<?=sort_by_get_class($param);?>"><?=$lang->line("admin.entity_list." . $entityName . "." . $param)?></th>
            <? endif; ?>
          <?endforeach;?>
          <th class="last">&nbsp;</th>
        </tr>
      </thead>
      <tbody>
        <?$colSpan = sizeof($params) + 2;?>
        <tr><td class="notFound" colspan="<?=$colSpan?>"><?=$lang->line("admin.no_items")?></td></tr>
      </tbody>
      <tfoot>
        <tr><td colspan="<?=$colSpan?>"><?=$lang->line("admin.total")?>: <?=$totalCount;?></td></tr>
      </tfoot>
    </table>
    <div class="actions-bar wat-cf">
      <div class="actions">
        <? if(!empty($exclude)): ?>
          <span><?=$lang->line("admin.email_broadcast.number_excluded", array('count' => count($exclude)));?><br/><a href="<?=site_url($adminBaseRoute . '/email_broadcast_' . $entityName . '/add_back')?>"><?=$lang->line("admin.email_broadcast.return_exluded")?></a></span>
        <? endif ?>
      </div>
    </div>
  <? endif; ?>
</div>
<div class="clear"></div>