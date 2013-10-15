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
 

class Mvc_URI {
 
  var $path = null;
 
	/**
	 * Class constructor
	 *
	 * Breaks apart the URI into an array
	 * 
	 * @return	void
	 */
		function __construct(){
			if(!empty($_SERVER['PATH_INFO'])) {
			  $this->path = explode('/',$_SERVER['PATH_INFO']);
			  $this->path = array_slice($this->path,1);
			}
		}

	// --------------------------------------------------------------------


	/**
	 * Retrieved the value of a specific segment of the URI array
	 *
	 * @param	int		$index		number of the segment array index you want to retrieve		
	 * 
	 * @return	void
	 */
		function segment($index){
			if(!empty($this->path[$index-1])){
			  return $this->path[$index-1];
			}else{ 
			  return false;
			}
		}

	// --------------------------------------------------------------------


	/**
	 * Get all of the relevent segments of the array
	 *
	 * @param	int		$index		offset of which index to start at when building the array		
	 * 
	 * @return	void
	 */
		function uri_to_array($index=0){
			if(is_array($this->path)){
				return array_slice($this->path,$index);
			}else{
				return false;
			}
		}
}

/* End of file uri.class.php */
/* Location: ./classes/uri.class.php */