<div class="group">
  <label for="<?=$id?>" class="label"><?=$label?></label>
  <?php
    // для поля total_cost не вешаем класс digits,
    // чтобы можно было вводить копейки (1552.50 / 1552,50)
    $extraClass = isset($params['class']) ? $params['class'] : '';
    $useDigits  = ($name !== 'total_cost'); // у всех, кроме total_cost
  ?>
  <input id="<?=$id?>"
         type="text"
         class="text-field <?= $useDigits ? 'digits ' : '' ?><?=$extraClass?>"
         name="<?=$name?>"
         value="<?=$entity[$key]?>"
         <?=$attrs?>
  />
  <?php if (!empty($message)) : ?>
    <span class="description"><?=$message?></span>
  <?php endif; ?>
</div>
