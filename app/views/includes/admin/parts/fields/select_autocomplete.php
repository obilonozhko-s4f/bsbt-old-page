<div class="group">
  <label for="<?=$id?>" class="label"><?=$label?></label>
  <div class="autocomplete-box">
    <select id="<?=$id?>"
            name="<?=$key?>"
            class="select comboboxUI <?=isset($params['class']) ? $params['class'] : ''?>">
      <option value=""></option>
      <?foreach ($params['options'] as $k => $v) :?>
        <option <?=$entity[$key] == $k ? 'selected="selected"' : ''?> value="<?=$k?>" <?=$attrs?> ><?=$v?></option>
      <?endforeach?>
    </select>
    <div class="clear"></div>
  </div>        
  <?if ( ! empty($message)) :?><span class="description"><?=$message?></span><?endif?>
</div>      