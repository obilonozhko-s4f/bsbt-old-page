<?php
require_once APPPATH . 'controllers/admin/base/base_admin_controller.php';

/**
 * xAdmin tree controller.
 * @author Itirra - http://itirra.com
 */
abstract class Base_Admin_Tree_Controller extends Base_Admin_Controller {

  /** Parameter for list page .*/
  protected $listParams = array();

  /** AddEdit entity fields. */
  protected $fields = array();

  /** Name field. */
  protected $nameField = 'title';

  /** Url field. */
  protected $urlField = 'page_url';

  /** Is list sortable. */
  protected $isListSortable = false;

  /** Delete all url. */
  protected $deleteAllUrl;


  /**
   * Constructor.
   * @return
   */
  public function Base_Admin_Tree_Controller() {
    parent::Base_Admin_Controller();
    $this->nameField = ManagerHolder::get($this->entityName)->getNameField();
  }

  /**
   * @see Base_Admin_Controller::init()
   */
  protected function init() {
    parent::init();
    $this->actions["add_child"] = $this->adminBaseRoute . '/' . strtolower($this->entityName) . '/add_child';
    $this->deleteAllUrl = $this->adminBaseRoute . '/' . strtolower($this->entityName) . '/delete_all';
    $this->additionalActions[] = 'change_root_order';
  }

  /**
   * @see Base_Admin_Controller::index()
   */
  public function index($page = "page1") {
    $this->checkPermissions('_view');
    $entities = ManagerHolder::get($this->entityName)->getAsArray();
    $this->setViewParamsIndex($entities, true);
    $this->layout->view("tree_list");
  }

  /**
   * @see Base_Admin_Controller::setViewParamsIndex()
   */
  protected function setViewParamsIndex(&$entities, $hasSidebar) {
    $this->layout->set("processListUrl", $this->processListUrl);
    $this->layout->set("deleteAllUrl", $this->deleteAllUrl);
    $this->layout->set("isListSortable", $this->isListSortable);
    $this->layout->set("actions", $this->actions);
    $this->layout->set("additionalActions", $this->additionalActions);
    $this->layout->set("hasSidebar", $hasSidebar);
    $this->layout->set("menuItems", $this->menuItems);
    $this->layout->set("params", $this->listParams);
    $this->layout->set("entities", $entities);
    $this->layout->set("name_field", $this->nameField);
  }

  /**
   * Add child.
   * @param $entityId
   * @param $parentId
   */
  public function add_child($parentId, $entityId = null) {
    if ($entityId) {
      redirect($this->adminBaseRoute . '/' . strtolower($this->entityName) . '/add_edit/' . $entityId);
    }
    $this->add_edit(null, $parentId);
  }


  /**
   * Change Root order
   */
  public function change_root_order() {
    $roots = ManagerHolder::get($this->managerName)->getRoots();
    $this->layout->set('roots', $roots);
    $this->layout->set("backUrl", $this->adminBaseRoute . '/' .  strtolower($this->entityName));
    $this->layout->set("processLink", $this->adminBaseRoute . '/' .  strtolower($this->entityName) . '/change_root_order_process');
    $this->layout->view('change_root_order');
  }

  /**
   * Change Root order
   */
  public function change_root_order_process() {
    if (empty($_POST) || !isset($_POST['roots'])) show_404();
    $priority = 1;
    foreach ($_POST['roots'] as $rId) {
      ManagerHolder::get($this->entityName)->updateById($rId, 'priority', $priority);
      $root = ManagerHolder::get($this->entityName)->getById($rId);
      $decendants = ManagerHolder::get($this->entityName)->getDescendants($root['id'], null, FALSE, Doctrine::HYDRATE_ARRAY);
      if ($decendants) {
        foreach ($decendants as $dec) {
          ManagerHolder::get($this->entityName)->updateById($dec['id'], 'priority', $priority);
        }
      }
      $priority++;
    }
    set_flash_notice('admin.messages.' . strtolower($this->entityName) . '.edit');
    redirect($this->adminBaseRoute . '/' .  strtolower($this->entityUrlName));
  }



