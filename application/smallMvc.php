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

/**
 * SmallMVC
 *
 * Put together the bones of the MVC environment and initiate the application framework
 * 
 */


//get all classes 
foreach (glob("classes/*.php") as $filename){
    include $filename;
}

//get all config files 
foreach (glob("config/*.php") as $filename){
    include $filename;
}


include 'load.php';
include 'model.php';

include 'controller.php';

new Controller(); 
 
/* End of file smallMVC.php */
/* Location: ./application/smallMVC.php */