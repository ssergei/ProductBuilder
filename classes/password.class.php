<?php 

class Password {
	
	
	function newpassword(){
		$password = "custom_".sha1(microtime(true).mt_rand(10000,90000)); 
		return $password; 
	}
	

	
}