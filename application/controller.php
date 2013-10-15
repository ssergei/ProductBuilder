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
 
 
class Controller {
	
   public $load;
   public $model; 
   public $uri; 
   public $password; 

	/**
	 * Class constructor
	 *
	 * Breaks apart the URI, mekes sure authenticated if required, displays appropriate page
	 * 
	 * @return	void
	 */
		function __construct(){
		
			$this->load = new Load();
			$this->model = new Model();
			$this->uri = new Mvc_URI();
			
			session_start(); 
			
			//if they are blocked see if its time to reset them 
			if ($_SESSION['login_attempts'] >= BLOCKED_AT){
				if (time() - $_SESSION['last_try'] > BLOCKED_SECONDS){
					unset ($_SESSION['login_attempts']);
				}
			}
			
			//break apart the URI and get the segments into an array
			$uri_segment = $this->uri->uri_to_array(); 
			
			//if they are not logged in then go to the login screen 
			//but only do this if they are not already on the login screen
			//also check if they are not using a controller that does not require login
			if (
				($uri_segment[0] != "login") && 
				($uri_segment[0] != "login_process") && 
				($uri_segment[0] != "blocked") && 
				($uri_segment[0] != "estimate") && 
				($uri_segment[0] != "json") && 
				($uri_segment[0] != "jsonPrices")){
					if (!$_SESSION['logged_in']){
						$_SESSION['message'] = "You must be logged in to go there!";
						$_SESSION['type'] = "error"; 
						$this->redirect('login'); 	
						return false; 
					}
			}
			
			
			//if a segment is not defined then go home 
			if($uri_segment[0]){
				$this->$uri_segment[0](); 
			}else{
				$this->home();  
			}
			
			return; 
		}

	// --------------------------------------------------------------------

	
	/**
	 * Display the admin dashboard
	 *
	 * @param	string	$view		Name of the view file to fetch 
	 * @return	mixed
	 */
		function home($view="index.php"){		
			
			//set the meta info 
			$meta['title'] = 'Dashboard';
			$meta['notify'] = $this->notify();
			
			
			//which data are we getting 
			$parent = $this->uri->segment(2);
			$level = $this->uri->segment(3);
			
			//build a breadcrumb array and pass it in as a meta value
			$meta['parent'] = $parent;
			$meta['level'] = $level; 
			
			$meta['bread'] = $this->model->breadcrumb($parent, $level);
			
			if ($parent && $level){
				$data = $this->model->selectProducts($level, $parent);
			}else{
				$data = $this->model->selectProducts();
			}
			
			//get the page
			$this->load->view($view, $data, $meta);
			
			return; 
		}
	
	// --------------------------------------------------------------------
	
	/**
	 * Display the admin's restrictions page
	 *
	 * @param	string	$view		Name of the view file to fetch 
	 * @return	mixed
	 */
		function restrictions($view="restrictions.php"){		
			
			//set the meta info 
			$meta['title'] = 'Dashboard';
			$meta['notify'] = $this->notify();
			
			
			//which data are we getting 
			$item = $this->uri->segment(2);
			$parent = $this->uri->segment(3);
			$level = 3;
			
			//build a breadcrumb array and pass it in as a meta value
			$meta['item'] = $item;
			$meta['parent'] = $parent;
			$meta['level'] = $level; 
			
			$meta['bread'] = $this->model->breadcrumb($parent, $level);
			
			$data = $this->model->selectRestrictions(3, $parent, $item);
			
			$meta['lastItem'] = $data['lastItem'];
			$meta['restrictions'] = $data['restrictions'];
			
			//get the page
			$this->load->view($view, $data['data'], $meta);
			
			return; 
		}
	
	// --------------------------------------------------------------------
	
	/**
	 * Process the restriction page's changes
	 *
	 * @param	string	$view		Name of the view file to fetch 
	 * @return	mixed
	 */
		function edit_restrictions(){
			if ($_POST){
				$data = $_POST;
				foreach ($_POST['source'] as $key=>$value){
					if($value != ""){
						$this->model->update_restrictions($value, $data['canthave']);
					}else{
						$this->model->update_restrictions($key, $data['canthave'], true);	
					} 
				}
			
				$_SESSION['message'] = "Your changes have been saved!";
				$_SESSION['type'] = "success";
				$this->redirect('restrictions/'.$_POST['canthave'].'/'.$_POST['parent']); 
			}else{
				$_SESSION['message'] = "An error occured!";
				$_SESSION['type'] = "error"; 
				$this->redirect('restrictions/'.$_POST['canthave'].'/'.$_POST['parent']); 
			}
			
			return;
		}
	// --------------------------------------------------------------------
	