  /**
   * Add edit entity.
   * @param $entityId
   * @param $parentId
   */
  public function add_edit($entityId = null, $parentId = null) {
    $entity = new $this->entityName;
    $entity = $entity->toArray();
    if ($entityId) {
      $entity = ManagerHolder::get($this->entityName)->getById($entityId);
    }
    $options = array();
    $options[] = $this->lang->line("admin.add_edit." . strtolower($this->entityName) . ".default_parent_category_value");
    if ($entityId) {
      $treeViewArray = ManagerHolder::get($this->entityName)->getTreeAsViewArrayWhithout($entityId);
    } else {
      $treeViewArray = ManagerHolder::get($this->entityName)->getTreeAsViewArray();
    }


    if (!empty($treeViewArray)) {
      $options = $options + $treeViewArray;
    }

    $this->fields["parent_id"]['type'] = 'select';
    $this->fields["parent_id"]["options"] = $options;
    $entity["parent_id"] = "";
    if ($parentId) {
      $entity["parent_id"] = $parentId;
      if (isset($this->fields[$this->urlField]) && isset($this->fields[$this->urlField]['attrs']['startwith'])) {
        $parent = ManagerHolder::get($this->entityName)->getById($parentId);
        if (!empty($parent[$this->urlField])) {
          $this->fields[$this->urlField]['attrs']['startwith'] = $parent[$this->urlField];
          if (!$entityId) {
            $entity[$this->urlField]  = $parent[$this->urlField];
          }
        }
      }
    }

    if ($entityId) {
      $parent = ManagerHolder::get($this->entityName)->getParent($entity);
      if (isset($this->fields[$this->urlField]) && isset($this->fields[$this->urlField]['attrs']['startwith'])) {
        if (!empty($parent[$this->urlField])) {
          $this->fields[$this->urlField]['attrs']['startwith'] = $parent[$this->urlField];
        }
      }
      $entity["parent_id"] = $parent['id'];
      $children = ManagerHolder::get($this->entityName)->getChildren($entity);
      if ($children) {
        $childViewArray = array();
        foreach ($children as &$child) {
          $childViewArray[$child['id']] = $child[$this->nameField];
        }
        $this->fields["children"]['type'] = 'sortable';
        $this->fields["children"]['options'] = $childViewArray;
      }
    }

    $this->setAddEditDataAndShowView($entity);
  }

  /**
   * SetAddEditDataAndShowView.
   * Set all needed view data and show add_edit form.
   * @param object $entity
   */
  protected function setAddEditDataAndShowView($entity) {
    $this->layout->set("fields", $this->fields);
    $this->layout->set("entity", $entity);
    $this->layout->set("backUrl", $this->adminBaseRoute . '/' .  strtolower($this->entityName));
    $this->layout->set("processLink", $this->adminBaseRoute . '/' .  strtolower($this->entityName) . '/add_edit_process');
    $this->layout->view("add_edit_entity");
  }


