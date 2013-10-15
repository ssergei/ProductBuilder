<table width="100%" class="table table-striped table-hover" cellspacing="0">
  	<tr class="heading">
		<th>Item Combo ID</th>
		<th>Actions</th>
	</tr>

<?php  
$i=0;

while ($result = $data->fetchObject()) { 
?>
	<tr>
  		<td>
    		<a href="<?php echo BASE_URL.'pricelist/'.$result->itemCombo; ?>" >
	  			<?php echo $result->itemCombo; ?>
      		</a>
		</td>
        <td>
            <div class="btn-group">
                <a class="btn btn-mini" href="<?php echo BASE_URL ?>pricelist/<?php echo $result->itemCombo; ?>"><i class="icon-edit"></i> Edit</a>
                <a class="btn btn-mini" href="<?php echo BASE_URL ?>delete_price_combo_process/<?php echo $result->itemCombo; ?>/"><i class="icon-remove"></i> Delete</a>
            </div>
    
    
        </td>
	</tr>
<?php 
}
?>
</table>

<?php // the url is in form http://website/add/parentid/level  ?>

<a class="btn btn-success" href="<?php echo BASE_URL ?>add_combo/"><i class="icon-plus icon-white"></i> Add Item Combo ID</a>