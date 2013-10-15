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
	 * Outputs items list in json format for jquery to use
	 *
	 *
	 */
	$res = array(); 
	
	foreach ($meta['restrictions'] as $restrict){
		$res[$restrict['canthave']] = $restrict['canthave'];
	}
	
	$info=array();
	
	//insert the label for the dropdown 
	$info[] = json_encode(Array(id=>$id, name => $label, order => $order, level=>$level));
	
	//now do the items
	while ($row = $listitems->fetch(PDO::FETCH_ASSOC)) { 
		if (!$res[$row['id']]){
			$info[] = json_encode($row);
		}
	}
	
	echo json_encode( $info );