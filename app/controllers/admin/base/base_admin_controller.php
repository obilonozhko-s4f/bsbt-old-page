<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'logic/common/ManagerHolder.php';

/**
 * Base admin controller.
 * @author Itirra - http://itirra.com
 *
 * @property CI_Loader $load
 * @property Layout $layout
 * @property CI_Session $session
 * @property Fileoperations $fileoperations
 */
abstract class Base_Admin_Controller extends Controller {

  /** Session keys. */
  const SAVED_URL_SESSION_KEY = "SAVED_URL_SESSION_KEY";
  const LOGGED_IN_ADMIN_SESSION_KEY = "LOGGED_IN_ADMIN_SESSION_KEY";
  const POST_SESSION_KEY = "POST_SESSION_KEY";
  const FOLDER_SESSION_KEY = "FOLDER_SESSION_KEY";
  const ENTITY_LIST_STRING_LIMIT = 255;

  /** Allowed file types. | is the delimeter. */
  protected $allowedFileTypes = "zip|rar";

  /** Allowed video types. | is the delimeter. */
  protected $allowedVideoTypes = "flv";

  /** Admin logged in entity. */
  protected $loggedInAdmin;

  /** Entity names. */
  protected $entityName;
  protected $entityUrlName;

  /** Manager name. */
  protected $managerName;

  /** Entity upload directory */
  protected $entityUploadDir;

  /** Per Page. */
  protected $perPage = 25;

  /** Menu Items List. */
  protected $menuItems = array('admin');

  /** List view file. */
  protected $listView = 'entity_list';

  /** Entity view file. */
  protected $itemView = 'add_edit_entity';

  /** Actions. */
  protected $actions;

  /** Import */
  protected $import = FALSE;

  /** Import Exclude Fields */
  protected $importExcludeFields = array();

  /** Export */
  protected $export = FALSE;

  /** Export Exclude Fields */
  protected $exportExcludeFields = array();

  /** Print */
  protected $print = FALSE;

  /** Is delete all action allowed */
  protected $isDeleteAllAllowed = TRUE;

  /** Is entity list sortable. */
  protected $isListSortable = FALSE;

  /** Process list action url */
  protected $processListUrl;

  /** Show numbers in list */
  protected $showNumbers = TRUE;

  /** Maximum allowed entities */
  protected $maxLines = null;

  /** Additional $_POST parameters. Input type="hidden" will be generated for each. */
  protected $additionalPostParams = array();

  /** An array of properties to ignore creating links in list view. */
  protected $listViewIgnoreLinks = array();

  /** An array of properties to rewrite creating links in list view. */
  protected $listViewLinksRewrite = array();

  /** An array of properties to rewrite in list view. */
  protected $listViewValuesRewrite = array();

  /** Additional Actions. */
  protected $additionalActions = array();

  /** Admin base route. */
  protected $adminBaseRoute = "xadmin";

  /** Filter. Row example: "column_name" => default_value. Default value may be null. */
  protected $filters = array();

  /** Batch update fields */
  protected $batchUpdateFields = array();

  /** Date Filters. Row example: array("created_at"). */
  protected $dateFilters = array();

  /** Order field. */
  protected $orderField = "position";

  /** AddEdit Entity Fields. */
  protected $fields = array();

  /** Parameter for list page .*/
  protected $listParams = array();

  /** Default order by .*/
  protected $defOrderBy;

  /** Field to search in. */
  protected $searchParams = array();

  /** Class Prefix to remove to get Entity name. */
  protected $classPrefix = "xAdmin_";

  /** ExcludeIds. */
  protected $excludeIds = array();

  /** Extra where. */
  protected $extraWhere = array();

  /**
   * Constructor.
   * Put loads here. Everything else should be in init().
   */
  public function Base_Admin_Controller() {
    parent::Controller();

    Header('Content-Type: text/html; charset=UTF-8');
    $this->config->load('thumbs');
    $this->config->load('admin', TRUE);
    $this->config->load('app_constants');

    // Language
    $this->lang->load('admin', $this->config->item('language'));
    $this->lang->load('message_properties', $this->config->item('language'));
    $this->lang->load('enum', $this->config->item('language'));

    // Helpers
    $this->load->helper('text');
    $this->load->helper('common/itirra_commons');
    $this->load->helper('common/itirra_date');

    $this->load->helper('common/itirra_messages');
    $this->load->helper('common/itirra_sortby');
    $this->load->helper('common/itirra_language');
    $this->load->helper('common/itirra_pager');
    $this->load->helper('common/itirra_resources');
    $this->load->helper('common/itirra_validation');
    $this->load->helper('url');

    // Libraries
    $this->load->library('Session');
    $this->load->library('common/Layout');
    $this->load->library('common/Fileoperations');
    $this->load->library('common/DoctrineLoader');

    // Fix dots in GET
    fix_dots_in_get();

    // Fix dots in POST
    fix_dots_in_post();

    $this->init();
  }

  /**
   * Get fields from manager.
   */
  protected function getFieldsFromManager() {
    $this->fields = ManagerHolder::get($this->managerName)->fields;
    $this->listParams = ManagerHolder::get($this->managerName)->listParams;
    $this->defOrderBy = ManagerHolder::get($this->managerName)->getOrderBy();
  }

  /**
   * Init.
   */
  protected function init() {
    ManagerHolder::setMode(ManagerHolder::MODE_ADMIN);

    $this->entityName = str_replace($this->classPrefix, "", get_class($this));

    $this->entityUrlName = $this->entityName;
    $this->managerName = str_replace("_", "", $this->entityName);

    // Uset folder for image
    $folder = $this->session->userdata(self::FOLDER_SESSION_KEY);
    if (strtolower($this->entityName) != 'resource') {
      $this->session->set_userdata(self::FOLDER_SESSION_KEY, strtolower($this->entityName));
    }

    $menuItems = $this->config->item("menu_items", "admin");
    if (is_array($menuItems) && !empty($menuItems)) $this->menuItems = $menuItems;

    $baseRoute = $this->config->item("base_route", "admin");
    if ($baseRoute !== FALSE) {
      $this->adminBaseRoute = $baseRoute;
    }
    $actions["add"] = $this->adminBaseRoute . '/' . strtolower($this->entityUrlName) . '/add_edit';
    $actions["edit"] = $this->adminBaseRoute . '/' . strtolower($this->entityUrlName) . '/add_edit';
    $actions["delete"] = $this->adminBaseRoute . '/' . strtolower($this->entityUrlName) . '/delete';
    $this->actions = $actions;

    if ($this->import) {
      $this->additionalActions[] = "import";
    }
    if ($this->export) {
      $this->additionalActions[] = "export";
    }

    $this->processListUrl = $this->adminBaseRoute . '/' . strtolower($this->entityUrlName) . '/process_list';

    $admin = $this->session->userdata(self::LOGGED_IN_ADMIN_SESSION_KEY);
    $segment = 2;
    if (empty($this->adminBaseRoute)) $segment = 1;
    if (!$admin
        && $this->uri->segment($segment) != "login"
        && $this->uri->segment($segment) != "forgot_password"
        && $this->uri->segment($segment > 1?$segment-1:1) != "email-image"
        && $this->uri->segment($segment>1?$segment-1:1) != "go-to-url"
        && $this->uri->segment($segment>1?$segment-1:1) != "unsubscribe") {
      if (uri_string() != '/' . $this->adminBaseRoute) {
        $this->session->set_userdata(self::SAVED_URL_SESSION_KEY, current_url());
        set_flash_warning('admin.need_to_login_message');
      }
      redirect($this->adminBaseRoute . '/login');
    }


    if (!empty($this->searchParams)) {
      $this->layout->set("searchParams", $this->searchParams);
    }

    $this->loggedInAdmin = $admin;
    if (isset($_GET['per_page'])) {
      $this->perPage = $_GET['per_page'];
    }
    
    foreach ($this->additionalActions as $addAct) {
      if($addAct == "printlist"){
        $pageUrl = uri_string();
        $pageUrlSegments = explode('/', trim($pageUrl, '/'));
        $lastSegment = $pageUrlSegments[count($pageUrlSegments) - 1];
        if (pager_has_prefix($lastSegment)) {
          $page = pager_remove_prefix($lastSegment);
          if(preg_match("/^[0-9]+/",$page)){
            unset($pageUrlSegments[count($pageUrlSegments) - 1]);
            $pageUrl = implode('/', $pageUrlSegments);
          } else {
            show_404();
          }
        } else {
          $page = 1;
        }
        $this->layout->set('pager_page', $page);
      }
    }
    
    $this->initBatchUpdateFields();

    $header["title"] = $this->lang->line("admin.title");
    $this->getFieldsFromManager();
    $this->layout->setLayout("admin");
    $this->layout->setModule("admin");
    $this->layout->set("lang", $this->lang);
    $this->layout->set("loggedInAdmin", $admin);
    $this->layout->set("entityName", strtolower($this->entityName));
    $this->layout->set("entityUrlName", strtolower($this->entityUrlName));
    $this->layout->set("header", $header);
    $this->layout->set("adminBaseRoute", $this->adminBaseRoute);
    $this->layout->set("showNumbers", $this->showNumbers);
  }

