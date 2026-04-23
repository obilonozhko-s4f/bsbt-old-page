<div class="top_part">
  <h2><?=lang('content_index.top_part.our_recomends');?></h2>
  <form>
    <select name="city" class="city">
      <option value=""><?=lang('content_index.top_part.all_sities');?></option>
      <? foreach($cities as $cId => $cName): ?>
        <option value="<?=$cId;?>"><?=$cName;?></option>
      <? endforeach; ?>
    </select>
  </form>
  <a href="<?=site_url('apartments' . '?sale=1');?>"><?=lang('content_index.top_part.all_rec');?></a>
  <div class="clear"></div>
</div>

<div class="recomendations">
  <img src="<?=site_img('preloader.gif');?>" class="preload" alt="loading..." title="loading..." />
</div>



<div class="seo">
  <?=$seo_text;?>
</div>
<div class="clear"></div>

<script type="text/javascript">
  $(document).ready(function() {
	  str = "";
	  $.get(base_url + "ajax/recomendations", {rId: str},
          function(data){
          $('div.recomendations').html(data);
      });
	  
	  $('select.city').change(function() {
	    $("select.city option:selected").each(function () {
		    str = $(this).attr('value');
        });

	    $('div.recomendations').empty();
	    $('div.recomendations').append('<img src="<?=site_img('preloader.gif');?>" class="preload" alt="loading..." title="loading..." />');
	    $.get(base_url + "ajax/recomendations", {rId: str},
	        function(data){
	        $('div.recomendations').html(data);
	    });

	    return false;
	  });
   
  });
</script>