  /**
   * Add edit process.
   */
  public function add_edit_process() {
    if (!empty($_POST["id"])) {
      $entity = ManagerHolder::get($this->entityName)->getById($_POST["id"]);
    }

    $entity = $this->createEntityPOST();
    $this->loadAndResizeImages($entity);


    if (empty($_POST["id"])) {
      // ADD
      try {
        $id = ManagerHolder::get($this->entityName)->insert($entity);
      } catch (Exception $e) {
        log_message('error', $e->getTraceAsString());
        set_flash_error($e->getMessage());
        $this->redirectToReffer();
      }

      if (empty($_POST["parent_id"])) {
        ManagerHolder::get($this->entityName)->getTree()->createRoot($entity);
        $entityArr = $entity->toArray();
        if (array_key_exists('priority', $entityArr)) {
          $maxPr = ManagerHolder::get($this->entityName)->getMaxPriority();
          ManagerHolder::get($this->entityName)->updateById($id, 'priority', $maxPr + 1);
        }
      } else {
        ManagerHolder::get($this->entityName)->insertLastChild($entity, $_POST["parent_id"]);
        // IF WE NEED TO REORDER ROOTS
        $entityArr = $entity->toArray();
        if (array_key_exists('priority', $entityArr)) {
          $rootId = $entity->getNode()->getRootValue();
          $root = ManagerHolder::get($this->entityName)->getById($rootId);
          ManagerHolder::get($this->entityName)->updateById($id, 'priority', $root['priority']);
        }
      }

      set_flash_notice('admin.messages.' . strtolower($this->entityName) . '.add');
      if (isset($_POST['save_and_return_to_list'])) {
        redirect($this->adminBaseRoute . '/' .  strtolower($this->entityUrlName));
      }
      $this->redirectToReffer($id);
    } else {
      // EDIT
      try {
        ManagerHolder::get($this->entityName)->update($entity);
      } catch (Exception $e) {
        log_message('error', $e->getTraceAsString());
        set_flash_error($e->getMessage());
        $this->redirectToReffer();
      }
      if (empty($_POST["parent_id"])) {
        if (!$entity->getNode()->isRoot()) {
          $entity->getNode()->makeRoot($entity->id);
          // IF WE NEED TO REORDER ROOTS
          $entityArr = $entity->toArray();
          if (array_key_exists('priority', $entityArr)) {
            $maxPr = ManagerHolder::get($this->entityName)->getMaxPriority();
            $maxPr = $maxPr + 1;
            ManagerHolder::get($this->entityName)->updateById($entity['id'], 'priority', $maxPr);
            $decendants = ManagerHolder::get($this->entityName)->getDescendants($entity['id'], null, FALSE, Doctrine::HYDRATE_ARRAY);
            if ($decendants) {
              foreach ($decendants as $dec) {
                ManagerHolder::get($this->entityName)->updateById($dec['id'], 'priority', $maxPr);
              }
            }
          }
        }
      } else {
        $parentId = null;
        $parent = $entity->getNode()->getParent();
        if ($parent) {
          $parentId = $parent->id;
        }
        if ($_POST["parent_id"] != $entity['id'] && $_POST["parent_id"] != $parentId) {
          ManagerHolder::get($this->entityName)->moveToLastChild($entity, $_POST["parent_id"]);

          // IF WE NEED TO REORDER ROOTS
          $entityArr = $entity->toArray();
          if (array_key_exists('priority', $entityArr)) {
            $newParent = ManagerHolder::get($this->entityName)->getById($_POST["parent_id"]);
            ManagerHolder::get($this->entityName)->updateById($entity['id'], 'priority', $newParent['priority']);
            $decendants = ManagerHolder::get($this->entityName)->getDescendants($entity['id'], null, FALSE, Doctrine::HYDRATE_ARRAY);
            if ($decendants) {
              foreach ($decendants as $dec) {
                ManagerHolder::get($this->entityName)->updateById($dec['id'], 'priority', $newParent['priority']);
              }
            }
          }

        }
      }
      if (isset($_POST["children"])) {
        foreach ($_POST["children"] as $chldId) {
          $chld = ManagerHolder::get($this->entityName)->getFullById($chldId);
          $chld->getNode()->moveAsLastChildOf($entity);
        }
      }
      set_flash_notice('admin.messages.' . strtolower($this->entityName) . '.edit');


      if (isset($_POST['save_and_return_to_list'])) {
        redirect($this->adminBaseRoute . '/' .  strtolower($this->entityUrlName));
      }
      $this->redirectToReffer($entity['id']);
    }
  }

  /**
   * Get Page Url By Id.
   */
  public function get_page_url() {
    if (isset($_POST["id"])) {
      if (isset($this->fields[$this->urlField])) {
        if ($_POST["id"] == 0) {
          $entity[$this->urlField] = $this->fields[$this->urlField]['attrs']['startwith'];
          $entity["level"] = 0;
        } else {
          $entity = ManagerHolder::get($this->entityName)->getById($_POST["id"], $this->urlField . ", level");
        }
        die("{\"error\": false, \"" . $this->urlField . "\": \"" . $entity[$this->urlField] . "\", \"level\": \"" . $entity["level"] . "\"}");
      } else {
        die("{\"error\": false}");
      }
    }
    die("{\"error\": true}");
  }

  // -----------------------------------------------------------------------------------------
  // ---------------------------- IMPORT/EXPORT METHODS --------------------------------------
  // -----------------------------------------------------------------------------------------

