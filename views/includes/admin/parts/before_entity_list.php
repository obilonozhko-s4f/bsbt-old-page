<?if (isset($filters)) :?>
  <div class="search-box filter-bar">
    <?if (isset($filters) && is_array($filters) && !empty($filters)) :?>
      <h2 style="display: none;" class="filter-title"><?=lang('admin.filter_title');?></h2>
      <form class="query-params " action="<?=current_url()?>" method="get">
  			<? foreach ($filters as $filter_name => $default_filter_value): ?>
  				<? $current_filter_values = isset($filter_values[$filter_name])?$filter_values[$filter_name]:null; ?>
  				<? if (isset($current_filter_values)
		             && is_array($current_filter_values)) :?>
  					<div class="input-row input-row-new">
            	<label for="<?=$filter_name?>" class="float-left"><?=lang("admin.entity_list." . $entityUrlName . ".filter." . $filter_name . "_title")?>:</label>
              <div class="fl">
                <select class="filter<?=isset($filterClasses[$filter_name]) ? (' ' . $filterClasses[$filter_name]) : ''?>" name="<?=$filter_name?>">
                  <? if(empty($default_values[$filter_name])): ?>
                 	  <option value=""><?=lang('admin.filter.all');?></option>
                  <? endif; ?>
                	<? foreach ($current_filter_values as $key => $value): ?>
                      <? $langValueKey = 'enum.' . strtolower($entityName) . '.' . $filter_name . '.' . $key; // poduct.type.PUBLISHED ?>
                      
                      <? preg_match('/-*\(([0-9]+)\).*/', $value, $matches); ?>
                      <? $level = (count($matches) > 1) && isset($matches[1]) ? $matches[1] : 0; ?>
                      
                      <? if(lang_exists($langValueKey)): ?>
                        <? $value = lang($langValueKey); ?>
                      <? endif; ?>
                		<option <?if($key && $level):?>class="level-<?=$level?>"<?endif;?> value="<?=$key?>"><?=$value;?></option>
                	<? endforeach; ?>
                </select>
              </div>
            </div>
  				<? endif; ?>
  			<? endforeach; ?>
        <? foreach ($dateFilters as $dateFilterName): ?>
          <div class="input-row input-row-date">
            <div class="l"><?=lang("admin.entity_list." . $entityUrlName . ".filter." . $dateFilterName . "_title")?>:</div>
            <div class="r">
              <span class="date-filter-label"><?=lang('admin.filter.from');?>:&nbsp;</span><input class="date-filter-input date from" type="text" name="<?=$dateFilterName?>_from"/>&nbsp;&nbsp;&nbsp;
              <span class="date-filter-label"><?=lang('admin.filter.to');?>:&nbsp;</span><input class="date-filter-input date to" type="text" name="<?=$dateFilterName?>_to"/>
            </div>
            <div class="clear"></div>
            <div class="date-filter-links">
              <div class="l"><?=lang('admin.filter.for');?>:</div>
              <div class="r"><a href="javascript:void(0)" class="today"><?=lang('admin.filter.today');?></a>&nbsp;/&nbsp;<a href="javascript:void(0)" class="this-week"><?=lang('admin.filter.this_week');?></a>&nbsp;/&nbsp;<a href="javascriptvoid(0)" class="this-month"><?=lang('admin.filter.this_month');?></a>&nbsp;/&nbsp;<a href="javascript:void(0)" class="cancel"><?=lang('admin.filter.cancel');?></a></div>
              <div class="clear"></div>
            </div>
          </div>
        <? endforeach; ?>
        <div class="input-row input-row-new tac">
          <a class="link clear-search" href="<?=current_url()?>"><?=lang('admin.search.clear_search_sort_filters');?></a>
        </div>
      </form>
      <span class="turn off"></span>
    <?endif?>
    <div class="clear"></div>
  </div>
<? endif; ?>

<script type="text/javascript">
  $(document).ready(function() {
    if ($.cookie('<?=$entityName;?>_filter_closed') == 'yes') {
      $('.filter-bar .filter-title').show();
      $('.filter-bar .query-params').hide();
      $('.filter-bar .turn').removeClass('off');
      $('.filter-bar .turn').addClass('on');
    }
    
    $('.filter-bar .turn').click(function(e){
      if ($(this).hasClass('on')) {
        $('.filter-bar .filter-title').hide();
        $('.filter-bar .query-params').show();
        $(this).removeClass('on');
        $(this).addClass('off');
        $.cookie('<?=$entityName;?>_filter_closed', null);
      } else if ($(this).hasClass('off')) {
        $('.filter-bar .filter-title').show();
        $('.filter-bar .query-params').hide();
        $(this).removeClass('off');
        $(this).addClass('on');
        $.cookie('<?=$entityName;?>_filter_closed', 'yes');
      }
      e.stopPropagation();
      e.preventDefault();
      return false;
    });
  });
</script>