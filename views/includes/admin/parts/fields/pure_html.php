<div class="group">
  <label class="label" for="<?=$id?>"><?=$label?></label>     
  <textarea id="<?=$id?>"
            name="<?=$name?>"
            class="text-area pure-html <?=isset($params['class']) ? $params['class'] : ''?>"
            <?=$attrs?>
  ><?=htmlspecialchars($entity[$key])?></textarea>
  <?if ( ! empty($message)) :?><span class="description"><?=$message?></span><?endif?>              
</div>   