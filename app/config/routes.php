<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
| 	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['scaffolding_trigger'] = 'scaffolding';
|
| This route lets you set a "secret" word that will trigger the
| scaffolding feature for added security. Note: Scaffolding must be
| enabled in the controller in which you intend to use it.   The reserved
| routes must come before any wildcard or regular expression routes.
|
*/

/*
|--------------------------------------------------------------------------
| Route constants
|--------------------------------------------------------------------------
|
*/
define('ADMIN_BASE_ROUTE', 'bsadmin');

global $gLangs;
global $CFG;

$urlLangs = $gLangs;
$language = $CFG->item('language');
$defLang = array_search($language, $gLangs);
unset($urlLangs[$defLang]);
$urlLangs = array_keys($urlLangs);


$lang = '(' . implode('|', $urlLangs) . ')';
$langPrefix = '^((' . implode('|', $urlLangs) . ')/)?';

$route['sitemap-generate'] = "sitemap_controller/index";

$route['default_controller'] = "home_controller";
$route[$lang . '(/)?$'] = $route['default_controller'];

$route[$langPrefix . 'apartments'] = "apartment_controller/index";
$route[$langPrefix . 'apartments/(.*)'] = "apartment_controller/index/$3";

$route[$langPrefix . 'object'] = "object_controller/index";
$route[$langPrefix . 'object/(.*)'] = "object_controller/index/$3";

$route[$langPrefix . 'reservation'] = "reservation_controller/index";
$route[$langPrefix . 'reservation/reserv'] = "reservation_controller/reserv";
$route[$langPrefix . 'reservation/thank_you'] = "reservation_controller/thankyou";

$route[$langPrefix . 'news'] = "news_controller/index";
$route[$langPrefix . 'news/(.*)'] = "newsitem_controller/index/$3";


$route[$langPrefix . 'ajax/(.*)'] = "ajax_controller/$3";

//------ ADMIN --------
$route[ADMIN_BASE_ROUTE . '/login'] = "admin/common/xadmin/login";
$route[ADMIN_BASE_ROUTE . '/forgot_password'] = "admin/common/xadmin/forgot_password";
$route[ADMIN_BASE_ROUTE . '/logout'] = "admin/common/xadmin/logout";
$route[ADMIN_BASE_ROUTE . '/change_info'] = "admin/common/xadmin/change_info";
$route[ADMIN_BASE_ROUTE . '/admin'] = "admin/common/xadmin_admin/index";
$route[ADMIN_BASE_ROUTE . '/admin/(.*)'] = "admin/common/xadmin_admin/$1";
$route[ADMIN_BASE_ROUTE . '/resource'] = "admin/common/xadmin_resource/index";
$route[ADMIN_BASE_ROUTE . '/resource/(.*)'] = "admin/common/xadmin_resource/$1";
$route[ADMIN_BASE_ROUTE . '/messageproperties'] = "admin/common/xadmin_messageproperties/index";
$route[ADMIN_BASE_ROUTE . '/messageproperties/(.*)'] = "admin/common/xadmin_messageproperties/$1";
$route[ADMIN_BASE_ROUTE . '/(.*)/(.*)/(.*)'] = "admin/xadmin_$1/$2/$3";
$route[ADMIN_BASE_ROUTE . '/(.*)/(.*)/(page\d+)'] = "admin/xadmin_$1/printlist/$3";
$route[ADMIN_BASE_ROUTE . '/(.*)/(page\d+)'] = "admin/xadmin_$1/index/$2";
$route[ADMIN_BASE_ROUTE . '/(.*)/(.*)'] = "admin/xadmin_$1/$2";
$route[ADMIN_BASE_ROUTE . '/(.*)'] = "admin/xadmin_$1/index";
$route[ADMIN_BASE_ROUTE . ''] = "admin/common/xadmin/index";

$route[$langPrefix . '(.*)'] = "page_controller/index/$3";

/* End of file routes.php */
/* Location: ./system/application/config/routes.php */