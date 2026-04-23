<form action="<?=site_url('apartments');?>" method="get" class="validate">
  <div class="top_part">
    <label for="city"><?=lang('find_form.top_part.city');?></label>
    <select name="city" class="required">
  	  <option value=""><?=lang('find_form.top_part.city_select');?></option>
        <? foreach($cities as $cId => $cName): ?>
          <option <? if(isset($get['city']) && $get['city'] == $cId): ?>selected="selected"<? endif; ?> value="<?=$cId;?>"><?=$cName;?></option>
        <? endforeach; ?>
	   </select>
    <label for="objecttype"><?=lang('find_form.top_part.object_type');?></label>
    <select name="objecttype" class="objecttype">
      <option value=""><?=lang('find_form.top_part.objecttype_select');?></option>
      <? foreach($objecttypes as $ot): ?>
        <option <? if(isset($get['objecttype']) && $get['objecttype'] == $ot['id']): ?>selected="selected"<? endif; ?> value="<?=$ot['id'];?>"><?=$ot['name'];?></option>
      <? endforeach; ?>
    </select>
    <div class="otc"></div>
  </div><!-- END top_part -->
  <script type="text/javascript">
    $(document).ready(function() {
  	  
      <? if (!empty($_GET['objecttypeclass'])): ?>
  	  var getotcId = <?=$_GET['objecttypeclass']?>;
  		  $.get(base_url + "ajax/typeclass", {otId: <?=$_GET['objecttype']?>, otcId: getotcId},
  	        function(data){
  	        $('div.otc').html(data);
  	      });
  	  <? endif; ?>
  	  
  	  $('select.objecttype').change(function() {
  		  $('.search_form form .top_part .otc').empty();
  	  	$('.search_form form .top_part .otc').append('<img src="<?=site_img('preloader_sf.gif');?>" class="preload" alt="loading..." title="loading..." />');

  	    $("select.objecttype option:selected").each(function () {
  		    str = $(this).attr('value');
          });
  	    $.get(base_url + "ajax/typeclass", {otId: str},
  	        function(data){
  	        $('div.otc').html(data);
  	    });
  	    if (str == "") {
          $('#left_part_index div.news').css('margin', '120px 0 0 0');
          $('.search_form img.frog').css('top', '94px');
        } else {
  	      $('#left_part_index div.news').css('margin', '175px 0 0 0');
  	      $('.search_form img.frog').css('top', '145px');
        }
  	    return false;
  	  });
  	  $("div.search_form").mouseenter(function(){
  		  $('.search_form img.frog').fadeIn("slow").delay(20000).fadeOut("slow", function(){
  			  $('.search_form img.frog').clearQueue();
  		  });
   	    return false;
  	  });
    });
  </script>
  <div class="mid_part">
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
    <div class="datepicker">
      <p><?=lang('find_form.mid_part.check_in');?></p>
      <input name="date_from" type="text" class="required" id="datepicker1" readonly="readonly" <? if(isset($_GET['date_from'])): ?>value="<?=$_GET['date_from']?>"<? endif;?> />
    </div>
    <div class="datepicker" style="margin-top: 8px;">
      <p><?=lang('find_form.mid_part.check_out');?></p>
      <input name="date_to" type="text" class="required" id="datepicker2" readonly="readonly" <? if(isset($_GET['date_to'])): ?>value="<?=$_GET['date_to']?>"<? endif;?> />
    </div>
  </div><!-- END mid_part -->
            
  <div class="bot_part">
    <div class="num_step">
    <p><?=lang('find_form.bot_part.persons_num');?></p>
    <input name="persons" type="text" <? if(isset($_GET['persons'])): ?>value="<?=$_GET['persons']?>" <? else:?> value="1" <? endif;?> size="2" id="stepper"/>
    <div class="clear"></div>
    
    <script type="text/javascript">
        $('#stepper').stepper({ limit: [1, 20] });
    </script>
    
    </div>
    <div class="code">
      <p><?=lang('find_form.bot_part.code');?></p>
      <input name="id" type="text" />
      <div class="clear"></div>
    </div>
  </div><!-- END bot_part -->
  <input type="submit" value="<?=lang('find_form.submit_button');?>" class="button orange" />
</form>
<img class="frog" src="<?=site_img('frog.png');?>"/>

<? if(isset($_GET['objecttypeclass']) && $_GET['objecttypeclass'] != ""):?>
<script type="text/javascript">
  $(document).ready(function() {
	$('.search_form img.frog').css('top', '145px');
  });
</script>
<? endif;?>