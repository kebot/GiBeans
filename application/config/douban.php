<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Douban API configuration
 *
 * http://www.douban.com/service/apidoc/
 */
return array(
	/**
	 * Douban API
	 * 
	 * @param api_key		API Key
	 * @param api_secret	API Secret
	 */
	'api_key'		=> '06de00790e2535e4149a016b2a6fa1ac',
	'api_secret'	=> '77ad79035d4e387f',
	
	/**
	 * Configuration
	 */
	'lifetime'		=> 3600 * 30 * 30,						// Store for 30 days
	'session_key'	=> array(
		'oauth_token'	=> 'douban_oauth_token',					// Saved access token
		'oauth_user'	=> 'douban_oauth_user',					// Saved current user
		),
);

