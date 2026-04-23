<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once './lib/phpExcel/PHPExcel.php';

/**
 * PhpExcel class.
 *
 * I'm Fucking Amazing!
 *
 * Documentation:
 * http://phpexcel.codeplex.com/documentation
 *
 * @version 1.0
 * @since 16.04.11
 * @author Alexei Chizhmakov (Itirra - www.itirra.com)
 */
class PhpExcelLib {

  /** PHPExcel Objcet. */
  private $objPHPExcel;

  /** Current worksheet number. */
  private $currentWorksheet = 0;

  /**
   * PhpExcel Class Options.
   * @var array
   */
  private $options = array();

  /**
   * Constructor.
   * @param array $options
   */
  public function PhpExcelLib($options = array()) {
    if (!empty($options)) {
      $this->options = array_merge($this->options, $options);
    }
    $this->objPHPExcel = new PHPExcel();
    $this->objPHPExcel->setActiveSheetIndex($this->currentWorksheet);
    
//    $this->objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
//    ->setLastModifiedBy("Maarten Balliauw")
//    ->setTitle("Office 2007 XLSX Test Document")
//    ->setSubject("Office 2007 XLSX Test Document")
//    ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
//    ->setKeywords("office 2007 openxml php")
//    ->setCategory("Test result file");
//    $this->objPHPExcel->getActiveSheet()->setShowGridLines(false);
//    $this->objPHPExcel->getActiveSheet()->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_NONE);
//    $this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
//    $this->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
//    $this->objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
//    $this->objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
//    $this->objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
  }
  
  /**
   * Read Template
   * @param string $fileName
   */
  public function readTemplate($fileName) {
    $this->objPHPExcel = PHPExcel_IOFactory::load($fileName);
  }

  
  /**
   * Get all cell values matrix
   * @param bool $excludeEmpty
   * @param bool $getTextOnly
   */
  public function getAllCellValuesMatrix($excludeEmpty = TRUE, $getTextOnly = TRUE) {
    $result = array();
    $objWorksheet = $this->getActiveWorksheet();
    $highestRow = $objWorksheet->getHighestRow();
    $highestColumn = $objWorksheet->getHighestColumn();
    $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
    for ($row = 1; $row <= $highestRow; ++$row) {
      for ($col = 0; $col <= $highestColumnIndex; ++$col) {
        $val = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
        if (!empty($val) || !$excludeEmpty) {
          if ($getTextOnly && get_class($val) == 'PHPExcel_RichText') {
            $val = $val->getPlainText();
          }
          $result[$col][$row] = $val;
        }
      }
    }
    return $result;
  }
  
  
  /**
   * ReplaceVariables
   * @param array $matrix
   * @param array $data
   */
  public function replaceVariables($matrix, $data) {
    foreach ($matrix as $x => &$v) {
      foreach ($v as $y => &$value) {
        foreach ($data as $dataKey => $dataValue) {
          if (is_array($dataValue)) {
            if (strpos($value, '{each.' . $dataKey) !== FALSE) {
              foreach ($dataValue as $index => $dvalue) {
                if (strpos($value, '{each.' . $dataKey . '.' . $index) !== FALSE) {
                  $attr = rtrim(str_replace('{each.' . $dataKey . '.' . $index . '.', '', $value), '}');
                  if ($attr == 'n') {
                    $dvalue[$attr] = $index + 1;
                  }
                  if (isset($dvalue[$attr])) {
                    $value = str_replace('{each.' . $dataKey . '.' . $index . '.' . $attr . '}', $dvalue[$attr], $value);
                  }
                }
              }
            }
          } else {
            if (strpos($value, '{' . $dataKey . '}') !== FALSE) {
              $value = str_replace('{' . $dataKey . '}', $dataValue, $value);
            }
            
          }
        }
      }
    }
    return $matrix;
  }
  
  /**
   * Preocess each
   * @param array $matrix
   * @param string $eachKey
   * @param count $count
   */
  public function processEach($matrix, $eachKey, $count) {
    $count = $count - 1;
    $objWorksheet = $this->getActiveWorksheet();
    $row = 0;
    foreach ($matrix as $x => &$v) {
      foreach ($v as $y => &$value) {
        if (strpos($value, '{each.' . $eachKey) !== FALSE) {
          $this->set($x, $y, str_replace('{each.' . $eachKey, '{each.' . $eachKey . '.0', $value));
          $row = $y;
        }
      }
    }
    if ($count > 0) {
      $this->insertRows($row, $count);
    }
  }
  
