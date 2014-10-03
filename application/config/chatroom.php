<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| Chatroom system config!
| -------------------------------------------------------------------
| This file contains the cofiguration of the chatroom system
| -------------------------------------------------------------------
| Things that need to config in the chatroom system:
| 
| 1. allowed capacity value
| 2. default capacity
*/

/*
| -------------------------------------------------------------------
| 1. allowed capacity value
| -------------------------------------------------------------------
| the values in the array are the only allowed number of the chatroom
| capacity
| -------------------------------------------------------------------
*/

$config['capacity_allowed_value'] = array(50,100);

/*
| -------------------------------------------------------------------
| 2. default capacity
| -------------------------------------------------------------------
| the values is the default capacity of the chatroom
| -------------------------------------------------------------------
*/

$config['capacity_default'] = 50;

/*
| -------------------------------------------------------------------
| 2. default expired time
| -------------------------------------------------------------------
| the values is the default expire time of the chatroom
| -------------------------------------------------------------------
*/

$config['expired_time_default'] = 3600*7; //one week
