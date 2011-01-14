<?php defined('SYSPATH') or die('No direct script access.');

//ucenter配置
define('UC_CONNECT', 'mysql');
define('UC_DBHOST', 'localhost');
define('UC_DBUSER', 'book');
define('UC_DBPW', '');
define('UC_DBNAME', 'center');
define('UC_DBCHARSET', 'utf8'); 
define('UC_DBTABLEPRE', '`center`.uc_');
define('UC_DBCONNECT', '0');
define('UC_KEY', '123');
define('UC_API', 'http://center.zjut.com');
define('UC_CHARSET', 'utf-8');
define('UC_IP', '');
define('UC_APPID', '14');
define('UC_PPP', '20');
//ucenter config end


return array(
    'salt' => 'myzjutnav', // the salt to encode the Cookie only for User class.
    'cookie_key' => 'jhnav_Cookie',
    'lifetime' => 365*24*60*60, // session store for a year
    'session_uid' => 'jhnav_uid',
    'session_username'=>'jhnav_username'
);

?>
