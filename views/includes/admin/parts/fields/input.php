<div class="group">
  <label for="<?=$id?>" class="label"><?=$label?></label>
  
  <?if (isset($params['class']) && strpos($params['class'], 'passwordGen') !== false) :?>
    <img alt="Generate password" class="passwordGenButton" style="cursor: pointer; position: absolute; top: 19px; right: 0px;" title="Generate password" src="<?=site_img('admin/icons/dice.png'); ?>"/>
  <?endif?>
  
  <?
    if (!isset($entity[$key])) {
      $entity[$key] = '';
    }
  ?>
  
  <input id="<?=$id?>"
         name="<?=$name?>"
         type="text"
         class="text-field <?=isset($params['class']) ? $params['class'] : ''?>"
         value="<?=htmlspecialchars($entity[$key])?>"
         <?=$attrs?>
  />
  <?if (!empty($message)) :?><span class="description"><?=$message?></span><?endif?>
</div>