<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once LIBPATH . 'parser/BaseCompanyParser.php';

/**
 * MapiaUaCompanyParser.
 * @author Misha Karpenko
 * @link http://itirra.com
 */
class MapiaUaCompanyParser extends BaseCompanyParser {
  
  protected $baseUrl = 'http://mapia.ua/';
  
  protected $listQueries = array(
  	'item_container' => '/html/body/div/div[2]/div[1]/div[2]/div/div',
    'item_data' => array(
      'details_url' => 'div[2]/h3/strong/a/@href'
    )
  );
  protected $itemQueries = array(
    'title' => '/html/body/div/div[2]/div[2]/div[2]/h1',
  	'content' => '/html/body/div/div[2]/div[2]/div[4]/div',
    'news' => '/html/body/div/div[2]/div[2]/div[2]/div[2]',
    'original_address' => '//div[@typeof="v:Address"]',
  	'phone' => '//span[@property="v:tel"]',
  	'website' => '//span[@property="v:url"]/a/@href',
  	'email' => '//span[@property="v:email"]',
  	'image' => '//img[@class="feature-logo"]/@src',
  	'city' => '//span[@property="v:locality"]'
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
  	'news' => array(
  		'html_entity_decode' => array(ENT_NOQUOTES, 'UTF-8'),
      'strip_tags' => array('<p><ul><li><a><b><i><h1><h2><h3><h4><h5><h6>'),
    	'remove_newlines',
    	'trim',
    	'fix_white_space'),
  	'original_address' => array(
  		'html_entity_decode' => array(ENT_NOQUOTES, 'UTF-8'),
  		'strip_tags',
  		'remove_newlines',
  		'add_prefix' => array('Украина, ')),
    'phone' => array(
    	'html_entity_decode' => array(ENT_NOQUOTES, 'UTF-8'),
    	'fix_white_space',
    	'split_phones'),
  	'website' => array(
  		'trim'),
    'image' => array(
    	'trim' => array('/'),
    	'add_prefix' => array('http://mapia.ua/')),
    'city' => array(
      'html_entity_decode' => array(ENT_NOQUOTES, 'UTF-8'),
      'trim')
  );

  protected $listPagePattern = '/\?page=(?P<page>\d+)/';
  protected $listPageUrlSuffix = '?page=';
  
  /**
   * (non-PHPdoc)
   * @see BaseParser::prepareRawData()
   */
  protected function prepareRawData(&$rawData) {
    parent::prepareRawData($rawData);
    
    // Add encoding
    $rawData = preg_replace('/<head[^>]*>/', '<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />', $rawData);

    // Remove photo id
    $rawData = str_replace('id="photos"', '', $rawData);
  }
  
}