<table width="100%" class="table table-striped table-hover" cellspacing="0">
  <tr class="heading">
    <th>Quantity</th>
    <th>Price</th>
    <th>Actions</th>
  </tr>

<?php  

$i=0;
while ($result = $data->fetchObject()) { 

?>
	<tr>

  	<td>

 
    <a href="<?php echo BASE_URL.'editprice/'.$result->ID; ?>" >
	  <?php 
        echo $result->quantity; 
      ?>
      </a>

      
      </td>
     
  	<td>

 
    
	  <?php 
        echo '$'.$result->price; 
      ?>
   

       
      </td>

  <td>

        
        <div class="btn-group">
  <a class="btn btn-mini" href="<?php echo BASE_URL ?>editprice/<?php echo $result->ID; ?>"><i class="icon-edit"></i> Edit</a>
  <a class="btn btn-mini" href="<?php echo BASE_URL ?>delete_price_process/<?php echo $result->id; ?>/<?php echo $result->itemCombo; ?>/"><i class="icon-remove"></i> Delete</a>
   
  
</div>


            </td>
  </tr>
<?php 
}
?>
</table>


<?php // the url is in form http://website/add/parentid/level  ?>

<a class="btn btn-success" href="<?php echo BASE_URL ?>add_combo/"><i class="icon-plus icon-white"></i> Add Price</a>
