<?php defined('SYSPATH') or die('No direct access allowed.');
defined('UC_API') or Kohana::config('ucenter');
defined('UC_API') or die('You must set the right ucenter config.');

return array
(
	'base' => array
        (
        'type' => 'mysql',
        'connection' => array(
            /**
             * The following options are available for MySQL:
             *
             * string   hostname     server hostname, or socket
             * string   database     database name
             * string   username     database username
             * string   password     database password
             * boolean  persistent   use persistent connections?
             *
             * Ports and sockets may be appended to the hostname.
             */
            'hostname' => '210.32.200.89',
            'database' => 'book',
            'username' => 'book',
            'password' => '',
            'persistent' => FALSE,
        ),
        'table_prefix' => 'base_',
        'charset' => 'utf8',
        'caching' => FALSE,
        'profiling' => TRUE,
    ),
    'pt' => array
        (
        'type' => 'mysql',
        'connection' => array(
            /**
             * The following options are available for MySQL:
             *
             * string   hostname     server hostname, or socket
             * string   database     database name
             * string   username     database username
             * string   password     database password
             * boolean  persistent   use persistent connections?
             *
             * Ports and sockets may be appended to the hostname.
             */
            'hostname' => 'localhost',
            'database' => 'nexusphp',
            'username' => 'nexusphp',
            'password' => 'pttest',
            'persistent' => FALSE,
        ),
        'table_prefix' => '',
        'charset' => 'utf8',
        'caching' => FALSE,
        'profiling' => TRUE,
    ),

);

?>
