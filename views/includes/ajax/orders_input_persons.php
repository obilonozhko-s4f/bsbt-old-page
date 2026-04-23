<? if(!empty($obj)): ?>
  <label class="label" for="reservation_persons">
	Персон
	<span class="red">*</span>
	:
  </label>
  <select id="reservation_persons" class="required" name="persons" style="width: 200px;">
    <? $i=0; ?>
      <? while ($i++<$obj['persons_max']): ?>
        <option <? if(isset($enp) && $enp == $i): ?>selected="selected"<? endif; ?> value="<?=$i;?>"><?=$i;?></option>
      <? endwhile; ?>
  </select>
<? endif; ?>