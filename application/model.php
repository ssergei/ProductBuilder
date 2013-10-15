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

class Model extends PDOquery{
	//connect to the DB 
    protected $_DBH;
    protected $_STH; 
    
	
	/**
	 * Class constructor
	 *
	 * Builds the connection to the database
	 * 
	 * @return	void
	 */
     function __construct() {
		$this->_DBH = $this->connect(); 
		return; 
     }
}


/* End of file model.php */
/* Location: ./application/model.php */