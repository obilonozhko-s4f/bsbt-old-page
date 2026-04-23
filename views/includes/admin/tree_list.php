<?=$this->load->view('includes/admin/parts/simple_tree_list', array('entities' => $entities, 
                                                                  	'params' => $params, 
                                                                  	'entityName' => $entityName,
                                                                    'actions' => $actions, 
                                                                    'maxLevel' => isset($maxLevel)?$maxLevel:null,
                                                                    'additionalActions' => $additionalActions));?>
