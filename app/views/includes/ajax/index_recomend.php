<? if(!empty($rec)): ?>
<? foreach($rec as $r): ?>
  <div class="rec_item">
    <div class="top_left">
      <p class="city"><?=$r['city']['title']?></p>
      <p class="object_typeclass"><?=$r['objecttype']['name']?></p>
    </div>
    <p class="code"><?=lang('content_index.recomendations.code');?> <?=$r['id']?></p>
    <div class="border"></div>
    <img src="<?=site_image_thumb_url('_small', $r['image']);?>" alt="" />
    <div class="info">
      <p><?=lang('content_index.recomendations.persons_max');?> <span><?=$r['persons_max']?><?if(!empty($r['add_person_in']) && !empty($r['add_person_out'])):?> + 1<? endif;?></span></p>
      <p><?=lang('content_index.recomendations.nights_min');?> <span><?=$r['nights_min']?></span></p>
      <p><?=lang('content_index.recomendations.square');?> <span><?=$r['space']?> м&#178;</span></p>
      <p><?=lang('content_index.recomendations.price');?> <span><?=$r['price_out']?>&#8364; / <?=lang('content_index.recomendations.per_night');?> </span></p>
      <a href="<?=site_url('object/' . $r['id']);?>"><?=lang('content_index.recomendations.more_details');?></a>
    </div>
  </div><!-- END rec_item -->
  <? endforeach; ?>
<? endif; ?>