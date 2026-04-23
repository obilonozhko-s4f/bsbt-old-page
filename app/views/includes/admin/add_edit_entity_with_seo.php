<?=$this->load->view('includes/admin/parts/simple_add_edit_entity', array('fields' => $fields, 
                                                                          'entity' => $entity, 
                                                                          'backUrl' => $backUrl, 
                                                                          'entityName' => $entityName,
                                                                          'processLink' => $processLink))?>



<? if (isset($seoEntity)): ?>

  <? if ($notSaved): ?>
    <div class="flash">
      <div class="message warning">
        <p>Headers Not Saved</p>
      </div>      
    </div>
  <? endif; ?>

  <?=$this->load->view('includes/admin/parts/simple_add_edit_entity', array('fields' => $seoFields, 
                                                                            'entity' => $seoEntity, 
                                                                            'backUrl' => null, 
                                                                            'entityName' => 'header',
                                                                            'processLink' => 'xadmin/header/add_edit_process',
                                                                            'backUrl' => $backUrl))?>

<? endif; ?>