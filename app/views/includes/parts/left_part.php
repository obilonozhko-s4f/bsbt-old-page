<p class="wtg"><?=lang('find_form.form_title.where_to_go');?></p>
<div class="search_form sf_common">
  <? $this->load->view("includes/parts/search_form"); ?>
</div>
<div class="our_rec">
  <h2><?=lang('left_part.our_recomendations.title');?></h2>
  <? foreach($recomendations as $rec): ?>
    <div class="rec_item">
      <div class="top_left">
        <p class="city"><?=$rec['city']['title']?></p>
        <p class="object_typeclass"><?=$rec['objecttype']['name']?></p>
      </div>
      <p class="code"><?=lang('content_index.recomendations.code');?> <?=$rec['id']?></p>
      <div class="border"></div>
      <img src="<?=site_image_thumb_url('_small', $rec['image']);?>" alt="" />
      <div class="info">
        <p><?=lang('content_index.recomendations.persons_max');?> <span><?=$rec['persons_max']?><?if(!empty($rec['add_person_in']) && !empty($rec['add_person_out'])):?> + 1<? endif;?></span></p>
        <p><?=lang('content_index.recomendations.nights_min');?> <span><?=$rec['nights_min']?></span></p>
        <p><?=lang('content_index.recomendations.square');?> <span><?=$rec['space']?> м&#178;</span></p>
        <p><?=lang('content_index.recomendations.price');?> <span><?=$rec['price_out']?>&#8364; / <?=lang('content_index.recomendations.per_night');?> </span></p>
        <a href="<?=site_url('object/' . $rec['id']);?>"><?=lang('content_index.recomendations.more_details');?></a>
      </div>
    </div><!-- END rec_item -->
  <? endforeach; ?>
</div>
<div class="social" style="display: none;">
  <p><?=lang('left_part.social.follow_us');?></p>
  <a href="#"><img src="<?=site_img('facebook.png');?>" alt="facebook" /></a>
  <a href="#"><img src="<?=site_img('tweeter.png');?>" alt="tweeter" /></a>
</div>
<div class="clear"></div>