  /**
   * Index.
   */
  public function index($page = "") {
    if (empty($page)) {
      $page = pager_add_prefix(1);
    }
    $this->checkPermissions('_view');
    $manager = ManagerHolder::get($this->managerName);
    $this->setSearchAndOrderBy($manager);
    $this->setFilter();
    $where = $this->filters;
    if (!empty($this->extraWhere)) {
      $where = array_merge($where, $this->extraWhere);
    }

    if ($this->perPage != 'all') {
      $res = $manager->getAllWhereWithPager($where, pager_remove_prefix($page), $this->perPage, $this->preProcessParams(), $this->excludeIds);

      // If search and found one - redirect to edit
//      if (count($res->data) == 1 && isset($_GET['q']) && isset($this->actions['edit'])) {
//        redirect($this->adminBaseRoute . '/' . strtolower($this->entityUrlName) . '/add_edit/' . $res->data[0]['id']);
//      }
    } else {
      $res = new stdClass();
      $res->data = $manager->getAllWhere($where, $this->preProcessParams());
      $res->pager = null;
    }
    $this->setViewParamsIndex($res->data, $res->pager, TRUE);
    $this->layout->view($this->listView);
  }

  /**
   * setViewParamsIndex
   * @param  $entities
   * @param  $pager
   * @param  $hasSidebar
   */
  protected function setViewParamsIndex(&$entities, &$pager, $hasSidebar) {
    $this->layout->set("processListUrl", $this->processListUrl);
    $this->layout->set("isDeleteAllAllowed", $this->isDeleteAllAllowed);
    $this->layout->set("isListSortable", $this->isListSortable);
    $this->layout->set("maxLines", $this->maxLines);
    $this->layout->set("export", $this->export);
    $this->layout->set("import", $this->import);
    $this->layout->set("actions", $this->actions);
    $this->layout->set("hasSidebar", $hasSidebar);
    $this->layout->set("menuItems", $this->menuItems);
    $this->layout->set("defOrderBy", $this->defOrderBy);
    $this->layout->set("params", $this->listParams);
    $this->layout->set("entities", $entities);
    $this->layout->set("pager", $pager);
    $this->layout->set("listViewIgnoreLinks", $this->listViewIgnoreLinks);
    $this->layout->set("listViewLinksRewrite", $this->listViewLinksRewrite);
    $this->layout->set("listViewValuesRewrite", $this->listViewValuesRewrite);
    $this->layout->set("additionalPostParams", $this->additionalPostParams);
    $this->layout->set("additionalActions", $this->additionalActions);
    $this->layout->set("batchUpdateFields", $this->batchUpdateFields);
  }


  /**
   * initBatchUpdateFields
   */
  protected function initBatchUpdateFields() {
    $this->batchUpdateFields = $this->filters;
  }


  /**
   * setSearch
   * @param $manager
   */
  protected function setSearchAndOrderBy(&$manager){
    if (!empty($_GET)) {
      // Order By
      $orderBy = '';
      foreach ($_GET as $k => $v) {
        if (strpos($k, 'sort_') === 0) {
          $key = str_replace('sort_', '', $k);
          $orderBy .= $key . ' ' . strtoupper($v) . ', ';
        }
      }
      if (!empty($orderBy)) {
        $orderBy = substr($orderBy, 0, strlen($orderBy) - 2);
        $manager->setOrderBy($orderBy);
      }

      // Search
      if (isset($_GET['q'])) {
        $search["search_string"] = $_GET['q'];
        if (!isset($search["search_type"])) {
          $search["search_type"] = 'contains';
        }
        if (!isset($search["search_in"])) {
          $search["search_in"] = implode(',', $this->searchParams);
        }
        $manager->setSearch($search["search_string"], $search["search_in"], $search["search_type"]);
        $this->layout->set("search", $search);
      }
    }
  }

  /**
   * Search action for ajax autocomplete in admin_entity_list.
   */
  public function search_autocomplete() {
    if (!isset($_GET['query'])) die();
    $result = '{"query":"%s","suggestions":[%s],"data":[%s]}';
    $suggestions = array();
    $suggestionsData = array();

    $manager = ManagerHolder::get($this->entityName);
    $search = array();
    $search["search_string"] = $_GET['query'];
    if (!isset($search["search_type"])) {
      $search["search_type"] = 'contains';
    }
    if (!isset($search["search_in"])) {
      $search["search_in"] = implode(',', $this->searchParams);
    }
    $manager->setSearch($search["search_string"], $search["search_in"], $search["search_type"]);

    $entities = $manager->getAll(implode(',', $this->searchParams), 5);

    foreach ($entities as $e) {
      $e = array_make_plain_with_dots($e);
      $suggestionData = "";
      foreach ($this->searchParams as $i => $sp) {
        if ($i == 0) {
          $suggestion = $e[$sp];
        } else {
          $suggestionData .= $e[$sp] . ', ';
        }
      }
      $suggestionData = trim($suggestionData);
      $suggestionData = rtrim($suggestionData, ',');
      $suggestions[] = json_encode($suggestion);
      $suggestionsData[] = json_encode($suggestionData);
    }
    $result = sprintf($result, $_GET["query"], implode(',', $suggestions), implode(',', $suggestionsData));
    die($result);
  }

  /**
   * Pre process params.
   * @return string
   */
  protected function preProcessParams($subkey = null) {
    $params = ManagerHolder::get($this->managerName)->getPk();
    if (is_array($params)) {
      $params = implode(', ', $params);
    }
    $params .= ', ';
    foreach ($this->listParams as $param) {
      $append = '';
      if (is_array($param)) {
        $param = array_make_plain_with_dots($param);
        $ks = array_keys($param);
        $vs = array_values($param);
        $append = $ks[0] . '.' . $vs[0];
      } else {
        $append = $param;
      }
      $params .= $append . ', ';
    }
    $params = rtrim($params, ', ');
    return $params;
  }

  /**
   * Add edit entity.
   * @param integer $entityId
   */
  public function add_edit($entityId = null){
    if ($entityId) {
      $this->checkPermissions("_edit");
    } else {
      $this->checkPermissions("_add");
    }
    $entity = $this->createEntityId($entityId);

    if (!$entityId) {
      $post = $this->getPost();
      if (!empty($post)) {
        $entity = array_merge($entity, $post);
      }
      if (!empty($_GET)) {
        foreach ($_GET as $k => $v) {
          if (array_key_exists($k, $entity)) {
            $entity[$k] = $v;
          } else {
            if (isset($this->filters[$k])) {
              $kar = explode('.', $k);
              $ent = new $this->entityName;
              $refEntityName = $ent[$kar[0]]->getTable()->getOption('name');
              $field = $kar[1];
              if (count($kar) > 2) {
                $field = '';
                foreach (array_slice($kar, 1, count($kar) - 1) as $kv) {
                  $field .= $kv . '.';
                }
                $field = rtrim($field, '.');
              }
              $refEnt = ManagerHolder::get($refEntityName)->getOneWhere(array($field => $v));
              
              $fks = ManagerHolder::get($this->managerName)->getForeignKeys();
              if (isset($fks[$kar[0]])) {
                if ($fks[$kar[0]]['type'] == 0) {
                  $entity[$kar[0]] = $refEnt;
                }
                if ($fks[$kar[0]]['type'] == 1) {
                  $entity[$kar[0]][] = $refEnt;
                }
              } else {
                $entity[$kar[0]][] = $refEnt;
              }
            }
          }
        }
      }
    }
    $this->setAddEditDataAndShowView($entity);
  }


  /**
   * Add Edit process.
   */
  public function add_edit_process() {
    $entity = $this->createEntityPOST();
    $this->loadAndResizeImages($entity);
    $this->loadFiles($entity);
    $this->loadVideos($entity);
    $entity = $this->addEditEntity($entity);
    if (isset($_POST['save_and_return_to_list'])) {
      redirect($this->adminBaseRoute . '/' .  strtolower($this->entityUrlName) . get_get_params());
    } else if (isset($_POST['save_and_add_new'])) {
      redirect($this->adminBaseRoute . '/' . strtolower($this->entityUrlName) . '/add_edit' . get_get_params());
    } else if (isset($_POST['save_and_next'])) {
      $this->next($entity['id']);
    }
    $this->redirectToReffer($entity['id']);
  }

  /**
   * SetAddEditDataAndShowView.
   * Set all needed view data and show add_edit form.
   * @param object $entity
   */
  protected function setAddEditDataAndShowView($entity) {
    $this->preProcessFields($entity);
    $this->layout->set("fields", $this->fields);
    $this->layout->set("backUrl", $this->adminBaseRoute . '/' .  strtolower($this->entityUrlName . get_get_params()));
    if (!empty($entity['id'])) {
      $this->layout->set("nextUrl", $this->adminBaseRoute . '/' .  strtolower($this->entityUrlName) . '/next/' . $entity['id'] . '/' . get_get_params());
      $this->layout->set("prevUrl", $this->adminBaseRoute . '/' .  strtolower($this->entityUrlName) . '/prev/' . $entity['id'] . '/' . get_get_params());
    }
    $this->layout->set("processLink", $this->adminBaseRoute . '/' .  strtolower($this->entityUrlName) . '/add_edit_process');
    $this->preAddEditView($entity);
    $this->layout->set("entity", $entity);
    $this->layout->set("actions", $this->actions);
    $this->layout->set("print", $this->print);
    $this->layout->view($this->itemView);
  }



