<div class="content default-box">
  <h2 class="title">
    <span class="fl"><?=lang('admin.import.' . $entityName . ".form_title");?></span>
    <? if ($export) :?>
     <ul class="act-list">
        <li>
          <a href="<?=site_url($exportUrl);?>"><?=lang('admin.export');?></a>
        </li>
      </ul>    
    <? endif;?>       
    <a class="link" href="<?=site_url($backUrl);?>"><?=lang('admin.add_edit_back');?></a>
    <?if ($backUrl) :?>
      <a class="link" href="<?=site_url($backUrl);?>"><?=lang('admin.add_edit_back');?></a>
    <?endif;?>
  </h2>
  <?=html_flash_message();?>
  
  <div class="inner export">
    <form action="<?=site_url($processLink);?>" method="post" class="form validate" autocomplete="off" enctype="multipart/form-data">
      <? if(!empty($importFilters)): ?>
        <div>
          <h3 style="color: #F00;"><?=lang('admin.import.fields.filters');?>:</h3>
          <ul>
            <? foreach($importFilters as $k => $v): ?>
              <li>
                <b><?=lang('admin.add_edit.' . $entityName . '.' . $k);?></b>&nbsp;:&nbsp;&nbsp;<span><?=$v['str'];?></span>
                <input type="hidden" name="importfilter_<?=$k?>" value="<?=$v['val'];?>" />
              </li>
            <? endforeach; ?> 
          </ul>
          <p><?=lang('admin.import.fields.filters.description');?></p>
        </div>
      <? endif; ?>
          
      <div class="fields">
        <h2><?=lang('admin.import.fields');?></h2>
        <p><?=lang('admin.import.fields.description');?></p>
        <div class="checkboxes">
          <div class="ex-box">
            <input class="checkbox" type="checkbox" name="id" id="<?=$entityName?>_id" checked="checked" value="1" disabled="disabled"/>
            <label class="cp" for="<?=$entityName?>_id">id</label>
          </div>
          <? foreach ($fields as $k => $params): ?>
            <div class="ex-box">
              <input class="checkbox" type="checkbox" name="<?=$k?>" id="<?=$entityName?>_<?=$k?>" value="1" <? if(!in_array($k, array_keys($importFilters))): ?>checked="checked"<? endif; ?> <? if(in_array($k, array_keys($importFilters)) || in_array($k, $required['simple'])): ?>disabled="disabled"<? endif; ?>/>
              <label class="cp" for="<?=$entityName?>_<?=$k?>"><?=lang('admin.add_edit.' . $entityName . '.' . $k);?></label>
            </div>
          <? endforeach; ?>
          <div class="clear"></div>
        </div>
        <ul class="e-list">
          <li><a href="#" id="select_all"><?=lang('admin.import.select_all');?></a></li>
          <li class="last"><a href="#" id="deselect_all"><?=lang('admin.import.deselect_all');?></a></li>
        </ul>
        <div class="clear"></div>
      </div>
      
      <div class="fields">
        <h2><?=lang('admin.import.file_preview');?></h2>
        <p><?=lang('admin.import.file_preview.description');?></p>      
        <table border="1" cellpadding="5" cellspacing="0" id="table" width="100%">
          <tr>
          </tr>
          <tr>
          </tr>          
        </table>
        <div class="clear"></div>
      </div>      
      
      <? if (!empty($required['thatDepend'])): ?>
        <div class="fields">
          <h2><?=lang('admin.import.depend.fields');?></h2>
          <p><?=lang('admin.import.depend.description');?></p>
          <ul>
            <? foreach ($required['thatDepend'] as $k): ?>
              <li><?=lang('admin.add_edit.' . $entityName . '.' . $k);?> (<?=lang('admin.add_edit.' . $entityName . '.' . $k . '.description');?>)</li>
            <? endforeach; ?>
          </ul>
        </div>
      <? endif; ?>
      
      <? if (!empty($required['thatTotallyDepend'])): ?>
        <div class="fields">
          <h2><?=lang('admin.import.totally_depend.fields');?></h2>
          <p><?=lang('admin.import.totally_depend.description');?></p>
          <ul>
            <? foreach ($required['thatTotallyDepend'] as $k): ?>
              <li><?=lang('admin.add_edit.' . $entityName . '.' . $k);?> (<?=lang('admin.add_edit.' . $entityName . '.' . $k . '.description');?>)</li>
            <? endforeach; ?>
          </ul>
        </div>
      <? endif; ?>
      
      <? if (in_array('multipleselect', get_array_vals_by_second_key($fields, 'type'))):  ?>
        <div class="fields">
          <h2><?=lang('admin.import.relation.fields');?></h2>
          <p><?=lang('admin.import.relation.description');?></p>      
        <? foreach ($fields as $k => $params): ?>
            <ul>
              <? if($params['type'] == 'multipleselect'): ?>
                <li><?=lang('admin.add_edit.' . $entityName . '.' . $k);?> (<?=lang('admin.add_edit.' . $entityName . '.' . $k . '.description');?>)</li>
              <? endif; ?>
            </ul>      
        <? endforeach; ?>
        </div>
      <? endif; ?>      

      
      <div class="clear"></div>
      <div class="import">
        <h2><?=lang('admin.import.import');?></h2>
        <p><?=lang('admin.import.import.description');?></p>
        <div class="input-row">
          <input class="input-file" type="file" name="import_file"/>
          <button class="button" type="submit" name="save" value="1">
            <img src="<?=site_img("admin/icons/tick.png")?>" alt="<?=lang('admin.import');?>"/><?=lang('admin.import');?>
          </button>
        </div>
      </div>    
    <div class="clear"></div>
    </form>
  </div>
</div>
<div class="clear"></div>

<script type="text/javascript">
  $(document).ready(function() {
  
    $('#select_all').click(function() {
      $('.checkboxes .checkbox:not(:disabled)').attr('checked', 'checked');
      refreshTable();    
      return false;
    });

    $('#deselect_all').click(function() {
      $('.checkboxes .checkbox:not(:disabled)').removeAttr('checked');
      refreshTable();
      return false;
    });    


    $('.checkboxes .checkbox:not(:disabled)').change(function(){
      refreshTable();
    })
    

    function refreshTable() { 
      clearTable();
      drawTable(); 
    };
    
    function clearTable() {
      $('#table').find('tr:first').empty();
      $('#table').find('tr:last').empty();
    }
        
    function tableAddRow(value) {
      $('#table').find('tr:first').append('<td>' + value + '</td>');
      $('#table').find('tr:last').append('<td>...</td>');
    }

    function drawTable() {
      $('.checkboxes .checkbox:checked').each(function(){
        tableAddRow($(this).next('label').html());  
      });      
    }


    drawTable();
  });
</script>
