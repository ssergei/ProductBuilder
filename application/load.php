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

class Load {
	/**
	 * Viewer builds the page templates 
	 *
	 * @param	string		$file_name		physical name of view file to load
	 * @param	object		$data			Object of data to pass to the view
	 * @param	array		$meta			Array of random data to use pass to the view
	 * @param	bool		$redirect
	 * 
	 * @return	void
	 */
   function view( $file_name, $data = null, $meta = array(), $redirect = false){
		if( is_array($data) ) {
			extract($data);
		}
		
		if(!$meta['cleanpage']){
			if($meta['skip-login']){
				include 'views/includes/header-estimate.php';
			}else{
				include 'views/includes/header.php';
			}
		
			if ($meta['notify']){
				include 'views/includes/displaymessage.php';
			}
			
			include 'views/' . $file_name;
		
			if($meta['skip-login']){
				include 'views/includes/footer-estimate.php';
			}else{
				include 'views/includes/footer.php';
			}
		}else{
			if ($meta['notify']){
				include 'views/includes/displaymessage.php';
			}
			
			include 'views/' . $file_name;
		}
		
		return; 
		
		}
}


/* End of file load.php */
/* Location: ./application/load.php */