  /**
   * preProcessFields
   * the method is called before passing
   * fields to the layout
   */
  protected function preProcessFields(&$entity) {
    foreach ($this->fields as $fieldName => &$field) {
      if (isset($field['type'])){
        switch ($field['type']) {
          case 'select':
            if (isset($field['enum'])) {
              // Setting enum options
              $field['options'] = ManagerHolder::get($this->managerName)->getEnumAsViewArray($fieldName);
            }
            if (isset($field['relation'])) {
              // Setting fk options
              $nameField = isset($field['relation']['name_field']) ? $field['relation']['name_field'] : ManagerHolder::get($field['relation']['entity_name'])->getNameField();
              $concatField = isset($field['relation']['concat_field']) ? $field['relation']['concat_field'] : '';
              $field['options'][''] = lang('admin.add_edit.' . strtolower($this->entityName) . '.' . $fieldName . '.default');
              $options = ManagerHolder::get($field['relation']['entity_name'])->getAsViewArray(array(), $nameField, $concatField, isset($field['relation']['where_array']) ? $field['relation']['where_array'] : null);
              if($options){
                $field['options'] += $options;
              }

              // If the field is named after an alias with a 1 to many relation
              $fKeys = ManagerHolder::get($this->managerName)->getForeignKeys();
              if (isset($fKeys[$fieldName]) && $fKeys[$fieldName]['type'] == 0) {
                if (!empty($entity[$fieldName])) {
                  $entity[$fieldName] = $entity[$fieldName]['id'];
                } else {
                  $entity[$fieldName] = '';
                }
              }
              
            }
            break;
          case 'multipleselect':
            if (isset($field['relation'])) {
              $whithoutIds = array();
              if (!empty($entity[$fieldName])) {
                $whithoutIds = get_array_vals_by_second_key($entity[$fieldName], 'id');
              }
              if ($this->entityName == $field['relation']['entity_name']) {
                if (is_not_empty($entity['id'])) {
                  $whithoutIds[] = $entity['id'];
                }
              }
              // Setting fk options
              $nameField = isset($field['relation']['name_field']) ? $field['relation']['name_field'] : ManagerHolder::get($field['relation']['entity_name'])->getNameField();
              $concatField = isset($field['relation']['concat_field']) ? $field['relation']['concat_field'] : '';
              $field['from'] = array();
              $options = ManagerHolder::get($field['relation']['entity_name'])->getAsViewArray($whithoutIds, $nameField, $concatField, isset($field['relation']['where_array']) ? $field['relation']['where_array'] : null);
              if($options){
                $field['from'] += $options;
              }
            }
            break;
        }
      }
    }
  }

  /**
   * Get Field Values Ajax
   * @param string $field
   */
  public function get_field_values_ajax($field) {
    $result = '';
    $option = '<option value="{val}">{name}</option>';
    $this->load->helper('text');
    if (isset($this->fields[$field]) && isset($_GET['q'])) {
      $field = $this->fields[$field];
      if (isset($field['relation'])) {
        $nameField = isset($field['relation']['name_field']) ? $field['relation']['name_field'] : 'name';
        $concatField = isset($field['relation']['concat_field']) ? $field['relation']['concat_field'] : '';
        $whereArray = isset($field['relation']['where_array']) ? $field['relation']['where_array'] : array();
        if (!empty($_GET['q'])) {
          if (!empty($concatField)) {
            $whereArray["CONCAT(e." . $nameField . ", ' ', e." . $concatField . ")"] = 'LIKE %' . $_GET['q'] . '%';
          } else {
            $whereArray[$nameField] = 'LIKE %' . $_GET['q'] . '%';
          }
        }
        $whithoutIds = array();
        if (isset($_GET['not']) && $_GET['not'] != 'null') {
          $whithoutIds = explode(',', $_GET['not']);
        }
        $options = ManagerHolder::get($field['relation']['entity_name'])->getAsViewArray($whithoutIds, $nameField, $concatField, $whereArray);
        if ($options){
          foreach ($options as $k => $v) {
            $result .=  kprintf($option, array('val' => $k, 'name' => $v));
          }
        }
      }
    }
    die($result);
  }



  /**
   * Implementation of PRE_ADD_EDIT event callback
   * @param Object $entity reference
   * @return Object
   */
  protected function preAddEditView(&$entity) {
  }

  /**
   * CreateEntityId.
   * Creates Entity By Id;
   * @param integer $entityId
   * @return Object
   */
  protected function createEntityId($entityId = null) {
    $entity = new $this->managerName;
    $entityObject = $entity;
    $entity = $entity->toArray();
    if ($entityId) {
      $params = "id,";
      foreach ($this->fields as $k => $v) {
        if($v['type'] == 'map') {
          // adding $k_left_px & $k_top_px fields
          $params .= ($k == 'map' ? '' : $k . '_') . "left_px,";
          $params .= ($k == 'map' ? '' : $k . '_') . "top_px,";
        } elseif($v['type'] == 'geo') {
          $params .= ($k == 'geo' ? '' : $k . '_') . "latitude,";
          $params .= ($k == 'geo' ? '' : $k . '_') . "longitude,";
          $params .= ($k == 'geo' ? '' : $k . '_') . "address,";
        } else {
          $params .= $k . ",";
        }
      }
      $params = rtrim($params, ',');
      $entity = ManagerHolder::get($this->managerName)->getById($entityId, $params);
      if (empty($entity)) {
        redirect($this->adminBaseRoute . '/' .  strtolower($this->entityUrlName));
      }
    }
    return $entity;
  }

  /**
   * CreateEntityPOST.
   * Prepares POST.
   * Creates Entity From Post.
   * Validates Entity.
   * @return Object
   */
  protected function createEntityPOST() {
    $this->preProcessPost();
    $entity = ManagerHolder::get($this->managerName)->createEntityFromPOST();
    $this->isValid($entity);
    return $entity;
  }

  /**
   * AddEditEntity.
   * Inserts/Updates entity in DB.
   * Sets flash message.
   * @param Object $entity
   * @return Object
   */
  protected function addEditEntity($entity) {
    $this->preSave($entity);  // fire pre save event
    if (empty($_POST["id"])) {
      try {
        $this->preInsert($entity);  // fire pre insert event
        $entity['id'] = ManagerHolder::get($this->managerName)->insert($entity);
        $this->postSave($entity);  // fire post save event - both insert and update
        $this->postInsert($entity); // fire post insert event
        set_flash_notice('admin.messages.' . strtolower($this->entityName) . '.add');
      } catch (Exception $e) {
        save_post();
        log_message('error', $e->getTraceAsString());
        set_flash_error($e->getMessage());
        $this->redirectToReffer();
      }
    } else {
      try {
        $this->preUpdate($entity);  // fire pre update event
        ManagerHolder::get($this->managerName)->update($entity);
        $this->postSave($entity); // fire post save event - both insert and update
        $this->postUpdate($entity); // fire post update event
        set_flash_notice('admin.messages.' . strtolower($this->entityName) . '.edit');
      } catch (Exception $e) {
        save_post();
        log_message('error', $e->getTraceAsString());
        set_flash_error($e->getMessage());
        $this->redirectToReffer();
      }
    }
    return $entity;
  }

  /**
   * Implementation of PRE_INSERT event callback
   * @param Object $entity reference
   * @return Object
   */
  protected function preInsert(&$entity) {
  }

  /**
   * Implementation of POST_INSERT event callback
   * @param Object $entity reference
   * @return Object
   */
  protected function postInsert(&$entity) {
  }

  /**
   * Implementation of PRE_UPDATE event callback
   * @param Object $entity reference
   * @return Object
   */
  protected function preUpdate(&$entity) {
  }

  /**
   * Implementation of POST_UPDATE event callback
   * @param Object $entity reference
   * @return Object
   */
  protected function postUpdate(&$entity) {
  }

  /**
   * Implementation of POST_SAVE event callback
   * @param Object $entity reference
   * @return Object
   */
  protected function postSave(&$entity) {
  }

  /**
   * Implementation  PRE_SAVE event callback
   * @param Object $entity reference
   * @return Object
   */
  protected function preSave(&$entity) {
  }


  /**
   * Load and resize images
   * @param array $images - image names
   * @return array
   */
  protected function loadAndResizeImages(&$entity) {
    $this->fileoperations->set_base_dir('./web/images');
    $folder = $this->session->userdata(self::FOLDER_SESSION_KEY);
    if ($folder) {
      $this->fileoperations->add_folder_to_uploads_dir($folder);
    }
    // Upload, resize and save images
    foreach ($this->fields as $fieldName => $params) {
      if ($params["type"] == "image") {
        $image = $this->loadAndResizeImage($fieldName);
        if ($image) {
          $entity[$fieldName] = $image;
        }
      } else if ($params["type"] == "image_list") {
        $this->saveImageListOrder($entity, $fieldName);
        $image = $this->loadAndResizeImage($fieldName);
        if ($image) {
          $refEntityName = $entity[$fieldName]->getTable()->getOption('name');
          $ref = new $refEntityName;
          $ref[strtolower($this->entityName) . '_id'] = $entity['id'];
          $ref['image_id'] = $image['id'];
          $entity[$fieldName]->add($ref);
        }
      }
    }
  }

  /**
   * Save image list order
   * @param array $entity
   * @param string $fieldName
   */
  protected function saveImageListOrder(&$entity, $fieldName) {
    if (isset($_POST[$fieldName . '_priority']) && is_array($_POST[$fieldName . '_priority'])) {
      $refEntityName = $entity[$fieldName]->getTable()->getOption('name');
      $refArr = array();
      $refArr[strtolower($this->entityName) . '_id'] = $entity['id'];
      $priority = count($_POST[$fieldName . '_priority']);
      foreach ($_POST[$fieldName . '_priority'] as $imgId) {
        $refArr['image_id'] = $imgId;
        ManagerHolder::get($refEntityName)->updateWhere($refArr, 'priority', $priority);
        $priority--;
      }
    }
  }

