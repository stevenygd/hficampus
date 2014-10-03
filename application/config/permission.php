<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| Permission system config!
| -------------------------------------------------------------------
| This file contains the cofiguration of the permission system
| -------------------------------------------------------------------
| There are these things that need to config in the auth system:
| 
| 1. Override URLs.
|
*/

/*
| -------------------------------------------------------------------
| 1. Override URLs
| -------------------------------------------------------------------
| URLS begins with these urls will not be controlled by the 
| permission system. While CI is varifying the permission, the 
| requests starting with these urls will automatically return TRUE!
| -------------------------------------------------------------------
| for example:
|	URLs begins with account(the entrance of the system),
|   home(home direction), and nothing will be controlled by
|   the user system. So URLs will be overrided.
|
*/

$config['overrides'] = array('','account','home','message','netdisk','ulibrary');
