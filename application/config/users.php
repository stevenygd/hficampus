<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| User system config!
| -------------------------------------------------------------------
| This file contains the cofiguration of the user system
| -------------------------------------------------------------------
| There are these things that need to config in the auth system:
| 
| 1. overide urls(controllers/pages that we don't need to login).
|
*/

/*
| -------------------------------------------------------------------
| 1. overide urls
| -------------------------------------------------------------------
| URLS begins with these urls will not be controlled by the 
| user system. These pages can be visited without successfully
| logged in.
| -------------------------------------------------------------------
| for example:
|	URLs begins with account(the entrance of the system),
|   home(home direction), and nothing will be controlled by
|   the user system. So URLs will be overrided.
|
*/

$config['overrides'] = array('account','ulibrary');