  /**
   *
   * Load and resize image
   * @param string $fieldName
   * @return integer
   */
  protected function loadAndResizeImage($fieldName) {
    try {
      if ($this->fileoperations->upload($fieldName, FALSE)) {
        $image = ManagerHolder::get("Image")->createEntityFromArray($this->fileoperations->file_info);
        $thumbs = $this->config->item(strtolower($this->entityName . '_' . $fieldName), 'thumbs');
        if(!$thumbs){
          // for backward compatibility
          $thumbs = $this->config->item(strtolower($this->entityName), 'thumbs');
        }
        $thumbs['_admin'] = $this->config->item('_admin', 'all');
        if ($thumbs) {
          foreach($thumbs as $name => $sizes) {
            if(isset($sizes['smart_crop']) && $sizes['smart_crop']){
              $this->fileoperations->createSmartCropThumb($image, $name, $sizes["width"], $sizes["height"]);
            } else {
              $this->fileoperations->createImageThumb($image, $name, $sizes["width"], $sizes["height"]);
            }
          }
        }
        $image['id'] = ManagerHolder::get("Image")->insert($image);
        return $image;
      }
    } catch (Exception $e) {
      $message = $e->getMessage();
      set_flash_error($message);
      log_message('error', $e->getMessage());
      $this->redirectToReffer();
    }
  }


  /**
   * Load files.
   * @param &$entity
   * @return void
   */
  protected function loadFiles(&$entity) {
    // Upload files
    foreach ($this->fields as $fieldName => $params) {
      if ($params["type"] == "file" && isset($_POST[$fieldName . "_entity"])) {
        try {
          $this->fileoperations->set_upload_lib_config_value("allowed_types", $this->allowedFileTypes);
          $class = $_POST[$fieldName . "_entity"];
          if ($this->fileoperations->upload($fieldName, FALSE, './web')) {
            $file = ManagerHolder::get($class)->createEntityFromArray($this->fileoperations->file_info);
            $fileId = ManagerHolder::get($class)->insert($file);
            $entity[$fieldName] = $fileId;
            $this->postLoadFiles($class, $entity, $file);
          }
        } catch (Exception $e) {
          $message = $e->getMessage() . '<br/>Allowed file types are: ' . $this->fileoperations->get_upload_lib_config_value("allowed_types");
          set_flash_error($message);
          log_message('error', $e->getMessage());
          $this->redirectToReffer();
        }
      }
    }
  }

  /**
   * Load Videos.
   * @param &$entity
   * @return void
   */
  protected function loadVideos(&$entity) {
    // Upload videos
    foreach ($this->fields as $fieldName => $params) {
      if ($params["type"] == "video") {
        $this->load->library("base/ffmpeg");
        try {
          $this->fileoperations->set_upload_lib_config_value("allowed_types", $this->allowedVideoTypes);
          $this->ffmpeg->isFfmpegSupported();
          if ($this->fileoperations->upload($fieldName, FALSE, './web')) {
            $file = ManagerHolder::get('Video')->createEntityFromArray($this->fileoperations->file_info);

            $movie = Ffmpeg::createMovie($file['file_path'] . $file['file_name']);
            $length = Ffmpeg::getDuration($movie);
            $file['duration'] = round($length);

            $fileId = ManagerHolder::get('Video')->insert($file);
            $entity[$fieldName] = $fileId;
            $this->postLoadFiles('Video', $entity, $file);
          }

          if (isset($file)) {
            $thumbName = str_replace($file['extension'], '_thumb.jpg', $file['file_name']);
            $commandParams = array(Ffmpeg::PARAM_PATH => $file['file_path'] . $file['file_name'],
            Ffmpeg::PARAM_RESULT_PATH => $file['file_path'] . $thumbName,
            Ffmpeg::PARAM_SCREENSHOT_TIME => '00:00:00');
            $this->ffmpeg->setCommandParams($commandParams);
            $this->ffmpeg->makeScreenshot();
            $thumb = new Image();
            $this->fileoperations->get_file_info($file['file_path'] . $thumbName);
            $thumb->fromArray($this->fileoperations->file_info);
            $thumbId = ManagerHolder::get('Image')->insert($thumb);

            $thumbSizes = $this->config->item('video', 'thumbs');
            foreach ($thumbSizes as $thName => $wh) {
              $this->fileoperations->createImageThumb($thumb, $thName, $wh['width'], $wh['height']);
            }
            ManagerHolder::get('Video')->updateById($fileId, 'image_id', $thumbId);
          }
        } catch (Exception $e) {
          $message = $e->getMessage() . '<br/>Allowed file types are: ' . $this->fileoperations->get_upload_lib_config_value("allowed_types");
          set_flash_error($message);
          log_message('error', $e->getMessage());
          $this->redirectToReffer();
        }
      }
    }
  }

  /**
   * Implementation of POST_LOAD_FILES event callback
   * @access protected
   * @param  $class  class of file entity
   * @param  $entity main entity instance
   * @param  $file   file instance
   * @return void
   */
  protected function postLoadFiles($class, &$entity, &$file) {
  }

  /**
   * Check if entity is valid.
   * @param Entinty $enitty
   */
  protected function isValid($entity) {
    if (!is_array($entity)) {
      if (!$entity->isValid()) {
        $this->savePost();
        set_flash_warning($entity->getErrorStackAsString());
        $this->redirectToReffer();
      }
    }
  }


  // -----------------------------------------------------------------------------------------
  // ----------------------------------- DELETE METHODS --------------------------------------
  // -----------------------------------------------------------------------------------------

  /**
   * Delete.
   * @param integer $entityId
   */
  public function delete($entityId) {
    $this->checkPermissions("_delete");
    $entity = $this->deleteFromDb($entityId);
    set_flash_notice('admin.messages.' . strtolower($this->entityName) . '.delete');
    $this->redirectToReffer();
  }


  /**
   * Delete_image.
   * @param integer $imageId
   */
  public function delete_image($imageId) {
    $image = $this->deleteImageFromDb($imageId);
    set_flash_notice('admin.messages.image_delete');
    $this->redirectToReffer();
  }


  /**
   * Delete_file.
   * @param integer $fileId
   * @return void
   */
  public function delete_file($fileId, $fileEntityName = 'File') {
    ManagerHolder::get($fileEntityName)->deleteById($fileId);
    set_flash_notice('admin.messages.file_delete');
    $this->redirectToReffer();
  }


  /**
   * Process_list
   * save_order
   */
  public function process_list(){
    if (isset($_POST['save_order'])) {
      $this->save_order();
    }
    $this->redirectToReffer();
  }

  /**
   * Delete all total.
   */
  public function delete_all_total() {
    $this->checkPermissions("_delete");
    ManagerHolder::get($this->managerName)->deleteAll();

    // Delete selected cookie
    $this->load->helper('cookie');
    $cookie = strtolower($this->entityName) . '_batch_update_ids';
    delete_cookie($cookie);

    set_flash_notice('admin.messages.' . strtolower($this->entityName) . '.delete_all');
    redirect_to_referral();
  }

  /**
   * SaveOrder
   * @param array $ids
   */
  protected function save_order() {
    $ids = $_POST['s_id'];
    $position = 1;
    foreach ($ids as $id) {
      ManagerHolder::get($this->managerName)->updateById($id, $this->orderField, $position);
      $position++;
    }
    set_flash_notice('admin.messages.' . strtolower($this->entityName) . '.save_order');
  }

  /**
   * DeleteFromDb.
   * Deletes entity from DB and its images.
   * @param integer $entityId
   * @return Object (the deleted entity)
   */
  protected function deleteFromDb($entityId) {
    $entity = ManagerHolder::get($this->managerName)->getById($entityId);
    if (isset($entity["image"]) && !empty($entity["image"])) {
      $this->deleteImageFromDb($entity["image"]['id']);
    }
    $this->preDelete($entityId);
    ManagerHolder::get($this->managerName)->deleteById($entityId);
    $this->postDelete($entityId);
    return $entity;
  }

  /**
   * Implementation of PRE_DELETE event callback
   * @param int $entityId id
   * @return Object
   */
  protected function preDelete($entityId) {
  }

  /**
   * Implementation of POST_DELETE event callback
   * @param int $entityId id
   * @return Object
   */
  protected function postDelete($entityId) {
  }


  /**
   * DeleteImageFromDb.
   * Delete image from db and hard drive.
   * @param integer $imageId
   * @return Image (the deleted image)
   */
  protected function deleteImageFromDb($imageId) {
    $image = ManagerHolder::get("Image")->getById($imageId);
    $thumbs = $this->config->item(strtolower($this->entityName), 'thumbs');
    if ($thumbs) {
      foreach($thumbs as $name => $kw) {
        @unlink($image["file_path"] . str_replace(".", $name . ".", $image["file_name"]));
      }
    }
    @unlink($image["file_path"] . $image["file_name"]);
    ManagerHolder::get("Image")->deleteById($imageId);
    return $image;
  }


