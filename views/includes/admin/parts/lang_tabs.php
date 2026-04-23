<div id="tabs_<?=str_replace('.', '', $key);?>">
  <ul>
    <? foreach ($languages as $l): ?>
      <li><a href="#websiteTabs-<?=$l;?>-<?=str_replace('.', '', $key);?>"><?=$l;?></a></li>
    <? endforeach; ?>
  </ul>
  
  <? foreach ($languages as $l): ?>
    <div id="websiteTabs-<?=$l;?>-<?=str_replace('.', '', $key);?>">
    
    	<? if(strpos($key, '.') !== FALSE): ?>
    	  <? $langEntity = array(); ?>
    		<? $alias = strtok($key, '.'); ?>
    		<? if(isset($entity[$alias]['translations'])): ?>
          <? foreach ($entity[$alias]['translations'] as $lent): ?>
            <? if ($lent['language'] == $l): ?>
              <? $langEntity = array(); ?>
              <? foreach ($lent as $k => $v): ?>
                <?
                  $langEntity[$l . '_' . $alias . '.' . $k] = $v;
                ?>
                <?  ?>
              <? endforeach; ?>
              <? break; ?>
            <? endif; ?>
          <? endforeach; ?>
        <? endif; ?>
      <? else: ?>
        <? foreach ($entity['translations'] as $lent): ?>
          <? if ($lent['language'] == $l): ?>
            <? $langEntity = array(); ?>
            <? foreach ($lent as $k => $v): ?>
              <?
                if (is_array($v)) {
                  foreach ($v as $vk => $vv) {
                    $langEntity[$l . '_' . $k . '.' . $vk] = $vv;
                  }
                } else {
                 $langEntity[$l . '_' . $k] = $v;
                }
              ?>
              <?  ?>
            <? endforeach; ?>
            <? break; ?>
          <? endif; ?>
        <? endforeach; ?>      
      <? endif; ?>

      
      <?$this->view("includes/admin/parts/fields/" . $type, array('key' => $l . '_' . $key,
                                                                  'attrs' => $attrs,
      	 																											    'params' => $params,
                                                                  'entity' => $langEntity,
                                                                  'entityName' => $entityName,
                                                                  'name' => $l . '_' . $name,
                                                                  'id' => $l . '_' . $id,
                                                                  'label' => $label,
                                                                  'message' => $message))?>
    </div> <!-- #websiteTabs -->
  <? endforeach; ?>
</div> <!-- #tabs -->

<script type="text/javascript">
  $(document).ready(function() {
    $('#tabs_<?=str_replace('.', '', $key);?>').tabs();
  });
</script>

<div class="clear" style="height: 15px;"></div>