<div class="group">
  <label for="<?=$id?>" class="label"><?=$label?></label>
  <input id="<?=$id?>"
         type="text"
         class="text-field number <?=isset($params['class']) ? $params['class'] : ''?>"
         name="<?=$name?>"
         value="<?=$entity[$key]?>"
         <?=$attrs?>
  />
  <?if ( ! empty($message)) :?><span class="description"><?=$message?></span><?endif?>
</div>