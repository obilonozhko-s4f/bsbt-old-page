<script type="text/javascript">
  $(document).ready(function() {
	  $('p.check_result').removeAttr('style');
	  <? if(!empty($ocheck) && isset($nights)):?>
	    $('p.check_result').attr('id', 'submit_yes');
	    $('p.check_result').css({'background' : '#ACCF68', 'border' : '1px solid #ACCF68', 'color' : '#FFF'});
	  <? elseif(!empty($ocheck) && !isset($nights)): ?>
	    $('.reserved_object .top_part p.period').text('<?=lang('reservation.right_part.noperiod');?>');
	    $('.reserved_object .top_part p.period').css('color','red');
	  <? else:?>
	    $('p.check_result').attr('id', 'submit_no');
	  <? endif;?>
	  $('p.check_result').css('display','block');
  });
</script>
<? if(!empty($ocheck) && isset($nights)):?>
  <?=lang('reservation.order_data.available');?>
<? elseif(!empty($ocheck) && !isset($nights)): ?>
  <?=lang('reservation.order_data.incorrect_period');?>
<? else:?>
  <?=lang('reservation.order_data.not_available');?>
<? endif;?>
