<?php defined('SYSPATH') or die('No direct script access.');
return array(
    'movie'    => array
    (
		'driver'             => 'file',
		'cache_dir'          => APPPATH.'cache/douban/movie',
		'default_expire'     => 24*3600,//a day
    ),
    
    'book'    => array
    (
		'driver'             => 'file',
		'cache_dir'          => APPPATH.'cache/douban/book',
		'default_expire'     => 24*3600,//a day
    ),
);



?>
