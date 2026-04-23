<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Thumbnail names and sizes
|--------------------------------------------------------------------------
|
| The format is following:
| $config['thumbs'][<entityName>_<fieldName>]['width'] = X;
| $config['thumbs'][<entityName>_<fieldName>]['height'] = Y;
|
| <entityName> - Entity name in LOWER CASE
| <fieldName> - Field name as it is in the entity manager
|
| If you want your thumb to be EXACTLY of width & height you set, just add:
| $config['thumbs'][<entityName>_<fieldName>]['smart_crop'] = TRUE;
|
| Examples:
| $config["thumbs"]["user_image_id"]["_tiny"]["width"] = 67;
| $config["thumbs"]["user_image_id"]["_tiny"]["height"] = 67;
|
| $config["thumbs"]["user_images"]["_small"]["width"] = 80;
| $config["thumbs"]["user_images"]["_small"]["height"] = 80;
| $config["thumbs"]["user_images"]["_small"]["smart_crop"] = TRUE;
|
*/


// These thumbs will be created for ALL images.
// They are needed for displaying in the Admin-dashboard (image preview and in the "window")
$config['all']['_admin']["width"] = 150;
$config['all']['_admin']["height"] = 100;

$config['thumbs']['objectfeature']['_small']["width"] = 18;
$config['thumbs']['objectfeature']['_small']["height"] = 18;

$config['thumbs']['apartment']['_small']["width"] = 90;
$config['thumbs']['apartment']['_small']["height"] = 90;

$config['thumbs']['apartment']['_medium']["width"] = 176;
$config['thumbs']['apartment']['_medium']["height"] = 158;

$config['thumbs']['apartment']['_big']["width"] = 320;
$config['thumbs']['apartment']['_big']["height"] = 290;

$config['thumbs']['newsitem']['_small']["width"] = 60;
$config['thumbs']['newsitem']['_small']["height"] = 60;

$config['thumbs']['newsitem']['_medium']["width"] = 120;
$config['thumbs']['newsitem']['_medium']["height"] = 120;

$config['thumbs']['newsitem']['_big']["width"] = 180;
$config['thumbs']['newsitem']['_big']["height"] = 180;