  /**
   * Insert rows
   * @param integer $y
   * @param integer $count
   */
  public function insertRows($y, $count) {
    $objWorksheet = $this->getActiveWorksheet();
    $objWorksheet->insertNewRowBefore($y + 1, $count);
    $lastRow = $y + 1 + $count;
    $firstRowCells = array();
    $rowCount = 1;
    foreach ($objWorksheet->getRowIterator() as $row) {
      if ($row->getRowIndex() == $y) {
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false);
        foreach ($cellIterator as $cell) {
          $firstRowCells[] = $cell;
        }
      } else {
        if (!empty($firstRowCells) && $row->getRowIndex() < $lastRow) {
          $cellIterator = $row->getCellIterator();
          $cellIterator->setIterateOnlyExistingCells(true);
          $count = 0;
          foreach ($cellIterator as $cell) {
            $cell->setValue(str_replace('0', $rowCount, $firstRowCells[$count]->getValue()));
            $count++;
          }
          $merged = array();
          // Check if cell is merged
          foreach ($firstRowCells as $fCell) {
            foreach ($objWorksheet->getMergeCells() as $mcells) {
              if ($fCell->isInRange($mcells)) {
                list($rangeStart, $rangeEnd) = PHPExcel_Cell::rangeBoundaries($mcells);
                if (!isset($merged[$rangeStart[0]][$rangeEnd[0]])) {
                  $merged[$rangeStart[0]][$rangeEnd[0]] = TRUE;
                  $objWorksheet->mergeCellsByColumnAndRow($rangeStart[0] - 1, $row->getRowIndex(), $rangeEnd[0] - 1, $row->getRowIndex());
                }
              }
            }
          }
          $rowCount++;
        }
      }
    }
  }
  
  /**
   * Delete rows
   * @param integer $rowNum
   * @param integer $num
   */
  public function deleteRows($rowNum, $num = 1) {
    $this->getActiveWorksheet()->removeRow($rowNum, $num);
  }

  /**
   * Set a value.
   * @param integer $column
   * @param integer $row
   * @param string $value
   */
  public function set($column, $row, $value) {
    $this->getActiveWorksheet()->setCellValueByColumnAndRow($column, $row, $value);
  }
  
  
  /**
   * Set a matrix of values.
   * @param array $matrix
   */
  public function setMatrix($matrix) {
    foreach ($matrix as $x => $v) {
      foreach ($v as $y => $value) {
        $this->set($x, $y, $value);
      }
    }
    
  }
  
  /**
   * Set Column Widht.
   * @param integer $column
   * @param integer $value
   */
  public function setColumnWidth($column, $value) {
    $this->getActiveWorksheet()->getColumnDimensionByColumn($column)->setWidth($value);
  }
  
  /**
   * Set Horizontal align in cell.
   * @param integer $column
   * @param integer $row
   * @param string $value (left, right, center)
   */
  public function setHAlign($column, $row, $value) {
    $this->getActiveWorksheet()->getStyleByColumnAndRow($column,$row)->getAlignment()->setHorizontal($value);
  }
  
  /**
   * Merger Cells.
   * @param integer $column1
   * @param integer $row1
   * @param integer $column2
   * @param integer $row2
   */
  public function merge($column1, $row1, $column2, $row2) {
    $this->getActiveWorksheet()->mergeCellsByColumnAndRow($column1, $row1, $column2, $row2);
  }
  
  /**
   * Set Bold.
   * @param integer $column
   * @param integer $row
   */
  public function setBold($column, $row) {
    $this->getActiveWorksheet()->getStyleByColumnAndRow($column, $row)->getFont()->setBold(true);
  }
  
  /**
   * Get Active Worksheet
   * @return PHPExcel_Worksheet
   */
  public function getActiveWorksheet() {
    return $this->objPHPExcel->getActiveSheet();
  }

  /**
   * Set Active Worksheet.
   * @param integer $num
   */
  public function setActiveWorksheet($num) {
    $this->currentWorksheet = $num;
    $this->objPHPExcel->setActiveSheetIndex($this->currentWorksheet);
  }
  
  /**
   * Get Xlsx.
   * @param string $fileName
   */
  public function getXlsx($fileName, $output = FALSE) {
    $objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel2007');
    if ($output) {
      header('Content-Type: application/excel');
      header('Content-Disposition: attachment;filename="' . $fileName . '.xlsx"');
      header('Cache-Control: max-age=0');
      $objWriter->save('php://output');
      exit;
    }
    $objWriter->save('./lib/cache/' . $fileName . '.xlsx');
    return './lib/cache/' . $fileName . '.xlsx';
  }
  
  /**
   * Get Xls.
   * @param string $fileName
   */
  public function getXls($fileName, $output = FALSE) {
    $objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel5');
    if ($output) {
      header('Content-Type: application/excel');
      header('Content-Disposition: attachment;filename="' . $fileName . '.xls"');
      header('Cache-Control: max-age=0');
      $objWriter->save('php://output');
      exit;
    }
    $objWriter->save('./lib/cache/' . $fileName . '.xls');
    return './lib/cache/' . $fileName . '.xls';
  }

  /**
   * Get PDF.
   * @param string $fileName
   */
  public function getPdf($fileName, $output = FALSE) {
    $objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'PDF');
    if ($output) {
      header('Content-Type: application/pdf');
      header('Content-Disposition: attachment;filename="' . $fileName . '.pdf"');
      header('Cache-Control: max-age=0');
      $objWriter->save('php://output');
      exit;
    }
    $objWriter->save('./lib/cache/' . $fileName . '.pdf');
    return './lib/cache/' . $fileName . '.pdf';
  }

}
?>