<div class="group group-row">
  <label for="<?=$id?>" class="label"><?=$label?></label>
  <?=$this->view($params['path'], array('key' => $key, 'params' => $params, 'attrs' => $attrs), TRUE);?>
</div>