	/**
	 * Display the admin's list of item combos screen
	 *
	 * @param	string	$view		Name of the view file to fetch 
	 * @return	mixed
	 */
		function prices($view="prices.php"){		
		
			//set the meta info 
			$meta['title'] = 'Prices';
			$meta['notify'] = $this->notify();
			
			//get which data are we fetching 
			$itemid = $this->uri->segment(2);
			
			if ($itemid){
				$data = $this->model->selectPrices($itemid);
			}else{
				$data = $this->model->selectPricesAdmin();
			}
			
			//get the page
			$this->load->view($view, $data, $meta);
			
			return; 
		}
	
	// --------------------------------------------------------------------
	
	/**
	 * Display the admin's list of item prices screen
	 *
	 * @param	string	$view		Name of the view file to fetch 
	 * @return	mixed
	 */
		function pricelist($view="pricelist.php"){		
		
			//set the meta info 
			$meta['title'] = 'Prices List';
			$meta['notify'] = $this->notify();
			
			//which data are we getting 
			$itemid = $this->uri->segment(2);
			
			$meta['comboid'] = $itemid; 
			
			if ($itemid){
				$data = $this->model->selectPricesList($itemid);
			}
			//get the page
			$this->load->view($view, $data, $meta);
			
			return; 
		}
		
	// --------------------------------------------------------------------
	
	/**
	 * Display the admin section login screen
	 *
	 * @param	string	$view		Name of the view file to fetch 
	 * @return	mixed
	 */	
	 
		function login($view="login.php"){
			//first make sure they havent maxed out their login attempts
			if ($_SESSION['login_attempts'] > 1){
				$this->redirect("blocked"); 
				return false; 
			}
			
			//set the meta info 
			$meta['title'] = 'Login';
			$meta['notify'] = $this->notify();
			
			//get the page
			$this->load->view($view, $data, $meta);
			
			return; 
		}
	
	// --------------------------------------------------------------------
	
	/**
	 * Process admin section logout request
	 *
	 * @return	mixed
	 */
		function logout(){
			
			unset($_SESSION['logged_in']); 
			
			//set the meta info
			$_SESSION['message'] = "You have been logged out!";
			$_SESSION['type'] = "success"; 
			
			//redirect to the login screen
			$this->redirect('login'); 
			
			return;
		}
	
	// --------------------------------------------------------------------
	
	/**
	 * Process redirect request
	 *
	 * @param	string	$path 		Name of the controller class to redirect to 
	 * @return	mixed
	 */	
		function redirect($path = ""){
			if ($path){
				header('Location: '.INSTALL_PATH.'/index.php/'.$path);
			}else{
				header('Location: '.INSTALL_PATH.'/index.php/home');
			}
				
			return; 
		}
		
	// --------------------------------------------------------------------
	
	/**
	 * Display blocked user page
	 *
	 * @return	mixed
	 */	
		function blocked(){
		   //check if they have been unblocked
		   if ($_SESSION['login_attempts'] < BLOCKED_AT){
			   $this->redirect("login"); 
				return false; 
		   }
		   
			//set the meta info 
			$meta['title'] = 'Blocked';
			
			//calculate the time in minutes
			$minutes = round(BLOCKED_SECONDS / 60); 
			$meta['notify'] = $this->notify("Too many failed login attempts! Please try again in $minutes minutes.", "error");
			
			//get the page
			$this->load->view("blocked.php", $data, $meta);
			
			return; 
		}
	
	// --------------------------------------------------------------------
	
	/**
	 * Display edit product page
	 *
	 * @param	string	$itemid		The ID of the item you want to edit
	 * @return	mixed
	 */	
	   function edit($itemid = null){
		  //access second parameter of URI 
			if ($itemid == null){
				$itemID = $this->uri->segment(2);
			}
			
			$data = $this->model->select($itemID);
			
			$meta['notify'] = $this->notify();
			$meta['bread'] = $this->model->breadcrumb($data->{'parent'}, $data->level);
	
			//set the meta info 
			$meta['title'] = 'Edit Attribute';
			
			$this->load->view('edit.php', $data, $meta);
			
			return;
	   }
	