  /**
   * Import.
   */
  public function import() {
    $tmp = ManagerHolder::get($this->entityName)->fields;
    ManagerHolder::get($this->entityName)->fields = array();
    $res = array('parent_id' => array('type' => 'input_integer', 'class' => 'required'));
    foreach ($tmp as $k => $f) {
      $res[$k] = $f;
    }
    ManagerHolder::get($this->entityName)->fields = $res;
    $this->fields = ManagerHolder::get($this->entityName)->fields;
    parent::import();
  }



  /**
   * Import process.
   */
  public function import_process() {
    if (!$this->import) show_404();
    $requiredFields = ManagerHolder::get($this->entityName)->getRequiredFields();
    try {
      $rowNum = 1;
      $this->fileoperations->set_upload_lib_config_value("allowed_types", 'csv');
      if ($this->fileoperations->upload('import_file', TRUE, './web')) {
        $fileInfo = $this->fileoperations->file_info;

        // Remove excluded fields
        if (!empty($this->importExcludeFields)) {
          foreach ($this->importExcludeFields as $exField) {
            if (isset($this->fields[$exField])) {
              unset($this->fields[$exField]);
            }
          }
        }

        $importFilters = array();
        $postFields = array();

        foreach ($_POST as $k => $v) {
          if (strpos($k, 'importfilter_') !== FALSE) {
            $kk = str_replace('importfilter_', '', $k);
            if (strpos($kk, '_') !== FALSE) {
              $kkk = str_replace('_', '.', $kk);
              if (isset($this->filters[$kkk])) {
                $importFilters[$kkk] = array($v);
              } else {
                $importFilters[$kk] = $v;
              }
            } else {
              $importFilters[$kk] = $v;
            }
          } else {
            if ($v == 1) {
              if (strpos($k, '_') !== FALSE) {
                $kk = str_replace('_', '.', $k);
                if (isset($this->fields[$kk])) {
                  $postFields[$kk] = $this->fields[$kk];
                } else if (isset($this->fields[$k])) {
                  $postFields[$k] = $this->fields[$k];
                }
              } else {
                if (isset($this->fields[$k])) {
                  $postFields[$k] = $this->fields[$k];
                }
              }
            }
          }
        }

        // Add required fields
        foreach ($requiredFields['all'] as $addField) {
          if (!isset($postFields[$addField])) {
            $postFields[$addField] = $this->fields[$addField];
          }
        }

        $this->fields = $postFields;

        $ftrans = array();
        $field['name'] = 'id';
        $field['key'] = 'id';
        $field['val']['type'] = 'input_integer';
        $ftrans[] = $field;
        $field['name'] = trim(html_entity_decode(lang('admin.add_edit.' . strtolower($this->entityName) . '.parent_id')));
        $field['key'] = 'parent_id';
        $field['val']['type'] = 'input_integer';
        $ftrans[] = $field;
        foreach ($this->fields as $f => $val) {
          $field['name'] = trim(html_entity_decode(lang('admin.add_edit.' . strtolower($this->entityName) . '.' .  str_replace('.*', '', $f))));
          $field['key'] = trim(str_replace('.*', '', $f));
          $field['val'] = $val;
          $ftrans[] = $field;
        }

        $this->load->library('common/csv');

        $this->csv->readFile($fileInfo['file_path'] . $fileInfo['file_name']);


        $header = $this->csv->readRow();
        if (!$header || count($header) < 2) {
          set_flash_error('admin.import.error.wrong_file_format');
          $this->redirectToReffer();
        }
        if ($header[0] != 'id') {
          set_flash_error('admin.import.error.first_column_must_be_id');
          $this->redirectToReffer();
        }

        if ($header[1] != trim(html_entity_decode(lang('admin.add_edit.' . strtolower($this->entityName) . '.parent_id')))) {
          set_flash_error('admin.import.error.second_column_must_be_parent_id');
          $this->redirectToReffer();
        }

        foreach ($ftrans as $k => &$fs) {
          $index = array_key_by_value($fs['name'], $header);
          if ($index !== null) {
            $fs['index'] = $index;
          }
        }

        $addCount = 0;
        $editCount = 0;
        ManagerHolder::get($this->entityName)->startTransaction();
        while (($row = $this->csv->readRow()) !== FALSE) {

          // Check for empty row
          $columns = count($row);
          $emptyColumns = 0;
          foreach ($row as $k => &$v) {
            $v = trim($v);
            if (empty($v)) {
              $emptyColumns++;
            }
          }
          if ($columns == $emptyColumns) {
            continue;
          }

          $entity = array();
          foreach ($ftrans as $field) {
            if (isset($field['index']) && isset($row[$field['index']])) {
              if ($field['val']['type'] == 'input_double') {
                $row[$field['index']] = str_replace('.', ',', $row[$field['index']]);
              }
              if ($row[$field['index']] === '' && ($field['val']['type'] == 'input_double' || $field['val']['type'] == 'input_integer')) {
                $row[$field['index']] = null;
              }
              $entity[$field['key']] = $row[$field['index']];
            }
          }


          // Import Filters
          foreach ($importFilters as $fk => $fv) {
            $entity[$fk] = $fv;
          }

          // Pre process import row
          $entity = $this->preProcessImportRow($entity);

          if (count(array_keys($entity)) > 1) {
            if ($entity['id']) {
              // ---------- EDIT -----------------
              $parentId = $entity['parent_id'];
              unset($entity['parent_id']);
              ManagerHolder::get($this->entityName)->update($entity);
              $entity = ManagerHolder::get($this->entityName)->getFullById($entity['id']);
              // ParentID = ''
              if ($parentId == '') {
                if (!$entity->getNode()->isRoot()) {
                  // Need to make a root
                  $entity->getNode()->makeRoot($entity->id);
                  // IF WE NEED TO REORDER ROOTS
                  $entityArr = $entity->toArray();
                  if (array_key_exists('priority', $entityArr)) {
                    $maxPr = ManagerHolder::get($this->entityName)->getMaxPriority();
                    $maxPr = $maxPr + 1;
                    ManagerHolder::get($this->entityName)->updateById($entity['id'], 'priority', $maxPr);
                    $decendants = ManagerHolder::get($this->entityName)->getDescendants($entity['id'], null, FALSE, Doctrine::HYDRATE_ARRAY);
                    if ($decendants) {
                      foreach ($decendants as $dec) {
                        ManagerHolder::get($this->entityName)->updateById($dec['id'], 'priority', $maxPr);
                      }
                    }
                  }
                }
                // ParentID != ''
              } else {
                $curParent = $entity->getNode()->getParent();
                if ($entity->getNode()->isRoot() ||  $parentId != $curParent['id']) {
                  // Parent has changed, we need to move the node
                  ManagerHolder::get($this->entityName)->moveToLastChild($entity, $parentId);
                  // IF WE NEED TO REORDER ROOTS
                  $entityArr = $entity->toArray();
                  if (array_key_exists('priority', $entityArr)) {
                    $newParent = ManagerHolder::get($this->entityName)->getById($parentId);
                    ManagerHolder::get($this->entityName)->updateById($entity['id'], 'priority', $newParent['priority']);
                    $decendants = ManagerHolder::get($this->entityName)->getDescendants($entity['id'], null, FALSE, Doctrine::HYDRATE_ARRAY);
                    if ($decendants) {
                      foreach ($decendants as $dec) {
                        ManagerHolder::get($this->entityName)->updateById($dec['id'], 'priority', $newParent['priority']);
                      }
                    }
                  }
                }
              }
              $editCount++;
            } else {
              // ---------- ADD -----------------
              $parentId = $entity['parent_id'];
              unset($entity['parent_id']);
              $entity['id'] = ManagerHolder::get($this->entityName)->insert($entity);
              $entity = ManagerHolder::get($this->entityName)->getFullById($entity['id']);
              if ($parentId == '') {
                ManagerHolder::get($this->entityName)->getTree()->createRoot($entity);
                $entityArr = $entity->toArray();
                if (array_key_exists('priority', $entityArr)) {
                  $maxPr = ManagerHolder::get($this->entityName)->getMaxPriority();
                  ManagerHolder::get($this->entityName)->updateById($entity['id'], 'priority', $maxPr + 1);
                }
              } else {
                // Parent is not empty, we need to move the node
                ManagerHolder::get($this->entityName)->insertLastChild($entity, $parentId);

                // Update the entity to trigger Process dependencies
                ManagerHolder::get($this->entityName)->update($entity);

                // IF WE NEED TO REORDER ROOTS
                $entityArr = $entity->toArray();
                if (array_key_exists('priority', $entityArr)) {
                  $newParent = ManagerHolder::get($this->entityName)->getById($parentId);
                  ManagerHolder::get($this->entityName)->updateById($entity['id'], 'priority', $newParent['priority']);
                  $decendants = ManagerHolder::get($this->entityName)->getDescendants($entity['id'], null, FALSE, Doctrine::HYDRATE_ARRAY);
                  if ($decendants) {
                    foreach ($decendants as $dec) {
                      ManagerHolder::get($this->entityName)->updateById($dec['id'], 'priority', $newParent['priority']);
                    }
                  }
                }
              }
              $addCount++;
            }
          }
          $rowNum++;
        }
      }
      ManagerHolder::get($this->entityName)->commitTransaction();
    } catch (Exception $e) {
      ManagerHolder::get($this->entityName)->rollbackTransaction();
      $errors = array();
      if ($e->getCode() == DOCTRINE_DUPLICATE_ENTRY_EXCEPTION_CODE) {
        $errors[] = 'admin.import.' . strtolower($this->entityName) . '.error.duplicate';
      }
      if ($rowNum > 1) {
        $errors[] = kprintf(lang('admin.import.error.on_line'), array('line' => $rowNum));
      }
      $errors[] = $e->getMessage();
      set_flash_error($errors);

      log_message('error', $e->getMessage());
      $this->redirectToReffer();
    }

    set_flash_notice('admin.import.message.imported', array('added' => $addCount, 'edited' => $editCount));
    $this->redirectToReffer();
  }

