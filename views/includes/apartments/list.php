<? if (!empty($get['city']) && !empty($get['date_from']) && !empty($get['date_to']) && !empty($get['persons'])): ?>
  <p class="wtg"><?=lang('apartment_list.search_result.title');?></p>
  <p class="find_obj"><?=lang('apartment_list.search_result.objects_found');?> (<?=$pager->getNumResults();?>)</p>
  <div class="clear"></div>
  <ul class="result">
    <? if(isset($get['city']) && isset($cities[$get['city']])): ?>
      <li>
        <p><?=lang('apartment_list.search_result.city');?> <span><?=$cities[$get['city']]?></span></p>
      </li>
    <? endif; ?>
    <? if(isset($get['date_from']) && isset($get['date_to'])): ?>
    <li>
      <p><?=lang('apartment_list.search_result.period');?> <span><?=$get['date_from']?> - <?=$get['date_to']?></span></p>
    </li>
    <? endif; ?>
    <? if(isset($get['persons'])): ?>
      <li>
        <p><?=lang('apartment_list.search_result.persons');?> <span><?=$get['persons']?></span></p>
      </li>
    <? endif; ?>
    <? if(isset($nights)): ?>
      <li>
        <p><?=lang('apartment_list.search_result.nights');?> <span><?=$nights;?></span></p>
      </li>
    <? endif; ?>
  </ul>
<? else: ?>
  <p class="wtg" style="margin-bottom: 13px;"><?=lang('apartment_list.catalog_title');?></p>
  <p class="find_obj"><?=lang('apartment_list.catalog_total_objects');?> (<?=$pager->getNumResults();?>)</p>
  <div class="clear"></div>
<? endif;?>

  <? if(!empty($apartments)): ?>
    
    <form method="get" action="<?=current_url();?>">
      <p class="sort"><?=lang('apartment_list.sort');?></p>
      <select name="sort">
        <option value="price_desc" <? if(isset($get['sort']) && $get['sort'] == 'price_desc'):?>selected="selected"<?endif;?> ><?=lang('apartment_list.sort.price_desc');?></option>
        <option value="price_asc"  <? if(isset($get['sort']) && $get['sort'] == 'price_asc'):?>selected="selected"<?endif;?> ><?=lang('apartment_list.sort.price_up');?></option>
      </select>
    </form>
    
    <script type="text/javascript">
    $(document).ready(function() {
      $('select[name="sort"]').change(function() {
        $.query.SET($(this).attr('name'), $(this).val());
        window.location.search = $.query.toString();
      });
    });
      
    </script>
  <? endif; ?>
  
  <? if(empty($_GET['date_from']) && empty($_GET['date_to'])):?>
    <p class="notsetperiod"><?=lang('apartment.object.noperiod');?></p>
  <? endif;?>
  
<div class="clear"></div>
<? if(!empty($apartments)): ?>
  <? foreach($apartments as $apart): ?>
    <div class="object">
      <div class="common">
        <div class="left">
          <img src="<?=site_image_thumb_url('_medium', $apart['image']);?>" alt="" />
          <p><?=lang('apartment.object.code');?> <?=$apart['id']?></p>
          <a href="<?=site_url('object/' . $apart['id'] . get_get_params());?>"><?=lang('apartment.object.details');?></a>
        </div>
        <div class="right">
          <p class="title"><?=$apart['objecttype']['name']?></p>
          <? if(!empty($_GET['date_from']) && !empty($_GET['date_to'])): ?>
            <p class="period"><?=lang('apartment.object.period');?></p>
          <? else: ?>
            <p class="noperiod"></p>
          <? endif; ?>
          <div class="clear"></div>
          <div class="param">
            <p><?=lang('apartment.object.param.city');?></p> <span><?=$apart['city']['title']?></span>
          </div>
          <div class="param">
            <p><?=lang('apartment.object.param.nights_min');?></p> <span><?=$apart['nights_min']?></span>
          </div>
          <div class="param">
            <p><?=lang('apartment.object.param.persons_max');?></p> <span><?=$apart['persons_max']?><?if(!empty($apart['add_person_in']) && !empty($apart['add_person_out'])):?> + 1<? endif;?></span>
          </div>
          <div class="param">
            <p><?=lang('apartment.object.param.space');?></p> <span><?=$apart['space']?> м&#178;</span>
          </div>
          <div class="param">
            <p><?=lang('apartment.object.param.price_out');?></p> <span><?=$apart['price_out']?>&#8364; / <?=lang('apartment.object.param.price_per_night');?></span>
          </div>
          <? if(!empty($_GET['date_from']) && !empty($_GET['date_to']) && !empty($_GET['persons'])): ?>
            <a href="<?=site_url('reservation' . '?id=' . $apart['id'] . '&date_from=' . $_GET['date_from'] . '&date_to=' . $_GET['date_to'] . '&persons=' . $_GET['persons']);?>" class="button green  list_submit"><?=lang('apartment.object.book');?></a>
          <? else:?>
            <a href="<?=site_url('reservation' . '?id=' . $apart['id']);?>" class="button green  list_submit"><?=lang('apartment.object.book');?></a>
          <? endif;?>
        </div>
      </div>
      <div class="features" id="<?=$apart['id'];?>">
        <img src="<?=site_img('preloader1.gif');?>" class="preload" alt="loading..." title="loading..." />
      </div>
    </div>
  <? endforeach; ?>
<? else: ?>
  <p class="unfortun"><?=lang('apartment.noresult');?></p>
<? endif; ?>
<div class="clear"></div>
<?=$this->load->view('includes/parts/paginator');?>


<script type="text/javascript">
  $(document).ready(function() {

    $('.features').each(function() {

      var that = $(this);
      $.get(base_url + "ajax/features", {aId: $(this).attr('id')},
        function(data){
          that.html(data);
        });
    });
   
    
  });
</script>
