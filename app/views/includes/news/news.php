<div class="all_news">
  <h2><?=lang('news.title.news');?></h2>
  <? foreach ($allnews as $ni): ?>
    <div class="news_item">
      <a href="<?=site_url($ni['page_url'] . get_get_params());?>" class="title"><?=$ni['title']?></a>
      <p class="date"><?=lang('news.publish_date');?> <?=$ni['date']?></p>
      <div class="clear"></div>
      <img src="<?=site_image_thumb_url('_medium', $ni['image']);?>" alt="<?=$ni['title']?>" />
      <div class="description">
        <p><?=$ni['description']?></p>
      </div>
      <a href="<?=site_url($ni['page_url'] . get_get_params());?>" class="details"><?=lang('news.details');?></a>
    </div>
  <? endforeach; ?>
</div>