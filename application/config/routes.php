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
|	example.com/class/method/id/
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
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "account";
$route['404_override'] = '';

/**
 * controller structure
 *
 * /controllers
 * 		/account.php
 * 		/home.php
 *		/mtype
 *			/mtype.php(default controller for the module)
 *			/mtype_ntype.php (controller for mtype.node)
 */

/**
 * User system
 */
$route['account/(:any)']     = 'account/$1/$2/$3/$4/$5';//default
$route['home/(:any)']        = 'home/$1/$2/$3/$4/$5';//default
$route['netdisk/(:any)']     = 'netdisk/$1/$2/$3/$4/$5';//default
$route['people/(:any)']      = 'people/$1/$2/$3/$4/$5';//default
$route['ulibrary/(:any)']    = 'ulibrary/$1/$2/$3/$4/$5';//default

/**
 * Channel Callback
 */
$route['_sae/channel/connected']    = 'home/api/channel_connected';
$route['_sae/channel/disconnected'] = 'home/api/channel_closed';
$route['_sae/channel/message']      = 'home/api/channel_message';

/**
 * Other none-node system
 */
//$route['msg/(:any)']        = 'msg/$1/$2/$3/$4/$5';


// URI:module/(method)
//$route['([a-z]+)']        = '$1/$1/get';//default, method blank=method:get
$route['([a-z]+)(/get)?']    = '$1/$1/get';
$route['([a-z]+)/delete'] = '$1/$1/delete';
$route['([a-z]+)/create'] = '$1/$1/create';
$route['([a-z]+)/edit']   = '$1/$1/edit';

// URI: module/mid/(method)
//$route['([a-z]+)/(\w+)']        = '$1/$1/get/$2';//default, method blank=method:get
$route['([a-z]+)/(\w+)(/get)?']    = '$1/$1/get/$2';
$route['([a-z]+)/(\w+)/delete'] = '$1/$1/delete/$2';
$route['([a-z]+)/(\w+)/create'] = '$1/$1/create/$2';
$route['([a-z]+)/(\w+)/edit']   = '$1/$1/edit/$2';

// URI: module/mid/node/(method)
//$route['([a-z]+)/(\w+)/([a-z]+)']        = '$1/$1_$3/get/$2';
$route['([a-z]+)/(\w+)/([a-z]+)(/get)?']    = '$1/$1_$3/get/$2';
$route['([a-z]+)/(\w+)/([a-z]+)/delete'] = '$1/$1_$3/delete/$2';
$route['([a-z]+)/(\w+)/([a-z]+)/create'] = '$1/$1_$3/create/$2';
$route['([a-z]+)/(\w+)/([a-z]+)/edit']   = '$1/$1_$3/edit/$2';

// URI: module/mid/node/nid/(method)
//$route['([a-z]+)/(\w+)/([a-z]+)/(\w+)']        = '$1/$1_$3/get/$2/$4';
$route['([a-z]+)/(\w+)/([a-z]+)/(\w+)(/get)?']    = '$1/$1_$3/get/$2/$4';
$route['([a-z]+)/(\w+)/([a-z]+)/(\w+)/delete'] = '$1/$1_$3/delete/$2/$4';
$route['([a-z]+)/(\w+)/([a-z]+)/(\w+)/create'] = '$1/$1_$3/create/$2/$4';
$route['([a-z]+)/(\w+)/([a-z]+)/(\w+)/edit']   = '$1/$1_$3/edit/$2/$4';

// URI: module/mid/node/nid/attach/(method)
//$route['([a-z]+)/(\w+)/([a-z]+)/(\w+)/([a-z]+)']        = '$1/$1_$3/get/$2/$4/$5';
$route['([a-z]+)/(\w+)/([a-z]+)/(\w+)/([a-z]+)(/get)?']    = '$1/$1_$3/get/$2/$4/$5';
$route['([a-z]+)/(\w+)/([a-z]+)/(\w+)/([a-z]+)/delete'] = '$1/$1_$3/delete/$2/$4/$5';
$route['([a-z]+)/(\w+)/([a-z]+)/(\w+)/([a-z]+)/create'] = '$1/$1_$3/create/$2/$4/$5';
$route['([a-z]+)/(\w+)/([a-z]+)/(\w+)/([a-z]+)/edit']   = '$1/$1_$3/edit/$2/$4/$5';

/* End of file routes.php */
/* Location: ./application/config/routes.php */