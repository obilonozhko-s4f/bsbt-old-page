<div class="news_item">
  <a href="<?=site_url('news' . get_get_params());?>" class="back"><?=lang('news_item.back');?></a>
  <h2><?=$newsitem['title']?></h2>
  <p class="date">(<?=$newsitem['date']?>)</p>
  <img src="<?=site_image_thumb_url('_big', $newsitem['image']);?>" alt="<?=$newsitem['title']?>" />
  <div class="content"><?=$newsitem['content']?></div>
</div>