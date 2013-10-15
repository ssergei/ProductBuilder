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



class PDOquery{

	// --------------------------------------------------------------------


	/**
	 * Initializes the connection to the database
	 *
	 * 
	 * @return	mixed
	 */
		function connect(){
			try {
				$connection = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASSWORD);	
				return $connection; 
			}catch(PDOException $e) {
				return $e->getMessage();
			}
		}
	

	// --------------------------------------------------------------------

	/**
	 * Kill the connection to the database
	 *
	 * 
	 * @return	void
	 */
		function disconnect(){
			$this->$DBH = null; 
		}

	// --------------------------------------------------------------------

	/**
	 * Gets all of the records from a table into an object
	 *
	 * @param	string		$table		Name of the databse table
	 * 
	 * @return	object
	 */
        function selectAll($table = DB_TABLE_NAME) {

            $this->_STH = $this->_DBH->query('SELECT * FROM `'.$table.'`'); 
            $this->_STH->setFetchMode(PDO::FETCH_OBJ);
            
            return $this->_STH;         
        }
       

	// --------------------------------------------------------------------

	/**
	 * Gets specific record from specified table
	 *
	 * @param	int			$id			The id of the record you want
	 * @param	string		$table		Name of the databse table to query
	 * 
	 * @return	object
	 */
		function select($id = 1, $table = DB_TABLE_NAME){
			$this->_STH = $this->_DBH->prepare('SELECT * FROM `'.$table.'` 
												 WHERE id = ?');
			$this->_STH->bindParam(1, $id);
			
			$this->_STH->execute(); 
			$result = $this->_STH->fetchObject();
			
			return $result; 
		} 
		

	// --------------------------------------------------------------------

	/**
	 * Returns the next sequence in the order field of sibling items
	 *
	 * @param	int			$parent		The id of the parent item
	 * @param	string		$table		Name of the databse table to query
	 * 
	 * @return	int
	 */
		function newOrder($parent, $table = DB_TABLE_NAME){
			
				$this->_STH = $this->_DBH->prepare('SELECT `order` FROM `'.$table.'` 
													 WHERE parent = ? 
													 ORDER BY `order` 
													 DESC LIMIT 1');
				$this->_STH->bindParam(1, $parent);
				
				$this->_STH->execute(); 
				
				$result = $this->_STH->fetchObject();
				
				//if there is no sibling then start at 0
				if( !$result){
					$order = 0; 
				}else{
					$order = $result->order;	
				}
					
				return $order+1; 
		}
		
	// --------------------------------------------------------------------

	/**
	 * Deletes all children and grandchildren if a parent is deleted
	 *
	 * @param	int			$parent		The id of the parent item
	 * 
	 * @return	void
	 */
		function delete_children($id = NULL){
			$this->_STH = $this->_DBH->prepare('SELECT id FROM items
													WHERE
													   id = ?
													   OR id IN (  
													-- first level
													SELECT DISTINCT id sid
													FROM items
													WHERE parent = ?
													-- second level
													UNION
													SELECT DISTINCT t2.id sid
													FROM items t1
													INNER JOIN items t2
													ON t1.id = t2.parent
													WHERE t1.parent = ?
													)'); 
			
			$this->_STH->bindParam(1, $id);
			$this->_STH->bindParam(2, $id);
			$this->_STH->bindParam(3, $id);
			
			$this->_STH->execute(); 
			
			$result = $this->_STH->fetchAll();
			
			foreach ($result as $item){
				$this->delete($item['id'], 'items');	
			}
			
			return;
		}
		
	// --------------------------------------------------------------------

	/**
	 * Deletes specific redord id from a specific table
	 *
	 * @param	int			$id			The id of the item to delete
	 * @param	string		$table		The name of the table to delete from
	 * 
	 * @return	bool
	 */
		function delete($id = null, $table = DB_TABLE_NAME){
			$this->_STH = $this->_DBH->prepare('DELETE FROM `'.$table.'` 
													WHERE id = ?');
			
			$this->_STH->bindParam(1, $id);
			
			return $this->_STH->execute(); 		
		}
		
		
		
		
	// --------------------------------------------------------------------

	/**
	 * Deletes specific redord id from the quantities table
	 *
	 * @param	int			$id			The id of the item to delete
	 * 
	 * @return	bool
	 */
		function delete_price($id = null){
			/*
			$this->_STH = $this->_DBH->prepare('DELETE FROM `quantities` 
													WHERE id = ?');
			$this->_STH->bindParam(1, $id);
			
		
			return $this->_STH->execute(); */
			
			return $this->delete($id, 'quantities');
		
		}
		
	// --------------------------------------------------------------------

	/**
	 * Deletes specific price combo from the quantities table
	 *
	 * @param	int			$id			The id of the item to delete
	 * 
	 * @return	bool
	 */
		function delete_price_combo($id = null){
			$this->_STH = $this->_DBH->prepare('DELETE FROM `quantities` 
													WHERE itemCombo = ?');
			$this->_STH->bindParam(1, $id);
			
			return $this->_STH->execute(); 
		}
		
		
	// --------------------------------------------------------------------

	/**
	 * Processes an update request for data on the default table
	 *
	 * @param	array		$data		Array with [name] and [id] indexes to use for udpate
	 * 
	 * @return	bool
	 */
		function update($data=null){
			$this->_STH = $this->_DBH->prepare('UPDATE `'.DB_TABLE_NAME.'` SET
											   name=?
											   WHERE id = ?');
			
			$this->_STH->bindParam(1, $data['name']);
			$this->_STH->bindParam(2, $data['id']);
			
			return $this->_STH->execute(); 
		}
		
	// --------------------------------------------------------------------

	/**
	 * Processes an update request for an item combo entry
	 *
	 * @param	array		$data		Array of comboid, price, quantitity, and id indexes
	 * 
	 * @return	bool
	 */
		function update_combo($data=null){
			$this->_STH = $this->_DBH->prepare('UPDATE `quantities` SET
											   itemCombo=?, price=?, quantity=?
											   WHERE id = ?');
			
			$this->_STH->bindParam(1, $data['comboid']);
			$this->_STH->bindParam(2, $data['price']);
			$this->_STH->bindParam(3, $data['quantity']);
			$this->_STH->bindParam(4, $data['id']);
			
			return $this->_STH->execute(); 
		}
	
	// --------------------------------------------------------------------

	/**
	 * Checks if there are restriction via the given data
	 *
	 * @param	int		$source		ID of the last selected item
	 * @param	int		$canthave	ID of the sibling item that you want to check the restrictions on
	 * 
	 * @return	int					Number of matching restrictions, either 0 or 1.
	 */
		function check_restriction($source, $canthave){
			$this->_STH = $this->_DBH->prepare('SELECT * FROM `restrictions` 
													WHERE source=? 
													AND canthave=?');
			$this->_STH->bindParam(1, $source);
			$this->_STH->bindParam(2, $canthave);
			
			$this->_STH->execute();
			
			$data = $this->_STH->fetchAll(); 
			
			return count($data);
		}
	
	// --------------------------------------------------------------------

	/**
	 * Processes an update or delete request for restrictions entry
	 *
	 * @param	int		$source		ID of the last selected item
	 * @param	int		$canthave	ID of the sibling item that you want to restrict
	 * @param	bool	$delete		If set to true, delete the matching restriction
	 * 
	 * @return	bool
	 */
		function update_restrictions($source, $canthave, $delete = false){
			if ($delete){
				
				$this->_STH = $this->_DBH->prepare('DELETE FROM `restrictions` WHERE source=? AND canthave=?');
				$this->_STH->bindParam(1, $source);
				$this->_STH->bindParam(2, $canthave);
				
				return $this->_STH->execute(); 
				
				exit;					
			}else{
				if ($this->check_restriction($source, $canthave) > 0){
					//if a matching entry is already in the database then there is nothign to do
					
					return true;
					
					exit; 
				}else{
					$this->_STH = $this->_DBH->prepare('INSERT INTO `restrictions` (source, canthave) VALUES (?,?)');
					$this->_STH->bindParam(1, $source);
					$this->_STH->bindParam(2, $canthave);
					
					return $this->_STH->execute(); 
					
					exit;
				}				
			}
			return true; 
		}
	
	// --------------------------------------------------------------------

	/**
	 * Processes an update to the order of items, usually run in a foreach loop
	 *
	 * @param	int		$id			ID of the item to update
	 * @param	int		$order  	The order to set the item to
	 * 
	 * @return	void
	 */
		function update_order($id, $order){
				$this->_STH = $this->_DBH->prepare('UPDATE `items` SET
												   `order`=? 
												   WHERE id = ?');
				
				$this->_STH->bindParam(1, $order);
				$this->_STH->bindParam(2, $id);
		
				$this->_STH->execute(); 
		
				return;
		}
	
	// --------------------------------------------------------------------

	/**
	 * Processes an attribute insert request to the default databse
	 *
	 * @param	array	$data		Array of data with parentid, name, level, islabel as indexes
	 * 
	 * @return	bool
	 */
		function insert($data=null){
				//get the new order
				$newOrder = $this->newOrder($data['parentid']); 
			
				$this->_STH = $this->_DBH->prepare('INSERT INTO `'.DB_TABLE_NAME.'` 
														(`name`,
														 `level`,
														 `label`,
														 `order`,
														 `parent`) 
														 VALUES (?, ?, ?, ?, ?)');
												   
				
				$this->_STH->bindParam(1, $data['name']);
				$this->_STH->bindParam(2, $data['level']);
				$this->_STH->bindParam(3, $data['islabel']);
				$this->_STH->bindParam(4, $newOrder);
				$this->_STH->bindParam(5, $data['parentid']);
			
				return $this->_STH->execute();
		}
	
	// --------------------------------------------------------------------

	/**
	 * Process the add new price combo request
	 *
	 * @param	array	$data		Array of data with comboid, quantity, and price as indexes
	 * 
	 * @return	bool
	 */
		function insert_combo($data=null){
				$this->_STH = $this->_DBH->prepare('INSERT INTO `quantities` 
														(`itemCombo`,
														 `quantity`,
														 `price`) 
														  VALUES (?, ?, ?)');
												   
				$this->_STH->bindParam(1, $data['comboid']);
				$this->_STH->bindParam(2, $data['quantity']);
				$this->_STH->bindParam(3, $data['price']);
				
				$this->_STH->execute();
		
				return $this->_DBH->lastInsertId();
		}
	
	// --------------------------------------------------------------------

	/**
	 * Process the select product request and returns the results as JSON
	 *
	 * @param	array	$data		Array of data with comboid, quantity, and price as indexes
	 * 
	 * @return	object
	 */
        function selectProductsJson($level = 1, $parent = 0, $order = NULL) {
            
			if ($order){
				$this->_STH = $this->_DBH->query('SELECT * from `items` 
													WHERE level = '.$level.' 
													AND parent = '.$parent.' 
													AND `order` = '.$order); 
			}else{
				$this->_STH = $this->_DBH->query('SELECT * from `items` 
													WHERE level = '.$level.'
													 AND parent = '.$parent);
			}
	
            $this->_STH->setFetchMode(PDO::FETCH_OBJ);
            
            return $this->_STH;         
        }
		
	// --------------------------------------------------------------------

	/**
	 * Process the select products request, with restrictions
	 *
	 * @param	int 	$level		the level of the items you want
	 * @param	int		$parent		the parent of the items you want 
	 * 
	 * @return	object
	 */
        function selectProducts($level = 1, $parent = 0) {
		
			$this->_STH = $this->_DBH->query('SELECT items.*,
												 restrictions.source,
												 restrictions.canthave  
												 FROM `items` 
												 LEFT JOIN restrictions ON items.id=restrictions.canthave
												 WHERE level = '.$level.' 
												 AND parent = '.$parent.' 
												 GROUP BY items.id  
												 ORDER BY `order` ASC');
		
            $this->_STH->setFetchMode(PDO::FETCH_OBJ);
            
			return $this->_STH;
        }	

	// --------------------------------------------------------------------

	/**
	 * Get a list of unallowed children for a specific parent
	 *
	 * @param	int 	$source		id of the parent
	 * 
	 * @return	array
	 */
		function getRestrictions($source){
			$this->_STH = $this->_DBH->query('SELECT * FROM `restrictions` 
												WHERE source = \''.$source.'\'');
			
			$this->_STH->execute(); 
			$result = $this->_STH->fetchAll();	
			
			return $result; 
		}

	// --------------------------------------------------------------------

	/**
	 * Used for the admin restriction selection view
	 * shows the previous parent and its children for creating a restriction on an attribute
	 *
	 * @param	int 	$level		id of the attributes level
	 * @param	int		$parent		id of the attributes parent
	 * @param 	int 	$item		id of the selected attribute
	 * 
	 * @return	array
	 */
        function selectRestrictions($level = 3, $parent = 0, $item) {
            
			//get the parent's order and its parent 
			$this->_STH = $this->_DBH->query('SELECT * FROM `items` 
												WHERE id = \''.$parent.'\'');
			
			$this->_STH->execute(); 
			$result = $this->_STH->fetchObject();
			
			$parentsOrder = $result->order; 
			$parentsParent = $result->{'parent'}; 
			$prevOrder = $parentsOrder-1; 
			
			//subtract one from the order and get the previous option
			$this->_STH = $this->_DBH->query('SELECT * FROM `items` 
												WHERE parent = '.$parentsParent.' 
												AND `order` = '.$prevOrder);
			$this->_STH->execute(); 
			$result = $this->_STH->fetchObject();
			
			$itemBefore = $result->id; 
				
			//get all of the previous option's attributes and pass it to the view
			$data['data'] = $this->selectProducts($level, $itemBefore);
			$data['lastItem'] = $result->name;
				
			//get all of the existing restrictions for the selected ID  
			$this->_STH = $this->_DBH->prepare('SELECT * FROM restrictions
												 WHERE canthave = '.$item);
			$this->_STH->execute(); 
			$data['restrictions'] = $this->_STH->fetchAll();

			return $data;
        }	

	// --------------------------------------------------------------------

	/**
	 * Select all of the prices for a given combo id (md5 hash)
	 *
	 * @param	string 	$md5		item combo identifier hashed using md5
	 * 
	 * @return	object
	 */
		function selectPrices($md5) {
			$this->_STH = $this->_DBH->query('SELECT * FROM `quantities`
												 WHERE itemCombo = \''.$md5.'\''); 
			
			$this->_STH->setFetchMode(PDO::FETCH_OBJ);
			
			return $this->_STH;         
		}
		
	
	
	// --------------------------------------------------------------------

	/**
	 * Select all of the prices for a given combo id (md5 hash) 
	 *
	 * @param	string 	$itemCombo		item combo identifier hashed using md5
	 * 
	 * @return	object
	 */
        function selectPricesList($itemCombo) {
			$this->_STH = $this->_DBH->query('SELECT * FROM `quantities`
												 WHERE itemCombo = \''.$itemCombo.'\' 
												 ORDER BY `quantity` ASC'); 
			
            $this->_STH->setFetchMode(PDO::FETCH_OBJ);
            
            return $this->_STH;         
        }
		
	// --------------------------------------------------------------------

	/**
	 * Select all of the prices for all existing combo ids (md5 hash) used in the admin screen
	 *
	 * @param	string 	$itemCombo		item combo identifier hashed using md5
	 * 
	 * @return	object
	 */
        function selectPricesAdmin() {
			$this->_STH = $this->_DBH->query('SELECT ID,itemCombo FROM quantities
												 GROUP BY itemCombo 
												 HAVING COUNT(*) >=1 
												 ORDER BY id DESC'); 
			
            $this->_STH->setFetchMode(PDO::FETCH_OBJ);
            
            return $this->_STH;         
		}
		
	// --------------------------------------------------------------------

	/**
	 * Builds breadcrumb for the admin section
	 *
	 * @param	int 	$parent		id of the current selection's parent
	 * @param	int		$level		the level of the current selection
	 * 
	 * @return	array
	 */
		function breadcrumb($parent, $level){
			
			$theLevel = $level;
			$bread = array(); 
			$i = 0;
			
			while( $theLevel > 1){
				$i++; 
				
				$this->_STH = $this->_DBH->query('SELECT * FROM `items`
													 WHERE id = \''.$parent.'\'');
				$this->_STH->execute(); 
            	$result = $this->_STH->fetchObject();
				
				$bread[$i]['name'] = $result->name; 
				$bread[$i]['id'] = $result->id;
				$bread[$i]['level'] = $result->level+1;
				$bread[$i]['parent'] = $result->{'parent'};
				
				$parent = $result->{'parent'};
				
				$theLevel = $theLevel-1; 
			}
			return array_reverse($bread);	
		}

}

/* End of file pdoquery.class.php */
/* Location: ./classes/pdoquery.class.php */