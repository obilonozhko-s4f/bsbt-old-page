<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once LIBPATH . 'parser/BaseCompanyParser.php';

/**
 * BaseCitySiteCompanyParser.
 * 
 * @author Misha Karpenko
 * @link http://itirra.com
 */
class BaseCitySiteCompanyParser extends BaseCompanyParser {
  
  protected $baseUrl = 'http://www.62.ua/';
  
  protected $listQueries = array(
  	'item_container' => '/html/body/div/table/tr/td[2]/table',
    'item_data' => array(
      'details_url' => 'tr/td[3]/h2/a/@href'
    )
  );
  protected $itemQueries = array(
    'title'   => '/html/body/div/table/tr/td[2]/table[1]/tr/td[3]/h2',
    'content' => '/html/body/div/table/tr/td[2]/table[2]/tr/td[1]/table/tr/td[2]',
    'original_address' => '/html/body/div/table/tr/td[2]/table[1]/tr/td[3]/table/tr[1]/td[3]/strong',
  	'phone'   => '/html/body/div/table/tr/td[2]/table[1]/tr/td[3]/table/tr[2]/td[3]/strong',
  	'website' => '/html/body/div/table/tr/td[2]/table[1]/tr/td[3]/table/tr[3]/td[3]/strong/a/@href',
  	'image'   => '/html/body/div/table/tr/td[2]/table[1]/tr/td[1]/p/img/@src',
  );
  protected $itemImageFields = array('image');
  protected $itemPostProcess = array(
    'title' => array(
    	'html_entity_decode' => array(ENT_NOQUOTES, 'UTF-8'),
    	'htmlspecialchars',
    	'trim'),
    'content' => array(
    	'html_entity_decode' => array(ENT_NOQUOTES, 'UTF-8'),
      'fix_content_text',
    	'remove_newlines',
    	'trim',
    	'fix_white_space'),
  	'original_address' => array(
  		'html_entity_decode' => array(ENT_NOQUOTES, 'UTF-8'),
  		'strip_tags',
      'remove_string_part' => array('(Показать на карте)'),
  		'remove_newlines',
      'fix_white_space',
      'trim',
  		'add_prefix' => array('Украина, ')),
    'phone' => array(
    	'html_entity_decode' => array(ENT_NOQUOTES, 'UTF-8'),
    	'fix_white_space',
      'trim',
    	'split_phones'),
    'website' => array(
  		'trim'),
    'image' => array(
    	'trim' => array('/'),
    	'add_prefix' => array('http://www.62.ua/')),
  );
  
  protected $listPagePattern = '/\/page\/(?P<page>\d+)/';
  protected $listPageUrlSuffix = '/page/';
  
	/**
   * Constructor.
   */
  public function BaseCitySiteCompanyParser() {
    parent::BaseCompanyParser();
    
    // Set URL prefix for image
    $this->itemPostProcess['image']['add_prefix'] = array($this->baseUrl);
  }
  
	/**
   * (non-PHPdoc)
   * @see BaseParser::prepareRawData()
   */
  protected function prepareRawData(&$rawData) {
    parent::prepareRawData($rawData);
    
    $rawData = preg_replace('/id="choice"/', '', $rawData);
    $rawData = preg_replace('/id="comment\d+"/', '', $rawData);
  }
  
}