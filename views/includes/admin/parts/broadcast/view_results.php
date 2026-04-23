<div class="content" id="content">
  <h2 class="title"><?=$lang->line('admin.broadcast.view_results.title')?><a href="<?=site_url($backUrl)?>" class="link"><?=$lang->line('admin.add_edit_back')?></a></h2>
  <div class="inner">
    <table class="table" id="list_broadcast">
      <thead>
        <tr>
          <th class="first"><?=$lang->line('admin.broadcast.view_results.recipent')?></th>
          <th class=""><?=$lang->line('admin.broadcast.view_results.is_read')?></th>
          <? if(isset($entity['links'])): ?>
            <? foreach($entity['links'] as $key => $link): ?>
              <th class=""><a href="<?=$link['url']?>" target="_blank" title="<?=$link['url']?>"><?=$lang->line('admin.broadcast.view_results.link')?> <?=$key+1?></a></th>
            <? endforeach; ?>
          <? endif; ?>
        </tr>
      </thead>
      <tbody>
      
        <? foreach($report as $key => $row): ?>
          <tr class="<?=$key%2 == 0 ? 'odd' : 'even'?>">
            <td><?=$row['recipent']?></td>
            <td><?=$row['is_read'] ? lang('admin.yes') : lang('admin.no'); ?></td>
            <? if(isset($row['links'])): ?>
              <? foreach($row['links'] as $visited): ?>
                <td><?=$visited ? lang('admin.yes') : lang('admin.no')?></td>
              <? endforeach; ?>
            <? endif; ?>
          </tr>
        <? endforeach; ?>
      </tbody>
      <tfoot>
          <? $linksCount = isset($entity['links']) ? count($entity['links']) : 0;?>
          <tr><td colspan="<?=$linksCount + 2?>"><?=$lang->line('admin.total')?>: <?=count($report)?></td></tr>
      </tfoot>
    </table>
      
  </div>
<div class="clear"></div></div>