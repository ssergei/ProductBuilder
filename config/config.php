<?php
/**
 * Zillion Price Estimator
 *
 *
 * @package		Zillion Price Estimator
 * @author		Zillion Labs Dev Team
 * @copyright	Copyright (c) 2008 - 2013, Zillion Labs (http://zillionlabs.com/)
 * @license		
 * @link		http://zillionlabs.com/support/priceestimator
 * @since		Version 1.0
 * @filesource
 */

	// --------------------------------------------------------------------


	/** Configuration Variables **/
	
	//debug mode
	ini_set('display_errors', true); 
	
	define('DB_NAME', 			' ');
	define('DB_USER', 			' ');
	define('DB_PASSWORD', 		' ');
	define('DB_HOST', 			' ');
	define('INSTALL_PATH', 		' ');
	define('BASE_URL', 			INSTALL_PATH.'/index.php/');
	define('DB_TABLE_NAME', 	'items');
	
	//admin section logins
	define('ADMIN_USERNAME', 	'admin');
	define('ADMIN_PASSWORD', 	'admin');
	
	//how many times can someone try to login before they are blocked?
	define('BLOCKED_AT', 		1);
	
	//for how many seconds do you want to block them?
	define('BLOCKED_SECONDS', 		60);
	
/* End of file config.php */
/* Location: ./config/config.php */