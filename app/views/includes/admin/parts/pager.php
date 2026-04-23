<?if($pager->haveToPaginate()):?>
	<?
	  // Internal Pager stuff!
	  $pagerRange = $pager->getRange('Sliding', array('chunk' => 10));
	  $pages = $pagerRange->rangeAroundPage();
	?>
	<div class="pagination">
	  <?if($pager->getPage() != $pager->getFirstPage()): ?>
    
	  	<a class="prev_page" href="<?=pager_url($pager->getFirstPage());?>">&laquo;&laquo; <?=lang('admin.pager.first');?></a>
	    <a class="prev_page" href="<?=pager_url($pager->getPreviousPage());?>">&laquo; <?=lang('admin.pager.prev');?></a>
	  <?else:?>
	  	<span class="disabled prev_page">&laquo;&laquo; <?=lang('admin.pager.first');?></span>
	    <span class="disabled prev_page">&laquo; <?=lang('admin.pager.prev');?></span>
	  <?endif; ?>
	  
	  <!-- Pages links -->  
    <?foreach($pages as $page): ?>
      <?if($page == $pager->getPage()):?>
          <span class="current"><?=$page?></span>
        <?else:?>
          <a href="<?=pager_url($page);?>"><?=$page?></a>
      <?endif; ?>
    <?endforeach;?>
    
    <!-- The "next page" and "last page" links -->  
    <?if($pager->getPage() != $pager->getLastPage()): ?>
      <a class="next_page" href="<?=pager_url($pager->getNextPage());?>"><?=lang('admin.pager.next');?> &raquo;</a>
      <a class="next_page" href="<?=pager_url($pager->getLastPage());?>"><?=lang('admin.pager.last');?> &raquo;&raquo;</a>
    <?else:?>
      <span class="disabled next_page"><?=lang('admin.pager.next');?> &raquo;</span>
      <span class="disabled next_page"><?=lang('admin.pager.last');?> &raquo;&raquo;</span>
    <?endif; ?>  
	
  </div>
<?endif; ?>