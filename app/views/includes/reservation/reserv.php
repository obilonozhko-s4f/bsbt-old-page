<?=html_flash_message();?>
<div class="clear"></div>
<? if(!empty($_GET['date_from']) && !empty($_GET['date_to'])): ?>
  <a href="<?=site_url('object/' . $res_object['id'] . '?city=' . $res_object['city']['id'] . '&objecttype=' . $res_object['objecttype']['root_id'] . '&objecttypeclass=' . $res_object['objecttype']['id'] . '&date_from=' . $_GET['date_from'] . '&date_to=' . $_GET['date_to'] . '&persons=' . $_GET['persons']);?>"><?=lang('reservation.back');?></a>
<? else:?>
  <a href="<?=site_url('object/' . $res_object['id']);?>"><?=lang('reservation.back');?></a>
<? endif; ?>

<form method="post" action="<?=site_url('reservation/reserv');?>" class="validate" autocomplete="off" id="res_form">
<h2><?=lang('reservation.title.reserv_form');?></h2>

  <select class="yurfiz" name="utype">
    <option value="yur"><?=lang('reservation.filter.yur');?></option>
    <option value="fiz"><?=lang('reservation.filter.fiz');?></option>
  </select>

<input type="hidden" name="apartment_id" value="<?=$res_object['id']?>"/>

