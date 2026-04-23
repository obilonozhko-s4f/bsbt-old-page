<div class="reserved_object">
  <div class="top_part">
    <p class="city"><?=$res_object['city']['title']?></p>
    <p class="code"><?=lang('reservation.right_part.code');?> <?=$res_object['id']?></p>
    <div class="clear"></div>
    <p class="obj_type"><?=$res_object['objecttype']['name']?></p>
    <p class="period"></p>
    <div class="clear"></div>
    <div class="border"></div>
  </div>
  <div class="mid_part">
    <img src="<?=site_image_thumb_url('_medium', $res_object['image']);?>" alt="<?=$res_object['objecttype']['name']?>" />
    <div class="info">
      <p><?=lang('reservation.right_part.max_persons');?> <span><?=$res_object['persons_max']?><?if(!empty($res_object['add_person_in']) && !empty($res_object['add_person_out'])):?> + 1<? endif;?></span></p>
      <p><?=lang('reservation.right_part.min_nights');?> <span><?=$res_object['nights_min']?></span></p>
      <p><?=lang('reservation.right_part.space');?> <span><?=$res_object['space']?> м&#178;</span></p>
      <p><?=lang('reservation.right_part.price_out');?> <span><?=$res_object['price_out']?>&#8364; / <?=lang('reservation.right_part.pernight');?> </span></p>
      <? if(!empty($_GET['date_from']) && !empty($_GET['date_to'])): ?>
        <a href="<?=site_url('object/' . $res_object['id'] . '?city=' . $res_object['city']['id'] . '&objecttype=' . $res_object['objecttype']['root_id'] . '&objecttypeclass=' . $res_object['objecttype']['id'] . '&date_from=' . $_GET['date_from'] . '&date_to=' . $_GET['date_to'] . '&persons=' . $_GET['persons']);?>">Подробнее</a>
      <? else:?>
        <a href="<?=site_url('object/' . $res_object['id']);?>"><?=lang('reservation.right_part.details');?></a>
      <? endif; ?>
    </div>
  </div>
  <div class="features"  id="<?=$res_object['id'];?>">
    <img src="<?=site_img('preloader1.gif');?>" class="preload" alt="loading..." title="loading..." />
  </div>
  <div class="border"></div>
  <div class="bot_part">
    <p><?=lang('reservation.right_part.total_cost');?></p>
    <div class="total_cost"></div>
  </div>
<img class="frog" src="<?=site_img('frog2.png');?>"/>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    $('.features').each(function() {
      var that = $(this);
      $.get(base_url + "ajax/features", {aId: $(this).attr('id')},
        function(data){
          that.html(data);
        });
    });

    $("div.reserved_object").mouseenter(function(){
        $('.reserved_object img.frog').fadeIn("slow").delay(20000).fadeOut("slow", function(){
          $('.reserved_object img.frog').clearQueue();
        });
        return false;
      });
    
  });
</script>