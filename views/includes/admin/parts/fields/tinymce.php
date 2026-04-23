<?$keyWithGroup = isset($group) ? $group . "_" . $key : $key?>
<div class="group group-tinymce">    
  <label for="<?=$id?>" class="label"><?=$label?></label>
  <div class="editor-changer">
    <input value="editor" type="radio" class="editor" id="radio1<?=$keyWithGroup?>" name="radio<?=$keyWithGroup?>" checked="checked"/>
    <label class="exclude" for="radio1<?=$keyWithGroup?>"><?=lang("admin.editor")?></label>
    
    <input value="html" type="radio" class="html" id="radio2<?=$keyWithGroup?>" name="radio<?=$keyWithGroup?>" />
    <label class="exclude" for="radio2<?=$keyWithGroup?>"><?=lang("admin.html")?></label>
  </div>               
  
  <textarea class="text-area <?=isset($params['class']) ? $params['class'] : ""?>"
            name="<?=$name?>"
            id="<?=$id?>"
  ><?=htmlspecialchars($entity[$key])?></textarea>
  <?if ( ! empty($message)) :?><span class="description"><?=$message?></span><?endif?>
</div>    