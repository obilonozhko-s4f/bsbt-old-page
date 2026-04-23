<div class="group mh50">
  <label class="label" for="<?=$id?>"><?=$label?></label>
  <?if (count($params['options']) < 1) :?>
    <span><?=lang('admin.no_children')?></span>
  <?endif?>            
  <ul class="sortable">
    <?foreach ($params['options'] as $k => $v) :?>
      <li class="ui-state-default">
        <span class="ui-icon ui-icon-arrowthick-2-n-s">&nbsp;</span>
        <?=$v?>
        <input type="hidden" name="<?=$name?>[]" value="<?=$k?>"/> 
      </li>
    <?endforeach?>
  </ul>
  <script type="text/javascript">
    $(function() {
      $(".sortable").sortable();
      $(".sortable").disableSelection();
    });
  </script>
</div>           