<p><?=lang('reservation.required_fields');?></p>
<div class="clear"></div>
<div class="info">
  <div class="contact">
    <h2><?=lang('reservation.contact_info.title');?></h2>
    <div class="clear"></div>
    <div class="sinput">
      <label for="name"><?=lang('reservation.contact_info.name');?> <span>*</span></label>
      <input name="name" class="required" type="text" />
    </div>
    <div class="sinput">
      <label for="surname"><?=lang('reservation.contact_info.surname');?> <span>*</span></label>
      <input name="surname" class="required" type="text" />
    </div>
    <div class="clear"></div>
    <div class="yur">
      <div class="sinput">
        <label for="org"><?=lang('reservation.contact_info.company_name');?> <span>*</span></label>
        <input name="org" type="text" class="required big" />
        <p><?=lang('reservation.contact_info.company_name.description');?></p>
      </div>
      <div class="clear"></div>
      <div class="sinput">
        <label for="vat"><?=lang('reservation.contact_info.vat_number');?> <span>*</span></label>
        <input name="vat" type="text" class="required mid" />
        <p><?=lang('reservation.contact_info.vat_number.description');?></p>
      </div>
    </div>

	<script type="text/javascript">
	  $(document).ready(function() {
      $('select.yurfiz').change(function() {
	      if ($(this).val() == 'fiz') {
          $('div.yur').hide();
          $('input[name="org"]').attr('disabled', 'disabled');
	      } else {
	    	  $('div.yur').show();
	    	  $('input[name="org"]').removeAttr('disabled');
	      }
        return false;
      });
	  });
	</script>

    <div class="clear"></div>
    <div class="sinput">
      <label for="phone"><?=lang('reservation.contact_info.phone');?> <span>*</span></label>
      <input name="phone" class="required" type="text" />
    </div>
    <div class="sinput">
      <label for="fax"><?=lang('reservation.contact_info.fax');?></label>
      <input name="fax" type="text" />
    </div>
    <p class="phone"><?=lang('reservation.contact_info.phone.description');?></p>
    <div class="clear"></div>
    <div class="sinput">
      <label for="email">E-mail <span>*</span></label>
      <input name="email" class="required" type="text" />
    </div>
  </div>
  <div class="contact">
    <h2><?=lang('reservation.adress.title');?></h2>
    <div class="clear"></div>
    <div class="sinput">
      <label for="country"><?=lang('reservation.adress.county');?> <span>*</span></label>
      <input name="country" class="required" type="text" />
    </div>
    <div class="sinput">
      <label for="city"><?=lang('reservation.adress.city');?> <span>*</span></label>
      <input name="city" class="required" type="text" />
    </div>
    <div class="sinput">
      <label for="index"><?=lang('reservation.adress.zip');?> <span>*</span></label>
      <input name="zip" class="required" type="text" />
    </div>
    <div class="clear"></div>
    <div class="sinput">
      <label for="street"><?=lang('reservation.adress.street');?> <span>*</span></label>
      <input name="street" type="text" class="required mid" />
    </div>
    <div class="sinput">
      <label for="home"><?=lang('reservation.adress.house');?> <span>*</span></label>
      <input name="home" type="text" class="required tiny" />
    </div>
    <div class="sinput">
      <label for="office"><?=lang('reservation.adress.office');?></label>
      <input name="office" type="text" class="tiny" />
    </div>
  </div>

  <script type="text/javascript">
        $(document).ready(function() {
            $( "#datepicker1" ).datepicker({
              	minDate: 0,
              	numberOfMonths: 2,
                showOn: "both",
                buttonImage: "<?=site_img('calendar.png');?>",
                buttonImageOnly: true,
                buttonText: '',
                dateFormat: 'dd/mm/yy',
                autoSize: true
            });
            $( "#datepicker2" ).datepicker({
              	minDate: 0,
              	numberOfMonths: 2,
                showOn: "both",
                buttonImage: "<?=site_img('calendar.png');?>",
                buttonImageOnly: true,
                buttonText: '',
                dateFormat: 'dd/mm/yy',
                autoSize: true
            });
        });
    </script>

  <div class="contact">
    <h2><?=lang('reservation.order_data.title');?></h2>
    <div class="clear"></div>
    <div class="sinput">
      <label for="code"><?=lang('reservation.order_data.code');?> <span>*</span></label>
      <input disabled="disabled" value="<?=$res_object['id']?>" name="code" type="text" readonly="readonly" class="required small" />
    </div>
    <div class="sinput" style="margin-left: 40px;">
      <label for="date_from"><?=lang('reservation.order_data.date_from');?> <span>*</span></label>
      <input name="date_from" type="text" class="required small" id="datepicker1" readonly="readonly" <? if(isset($_GET['date_from'])): ?>value="<?=$_GET['date_from']?>"<? endif;?> />
    </div>
    <div class="sinput">
      <label for="date_to"><?=lang('reservation.order_data.date_to');?> <span>*</span></label>
      <input name="date_to" type="text" class="required small" id="datepicker2" readonly="readonly" <? if(isset($_GET['date_to'])): ?>value="<?=$_GET['date_to']?>"<? endif;?> />
    </div>

    <script type="text/javascript">
      $(document).ready(function() {

        var df = $("#datepicker1").attr('value');
        var dt = $("#datepicker2").attr('value');
        var p = $("select.persons").attr('value');
        var add = $("input.added_person:checked").length;
        if ($("input.breakfast").attr('checked')){
          var br = '1';
        } else {
          var br = '0';
        }

        if (df == '' || dt == '') {
    	    $('.reserved_object .top_part p.period').css('color','red');
          $('.reserved_object .top_part p.period').text('<?=lang('reservation.right_part.noperiod');?>');
        } else {
          $('.reserved_object .top_part p.period').empty();
          $('.reserved_object .top_part p.period').removeAttr('style');
          $('.reserved_object .top_part p.period').text('<?=lang('reservation.right_part.period');?> '+df+' - '+dt+'');
        }

        if (df != '' || dt != '') {
    	  $('.bot_part .total_cost').empty();
     	  $('.bot_part .total_cost').append('<img src="<?=site_img('preloader2.gif');?>" class="preload" alt="loading..." title="loading..." />');
          $.get(base_url + "ajax/totalcost", {dateFrom: df, dateTo: dt, persons: p, added_person: add, breakfast: br, id: <?=$res_object['id']?>},
              function(data){
                $('.bot_part .total_cost').html(data);
           });
          $.get(base_url + "ajax/datecheck", {dateFrom: df, dateTo: dt, id: <?=$res_object['id']?>},
              function(data){
                $('p.check_result').html(data);
           });
        }

        $('#datepicker1,#datepicker2,select.persons,input.breakfast,input.added_person').change(function() {
        	  df = $("#datepicker1").attr('value');
            dt = $("#datepicker2").attr('value');
            p = $("select.persons").attr('value');
            add = $("input.added_person:checked").length;
            if ($("input.breakfast").attr('checked')){
              br = '1';
            } else {
              br = '0';
            }

            $('.bot_part .total_cost').empty();
            $('.bot_part .total_cost').append('<img src="<?=site_img('preloader2.gif');?>" class="preload" alt="loading..." title="loading..." />');
            $.get(base_url + "ajax/totalcost", {dateFrom: df, dateTo: dt, persons: p, added_person: add, breakfast: br,  id: <?=$res_object['id']?>},
                function(data){
                  $('.bot_part .total_cost').html(data);
            });
        });

        $('#datepicker1,#datepicker2').change(function() {
        	df = $("#datepicker1").attr('value');
          dt = $("#datepicker2").attr('value');

          $.get(base_url + "ajax/datecheck", {dateFrom: df, dateTo: dt, id: <?=$res_object['id']?>},
                  function(data){
                    $('p.check_result').html(data);
               });

          if (df == '' || dt == '') {
            $('.reserved_object .top_part p.period').empty();
            $('.reserved_object .top_part p.period').css('color','red');
            $('.reserved_object .top_part p.period').append('<?=lang('reservation.right_part.noperiod');?>');
          } else {
            $('.reserved_object .top_part p.period').empty();
            $('.reserved_object .top_part p.period').removeAttr('style');
            $('.reserved_object .top_part p.period').append('<?=lang('reservation.right_part.period');?> '+df+' - '+dt+'');
          }

        });

      });
    </script>

    <div class="sinput" style="margin-left: 40px;">
      <label for="time_h"><?=lang('reservation.order_data.time');?></label>
      <select name="time_h" class="tiny">
        <option>00</option>
        <option>02</option>
        <option>04</option>
        <option>06</option>
        <option>08</option>
        <option>10</option>
        <option>12</option>
        <option>14</option>
        <option>16</option>
        <option>18</option>
        <option>20</option>
        <option>22</option>
      </select>
      <h3>:</h3>
      <select name="time_m" class="tiny" style="clear: none;">
        <option>00</option>
        <option>15</option>
        <option>30</option>
        <option>45</option>
      </select>
    </div>
    <p class="check_result" id="submit_no"><?=lang('reservation.order_data.not_available');?></p>
    <div class="clear"></div>
    <div class="sinput">
      <label for="persons"><?=lang('reservation.order_data.persons');?> <span>*</span></label>
      <select name="persons" class="required small persons">
      <? $i=0; ?>
      <? while ($i++<$res_object['persons_max']): ?>
        <option <? if(isset($_GET['persons']) && $_GET['persons'] == $i): ?>selected="selected"<? endif; ?> value="<?=$i;?>"><?=$i;?></option>
      <? endwhile; ?>
      </select>
    </div>

    <? if(!empty($res_object['add_person_in']) && !empty($res_object['add_person_out'])):?>
      <div class="sinput" style="margin: 33px 0 0 20px;">
        <input type="checkbox" name="added_person" class="checkbox added_person" value="1" />
        <p class="transfer"><?=lang('reservation.order_data.added_person');?> (<?=$res_object['add_person_out'];?> <?=lang('reservation.order_data.added_person.per_night');?>)</p>
      </div>
    <? endif;?>

    <div class="clear"></div>
    <div class="add_persons"></div>

    <script type="text/javascript">
	  $(document).ready(function() {
	    var str = parseInt($("select.persons option:selected").attr('value'));
		  var add = $("input.added_person:checked").length;

	    for (var p=1; p<=str+add; p++){
	    	 	$('.add_persons').append('<div class="sinput"><label for="p_name_'+p+'"><?=lang('reservation.order_data.person_name');?> '+p+'</label><input name="p_name_'+p+'" type="text" /></div><div class="sinput"><label for="p_surname_'+p+'"><?=lang('reservation.order_data.person_surname');?> '+p+'</label><input name="p_surname_'+p+'" type="text" /></div><div class="sinput"><label for="p_pas_'+p+'"><?=lang('reservation.order_data.pasport');?> '+p+'</label><input name="p_pas_'+p+'" type="text" /></div>');
	      }
	    $('select.persons').change(function() {
	    	  $('.add_persons').empty();
	        $("select.persons option:selected").each(function () {
	          str = parseInt($(this).attr('value'));
	          });
	        var add = $("input.added_person:checked").length;
	        for (var p=1; p<=str+add; p++){
	            $('.add_persons').append('<div class="sinput"><label for="p_name_'+p+'"><?=lang('reservation.order_data.person_name');?> '+p+'</label><input name="p_name_'+p+'" type="text" /></div><div class="sinput"><label for="p_surname_'+p+'"><?=lang('reservation.order_data.person_surname');?> '+p+'</label><input name="p_surname_'+p+'" type="text" /></div><div class="sinput"><label for="p_pas_'+p+'"><?=lang('reservation.order_data.pasport');?> '+p+'</label><input name="p_pas_'+p+'" type="text" /></div>');
	          }
	        return false;
	      });
	    $("input.added_person").change(function() {
	    	$('.add_persons').empty();
        $("select.persons option:selected").each(function () {
          str = parseInt($(this).attr('value'));
          });
        var add = $("input.added_person:checked").length;
        for (var p=1; p<=str+add; p++){
            $('.add_persons').append('<div class="sinput"><label for="p_name_'+p+'"><?=lang('reservation.order_data.person_name');?> '+p+'</label><input name="p_name_'+p+'" type="text" /></div><div class="sinput"><label for="p_surname_'+p+'"><?=lang('reservation.order_data.person_surname');?> '+p+'</label><input name="p_surname_'+p+'" type="text" /></div><div class="sinput"><label for="p_pas_'+p+'"><?=lang('reservation.order_data.pasport');?> '+p+'</label><input name="p_pas_'+p+'" type="text" /></div>');
          }
	    	return false;
      });
	    $('.sinput textarea').click(function(){
	    	    $('.sinput textarea').empty();
	        });
	  });
	</script>

    <div class="clear"></div>
    <div class="sinput" style="margin-bottom: 8px;">
      <input type="checkbox" name="transfer" checked="checked" class="checkbox" value="1" />
      <p class="transfer"><?=lang('reservation.order_data.transfer');?></p>
    </div>
    <div class="clear"></div>
    <div class="sinput" style="margin-bottom: 8px;">
      <input type="checkbox" name="breakfast" class="checkbox breakfast" value="1" />
      <p class="transfer"><?=lang('reservation.order_data.breakfast');?></p>
    </div>
    <div class="clear"></div>
    <div class="sinput">
      <textarea rows="5" cols="79" name="comments"><?=lang('reservation.order_data.comment');?></textarea>
    </div>
    <div class="sinput terms" style="margin-bottom: 8px; position: relative;">
      <input type="checkbox" name="terms" class="checkbox required" value="1" />
      <p class="transfer"><?=lang('reservation.order_data.agree');?> <a href="<?=site_url('terms' . get_get_params());?>" target="_blank" class="terms"><?=lang('reservation.order_data.terms');?></a> <span>*</span></p>
    </div>
    <div class="clear"></div>
  </div>
</div>
<input type="submit" class="button green checkout" value="<?=lang('reservation.order_data.checkout');?>"/>
</form>

<?=fill_form_with_saved_post('res_form');?>