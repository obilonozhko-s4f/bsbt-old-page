<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Sitemap
| -------------------------------------------------------------------------
| This file lets you define "urls" for generating sitemap
|
| $config['sitemap']['entities'][]=Array('name' => 'Article');
*/

/*path to child sitemap files realtively to root_path (relative to site root path) */
$config['sitemap']['children_path'] = 'web/sitemap';

/* maximum url count in site.xml */
$config['sitemap']['max_url_count'] = 50000;
/* maximum file size of site.xml */
$config['sitemap']['max_file_size'] = 10000000;
/* search engine to ping */
$config['sitemap']['search_engine'] = 'google';

/* End of file sitemap.php */