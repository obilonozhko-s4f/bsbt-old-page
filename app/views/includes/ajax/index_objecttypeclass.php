<? if(!empty($ot)): ?>
  <label for="objecttypeclass"><?=lang('find_form.top_part.object_typeclass');?></label>
  <select name="objecttypeclass" class="objecttypeclass">
      <option value=""><?=lang('find_form.top_part.object_typeclass.select');?></option>
      <? foreach($ot as $o): ?>
        <? foreach($o['__children'] as $oo): ?>
          <option <? if(isset($otcId) && $otcId == $oo['id']): ?>selected="selected"<? endif; ?> value="<?=$oo['id'];?>"><?=$oo['name'];?></option>
        <? endforeach; ?>
      <? endforeach; ?>
  </select>
<? endif; ?>