  /**
   * Set filter.
   * Uses GET parameters.
   */
  protected function setFilter() {
    if (empty($this->filters)) return;

    // Default values
    $defaultValues = array();
    foreach ($this->filters as $key => $value) {
      $defaultValues[$key] = $value;
    }

    // Get from GET array
    if (!empty($_GET)) {
      foreach ($this->filters as $key => $value) {
        if (!isset($_GET[$key])) continue;
        $this->filters[$key] = $_GET[$key];
      }
    }

    // Filter values
    $filterValues = array();
    foreach ($this->filters as $key => $value) {
      $filterValues[$key] = ManagerHolder::get($this->entityName)->getFilterValues($key);
    }


    // Get DateFilters from GET array
    if (!empty($_GET)) {
      foreach ($this->dateFilters as $key) {
        if (isset($_GET[$key . '_from']) && isset($_GET[$key . '_to'])) {
          $this->filters[$key] = 'BETWEEN ' . $_GET[$key . '_from'] . ' 00:00:00' . ' AND ' . $_GET[$key . '_to'] . ' 23:59:59';
        } else {
          if (isset($_GET[$key . '_from'])) {
            $this->filters[$key] = '> ' . $_GET[$key . '_from'] . ' 00:00:00';
          }
          if (isset($_GET[$key . '_to'])) {
            $this->filters[$key] = '< ' . $_GET[$key . '_to'] . ' 23:59:59';
          }
        }
      }
    }

    // Layout
    $this->layout->set("filters", $this->filters);
    $this->layout->set("dateFilters", $this->dateFilters);
    $this->layout->set("filter_values", $filterValues);
    $this->layout->set("default_values", $defaultValues);
    foreach ($this->filters as $key => &$value) {
      if (trim($value) == '') {
        unset($this->filters[$key]);
      }
      if ($value === 'NULL') {
        $value = null;
      }
    }
  }

  // -----------------------------------------------------------------------------------------
  // ----------------------------------- BATCH METHODS --------------------------------------
  // -----------------------------------------------------------------------------------------

  /**
   * Bacth process function
   */
  public function batch_process() {
    simple_validate_post('ids');
    $ids = explode(',', $_POST['ids']);
    $count = count($ids);
    $this->load->helper('cookie');
    $cookie = strtolower($this->entityName) . '_batch_update_ids';

    // DELETE
    if (isset($_POST['delete'])) {
      ManagerHolder::get($this->entityName)->deleteAllWhere(array('id' => $ids));
      delete_cookie($cookie);
      set_flash_notice('admin.messages.many_delete', array('count' => $count));
      redirect_to_referral();
    }

    // EXPORT
    if (isset($_POST['export'])) {
      $this->session->set_flashdata('ids', $_POST['ids']);
      redirect($this->adminBaseRoute . '/' . strtolower($this->entityName) . '/export');
    }

    // UPDATE
    if (isset($_POST['update'])) {
      $success = $this->batch_update($ids);
      if($success) {
        delete_cookie($cookie);
        set_flash_notice('admin.messages.batch_update', array('count' => $count));
      }
      redirect_to_referral();
    }

  }



  /**
   * batch_update
   * @param array $ids
   */
  private function batch_update($ids) {
    $updateValues = array();
    $needsFetching = FALSE;
    foreach($_POST['value'] as $key => $value) {
      if($value != '' && isset($_POST['field'][$key])) {
        $fieldName = $_POST['field'][$key];
        $updateValues[$fieldName] = $value;
        if(strpos($fieldName, '.') !== FALSE) {
          $needsFetching = TRUE;
        }
      }
    }

    if($updateValues) {
      $count = 0;
      $ids = explode(',', $_POST['ids']);
      foreach($ids as $id) {
        try {
          if($needsFetching) {
            $e = ManagerHolder::get($this->entityName)->getById($id);

            // checking for foreign alias & unsetting them from entity
            $fks = ManagerHolder::get($this->entityName)->getForeignKeys();
            if($fks) {
              foreach($updateValues as $field => $value) {
                foreach($fks as $alias => $fkData) {
                  if($fkData['local'] == $field) {
                    // foreign key found
                    unset($e[$alias]);
                  }
                }
              }
            }

            // apply update values by merging arrays
            $e = merge_entity_with_array($e, $updateValues);
            ManagerHolder::get($this->entityName)->update($e);
          } else {
            ManagerHolder::get($this->entityName)->updateAllWhere(array('id' => $id), $updateValues);
          }
        } catch(Exception $e) {
          // we don't care about individuals. we change masses.
          // just log the error and go on
          log_message('error', 'Batch update error on id: ' . $id . '; Message = ' . $e->getMessage());
          $count--;
        }
        $count++;
      }
      return TRUE;
    }
    return FALSE;
  }



  // -----------------------------------------------------------------------------------------
  // ---------------------------- NEXT / PREV METHODS --------------------------------------
  // -----------------------------------------------------------------------------------------

  /**
   * Redirect to edit next entity
   * @param integer $id
   */
  public function next($id) {
    $manager = ManagerHolder::get($this->managerName);
    $params = $_GET;
    if (isset($params['q'])) {
      $manager->setSearch($params['q'], implode(',', $this->searchParams), 'contains');
      unset($params['q']);
    }
    $nid = $manager->getNextWhereId($id, $params);
    if ($nid) {
      redirect($this->adminBaseRoute . '/' . strtolower($this->entityUrlName) . '/add_edit/' . $nid . get_get_params());
    } else {
      set_flash_error("admin.next.error.no_more");
      redirect($this->adminBaseRoute . '/' . strtolower($this->entityUrlName));
    }
  }

  /**
   * Redirect to edit prev entity
   * @param integer $id
   */
  public function prev($id) {
    $manager = ManagerHolder::get($this->managerName);
    $params = $_GET;
    if (isset($params['q'])) {
      $manager->setSearch($params['q'], implode(',', $this->searchParams), 'contains');
      unset($params['q']);
    }
    $nid = $manager->getPrevWhereId($id, $params);
    if ($nid) {
      redirect($this->adminBaseRoute . '/' . strtolower($this->entityUrlName) . '/add_edit/' . $nid . get_get_params());
    } else {
      set_flash_error("admin.prev.error.no_more");
      redirect($this->adminBaseRoute . '/' . strtolower($this->entityUrlName));
    }
  }

  // -----------------------------------------------------------------------------------------
  // ---------------------------- IMPORT/EXPORT METHODS --------------------------------------
  // -----------------------------------------------------------------------------------------


  // ---------------------------------- IMPORT -----------------------------------------------
  /**
   * Import.
   */
  public function import() {
    if (!$this->import) show_404();

    // Import Filters
    $importFilters = array();
    if (!empty($_GET) && !empty($this->filters)) {
      foreach ($this->filters as $k => $v) {
        if (isset($_GET[$k]) && (!empty($_GET[$k]) || $_GET[$k] === '0')) {
          $vals = ManagerHolder::get($this->managerName)->getFilterValues($k);
          if (isset($vals[$_GET[$k]])) {
            $importFilter = array();
            $importFilter['val'] = $_GET[$k];
            $importFilter['str'] = $vals[$_GET[$k]];
            $importFilters[$k] = $importFilter;
          }
        }
      }
    }

    $this->layout->set("required", ManagerHolder::get($this->entityName)->getRequiredFields());
    $this->layout->set("importFilters", $importFilters);

    // Remove excluded fields
    if (!empty($this->importExcludeFields)) {
      foreach ($this->importExcludeFields as $exField) {
        if (isset($this->fields[$exField])) {
          unset($this->fields[$exField]);
        }
      }
    }

    $this->layout->set("fields", $this->fields);
    $this->layout->set("export", $this->export);
    $this->layout->set("exportUrl", $this->adminBaseRoute . '/' . strtolower($this->entityUrlName) . '/export'. get_get_params());
    $this->layout->set("backUrl", $this->adminBaseRoute . '/' .  strtolower($this->entityUrlName) . get_get_params());
    $this->layout->set("processLink", $this->adminBaseRoute . '/' .  strtolower($this->entityUrlName) . '/import_process' . get_get_params());

    $this->layout->view('import');
  }


