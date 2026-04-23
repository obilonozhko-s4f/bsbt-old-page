<?=$this->load->view('includes/admin/parts/simple_entity_list', array('entities' => $entities, 
                                                                      'pager' => $pager,
                                                                      'params' => $params,
                                                                      'entityName' => $entityName,
                                                                      'actions' => $actions))?>