	// --------------------------------------------------------------------
	
	/**
	 * Process the the edit product request
	 *
	 * @return	mixed
	 */	
		function edit_process(){
		   if ($_POST){
				$data = $_POST; 
				
			   $this->model->update($data);
			   
			   //set the notification message
			   $_SESSION['message'] = "Your changes have been saved!";
			   $_SESSION['type'] = "success";
			   
			   $this->redirect('edit/'.$_POST['id'].'/'); 
		   }else{
			   //set the notification message
			   $_SESSION['message'] = "An error occured!";
			   $_SESSION['type'] = "error"; 
			   
			   $this->redirect('edit/'.$_POST['id'].'/'); 
		   }
		        
			return;
		}
   
	// --------------------------------------------------------------------
	
	/**
	 * Process the the edit product combo request
	 *
	 * @return	mixed
	 */	
		function edit_combo_process(){
			if ($_POST){
				$data = $_POST; 
				
				$this->model->update_combo($data);
				
				//set the notification message
				$_SESSION['message'] = "Your changes have been saved!";
				$_SESSION['type'] = "success";
				$this->redirect('pricelist/'.$_POST['comboid'].'/'); 
			}else{
				
				//set the notification message
				$_SESSION['message'] = "An error occured!";
				$_SESSION['type'] = "error"; 
				
				$this->redirect('edit_combo/'.$_POST['id'].'/'); 
			}
			
			return;         
		}
   
	// --------------------------------------------------------------------
	
	/**
	 * Process the attribute add request
	 *
	 * @return	mixed
	 */	
	   function add_process(){
		   if ($_POST['name']){
			   $data = $_POST; 
			   
			   $this->model->insert($data); 
			   
			   //set the notification message
			   $_SESSION['message'] = "Successfully added!";
			   $_SESSION['type'] = "success";
			   
			   if ($data['level'] == 1){
				   $this->redirect('home');
			   }else{
				   $this->redirect('home/'.$data['parentid'].'/'.$data['level']);
			   }
		   }else{
			   //set the notification message
			   $_SESSION['message'] = "An error occured!";
			   $_SESSION['type'] = "error"; 
			   
			   $this->redirect('home/'.$data['parentid'].'/'.$data['level']); 
		   }
		   
		   return;
	   }
   
	// --------------------------------------------------------------------
	
	/**
	 * Process the product combo add request
	 *
	 * @return	mixed
	 */	
		function add_combo_process(){
			if ($_POST['comboid']){
				$data = $_POST; 
				
				$this->model->insert_combo($data); 
				
				//set the notification message
				$_SESSION['message'] = "Successfully added!";
				$_SESSION['type'] = "success";
				
				$this->redirect('pricelist/'.$_POST['comboid']); 
			}else{
				
				//set the notification message
				$_SESSION['message'] = "An error occured!";
				$_SESSION['type'] = "error"; 
				
				$this->redirect('add_combo'); 
			}
			
			return;
		}
   
	// --------------------------------------------------------------------
	
	/**
	 * Process the admin login request
	 *
	 * @return	mixed
	 */		
		function login_process(){
			if ($_POST){
				//check if they filled out honeypot hidden field
				if ($_POST['city']){
					
					//set the notification message
				   $_SESSION['message'] = "You seem to be a bot!";
				   $_SESSION['type'] = "error"; 
				   
				   $this->redirect('login/'); 
				   
				   return false; 
				}else{
					//do the actual username/password verification
					if (($_POST['username'] == ADMIN_USERNAME) && ($_POST['password'] == ADMIN_PASSWORD)){
						$_SESSION['logged_in'] = ADMIN_USERNAME; 
						
						//clear failed login attempts
						unset($_SESSION['login_attempts']); 
						
						//set the success message
						$_SESSION['message'] = "You have been logged in!";
						$_SESSION['type'] = "success";
						
						//redirect to dashboard
						$this->redirect(); 
						
					}else{
						//add an failed attempt and set the time of last try
					   $_SESSION['login_attempts']++;  
					   $_SESSION['last_try'] = time(); 
					   
					   //set notification message
					   $_SESSION['message'] = "Login attempt failed!";
					   $_SESSION['type'] = "error"; 
					   
					   $this->redirect('login/'); 
					   
					   return false; 
					}
				}
			}else{
			   $_SESSION['message'] = "An error occured!";
			   $_SESSION['type'] = "error"; 
			   $this->redirect('login/'); 
			}
			
			return; 
		}
	
