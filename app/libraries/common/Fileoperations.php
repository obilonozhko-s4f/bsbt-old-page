<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * File Operations library
 * @author Alexei Chizhmakov (Itirra - http://itirra.com)
 */
class Fileoperations {

  const THUMB_MARKER = "_thumb";
  const CREATED_DATE_FORMAT = "Y-m-d";
  const ARCHIVE_ZIP_EXT = '.zip';
	const ARCHIVE_RAR_EXT = '.rar';

  /** CodeIgniter. */
  private $ci;
  
  /** Upload Path (WITHOUT SLASHES).  */
  private $uploadPath = "uploads";

  /** Base dir path.  */
  private $baseDir = "./web";

  /** The Upload library object. */
  private $upload_lib;

  /** The Image library object. */
  private $image_lib;

  /** Max memory limit to be assigned (in megabytes) **/
  private $maxMemoryLimit = 256;

  /** File info array. */
  public $file_info;

  /** prefix */
  private $smart_crop_prefix = "smart_crop_";

  /** Default values. */
  private $upload_lib_defaults = array(
    'allowed_types' => 'gif|jpg|png',
    'max_size' => '25000',
    'overwrite' => FALSE,
    'max_width' => '0',
    'max_height' => '0',
    'max_filename' => '0',
    'remove_spaces' => TRUE,
    'encrypt_name' => FALSE
  );

  /** Default values. */
  private $image_lib_defaults = array(
    'image_library' => 'gd2', //(GD, GD2, ImageMagick, NetPBM): Sets the image library to be used.
    'create_thumb' => TRUE, //(TRUE/FALSE): Tells the image processing function to create a thumb.
    'thumb_marker' => self::THUMB_MARKER, // Specifies the thumbnail indicator. It will be inserted just before the file extension.
    'dynamic_output' => FALSE, // (TRUE/FALSE): Determines whether the new image file should be written to disk or generated dynamically.
    'quality' => '100%', // (1 - 100%): Sets the quality of the image. The higher the quality the larger the file size.

  //--- Resize ---
    'maintain_ratio' => TRUE, //(TRUE/FALSE): Specifies whether to maintain the original aspect ratio when resizing or use hard values.
    'width' => '100', // Sets the width you would like the image set to.
    'height' => '100', // Sets the height you would like the image set to.
    'master_dim' => 'auto', // (width, height): Specifies what to use as the master axis when resizing or creating thumbs.

  //--- Crop ---
    'x_axis' => '10', // Sets the X coordinate in pixels for image cropping.
    'y_axis' => '10', // Sets the Y coordinate in pixels for image cropping.

  //--- Rotate ---
    'rotation_angle' => '90', // Specifies the angle of rotation when rotating images.

  //--- Watermark ---
    'wm_type' => 'text', // (text, overlay) Sets the type of watermarking that should be used.
    'padding' => null, // The amount of padding, set in pixels, that will be applied to the watermark to set it away from the edge of your images.
    'wm_vrt_alignment' => 'bottom', // (top, middle, bottom) Sets the vertical alignment for the watermark image.
    'wm_hor_alignment' => 'right', // (left, center, right) Sets the horizontal alignment for the watermark image.
    'wm_hor_offset' => null, // You may specify a horizontal offset (in pixels) to apply to the watermark position.
    'wm_vrt_offset' => null, // You may specify a vertical offset (in pixels) to apply to the watermark position.
    'wm_text' => "WATERMARK", // The text you would like shown as the watermark.
    'wm_font_path' => './lib/fonts/texb.ttf', // The server path to the True Type Font you would like to use.
    'wm_font_size' => '16', // The size of the text.
    'wm_shadow_color' => null, // The color of the drop shadow, specified in hex.
    'wm_shadow_distance' => '3', // The distance (in pixels) from the font that the drop shadow should appear.
    'wm_overlay_path' => null, // The server path to the image you wish to use as your watermark.
    'wm_opacity' => '50', // (1 - 100) Image opacity.
    'wm_x_transp' => '4', // If your watermark image is a PNG or GIF image, you may specify a color on the image to be "transparent".
    'wm_y_transp' => '4' // Along with the previous setting, this allows you to specify the coordinate to a pixel representative of the color you want to be transparent.
  );

  /** Upload Lib Options. */
  private $upload_lib_config = array();

  /** Image Lib Options. */
  private $image_lib_config = array();

  /** Whether to resize the image. */
  private $resizeImage = FAlSE;

  /** Whether to crop the image. */
  private $cropImage = FAlSE;

  /**
   * Constructor.
   * @return FileOperations
   */
  public function Fileoperations($newBaseDir = null) {
    if ($newBaseDir) {
      $this->baseDir = $newBaseDir;
    }

    $this->ci =& get_instance();
    // Instance of Upload library
    $this->reset_upload_lib_defaults();
    $this->ci->load->library('upload');
    $this->upload_lib = new CI_Upload();
    
    $this->ci->load->helper('common/itirra_language');

    // Instance of Image library
    $this->reset_image_lib_defaults();
    $this->ci->load->library('image_lib');
    $this->image_lib = new CI_Image_lib();
  }

  /**
   * Init_upload_lib.
   * Initializes the upload library.
   */
  private function init_upload_lib($newBaseDir = null) {
    if ($newBaseDir) $this->baseDir = $newBaseDir;
    $upload_dir_path_full = add_slash($this->baseDir) . $this->uploadPath;
    if (!file_exists($upload_dir_path_full)) mkdir($upload_dir_path_full, 0777, true);
    $this->upload_lib_config['upload_path'] = $upload_dir_path_full;
    $this->upload_lib->initialize($this->upload_lib_config);
  }

  /**
   * Init_image_lib.
   * Initializes the image library.
   */
  private function init_image_lib() {
    $this->image_lib->clear();
    $this->image_lib->initialize($this->image_lib_config);
  }

  /**
   * Upload.
   * @param string $inputName
   * @param bool $required
   * @return path of uploaded file
   */
  public function upload($input_name, $required = FALSE, $newBaseDir = null) {
    $result = FALSE;
    $this->init_upload_lib($newBaseDir);
    if (isset($_FILES[$input_name]) && !empty($_FILES[$input_name]["name"])) {
      $_FILES[$input_name]["name"] = to_translit($_FILES[$input_name]["name"]);
      if (!$this->upload_lib->do_upload($input_name)) {
        throw new Exception($this->upload_lib->display_errors('', ''));
      } else {
        $this->fill_uploaded_file_info();
        $result = TRUE;
      }
    } else if ($required) {
      throw new Exception("image.upload.messages.file_required");
    }
    return $result;
  }

  /**
   * Fill uploaded file info.
   */
  private function fill_uploaded_file_info() {
    $this->file_info['file_name'] = $this->upload_lib->file_name;
    $this->file_info['extension'] = $this->upload_lib->file_ext;
    $this->file_info['file_path'] = $this->upload_lib->upload_path;
    $this->file_info['web_path'] = add_slash($this->uploadPath);
    $this->file_info['size'] = round($this->upload_lib->file_size);
    $this->file_info['created_date'] = date(self::CREATED_DATE_FORMAT);
    if ($this->upload_lib->is_image() == 1) {
      $this->file_info['width'] = $this->upload_lib->image_width;
      $this->file_info['height'] = $this->upload_lib->image_height;
    }
    $this->file_info['mime_type'] = $this->upload_lib->file_type;
  }

  /**
   * Get file info by file path.
   * @param string $file_path
   */
  public function get_file_info($file_path) {
    $this->reset_file_info();
    $correct_file_name = to_translit(iconv("cp1251", "utf-8", basename($file_path)));
    if (basename($file_path) != $correct_file_name) {
      if (!$this->rename_file($file_path, $this->baseDir . surround_with_slashes($this->uploadPath) . $correct_file_name)) {
        return FALSE;
      }
      $file_path = $this->baseDir . surround_with_slashes($this->uploadPath) . $correct_file_name;
    }
    $this->file_info['file_name'] = $correct_file_name;
    $this->file_info['extension'] = strtolower($this->upload_lib->get_extension($this->file_info['file_name']));
    $this->file_info['file_path'] = add_slash(dirname($file_path));
    // Get the part of file_path beginning with the $this->uploadPath string
    $this->file_info['web_path'] = substr($this->file_info['file_path'], strpos($this->file_info['file_path'], $this->uploadPath));
    $this->file_info['size'] = round((@filesize($file_path) / 1024))<1?1:round((@filesize($file_path) / 1024));
    $this->file_info['created_date'] = date(self::CREATED_DATE_FORMAT);
    $file_ext_without_dot = strtolower(str_replace(".", "", $this->file_info['extension']));
    if (!$this->is_allowed_file_type($file_ext_without_dot)) {
      return FALSE;
    }
    @require APPPATH.'config/mimes'.EXT;
    if (!isset($mimes[$file_ext_without_dot])) {
      return FALSE;
    }
    $mime_type = $mimes[$file_ext_without_dot];
    if (is_array($mime_type)) {
      $mime_type = $mime_type[0];
    }
    $this->upload_lib->file_type = $mime_type;
    if ($this->upload_lib->is_image()) {
      if (FALSE !== ($D = @getimagesize($file_path))) {
        $this->file_info['width'] = $D['0'];
        $this->file_info['height'] = $D['1'];
      }
    }
    $this->file_info['mime_type'] = $this->upload_lib->file_type;
    return TRUE;
  }
  
 
  /**
   * Create Image Thumb
   * @param Image $image
   * @param string $thumbName
   * @param integer $thumbWidth
   * @param integer $thumbHeight
   */
  public function createImageThumb($image, $thumbName, $thumbWidth, $thumbHeight) {
    if (file_exists($image['file_path'] . $image['file_name'])) {
    	if (isset($image['width']) && isset($image['height']) && isset($image['file_path']) && isset($image['file_path'])) {
  	    if ($image['width'] > $thumbWidth || $image['height'] > $thumbHeight) {
  	      $this->set_image_lib_config_value("width", $thumbWidth);
  	      $this->set_image_lib_config_value("height", $thumbHeight);
  	      $this->set_image_lib_config_value("thumb_marker", $thumbName);
  	      $this->image_resize($image['file_path'], $image['file_name']);
  	    } else {
  	      @copy($image['file_path'] . $image['file_name'], $image['file_path'] . str_replace($image['extension'], $thumbName . $image['extension'], $image['file_name']));
  	    }
    	}
    }
  }
  
  /**
   * Creates for each Image from an array of necessary Thumbs
   * @param array $images
   * @param array $thumbs
   * Example:
   *  images:																  thumbs:				     						 result:
   *   ARRAY "example.jpg" 	 			 -> 400x400				   _small 	-> 140x140					   example_small.jpg 	  			-> 140x140
   *   ARRAY "example_b_small.jpg" -> 90x90																					   example_b_small_small.jpg	-> 90x90
   */
  public function createImageThumbs($images, $thumbs) {
    if(isset($images) && !empty($images) && is_array($images)
       && isset($thumbs) && !empty($thumbs) && is_array($thumbs)) {
      foreach ($images as $image) {
        foreach ($thumbs as $thumbName => $thumbDimensions) {
          if(strpos($image['file_name'], $thumbName . $image['extension']) === FALSE) {
            $this->createImageThumb($image, $thumbName, $thumbDimensions['width'], $thumbDimensions['height']);
          }
        }
      }
    }
  }
  



  /**
   * Create Smart Crop Thumb
   * @param Image $image
   * @param string $thumbName
   * @param integer $thumbWidth
   * @param integer $thumbHeight
   */
  public function createSmartCropThumb($image, $thumbName, $thumbWidth, $thumbHeight) {
    if ($image['width'] > $thumbWidth || $image['height'] > $thumbHeight) {
      $smartCrop = $thumbWidth / $thumbHeight;
      $this->smart_crop($image["file_path"], $image["file_name"], $smartCrop, TRUE);
      $smartCropFileName = $this->get_smart_crop_file_name($image["file_name"]);

      $this->set_image_lib_config_value("width", $thumbWidth);
      $this->set_image_lib_config_value("height", $thumbHeight);
      $this->set_image_lib_config_value("thumb_marker", $thumbName);

      $this->image_resize($image["file_path"], $smartCropFileName);
      $this->delete_file($smartCropFileName, $image["file_path"]);
    }
  }



  /**
   * Renamefile
   * @param string $oldfile
   * @param string $newfile
   * @return bool
   */
  private function rename_file($oldfile, $newfile) {
    if (copy ($oldfile, $newfile)) {
      unlink($oldfile);
      return TRUE;
    }
    return FALSE;
  }


  /**
   * Is allowed file type.
   * @param string $extension
   * @return bool
   */
  private function is_allowed_file_type($extension) {
    $extension = strtolower($extension);
    $allowed_types = $this->upload_lib_defaults["allowed_types"];
    if(empty($allowed_types)){
      // everything is allowed
      return TRUE;
    }
    $allowed_types_arr = explode("|", $allowed_types);
    if (in_array($extension, $allowed_types_arr)) {
      return TRUE;
    }
    return FALSE;
  }
  
  
 /**
  * BETA VERSION !!
  * DO NOT USE OR REWRITE FIRST! 
  * Is allowed file mime.
  * @param string $extension
  * @return bool
  */
  public function is_allowed_file_mime($mime) {
    $allowed_types = $this->upload_lib_defaults["allowed_types"];
    $allowed_types_arr = explode("|", $allowed_types);
    if(empty($allowed_types)){
      // everything is allowed
      return TRUE;
    }
    foreach ($allowed_types_arr as $val) {
      $typeMime = $this->upload_lib->mimes_types(strtolower($val));
      if(is_array($typeMime) && in_array($mime, $typeMime) || $mime == $typeMime) { 
        return TRUE;
      }
    }
    return FALSE;
  }


  /**
   * Create a folder.
   * @param string $name
   * @return bool
   */
  public function create_folder($name) {
    $name = to_translit($name);
    $name = str_replace(' ', '_', $name);
    return @mkdir($this->baseDir . '/' . $this->uploadPath . '/' . $name, 0777, true);
  }

  /**
   * Remove folder
   * @param string $dir
   */
  public function remove_folder($dir) {
    $dir = $this->baseDir . '/' . $this->uploadPath . '/' . $dir;
    if (is_dir($dir)) {
      $objects = scandir($dir);
      foreach ($objects as $object) {
        if ($object != '.' &&  $object != '..') {
          return FALSE;
        }
      }
      @rmdir($dir);
    }
    return TRUE;
  }

  /**
   * GetFolders.
   * @return array
   */
  public function get_folders() {
    $result = array();
    if ($handle = opendir($this->baseDir . '/' . $this->uploadPath)) {
      while (FALSE !== ($file = readdir($handle))) {
        if ($file != "." && $file != ".." && filetype($this->baseDir . '/' . $this->uploadPath . '/' . $file) == 'dir') {
          $result[] = $file;
        }
      }
    }
    return $result;
  }


  /**
   * Image Resize
   * @param string $file_path
   * @param string $file_name
   * @param bool $increaseMemoryLimit
   */
  public function image_resize($file_path, $file_name) {
    $this->image_lib_config['source_image'] = $file_path . $file_name;
    $this->image_lib_config['new_image'] = $file_path . str_replace($this->smart_crop_prefix, "", $file_name);
    $this->image_lib_config['create_thumb'] = TRUE;
    $this->image_lib_config['maintain_ratio'] = TRUE;
    
    $this->init_image_lib();
    
    if (!$this->image_lib->resize()) {
      throw new Exception($this->image_lib->display_errors('', ''));
    }
    return $this->image_lib->full_dst_path;
  }



  /**
   * Image Crop
   * @param string $file_path
   * @param string $file_name
   */
  public function image_crop($file_path, $file_name) {

    $this->image_lib_config['source_image'] = $file_path . $file_name;
    $this->init_image_lib();
    if (!$this->image_lib->crop()) {
      throw new Exception($this->image_lib->display_errors('', ''));
    } else {
      $result = TRUE;
    }
    return $result;
  }

  public function get_smart_crop_file_name($file_name) {
    return $this->smart_crop_prefix . $file_name;
  }

  public function smart_crop($file_path, $file_name, $neededScale) {

    $this->image_lib_config['source_image'] = $file_path . $file_name;

    $imageSize = getimagesize($this->image_lib_config['source_image']);
    $newW = $imageSize[0];
    $newH = $imageSize[1];

    $x = round($neededScale * 100);
    $y = 100;
    $dx = $imageSize[0] / $x;
    $dy = $imageSize[1] / $y;

    $d = ($dx > $dy) ? $dy : $dx;

    $newH = $y * $d;
    $newW = $x * $d;

    //var_dump($newW . " x " . $newH);

    $this->image_lib_config['new_image'] = "$file_path" . $this->get_smart_crop_file_name($file_name);
    $this->image_lib_config['create_thumb'] = FALSE;
    $this->image_lib_config['maintain_ratio'] = FALSE;
    $this->image_lib_config['x_axis'] =  round(($imageSize[0] - $newW) / 2);
    $this->image_lib_config['y_axis'] = round(($imageSize[1] - $newH) / 2);
    $this->image_lib_config['width'] = $newW;
    $this->image_lib_config['height'] = $newH;
    
    
    $this->init_image_lib();

    if (!$this->image_lib->crop()) {

      throw new Exception($this->image_lib->display_errors('', ''));
    }
    return $this->image_lib->full_dst_path;
  }


  /**
   * Image Rotate
   * @param string $file_path
   * @param string $file_name
   */
  public function image_rotate($file_path, $file_name) {
    $result = FALSE;
    $this->image_lib_config['source_image'] = $file_path . $file_name;
    $this->init_image_lib();
    if (!$this->image_lib->rotate()) {
      throw new Exception($this->image_lib->display_errors('', ''));
    } else {
      $result = TRUE;
    }
    return $result;
  }


  /**
   * Image Rotate
   * @param string $file_path
   * @param string $file_name
   */
  public function image_watermark($file_path, $file_name) {
    $result = FALSE;
    $this->image_lib_config['source_image'] = $file_path . $file_name;
    $this->init_image_lib();
    if (!$this->image_lib->watermark()) {
      throw new Exception($this->image_lib->display_errors('', ''));
    } else {
      $result = TRUE;
    }
    return $result;
  }


  /**
   * Get an array with all files and thier attributes in upload dir.
   * @return array
   */
  public function get_all_files_from_upload_dir() {
    $result = array();
    if ($handle = opendir($this->baseDir . '/' . $this->uploadPath)) {
      while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != "..") {
          $full_path = realpath($this->baseDir . '/' . $this->uploadPath . '/' . $file);
          if ($this->get_file_info($full_path)) {
            $result[] = $this->file_info;
          } else {
            @unlink($full_path);
          }
        }
      }
      closedir($handle);
    }
    return $result;
  }
  
  /**
   * Remove all files in upload dir.
   * @return array
   */
  public function remove_files_from_upload_dir() {
    if ($handle = opendir($this->baseDir . '/' . $this->uploadPath)) {
      while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != "..") {
          $full_path = realpath($this->baseDir . '/' . $this->uploadPath . '/' . $file);
          @unlink($full_path);
        }
      }
      closedir($handle);
    }
  }
  

  /**
   * Set upload lib config value
   * @param string $key
   * @param string $value
   */
  public function set_upload_lib_config_value($key, $value) {
    $this->upload_lib_config[$key] = $value;
  }

  /**
   * Set upload lib config value
   * @param string $key
   * @param string $value
   */
  public function get_upload_lib_config_value($key) {
    return $this->upload_lib_config[$key];
  }

  /**
   * Set image lib config value
   * @param string $key
   * @param string $value
   */
  public function set_image_lib_config_value($key, $value) {
    $this->image_lib_config[$key] = $value;
  }

  /**
   * ResetUploadDefaults.
   */
  public function reset_upload_lib_defaults() {
    $this->upload_lib_config = $this->upload_lib_defaults;
  }

  /**
   * ResetImageDefaults.
   */
  public function reset_image_lib_defaults() {
    $this->image_lib_config = $this->image_lib_defaults;
  }

  /**
   * SetBaseDir.
   * @param string $baseDir
   */
  public function set_base_dir($baseDir) {
    $this->baseDir = $baseDir;
  }

  /**
   * SetUploadsDir.
   * @param string $uploadsDir
   */
  public function set_uploads_dir($uploadsDir) {
    $this->uploadPath = $uploadsDir;
  }

  /**
   * AddToUploadsDir.
   * @param string $folder
   */
  public function add_folder_to_uploads_dir($folder) {
    $this->uploadPath .= surround_with_slashes($folder);
    $dir = $this->baseDir . '/' . $this->uploadPath;
    if (!is_dir($dir)) {
      @mkdir($dir, 0777, true);
    }
  }

  /**
   * GetUploadDir.
   * @return string
   */
  public function get_upload_dir() {
    return $this->uploadPath;
  }

  /**
   * ResetFileInfo.
   */
  public function reset_file_info() {
    $this->file_info = array();
  }

  /**
   * delete_file
   * @param $file_name
   */
  public function delete_file($file_name, $file_path) {
    $path = realpath($file_path . '/' . $file_name);
    return @unlink($path);
  }
  
  /**
   * Copy file
   * @param array $file - input file
   * @param string $new_dir - new uploads dir
   * @param bool $remove - remove file after copying
   */
  public function copy_file($file, $new_dir, $remove = FALSE) {
    $dir = $this->baseDir . '/' . $this->uploadPath . surround_with_slashes($new_dir);
    if (!is_dir($dir)) {
      @mkdir($dir, 0777, true);
    }
    $newLoc = $dir . $file['file_name'];
    @copy($file['file_path'] . $file['file_name'], $newLoc);
    if ($remove) {
      @unlink($file['file_path'] . $file['file_name']);
    }
    return $newLoc;
  }

}
