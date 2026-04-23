<div class="news">
  <h2><?=lang('left_part.news');?></h2>
  <? foreach($newsitems as $ni): ?>
    <div class="news_item">
      <img src="<?=site_image_thumb_url('_small', $ni['image']);?>" alt="<?=$ni['title']?>" />
      <h3><?=$ni['date']?></h3>
      <a href="<?=site_url($ni['page_url'] . get_get_params());?>"><?=$ni['title']?></a>
      <div class="clear"></div>
      <p><?=$ni['description']?></p>
      <div class="border"></div>
    </div>
  <? endforeach;?>
  <a href="<?=site_url('news'. get_get_params());?>"><?=lang('left_part.news.all_news');?></a>
</div>
<div class="social" style="display: none;">
  <p><?=lang('left_part.social.follow_us');?></p>
  <a href="#"><img src="<?=site_img('facebook.png');?>" alt="facebook" /></a>
  <a href="#"><img src="<?=site_img('tweeter.png');?>" alt="tweeter" /></a>
</div>
<div class="clear"></div>