	// --------------------------------------------------------------------
	
	/**
	 * Display the add attribute screen
	 *
	 * @return	mixed
	 */		
		function add(){
			//set the meta info
			$meta['title'] = 'Add Attribute';
			$meta['notify'] = $this->notify();
			
			$meta['theParent'] = $this->uri->segment(2);
			$meta['theLevel'] = $this->uri->segment(3);
			
			$this->load->view('add.php', $data, $meta);
			
			return; 
		}
	
	
	// --------------------------------------------------------------------
	
	/**
	 * Display the add item combination screen 
	 *
	 * @return	mixed
	 */ 
		function add_combo(){
			//set the meta info
			$meta['title'] = 'Add Price Combo';
			$meta['notify'] = $this->notify();
			
			//auto-populate the input field
			$meta['comboid'] =  htmlentities($this->uri->segment(2));
			
			$this->load->view('add_combo.php', $data, $meta); 
			
			return;  
		}
	
	// --------------------------------------------------------------------
	
	/**
	 * Display the edit item combination screen
	 *
	 * @return	mixed
	 */ 
		function edit_combo(){
			//set the meta info
			$meta['title'] = 'Edit Price Combo';
			$meta['notify'] = $this->notify();
			
			$meta['id'] = $this->uri->segment(2);
			
			$data = $this->model->select($meta['id'], 'quantities');
			
			$this->load->view('edit_combo.php', $data, $meta);
			
			return; 
		}
	
	// --------------------------------------------------------------------
	
	/**
	 * Process an attribute delete request
	 *
	 * @return	mixed
	 */
		function delete_process(){
			$itemID = $this->uri->segment(2);
			$theParent = $this->uri->segment(3);
			
			//set the defualt parent ID if not defined
			if(!$theParent){
				$theParent = 0;
			}
			
			$theLevel = $this->uri->segment(4);
			
			if (is_numeric($itemID)){
				$this->model->delete_children($itemID); 
				$_SESSION['message'] = "Successfully deleted!";
				$_SESSION['type'] = "success";
				
				//we must now re-order the items!
				$items = $this->model->selectProducts($theLevel, $theParent);
				
				$i = 0;
				
				while ($result = $items->fetchObject()) { 
					$i++;
					$this->model->update_order($result->id, $i);
				}
			
				$this->redirect('home/'.$theParent.'/'.$theLevel);  
			}else{
				
				//set the notification message
				$_SESSION['message'] = "An error occured!";
				$_SESSION['type'] = "error"; 
				$this->redirect('home/'.$theParent.'/'.$theLevel); 
			}
			
			return;
		}
		
	// --------------------------------------------------------------------
	
	/**
	 * Process a price delete request
	 *
	 * @return	mixed
	 */
		function delete_price_process(){
			$itemID = $this->uri->segment(2);
			$comboID = $this->uri->segment(3);
			
			if ($itemID){
				$this->model->delete_price($itemID); 
				
				//set notification message
				$_SESSION['message'] = "Successfully deleted!";
				$_SESSION['type'] = "success";
				$this->redirect('pricelist/'.$comboID);  
			}else{
				//set the notification message
				$_SESSION['message'] = "An error occured!";
				$_SESSION['type'] = "error"; 
				$this->redirect('pricelist/'.$comboID); 
			}
			
			return;
		}
		
	// --------------------------------------------------------------------
	
	/**
	 * Process a item price combo request
	 *
	 * @return	mixed
	 */
		function delete_price_combo_process(){
			$comboID = $this->uri->segment(2);
			
			if ($comboID){
				$this->model->delete_price_combo($comboID);
				
				//set the notification message
				$_SESSION['message'] = "Successfully deleted!";
				$_SESSION['type'] = "success";
				$this->redirect('prices');  
			}else{
				//set the notification message
				$_SESSION['message'] = "An error occured!";
				$_SESSION['type'] = "error"; 
				$this->redirect('prices'); 
			}
			
			return; 
		}
		
