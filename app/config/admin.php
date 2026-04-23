<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Admin base URL
|--------------------------------------------------------------------------
|
| WITH a trailing slash:
|	xadmin/
|
*/
$config['base_route'] = ADMIN_BASE_ROUTE; // see routes.php

/*
|--------------------------------------------------------------------------
| Admin menu items
|--------------------------------------------------------------------------
*/
$config['menu_items'] = array('apartment',
                              'apartmentreserv',
															'reservation',
                              'newsitem',
                              'page',
                              'objecttype',
                              'objectfeature',
                              'city',
                              'admin',
                              'settings',
					          					'messageproperties');
