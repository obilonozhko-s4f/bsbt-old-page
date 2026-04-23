<div class="group">
  <input type="checkbox"
         name="<?=$name?>"
         id="<?=$id?>"
         class="checkbox <?=isset($params['class']) ? $params['class'] : ''?>"
         <?=$entity[$key] ? 'checked="checked"' : ''?>
         value="1"
         <?=$attrs;?> /> - 
  <label for="<?=$id?>" class="cp"><?=$label?></label>
  <?if ( ! empty($message)) :?><span class="description"><?=$message?></span><?endif?> 
</div>      