  // ---------------------------------- IMPORT PROCESS --------------------------------------------
  /**
   * Import process.
   */
  public function import_process() {
    if (!$this->import) show_404();
    set_time_limit(0);

    $requiredFields = ManagerHolder::get($this->entityName)->getRequiredFields();

    $transactionStarted = FALSE;
    $ignoreErrors = (isset($_POST['ignore_errors']) && $_POST['ignore_errors'] == 1) ? TRUE : FALSE;

    $entityObject = new $this->entityName;
    $addCount = 0;
    $editCount = 0;
    try {
      $rowNum = 0;

      $zipRes = array();

      if (isset($_POST['zip']) && $_POST['zip'] == 1) {
        $zipRes = $this->import_zip_process();
      }

      $images = array();
      $uploadResult = FALSE;
      if (!empty($zipRes)) {
        $fileInfo = $zipRes['csv'];
        $images = $zipRes['images'];
        $uploadResult = TRUE;
      } else {
        $this->fileoperations->set_upload_lib_config_value("allowed_types", 'csv');
        $uploadResult = $this->fileoperations->upload('import_file', TRUE, './web');
        $fileInfo = $this->fileoperations->file_info;
      }

      if ($uploadResult) {
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

        // Add import filters fields
        foreach ($importFilters as $k => $v) {
          if (!isset($postFields[$k]) && isset($this->fields[$k])) {
            $postFields[$k] = $this->fields[$k];
          }
        }

        // Form the translation for fields
        $ftrans = array();
        $field['name'] = 'id';
        $field['key'] = 'id';
        $field['val']['type'] = 'input_integer';
        $ftrans[] = $field;
        foreach ($this->fields as $f => $val) {
          $field['name'] = trim(html_entity_decode(lang('admin.add_edit.' . strtolower($this->entityName) . '.' .  str_replace('.*', '', $f))));
          $field['key'] = trim(str_replace('.*', '', $f));
          $field['val'] = $val;
          $ftrans[] = $field;
        }

        // Load CSV Library
        $this->load->library('common/csv');

        // Read CSV File
        $this->csv->readFile($fileInfo['file_path'] . $fileInfo['file_name']);

        // Header
        $header = $this->csv->readRow();
        if (!$header || count($header) < 1) {
          set_flash_error('admin.import.error.wrong_file_format');
          $this->redirectToReffer();
        }
        if ($header[0] != 'id' && $_POST['import_type'] != 'add_only') {
          set_flash_error('admin.import.error.first_column_must_be_id');
          $this->redirectToReffer();
        }

        foreach($header as &$h) {
          $h = mb_strtoupper($h, 'UTF-8');
        }

        foreach ($ftrans as $f) {
          if (in_array($f['key'], $requiredFields['simple']) && !in_array($f['key'], array_keys($importFilters))) {
            if (!in_array(mb_strtoupper($f['name'], 'UTF-8'), $header)) {
              set_flash_error('admin.import.error.missing_required_fields', array('fname' => $f['name']));
              $this->redirectToReffer();
            }
          }
        }

        // Set Indexes
        foreach ($ftrans as $k => &$fs) {
          $index = array_key_by_value(mb_strtoupper($fs['name'], 'UTF-8'), $header, TRUE);
          if ($index !== null) {
            $fs['index'] = $index;
          }
        }

        ManagerHolder::get($this->entityName)->startTransaction();
        $transactionStarted = TRUE;
        while (($row = $this->csv->readRow()) !== FALSE) {
          $row = $this->preProcessImportRow($row);
          $rowNum += 1;
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

            // Skip lines with no ID for EDIT ONLY
            if ($field['key'] == 'id' && $_POST['import_type'] == 'edit_only') {
              if (!isset($row[$field['index']]) || empty($row[$field['index']])) {
                continue 3;
              }
            }

            // Do not process import filters when they are referenced entities
            foreach ($importFilters as $fk => $fv) {
              if (strpos($fk, '.') !== FALSE && $field['key'] == strtok($fk, '.')) {
                continue 2;
              }
            }

            // Field found in import
            if (isset($field['index']) && isset($row[$field['index']])) {

              $rowProcessValue = $this->processImportRow($field, $row, $importFilters);

              if ($rowProcessValue !== null) {
                $row[$field['index']] = $rowProcessValue;
              } else {
                // Check for double
                if ($field['val']['type'] == 'input_double') {
                  $row[$field['index']] = (double)str_replace(',', '.', $row[$field['index']]);
                }
                // Check for double/interger
                if ($row[$field['index']] === '' && ($field['val']['type'] == 'input_double' || $field['val']['type'] == 'input_integer')) {
                  $row[$field['index']] = null;
                }
                // Check for Enum
                if (isset($field['val']['enum'])) {
                  $enumValues = ManagerHolder::get($this->entityName)->getEnumValues($field['key']);
                  foreach ($enumValues as $enval) {
                    $lng = lang('enum.' . strtolower($this->entityName) . '.' . $field['key'] . '.' . $enval);
                    if (trim($row[$field['index']]) == $lng) {
                      $row[$field['index']] = $enval;
                      break;
                    }
                  }
                }

                // Check for multiple select and select
                if (($field['val']['type'] == 'multipleselect' || $field['val']['type'] == 'select') && !empty($row[$field['index']])) {
                  if (!isset($field['val']['enum'])) {
                    $values = array();

                    if (!isset($importFilters[$field['key']]) || empty($importFilters[$field['key']])) {

                      $resultValues = array();
                      $isOne = FALSE;
                      if (get_class($entityObject[$field['key']]) != 'Doctrine_Collection') {
                        $isOne = TRUE;
                        foreach ($entityObject->getTable()->getRelations() as $relName => $relObject) {
                          if ($field['key'] == $relObject->getLocal()) {
                            $refEntityName = $relObject->getTable()->getOption('name');
                            $values = array($row[$field['index']]);
                            $nameField = ManagerHolder::get($refEntityName)->getNameField();
                            break;
                          }
                        }
                      } else {
                        $refEntityName = $entityObject[$field['key']]->getTable()->getOption('name');
                        $values = explode(',', $row[$field['index']]);
                        $nameField = ManagerHolder::get($refEntityName)->getNameField();
                      }

                      foreach ($values as $val) {
                        $val = trim($val);
                        if (!empty($val)) {
                          
                          if (ManagerHolder::get($refEntityName) instanceof BaseTreeManager ) {
                            $refEnt = ManagerHolder::get($refEntityName)->getOneWhere(array($nameField => $val), 'id, root_id, lft, rgt, level, ' . $nameField);
                          } else {
                            $refEnt = ManagerHolder::get($refEntityName)->getOneWhere(array($nameField => $val), 'id, ' . $nameField);
                          }
                          if ($refEnt) {
                            if (!in_array($refEnt['id'], $resultValues)) {
                              $resultValues[] = $refEnt['id'];
                            }
                          } else {
                            if (ManagerHolder::get($refEntityName) instanceof BaseTreeManager) {
                              $nodeId = ManagerHolder::get($refEntityName)->insert(array($nameField => $val));
                              $resultValues[] = $nodeId;
                              $nodeEnt = ManagerHolder::get($refEntityName)->getFullById($nodeId);
                              ManagerHolder::get($refEntityName)->getTree()->createRoot($nodeEnt);
                              $nodeEnt = $nodeEnt->toArray();
                              if (array_key_exists('priority', $nodeEnt)) {
                                $maxPr = ManagerHolder::get($refEntityName)->getMaxPriority();
                                ManagerHolder::get($refEntityName)->updateById($nodeId, 'priority', $maxPr + 1);
                              }
                            } else {
                              $resultValues[] = ManagerHolder::get($refEntityName)->insert(array($nameField => $val));
                            }
                          }
                        }
                      }
                      if ($isOne) {
                        $resultValues = array_pop($resultValues);
                      }
                      $row[$field['index']] = $resultValues;
                    }
                  }
                }
                // Check for images
                if (($field['val']['type'] == 'image')) {
                  $imgName = $row[$field['index']];
                  $image = array();
                  foreach ($images as $img) {
                    if ($img['file_name'] == $imgName) {
                      $image = $img;
                      break;
                    }
                  }
                  $imageId = null;
                  if (!empty($image)) {

                    $imageInfo = @getimagesize($image['file_path'] . $image['file_name']);
                    if(is_not_empty($imageInfo['mime'])) {
                      $mime = $imageInfo['mime'];
                      $isAllowedMime = $this->fileoperations->is_allowed_file_mime($mime);
                    }

                    if(!isset($mime) || $isAllowedMime) {
                      $this->fileoperations->set_base_dir('./web/images');
                      $folder = $this->session->userdata(self::FOLDER_SESSION_KEY);
                      $newPath = $this->fileoperations->copy_file($image, $folder, TRUE);
                      $this->fileoperations->get_file_info($newPath);
                      $image = $this->fileoperations->file_info;
                      $thumbs = $this->config->item(strtolower($this->entityName . '_' . $field['key']), 'thumbs');
                      if(!$thumbs) {
                        // for backward compatibility
                        $thumbs = $this->config->item(strtolower($this->entityName), 'thumbs');
                      }
                      $thumbs['_admin'] = $this->config->item('_admin', 'all');
                      $this->fileoperations->createImageThumbs(array($image), $thumbs);

                      $imageId = ManagerHolder::get('Image')->insert($image);
                    }
                  }
                  $row[$field['index']] = $imageId;
                }
              }

              $entity[$field['key']] = $row[$field['index']];
            }
          }

          // Import Filters
          foreach ($importFilters as $fk => $fv) {
            $entity[$fk] = $fv;
          }


          //image hack
          if(isset($entity['image'])) {
            $entity['image_id'] = $entity['image'];
            unset($entity['image']);
          }

          try {
            if (count(array_keys($entity)) > 1) {
              if ($_POST['import_type'] == 'add_only') {
                unset($entity['id']);
              }
              if (isset($entity['id']) || !empty($entity['id'])) {
                ManagerHolder::get($this->entityName)->update($entity);
                $editCount++;
              } else {
                ManagerHolder::get($this->entityName)->insert($entity);
                $addCount++;
              }
            }
          } catch (Exception $e) {
            if (!$ignoreErrors) {
              throw $e;
            }
          }
        }

        ManagerHolder::get($this->entityName)->commitTransaction();
      }
      $this->fileoperations->delete_file($fileInfo['file_name'], $fileInfo['file_path']);
    } catch (Exception $e) {
      if ($transactionStarted) {
        ManagerHolder::get($this->entityName)->rollbackTransaction();
      }
      $errors = array();
      //      if ($addCount > 0 || $editCount > 0) {
      //        $errors[] =  kprintf(lang('admin.import.message.imported'), array('added' => $addCount, 'edited' => $editCount));
      //      }
      if ($e->getCode() == DOCTRINE_DUPLICATE_ENTRY_EXCEPTION_CODE) {
        $errors[] = 'admin.import.' . strtolower($this->entityName) . '.error.duplicate';
      }
      if ($rowNum > 0) {
        $errors[] = kprintf(lang('admin.import.error.on_line'), array('line' => $rowNum + 1));
      }
      $message =  $e->getMessage();
      if ($message == 'Failed to refresh. Record does not exist.') {
        $message = kprintf(lang('admin.import.error.falied_to_refresh'), array('id' => $entity['id']));
      }
      $errors[] = $message;
      set_flash_error($errors);
      log_message('error', $e->getMessage());
      $this->redirectToReffer();
    }

