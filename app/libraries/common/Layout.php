<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Layout library
 * Itirra - http://itirra.com
 */
class Layout {
  
  /** The variable to pass the main content to layout. */
  const CONTENT_VARIABLE = "content";
  
  /** The variable to pass the module name to layout. */
  const MODULE_VARIABLE = "module";  

  /** Default Layout. */
  const DEFAULT_LAYOUT = "main";

  /** Default Layout. */
  const DEFAULT_PATH_LAYOUT = "layouts";

  /** Default Module. */
  const DEFAULT_MODULE = "includes";
  
  /**
   * Block - entity array.
   * #key - block name ("_block" will be added to get the view file)
   * #value - entity name (name of the entity to get the manager)
   *
   * Important! Manager must have a getBlockData() method.
   *
   * Example:
   * array("are_you_a" => "", "articles" => "Article");
   * @var array
   */
  private $block_entity = array();

  /**
   * Block Config.
   *
   * Array:
   * #key - page regexp
   * #key1 -  block position (name of content vaiable)
   * #value1 -  array of block names
   *
   * Example:
   *     "*" => array("right" => array("are_you_a",
   *                                  "recent_news",
   *                                  "faq")),
   * @var array
   */
  private $block_config = array();

  /** The CI object. */
  protected $obj;

  /** View data array. */
  protected $viewData = array();

  /** Block data array. */
  protected $block_data = array();
  
  /** Path to layout file. */
  protected $layout = self::DEFAULT_PATH_LAYOUT;

  /** Path to includes folder where view files are stored. */
  protected $module = self::DEFAULT_MODULE;

  /**
   * Set blocks.
   */
  protected function set_blocks() {
    if (!$this->block_config) return;
    
    // Match uri
    $uri = uri_string();
    $uri = empty($uri) ? '/' : $uri;
    $block_data = array();
    foreach ($this->block_config as $path => $cfg) {
      $path = '/' . $path . '/';
      if (preg_match($path, $uri)) {
        $block_data[] = $cfg;
      }
    }
    if (empty($block_data)) return;
    
    // Merge positions
    $page_blocks = array();
    foreach ($block_data as $matchedItem) {
      foreach ($matchedItem as $position => $blockName) {
        if (isset($page_blocks[$position])) {          
          $page_blocks[$position] = array_merge($page_blocks[$position], $blockName);
        } else {
          $page_blocks[$position] = $blockName;
        }
      }
      
    }
    
    // Render blocks
    foreach ($page_blocks as $position => $blocks) {
      if (empty($blocks)) continue;
      $block_result = "";
      foreach ($blocks as $block) {
        $data = array();
        if (!empty($this->block_entity[$block])) {
          $params = array();
          $params['block'] = $block; 
          if (isset($this->block_data[$block])) {
            $params['data'] = $this->block_data[$block];
          }
          $data = ManagerHolder::get($this->block_entity[$block])->getBlockData($params);
        }
        $block_file = "includes/blocks/" . $block . "_block";
        
        $block_data_tmp = array();
        $block_data_tmp = array_merge($block_data_tmp, $this->viewData);
        $block_data_tmp["data"] = $data;
        $block_result .= $this->obj->load->view($block_file, $block_data_tmp, TRUE);
        
        // Forward the data to view
        $key = "block_" . $position . '_data';
        $this->set($key, $data);
      }

      $key = "block_" . $position;
      if (isset($this->viewData[$key])) {
        $this->append($key, $block_result);
      } else {
        $this->set($key, $block_result);
      }
    }
  }

  /**
   * Constructor.
   * @param string $layout
   * @return Layout
   */
  public function Layout($layout = null) {
    $this->obj =& get_instance();
    $this->obj->load->config('block_config');
    if ($layout) {
      $this->setLayout($layout);
    } else {
      $this->setLayout(self::DEFAULT_LAYOUT);
    }
    $this->block_entity = $this->obj->config->item('block_entity');
    $this->block_config = $this->obj->config->item('block_config');
  }

  /**
   * Setter for a value to add to the view data array.
   * @param string $key
   * @param mixed $value
   */
  public function set($key, $value) {
    $this->viewData[$key] = $value;
  }
  
  /**
   * Getter for a value to get from the view data array.
   * @param string $key
   */
  public function get($key) {
    return $this->viewData[$key];
  }  

  /**
   * Append for a value to the view data array.
   * @param string $key
   * @param mixed $value
   */
  public function append($key, $value) {
    $this->viewData[$key] .= $value;
  }

  /**
   * Setter for a value to add to the block data array.
   * @param string $block
   * @param mixed $value
   */
  public function set_block_data($block, $value) {
    $this->block_data[$block] = $value;
  }
  
  /**
   * Getter for a value to get from block data array.
   * @param string $block
   */
  public function get_block_data($block) {
    $result = array();
    if (isset($this->block_data[$block])) {
      $result = $this->block_data[$block];
    }
    return $result;
  }  
  
  /**
   * Setter for a value witch is array to add to the view data array.
   * @return void
   */
  public function setArray($value) {
    if (is_array($value)) {
      foreach ($value as $key => $val) {
        $this->viewData[$key] = $val;
      }
    }
  }

  /**
   * Setter for path to layout.
   * @param string $layout
   */
  public function setLayout($layout) {
    $this->layout = self::DEFAULT_PATH_LAYOUT . '/' . $layout;
  }

  /**
   * Setter for path to includes folder.
   * @param string $module
   */
  public function setModule($module) {
    $this->module = self::DEFAULT_MODULE . '/' . $module;
  }

  /**
   * The view function.
   * @param string $view
   * @param array $data
   * @param bool $return
   * @return html to return
   */
  public function view($view, $return = FALSE, $set_blocks = TRUE) {
    if ($set_blocks) {
      $this->set_blocks();
    }
    $loadedData = array();
    $this->viewData[self::MODULE_VARIABLE] = $this->module;
    $loadedData[self::CONTENT_VARIABLE] = $this->render($this->module . "/" . $view, $this->viewData, TRUE);
    if ($return) {
      return $this->obj->load->view($this->layout, $loadedData, TRUE);
    } else {
      $this->obj->load->view($this->layout, $loadedData, FALSE);
    }
  }

  /**
   * Render a layout without any content variable.
   * @param bool $return
   * @return HTML or nothing
   */
  public function renderLayout($return = FALSE) {
    $this->set_blocks();
    if ($return) {
      return $this->obj->load->view($this->layout, $this->viewData, TRUE);
    } else {
      $this->obj->load->view($this->layout, $this->viewData, FALSE);
    }
  }

  /**
   *  Adapter for the native CI view
   * @param  $view
   * @param  $data
   * @param boolean $return
   * @return void
   */
  public function render($view, $data = array(), $return = FALSE) {
    return $this->obj->load->view($view, $data, $return);
  }

    /**
   *  Adapter for the native CI view
   * @param  $view
   * @param  $data
   * @param boolean $return
   * @return void
   */
  public function renderInModule($view, $data = array(), $return = FALSE) {
    return $this->obj->load->view($this->module . "/" . $view, $data, $return);
  }
  
  public function hasViewInModule($view) {
    if (!strstr($view, "php")) $view .= ".php";        
    return file_exists(APPPATH . "views/".$this->module ."/". $view);
  }

}
