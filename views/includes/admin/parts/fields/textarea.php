<?
  if (!isset($entity[$key])) {
    $entity[$key] = '';
  }
?>

<?if (is_array($entity[$key])) $entity[$key] = implode("\n", $entity[$key])?>
  
<div class="group">
  <label class="label" for="<?=str_replace('.', '_', $id)?>"><?=$label?></label>     
  <textarea id="<?=str_replace('.', '_', $id)?>"
            class="text-area <?=isset($params['class']) ? $params['class'] : ""?>"
            name="<?=$name?>"
            <?=$attrs?>
  ><?=htmlspecialchars($entity[$key])?></textarea>
  <?if ( ! empty($message)) :?><span class="description"><?=$message?></span><?endif?>              
</div>   