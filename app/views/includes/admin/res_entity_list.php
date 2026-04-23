<?=$this->load->view('includes/admin/parts/res_simple_entity_list', array('entities' => $entities, 
                                                                      'pager' => $pager,
                                                                      'params' => $params,
                                                                      'entityName' => $entityName,
                                                                      'actions' => $actions,
                                                                      'priceArr' => $priceArr))?>