    set_flash_notice('admin.import.message.imported', array('added' => $addCount, 'edited' => $editCount));
    $this->redirectToReffer();
  }

  /**
   * Import ZIP with images
   */
  private function import_zip_process() {
    $this->fileoperations->set_upload_lib_config_value("allowed_types", 'zip');
    $this->fileoperations->set_upload_lib_config_value("max_size", 100*1024*1024);
    if ($this->fileoperations->upload('import_file', TRUE, './web')) {
      $fileInfo = $this->fileoperations->file_info;
      $this->load->library('common/UnZip');
      $res = $this->unzip->extract($fileInfo['file_path'] . $fileInfo['file_name']);
      $files = array();
      foreach ($res as $fName) {
        $this->fileoperations->get_file_info($fName);
        $files[] = $this->fileoperations->file_info;
      }
      $csvFile = "";
      foreach ($files as $index => $file) {
        if (strtolower($file['extension']) == '.csv') {
          $csvFile = $file;
          unset($files[$index]);
          break;
        }
      }
      $this->fileoperations->delete_file($fileInfo['file_name'], $fileInfo['file_path']);
    }
    return array('csv' => $csvFile, 'images' => $files);
  }

  /**
   *
   * Method to customly handle a field and row of import
   * @param array $field - field data
   * @param array $row - row data
   * @return mixed (IF NULL - THEN ROW WILL BE PROCESSED AS DEFAULT)
   */
  protected function processImportRow($field, $row) {
    return null;
  }

  /**
   *
   * Method to customly pre process a row of import
   * @param array $row - row data
   * @return array $row - row data
   */
  protected function preProcessImportRow($row) {
    return $row;
  }

  // ---------------------------------- EXPORT --------------------------------------------------
  /**
   * Export.
   */
  public function export() {
    if (!$this->export) show_404();

    // Add Id field
    $this->fields = array_merge(array('id' => array('type' => 'input')), $this->fields);

    // Export Filters
    $exportFilters = array();
    if (!empty($_GET) && !empty($this->filters)) {
      foreach ($this->filters as $k => $v) {
        if (isset($_GET[$k]) && (!empty($_GET[$k]) || $_GET[$k] === '0')) {
          $vals = ManagerHolder::get($this->managerName)->getFilterValues($k);
          $exportFilter = array();
          $exportFilter['val'] = $_GET[$k];
          $exportFilter['str'] = $vals[$_GET[$k]];
          $exportFilters[$k] = $exportFilter;
        }
      }
    }

    if (!empty($_GET) && !empty($this->dateFilters)) {
      foreach ($this->dateFilters as $key) {
        $exportFilter = array();
        if (isset($_GET[$key . '_from']) && isset($_GET[$key . '_to'])) {
          $exportFilter['val'] = 'BETWEEN ' . $_GET[$key . '_from'] . ' 00:00:00' . ' AND ' . $_GET[$key . '_to'] . ' 23:59:59';
          $exportFilter['str'] = lang('admin.filter.from') . ' ' . $_GET[$key . '_from'] . ' ' . lang('admin.filter.to') . ' ' . $_GET[$key . '_to'];
        } else {
          if (isset($_GET[$key . '_from'])) {
            $exportFilter['val'] = '> ' . $_GET[$key . '_from'] . ' 00:00:00';
            $exportFilter['str'] = lang('admin.filter.from') . ' ' . $_GET[$key . '_from'];
          }
          if (isset($_GET[$key . '_to'])) {
            $exportFilter['val'] = '< ' . $_GET[$key . '_to'];
            $exportFilter['str'] = lang('admin.filter.to') . ' ' . $_GET[$key . '_to'] . ' 23:59:59';
          }
        }
        if (!empty($exportFilter)) {
          $exportFilters[$key] = $exportFilter;
        }
      }
    }

    // Process Batch Export
    $ids = $this->session->flashdata('ids');
    if ($ids) {
      $exportFilters = array();
      $exportFilter = array();
      $exportFilter['val'] = $ids;
      $exportFilter['str'] = count(explode(',', $ids));
      $exportFilters['batch_export_ids'] = $exportFilter;
    }

    $this->layout->set("exportFilters", $exportFilters);

    // Remove excluded fields
    if (!empty($this->exportExcludeFields)) {
      foreach ($this->exportExcludeFields as $exField) {
        if (isset($this->fields[$exField])) {
          unset($this->fields[$exField]);
        }
      }
    }

    $nestedFields = $this->getFieldsNestedArray($this->fields);

    $this->layout->set("fields", $this->fields);
    $this->layout->set("nestedFields", $nestedFields);
    $this->layout->set("requiredFields", ManagerHolder::get($this->entityName)->getRequiredFields());
    $this->layout->set("import", $this->import);
    $this->layout->set("importUrl", $this->adminBaseRoute . '/' . strtolower($this->entityUrlName) . '/import'. get_get_params());
    $this->layout->set("backUrl", $this->adminBaseRoute . '/' .  strtolower($this->entityUrlName . get_get_params()));
    $this->layout->set("processLink", $this->adminBaseRoute . '/' .  strtolower($this->entityUrlName) . '/export_process' . get_get_params());
    $this->layout->set('importExcludeFields', $this->importExcludeFields);
    $this->layout->view('export');
  }


 // ---------------------------------- EXPORT PROCESS --------------------------------------------------
  /**
   * Export process.
   */
  public function export_process() {
    if (!$this->export) show_404();

    $entityObject = new $this->entityName;
    
    $_POST = array_make_plain_with_dots($_POST);

    $fields = array();
    if (!isset($this->fields['id'])) {
      $fields = array('id');
    }
    
    foreach ($this->fields as $k => $v) {
      if (!isset($_POST[$k]) || $_POST[$k] == 0) continue;
          $fields[] = $k;
    }
    
    // Export filters
    $exportFilters = array();
    
    if (isset($_POST['exportfilter_batch_export_ids'])) {
      $exportFilters['id'] = explode(',', $_POST['exportfilter_batch_export_ids']);
    } else {
      foreach ($this->filters as $k => $v) {
        $key = 'exportfilter_' . str_replace('.', '_', $k);
        if (isset($_POST[$key])) {
          $exportFilters[$k] = $_POST[$key];
          if (!in_array($k, $fields)) {
            $fields[] = $k;
          }
        }
      }
      foreach ($this->dateFilters as $k) {
        $key = 'exportfilter_' . str_replace('.', '_', $k);
        if (isset($_POST[$key])) {
          $exportFilters[$k] = $_POST[$key];
          if (!in_array($k, $fields)) {
            $fields[] = $k;
          }
        }
      }
    }

    if (!empty($exportFilters)) {
      $entities = ManagerHolder::get($this->managerName)->export($exportFilters, $fields);
    } else {
      // Get everything from DB
      $entities = ManagerHolder::get($this->managerName)->export(array(), $fields);
    }

    $entityObject = new $this->entityName;

    // Load CSV Library
    $this->load->library('common/csv');
    
    if (!isset($_POST['id']) || empty($_POST['id'])) {
      unset($fields[array_search('id', $fields)]);
    }

    // Set headers
    $ftrans = array();
    foreach ($fields as $f) {
      $ftrans[] = html_entity_decode(lang('admin.add_edit.' . strtolower($this->entityName) . '.' .  str_replace('.*', '', $f)));
    }
    
    $this->csv->addHeader($ftrans);
    if ($entities) {
      // Process Rows
      $rows = array();
      foreach ($entities as $e) {
        $row = array();
        foreach ($fields as $key) {
          $row[$key] = $e[$key];
        }
        $row = $this->preProcessExportRow($row, $fields);
        $rows[] = $row;
      }
      $this->csv->addRows($rows);
    }

    // Send file to output
    $this->csv->flushFile(lang('admin.entity_list.' . strtolower($this->entityName) . '.list_title') . '.csv');
    die();
  }

  /**
   * Pre process export row
   * @param array $row
   * @param array $fields
   */
  protected function preProcessExportRow($row, $fields) {
    return $row;
  }

  // -----------------------------------------------------------------------------------------
  // ---------------------------------- PRINT METHODS --------------------------------------
  // -----------------------------------------------------------------------------------------

  /**
   * Redirect to edit prev entity
   * @param integer $id
   */
  public function printpage($id) {
    $this->layout->setLayout("ajax");
    $manager = ManagerHolder::get($this->managerName);
    $e = $manager->getById($id);
    if ($e) {
      $this->layout->set('fields', $this->fields);
      $this->layout->set('e', $e);
      $this->layout->set('entityName', strtolower($this->entityName));
    }
    $this->layout->view('parts/print');
  }


  // -----------------------------------------------------------------------------------------
  // ---------------------------------- UTILITY METHODS --------------------------------------
  // -----------------------------------------------------------------------------------------


  /**
   * GetFieldsNestedArray.
   * $fields = array('auth_info.name' => array("type" => "input"))
   * RESULT:
   * array('auth_info[name]' => array("type" => "input"))
   */
  protected function getFieldsNestedArray($array) {
    $nestedArray = array_make_nested($array);
    $result = array();
    foreach ($nestedArray as $k => $v) {
      if (isset($v['type'])) {
        $result[$k] = $v;
      } else {
        $result = array_merge($result, $this->FieldsNestedLoop($k, $v));
        unset($result[$k]);
      }
    }

    $finalResult = array();
    // To preserv order
    foreach (array_keys($array) as $ik) {
      if (isset($result[$ik])) {
        $finalResult[$ik] = $result[$ik];
      } else {
        // Don't try to understand this :)
        $fPart = strtok($ik, '.');
        $ik = str_replace($fPart . '.', '', $ik);
        $ik = $fPart . '[' . str_replace('.', '][', $ik) . ']';
        $finalResult[$ik] = $result[$ik];
      }
    }
    return $finalResult;
  }

  /**
   *
   * Fields Neteted Loop Nested
   * @param string $key
   * @param array $value
   * @param array $result
   */
  protected function FieldsNestedLoop($key, $value, &$result = null) {
    if (!$result) {
      $result = array();
    }
    foreach ($value as $k => $v) {
      if (isset($v['type'])) {
        $result[$key . '[' . $k . ']'] = $v;
      } else {
        $result = array_merge($result, $this->FieldsNestedLoop($key . '[' . $k . ']', $v, $result));
      }
    }
    return $result;

  }

  /**
   * Save post to flash data.
   */
  protected function savePost() {
    $this->session->set_flashdata(self::POST_SESSION_KEY, $_POST);
  }

  /**
   * Get post from flash data.
   */
  protected function getPost() {
    $post = $this->session->flashdata(self::POST_SESSION_KEY);
    if ($post) {
      return $post;
    }
    return array();
  }

  /**
   * Redirect to main page of entity list.
   * @param string $param the parameter to add to the refering url.
   */
  protected function redirectToReffer($param = null) {
    $this->load->library('user_agent');
    if ($this->agent->is_referral()) {
      $ref = $this->agent->referrer();
      if ($param) {
        $ref = strtok($this->agent->referrer(), '?');
        if (strstr($ref, '/' . $param) === FALSE) {
          $ref = surround_with_slashes($ref);
          $ref .= $param;
        }
        $ref .= get_get_params();
      } elseif (strpos($this->agent->referrer(), '?') === FALSE) {
        $ref = surround_with_slashes($this->agent->referrer());
      }
      redirect($ref);
    } else {
      redirect($this->adminBaseRoute . '/' . strtolower($this->entityUrlName));
    }
  }

  /**
   * PreProcessPost.
   * htmlspecialchars all fields except TinyMCE
   * remove all empty fields.
   */
  protected function preProcessPost() {
    if (count($_POST) > 0) {
      foreach ($this->fields as $name => $params) {

        // Fix dots
        if (strpos($name, '.') !== FALSE) {
          $key = str_replace('.', '_', $name);
          if (isset($_POST[$key])) {
            $_POST[$name] = $_POST[$key];
            unset($_POST[$key]);
          }
        }


        // Multipleselect
        // If nothing came in POST - set empty array to empty it
        if ($params["type"] == "multipleselect" || $params["type"] == "related") {
          if (!isset($_POST[$name])) {
            $_POST[$name] = array();
          }
        }
        
        // Select
        if ($params["type"] == "select" && isset($params['relation'])) {
          // If the field is named after an alias with a 1 to many relation
          $fKeys = ManagerHolder::get($this->managerName)->getForeignKeys();
          if (isset($fKeys[$name]) && $fKeys[$name]['type'] == 0) {
            $_POST[$fKeys[$name]['local']] = $_POST[$name];
            unset($_POST[$name]);
          }
        }

        if (isset($_POST[$name])) {
          if (($params["type"] == "date"  || $params["type"] == "select" || $params["type"] == "input_integer" || $params["type"] == "input_double") && empty($_POST[$name]) && $_POST[$name] !== '0') {
            $_POST[$name] = null;
          }
        }
      }

      foreach ($_POST as $key => $value) {
        if (isset($this->fields[$key]['type'])
        && $this->fields[$key]['type'] != "multipleselect"
        && $this->fields[$key]['type'] != "related"
        && $this->fields[$key]['type'] != "checkbox"
        && (isset($this->fields[$key]['class'])) && strstr($this->fields[$key]['class'], 'required') !== FALSE) {
          if (!is_null($_POST[$key]) && empty($_POST[$key])) {
            unset($_POST[$key]);
          }
        }
      }
    }
  }

  /**
   * mArr.
   * @param unknown_type $entityName
   * @param unknown_type $key
   * @param unknown_type $value
   * @param unknown_type $type
   */
  private function mArr($entityName, $key, $value, $type = 'entity_list') {
    $value = str_replace('_', ' ', $value);
    if ($value != 'id') {
      $value = str_replace('id', '', $value);
    }
    //    if ($this->config->item('language') != 'en' && !empty($value)) {
    //      $value = google_translate($value, 'en', substr($this->config->item('language'), 0, 2));
    //    }
    return '$lang[\'admin.' . $type . '.' . strtolower($entityName) .'.' . $key . '\'] = \''. $value . '\';';
  }

  /**
   * Message Properties
   * A function to display all message properties needed for the admin contoller.
   */
  public function message_properties($lang = '') {
    if (!empty($lang)) {
      $this->config->config['language'] = $lang;
    }

    $spaceCase = preg_replace('/(?!^)[[:upper:]][[:lower:]]/', ' $0', preg_replace('/(?!^)[[:upper:]]+/', ' $0', $this->entityName));
    $spaceCase = ucfirst($spaceCase);

    $lines = array();
    $lines[] = '//------------------------ ' . strtoupper($this->entityName) . ' > START -----------------------------------';
    $lines[] = $this->mArr($this->entityName, 'name', $spaceCase . 's', 'menu');
    $lines[] = $this->mArr($this->entityName, 'list_title', $spaceCase . 's');

    if (!empty($this->searchParams)) {
      $lines[] = $this->mArr($this->entityName, 'description', 'by ' . implode(' and ', $this->searchParams), 'search');
    }

    if (!empty($this->filters)) {
      $filters = array_keys($this->filters);
      foreach ($filters as $fname) {
        $lines[] = $this->mArr($this->entityName, 'filter.' . $fname . '_title', ucfirst($fname));
      }
    }

    if (!empty($this->dateFilters)) {
      foreach ($this->dateFilters as $fname) {
        $lines[] = $this->mArr($this->entityName, 'filter.' . $fname . '_title', ucfirst($fname));
      }
    }

    $lines[] = " ";

    // List params
    foreach ($this->listParams as $lp) {
      if (is_array($lp)) {
        $ks = array_keys($lp);
        $vs = array_values($lp);
        $lpk = implode('.', $ks) . '.' . implode('.', $vs) ;
        $lpv = implode('.', $ks) . ' ' . implode('.', $vs) ;
        $lines[] = $this->mArr($this->entityName, $lpk, ucfirst($lpv));
      } else {
        $lines[] = $this->mArr($this->entityName, $lp, ucfirst($lp));
      }
    }

    // Import
    if ($this->import) {
      $lines[] = " ";
      $lines[] = $this->mArr($this->entityName, 'form_title', 'Import ' . $spaceCase . 's', 'import');
    }

    // Export
    if ($this->export) {
      $lines[] = " ";
      $lines[] = $this->mArr($this->entityName, 'form_title', 'Export ' . $spaceCase . 's', 'export');
    }

    //    if (isset($this->searchParams)) {
    //      foreach($this->searchParams as $param){
    //        $text = $param;
    //        if(strstr($text, '.') !== FALSE){
    //          $text = str_replace('.', ' ', $text);
    //        }
    //        $lines[] = $this->mArr($this->entityName, 'search.' . $param, ucfirst($text));
    //      }
    //    }

    // Search fields
    //admin.entity_list.product.search.name

    // Fields
    $lines[] = " ";
    $lines[] = $this->mArr($this->entityName, 'form_title', 'Add/Edit ' . $spaceCase, 'add_edit');
    $lines[] = $this->mArr($this->entityName, 'id', 'id', 'add_edit');
    foreach ($this->fields as $k => $v) {
      if ($v['type'] == 'multipleselect') {
        $lines[] = $this->mArr($this->entityName, $k . '.to', ucfirst($k), 'add_edit');
        $lines[] = $this->mArr($this->entityName, $k . '.from', ucfirst($k), 'add_edit');
      } else {
        $lines[] = $this->mArr($this->entityName, $k, ucfirst($k), 'add_edit');
        $lines[] = $this->mArr($this->entityName, $k . '.description', '', 'add_edit');
      }
    }

    // Actions
    $lines[] = " ";
    foreach ($this->actions as $k => $v) {
      $postFix = 'ed.';
      if ($k == 'delete') {
        $postFix = 'd.';
      }

      //http://api.microsofttranslator.com/v2/Http.svc/Translate?from=en&to=ru&text=dsa&appId=CC29908D870DF1B8A485A0908266852560C63325

      $lines[] = $this->mArr($this->entityName, $k, $spaceCase . ' successfully ' . $k . $postFix, 'messages');
    }
    if ($this->isDeleteAllAllowed) {
      $lines[] = $this->mArr($this->entityName, 'delete_all', $spaceCase . 's deleted.', 'messages');
    }

    $lines[] = '//------------------------ ' . strtoupper($this->entityName) . ' > END -------------------------------------';

    $result = implode('<br/>', $lines);
    if ($lang != 'en' && !empty($lang)) {
      $result = bing_translate($result, 'en', $lang);
      $result = str_replace('/ /', '//', $result);
    }


    die($result);
  }


  /**
   * Check permission.
   * Shows 404 page if not allowed.
   * @param $actionSuffix (E.g.: '_add', '_edit')
   * @return mixed
   */
  protected function checkPermissions($actionSuffix = '', $returnResult = false) {
    if (isset($this->loggedInAdmin['permissions'])
    && !in_array(strtolower($this->entityUrlName) . $actionSuffix, explode('|', $this->loggedInAdmin['permissions']))) {
      if ($returnResult) {
        return false;
      } else {
        show_404();
      }
    } elseif ($returnResult) {
      return true;
    }
  }

  /**
   * Is Super Admin
   */
  protected function isSuperAdmin() {
    return in_array('admin_edit', explode('|', $this->loggedInAdmin['permissions']))
    || in_array('admin_delete', explode('|', $this->loggedInAdmin['permissions']))
    || in_array('admin_admin', explode('|', $this->loggedInAdmin['permissions']));
  }

}