	// --------------------------------------------------------------------
	
	/**
	 * Notification creator and displayer
	 *
	 * @param	string		$message	The body of the notfication to display
	 * @param	string		$type		Type of notification [error, success]
	 * @return	mixed					reuturn the HTML of the notification to include directly on the page
	 */
		function notify($message = null, $type = null){
			if (!$message){
				$message = $_SESSION['message']; 
			}
			
			if (!$type){
				$type = $_SESSION['type'];
			}
			
			$html = '<div class="alert alert-'.$type.'" style="';
			
			if (!$type){
				$html .= 'display:none'; 	
			}
			
			$html .= '" >'; 
			$html .= $message; 
			$html .= '</div><!-- end notify-'.$type.' -->'; 
			
			unset($_SESSION['message'], $_SESSION['type']); 
			
			return $html;  
		}
	
	// --------------------------------------------------------------------
	
	/**
	 * Display the public front page for he price estimator
	 *
	 * @param	string		$view	The body of the notfication to display
	 * @return	mixed				reuturn the HTML of the notification to include directly on the page
	 */
		function estimate($view="estimate.php"){		
			//set the meta info 
			$meta['title'] = 'Print Estimator';
			$meta['skip-login'] = true;
			
			//set initial data to display
			$level = 1;
			$parent = 0;
			
			if ($parent && $level){
				$data = $this->model->selectProducts($level, $parent, 1);
			}else{
				$data = $this->model->selectProducts();
			}
			
			//get the page
			$this->load->view($view, $data, $meta);
			
			return; 
		}
	
	// --------------------------------------------------------------------
	
	/**
	 * Displays the JSON response string that jquery uses to populate the dropdowns
	 *
	 * @param	string		$view	The name of the page to display
	 * @return	mixed				
	 */
		function json($view = "json.php"){
			
			//no need for authentication or header/footer
			$meta['skip-login'] = true;
			$meta['cleanpage'] = true;
			
			//get the current selectiosn data 
			$parent = $this->uri->segment(2);
			$level = $this->uri->segment(3);
			$order = $this->uri->segment(4);
			
			//used to get restrictions
			$meta['lastItem'] = $this->uri->segment(5);
			
			//add one to the order since we want to display the next sibling
			//of the current selection 
			if ($level !=1){
				$order = $order + 1;
			}
			$level = $level + 1;
			
			$data = $this->model->selectProductsJson($level, $parent, $order);
			$data = $data->fetchObject(); 
			
			$info['id'] = $data->id; 
			$info['label'] = $data->name;
			$info['level'] = $data->level;
			$info['order'] = $data->order;
			
			$meta['lastSelection'] = $parent;
			
			$meta['restrictions'] = $this->model->getRestrictions($meta['lastItem']);
			
			$level = $level+1;
			$info['listitems'] = $this->model->selectProducts($level, $info['id']);
			
			$this->load->view($view, $info, $meta);
			
			return; 
		}
	
	// --------------------------------------------------------------------
	
	/**
	 * Displays the JSON response string that is used to popualte the final "price" dropdown box
	 *
	 * @param	string		$view	The body of the notfication to display
	 * @return	mixed				JSON String
	 */
		function jsonPrices($view = "jsonprices.php"){
			
			//no need for authentication or header/footer
			$meta['skip-login'] = true;
			$meta['cleanpage'] = true;
		
			$md5 = $this->uri->segment(2);
			
			$info['listprices'] =  $this->model->selectPrices($md5);
			
			$this->load->view($view, $info, $meta);
			
			return;
		}
		
	// --------------------------------------------------------------------
	
	/**
	 * Processes the item order update request 
	 *
	 * @param	string		$view	The body of the notfication to display
	 * @return	mixed				JSON String
	 */
	 
	 
		function jsonOrder($view = "jsonorder.php"){
		
			$meta['cleanpage'] = true;
			
			//passed in via URI as a comma seperated string
			$items = $this->uri->segment(2);
			$items = explode(',', $items);
			
			$i = 0;
			foreach ($items as $item){
				$i++;
				$item = substr($item, 5);	
				$this->model->update_order($item, $i);
			}
			
			return; 
		}
}

/* End of file controller.php */
/* Location: ./application/controller.php */