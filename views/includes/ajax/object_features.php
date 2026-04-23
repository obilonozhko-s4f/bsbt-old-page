<? if(!empty($features)): ?>
  <ul class="result">
    <? foreach($features as $f): ?>
      <li>
        <img src="<?=site_image_thumb_url('_small', $f['image']);?>" alt="<?=$f['name']?>" />
        <p><?=$f['name']?></p>
      </li>
    <? endforeach; ?>
  </ul>
<? endif; ?>