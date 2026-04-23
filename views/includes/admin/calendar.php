<div class="content default-box">
  <h2 class="title">
    Календарь резерваций
    <?if ($backUrl) :?>
      <a class="link" href="<?=site_url($backUrl);?>"><?=lang('admin.add_edit_back');?></a>
    <?endif;?>
  </h2>
  <?=html_flash_message();?>
  <div class="inner calendar">
  
   <form action="<?=admin_site_url($entityName . '/calendar')?>" method="get" id="aForm">
     <select name="apartment.id">
       <option value="">-- Выберите обьект --</option>
       <? foreach($apartments as $key => $value): ?>
         <option <? if($aId && $aId == $key): ?>selected="selected"<? endif; ?> value="<?=$key?>" ><?=$value;?></option>
       <? endforeach; ?>
     </select>
     
     <select name="y">
       <option value="">-- Выберите год --</option>
       <? $yearMin = $year - 15; ?>
       <? $yearMax = $year + 15; ?>
       <? for ($i = $yearMin; $i < $yearMax; $i++): ?>
         <option <? if($year == $i): ?>selected="selected"<? endif; ?> value="<?=$i?>" ><?=$i;?></option>
       <? endfor; ?>
     </select>
     
     <select name="m">
       <option value="">-- Выберите месяц --</option>
       <? foreach(generate_months('ru', FALSE) as $k => $v): ?>
         <option <? if($month == $k): ?>selected="selected"<? endif; ?> value="<?=$k?>" ><?=$v;?></option>
       <? endforeach; ?>
     </select>
     

    </form>
  
  
    <? if($aId): ?>
      <div class="calendar_nav">
        <a href="<?=admin_site_url($entityName . '/calendar') . '?apartment.id=' . $aId . '&m=' . $prevMonth . '&y=' . $prevYear;?>">Пред. месяц</a>
        <a href="<?=admin_site_url($entityName . '/calendar') . '?apartment.id=' . $aId?>">Текущий</a>
        <a href="<?=admin_site_url($entityName . '/calendar') . '?apartment.id=' . $aId . '&m=' . $nextMonth . '&y=' . $nextYear;?>">След. месяц</a>
      </div>
      <?=$calendar->generate($year, $month, $reservations);?>
    <? else: ?>
    <p>НЕ ВЫБРАН ОБЬЕКТ!</p>
    <? endif; ?>
  
  </div>
</div>
<div class="clear"></div>


<script type="text/javascript">
  $(document).ready(function() {
    $('#aForm select').change(function(){
      $(this).parents('form:first').submit();
    });

    $('.highlight').parent().css({'background' : '#FCF1C0', 'border' : '2px solid #E8E1B5'});
    $('.reserv').parent().css({'background' : '#F66A6A', 'border' : '2px solid #FFF'});
    $('.highlight.reserv').parent().css({'background' : '#F66A6A', 'border' : '2px solid #E8E1B5'});
  });
</script>