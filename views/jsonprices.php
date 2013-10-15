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

	
	/**
	 * Outputs data in JSON format for jQuery to use
	 *
	 */

	$info=array();
	
	//insert the label for the dropdown 
	while ($row = $listprices->fetch(PDO::FETCH_ASSOC)) { 
		$info[] = json_encode($row);
	
	}
	echo json_encode( $info );