  /**
   * Pre process import row
   */
  protected function preProcessImportRow($row) {
    return $row;
  }

 	/**
   * Export process.
   */
  public function export_process() {
    if (!$this->export) show_404();

    // Prepare POST and set the neede fields
    $this->preProcessPost();
    $fields = array('id', 'parent_id');
    foreach ($_POST as $k => $v) {
      if ($v == 1 && isset($this->fields[$k])) {
        if ($this->fields[$k]['type'] == 'multipleselect'
        || $this->fields[$k]['type']['type'] == 'image'
        || $this->fields[$k]['type']['type'] == 'video'
        || $this->fields[$k]['type']['type'] == 'image_list'
        || $this->fields[$k]['type']['type'] == 'file') {
          $k = str_replace("_id", "",  $k);
          $fields[] = $k . ".*";;
        } else {
          $fields[] = $k;
        }
      }
    }

    $entities = ManagerHolder::get($this->managerName)->getWithParentId('*');

    // Load CSV Library
    $this->load->library('common/csv');

    // Set headers
    $ftrans = array();
    foreach ($fields as $f) {
      if ($f == 'id') {
        $ftrans[] = 'id';
      }  else {
        $ftrans[] = html_entity_decode(lang('admin.add_edit.' . strtolower($this->entityName) . '.' .  str_replace('.*', '', $f)));
      }
    }
    $this->csv->addHeader($ftrans);

    if ($entities) {
      // Process Rows
      $rows = array();
      foreach ($entities as $e) {
        $row = array();
        foreach ($fields as $f) {
          if (array_key_exists($f, $e)) {
            $row[] = $e[$f];
          } else {
            if (strpos($f, '.*') !== FALSE) {
              $entsKey = str_replace('.*', '', $f);
              if (array_key_exists($entsKey, $e)) {
                $ents = get_array_vals_by_second_key($e[$entsKey], 'name');
                $row[] = implode(',', $ents);
              } else {
                show_error('Field ' . $entsKey . ' not found');
              }
            } else if (strpos($f, '.') !== FALSE) {
              $e = array_make_plain_with_dots($e);
              if (array_key_exists($f, $e)) {
                $row[] = $e[$f];
              } else {
                $row[] = '';
              }
            }
          }
        }
        $rows[] = $row;
      }
      $this->csv->addRows($rows);
    }

    // Send file to output
    $this->csv->flushFile(lang('admin.entity_list.' . strtolower($this->entityName) . '.list_title') . ' ' .  date('d.m.y') . ' ' . date('H.i') . '.csv');
    die();
  }

}