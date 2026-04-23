<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/** Utility function. */
function is_not_array($var) {
  return !is_array($var);
}

/**
 * Csv library
 * Itirra - http://itirra.com
 */
class Csv {

  /** CSV Sepparator. */
  protected $sep = ';';

  /** Line break. */
  protected $lbr = "\r\n";

  /** File responce header mime. */
  protected $mime = 'application/csv';

  /** CSV Data. X;Y\nZ;V. */
  protected $data = "";
  
  /** File Path. */
  protected $filePath;
  
  /** Current row. */
  protected $currentRow = 1;
  
  /** Input file encoding. */
  protected $inputFileEncoding = 'cp1251';
        
  /** Max length in bytes of one line. */
  protected $maxLineLength = 5120;
  
  /** Current position of file to read. */
  protected $curPosition;

  /**
   * Constructor.
   * @return Csv
   */
  public function Csv () {
    $this->clean();
  }
  
  // -----------------------------------------------------------------------------------------
  // ------------------------------------ EXPORT METHODS -------------------------------------
  // -----------------------------------------------------------------------------------------

  /**
   * Add header to CSV string.
   * @param array $values
   */
  public function addHeader(array $values) {
    if (!empty($values)) {
      $values = $this->prepareValues($values);
      $this->addRowToCsvData($values);
    }
  }

  /**
   * Add rows to CSV string.
   * @param array $values
   */
  public function addRows(array $values) {
    if (!empty($values)) {
      $values = $this->prepareValues($values);
      foreach ($values as $k => $row) {
        $row = array_filter($row, 'is_not_array');
        $this->addRow($row, FALSE);
      }
    }
  }

  /**
   * Add row to CSV string.
   * @param array $values
   * @param bool $prepare
   */
  public function addRow(array $values, $prepare = TRUE) {
    if (!empty($values)) {
      if ($prepare) {
        $values = array_filter($values, 'is_not_array');
        $values = $this->prepareValues($values);
      }
      $this->addRowToCsvData($values);
    } else {
      $this->addRowToCsvData(array());
    }
  }

  /**
   * Flush CSV file to outout.
   * @param string $fileName
   */
  public function flushFile($fileName = 'example.csv') {
    header('Content-type: ' . $this->mime);
    header('Content-Disposition: attachment; filename="' . $fileName . '"');
    $result = iconv("utf-8", "cp1251", $this->data);
    die($result);
  }
  
  public function getFile($fileName = 'example.csv') {
    $dir = './lib/cache/';
    $fPath = $dir . $fileName;
    $fh = fopen($fPath, 'w');
    fwrite($fh, iconv("utf-8", "cp1251", $this->data));
    fclose($fh);
    return realpath($fPath);
  }
  
  
  // -----------------------------------------------------------------------------------------
  // ------------------------------------ IMPORT METHODS -------------------------------------
  // -----------------------------------------------------------------------------------------
  
  
  public function readFile($filePath) {
    $this->filePath = $filePath;
  }
  
  public function readRow() {
    $fh = @fopen($this->filePath, "r");
    if ($this->curPosition) {
      fseek($fh, $this->curPosition);
    }
    $data = fgetcsv($fh, $this->maxLineLength, $this->sep);
    if ($data !== FALSE) {
      if ($this->inputFileEncoding != "utf-8") {
        foreach ($data as &$el) {
          $el = @iconv("cp1251", "utf-8", $el);
        }
      }
    }
    $this->curPosition = ftell($fh);
    fclose($fh);
    return $data;
  }
  
  
  
  
  // -----------------------------------------------------------------------------------------
  // ------------------------------------ COMMON METHODS -------------------------------------
  // -----------------------------------------------------------------------------------------

  /**
   * Clean internal data.
   */
  public function clean() {
    $this->data = "";
    $this->currentRow = 1;
  }

  /**
   * Get current CVS String.
   */
  public function getCsvString() {
    return $this->data;
  }
    
  // -----------------------------------------------------------------------------------------
  // ---------------------------------- INTERNAL METHODS -------------------------------------
  // -----------------------------------------------------------------------------------------
  

  function getcsvline($list,  $seperator, $enclosure, $newline = "" ){
    $fp = fopen('php://temp', 'r+');

    fputcsv($fp, $list, $seperator, $enclosure );
    rewind($fp);

    $line = fgets($fp);
    if( $newline and $newline != "\n" ) {
      if( $line[strlen($line)-2] != "\r" and $line[strlen($line)-1] == "\n") {
        $line = substr_replace($line,"",-1) . $newline;
      } else {
        die( 'original csv line is already \r\n style' );
      }
    }
    return $line;
  }



  /**
   * Adds a row to CVS string
   * @param array $values
   */
  protected function addRowToCsvData(array $values) {
    $outstream = fopen("php://temp", 'r+');
    fputcsv($outstream, $values, $this->sep, '"');
    rewind($outstream);
    $csv = stream_get_contents($outstream);
    fclose($outstream);
    $this->data .= $csv;
  }
  
  
  /**
   * Prepare an array of values
   * @param array $values
   * @return array
   */
  protected function prepareValues(array $values) {
    $result = array();
    if (is_array($values) || !empty($values)) {
      foreach ($values as $k => $val) {
        if (is_array($val)) {
          $result[] = $this->prepareValues($val);
        } else {
          $result[] = $this->prepareValue($val);
        }
      }
    }
    return $result;
  }

  /**
   * Prepare one value.
   * Surround with "" and replace " with "" inside
   * @param string $value
   * @return string
   */
  protected function prepareValue($value) {
    $result = $value;
    if (is_bool($result)) {
      if ($value) {
        $result = '1';
      } else {
        $result = '0';
      }
    } else if (strpos($result, "\n") !== FALSE) {
//      $result = '"' . $result . '"';
//      $result = preg_replace("/\t/", "\\t", $result);
//      $result = preg_replace("/\r?\n/", "\\n", $result);
      $result = str_replace("\n", PHP_EOL, $result);
    }
    return $result;
  }

}
?>