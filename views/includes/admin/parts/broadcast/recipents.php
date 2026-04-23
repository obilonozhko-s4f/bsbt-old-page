<? if(is_not_empty($entity['recipents'])): ?>
  <ul class="recipent-list" style="list-style: none; margin: 0px; padding: 0px;">
    <? foreach($entity['recipents'] as $rec): ?>
      <li class="recipent">
        <table cellpadding="0" cellspacing="0" border="0">
          <tr>
            <td><?=$rec['email']?></td>
            <td>
              <? if(!isset($readOnly)): ?>
                <span class="delete link" id="rec<?=$rec['id']?>" style="cursor: pointer;"><img src="<?=site_img('admin/icons/cross.png')?>"/></span>
              <? endif; ?>
            </td>
          </tr>
        </table>
      </li>
    <? endforeach; ?>
  </ul>
  <div class="clear"></div>
  <? if(!isset($readOnly)): ?>
    <script type="text/javascript">
      $(document).ready(function(){
        $('.recipent .delete').click(function(){
          $(this).parents('li').remove();
          var id = this.id.replace('rec', '');
          $('form').append('<input type="hidden" name="del_rec[]" value="' + id + '"/>');
        });
      });
    </script>
  <? endif; ?>
<? else: ?>
  <input id="<?=$entityName . '_' . $key?>" type="file" class="<?=isset($params['class'])?$params['class']:""?>" name="<?=$key?>" <?=$attrs?> />
  <table border="1" cellpadding="5" cellspacing="0" id="table" width="100%">
   <tr>
     <td>Email</td>
     <td>...</td>
   </tr>
   <tr>
     <td>...</td>
     <td>...</td>
   </tr>
  </table>
  <?if (!empty($message)) :?><span class="description"><?=$message?></span><?endif?>
<? endif; ?>