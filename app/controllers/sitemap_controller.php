<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . "controllers/base_project_controller.php";

/**
 * SiteMap_Controller
 * @author Алексей Бойко
 */
class Sitemap_Controller extends Base_Project_Controller {
	
  /**
   * Constructor.
   */
  public function Sitemap_Controller() {
    parent::Base_Controller();
    $this->load->library('common/DoctrineLoader');
    $this->load->library('common/Sitemap');
  }
  
  /**
   * Index action.
   */
  public function index() {
    
    $this->load->helper('common/file');
    
    $ServerUrl = site_url();
    
    $ServerUrl = substr($ServerUrl, 0, -1);
    
    // создаем новый xml документ
    $dom = new DOMDocument('1.0', 'UTF-8');
    $dom->formatOutput = true;
    
    $pages = array();
        
    $site_pages = ManagerHolder::get('Page')->getAllWhere(array('is_published' => TRUE));
    foreach($site_pages as $sp) {
        $pages[] = array(
            'url' => $sp['page_url']
        );
        $pages[] = array(
            'url' => '/ru' . $sp['page_url']
        );
        $pages[] = array(
            'url' => '/de' . $sp['page_url']
        );
        if(isset($sp['__children']) && !empty($sp['__children'])){
        	foreach ($sp['__children'] as $ch) {
        		$pages[] = array(
		            'url' => $ch['page_url']
		        );
				    $pages[] = array(
		            'url' => '/ru' . $ch['page_url']
		        );
		        $pages[] = array(
		            'url' => '/de' . $ch['page_url']
		        );
        	}
        }
    } 
    
    $news_items = ManagerHolder::get('NewsItem')->getAllWhere(array('is_published' => TRUE));
    foreach($news_items as $ni) {
        $pages[] = array(
            'url' => $ni['page_url']
        );
        $pages[] = array(
            'url' => '/ru' . $ni['page_url']
        );
        $pages[] = array(
            'url' => '/de' . $ni['page_url']
        );
    }
    
    $objects = ManagerHolder::get('Apartment')->getAllWhere(array('is_published' => TRUE));
    foreach($objects as $o) {
        $pages[] = array(
          'url' => '/object/' . $o['id']
        );
        $pages[] = array(
          'url' => '/ru/object/' . $o['id']
        );
        $pages[] = array(
          'url' => '/de/object/' . $o['id']
        );
    }
    
    $SITEMAP_NS = 'http://www.sitemaps.org/schemas/sitemap/0.9';
    $SITEMAP_NS_XSD = 'http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd';

    // ...and urlset (root) element
    $urlSet = $dom->createElementNS($SITEMAP_NS, 'urlset');
    $dom->appendChild($urlSet);
    $urlSet->setAttributeNS('http://www.w3.org/2000/xmlns/' ,
        'xmlns:xsi',
        'http://www.w3.org/2001/XMLSchema-instance');
    $urlSet->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance',
        'schemaLocation',
        $SITEMAP_NS . ' ' . $SITEMAP_NS_XSD);
    
        
    foreach($pages as $page) {

      $url = $ServerUrl . $page['url'];
      // create url node for this page
      $urlNode = $dom->createElementNS($SITEMAP_NS, 'url');
      $urlSet->appendChild($urlNode);
  
      // put url in 'loc' element
      $urlNode->appendChild($dom->createElementNS(
          $SITEMAP_NS,
          'loc', $url));
  /*  $urlNode->appendChild(
          $dom->createElementNS(
              $SITEMAP_NS,
              'changefreq',
              $page['changefreq'])
      );
  
      $urlNode->appendChild(
          $dom->createElementNS(
              $SITEMAP_NS,
              'priority',
              $page['priority'])
      );*/
    }

    $xml = $dom->saveXML();
    //сохраняем файл sitemap.xml на диск
    write_file('./sitemap.xml' , $xml);
    $this->sitemap->pingSE(site_url('sitemap.xml'), 'google');
    $this->sitemap->pingSE(site_url('sitemap.xml'), 'bing');
		echo 'Sitemap generated succesfully!';
  }

}