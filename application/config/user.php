<?php defined('SYSPATH') or die('No direct script access.');

return array(
    'salt' => 'myzjutnav', // the salt to encode the Cookie only for User class.
    'cookie_key' => 'jhnav_Cookie',
    'lifetime' => 365*24*60*60, // session store for a year
    'session_uid' => 'jhnav_uid',
    'session_username'=>'jhnav_username'
);

?>
