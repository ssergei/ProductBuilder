<ul class="breadcrumb">
	<?php if (!$theParent){?>
  <li><a href="<?php echo BASE_URL ?>">Home</a> <span class="divider">></span></li>
 	<?php }else{ ?>
    
    <?php } ?>
<?php 
	foreach($meta['bread'] as $k){
		
		echo '<li>';
		echo '<a href="'.BASE_URL.'home/'.$k['id'].'/'.$k['level'].'">';
		echo $k['name']; 
		echo '</a> <span class="divider">></span>';
		echo '</li>';	
	}
?>

</ul>

<script>
  $(function() {
	  
	  //add the drag and drop functionality
	  
	  // Return a helper with preserved width of cells
		var fixHelper = function(e, ui) {
			ui.children().each(function() {
				$(this).width($(this).width());
			});
			return ui;
		};

	$("#sortable tbody").sortable({
		helper: fixHelper,
		update: function( event, ui ) { 
			var itemOrder = $(this).sortable('toArray').toString();
			$.get('/mocks/print/index.php/jsonOrder/'+itemOrder);
			console.log(itemOrder);
		}
	}).disableSelection();
  });
</script>


<table width="100%" id="sortable" class="table table-striped table-hover" cellspacing="0">
    <tr class="heading">
        <th>Item</th>
        <th>Actions</th>
    </tr>
    <tbody>
		<?php  
        
        $i=0;
        while ($result = $data->fetchObject()) { 
        
        $i++;
        
        if ($i%2 == 0){
            $class="even"; 
        }else{
            $class=""; 
        }
        ?>
        <tr class="<?php echo $class; ?>" id="item_<?php echo $result->id; ?>">
            <td>
            <?php 
            //generate the next level 
            
            //use these vars for the ADD button below
            $theLevel = $result->level;
            $theParent = $result->{'parent'};
            
            $level = $result->level; 
            
            if ($level < 3){
                $level = $level+1;		
            }else{
                $level = 'max';
            }
            ?>
            <?php if ($level != 'max'){ ?>
                    <a href="<?php echo BASE_URL.'home/'.$result->id.'/'.$level; ?>" >
                    <?php 
                    echo $result->name; 
                    ?>
                    </a>
                <?php 
                }else{ 
                    echo $result->name; 
                } ?>
            </td>
            <td class="actions">
                <div class="btn-group">
                    <a class="btn btn-mini" href="<?php echo BASE_URL ?>edit/<?php echo $result->id; ?>"><i class="icon-edit"></i> Edit</a>
                    <a class="btn btn-mini" href="<?php echo BASE_URL ?>delete_process/<?php echo $result->id; ?>/<?php echo $theParent; ?>/<?php echo $theLevel; ?>"><i class="icon-remove"></i> Delete</a>
                    <?php if ($result->level == 3){ ?>
                        <a class="btn btn-mini" href="<?php echo BASE_URL ?>restrictions/<?php echo $result->id; ?>/<?php echo $theParent; ?>/"<i class="icon-exclamation-sign"></i> Restrictions</a>
                    <?php } ?>
                    
                    <a class="btn btn-mini" href="#"><i class="icon-move"></i> Move</a>
                </div>
            </td>
        </tr>
        <?php 
        }
        ?>
    </tbody>
</table>


<?php //set the parent and level vars if there arent any rows 
if ($i == 0){
	$theLevel = $meta['level'];
	$theParent = $meta['parent'];
}
?>
<?php if ($theParent){
	$itemName = "Attribute";
}else{
	$itemName = "Product";	
}
?>
<?php // the url is in form http://website/add/parentid/level  ?>

<a class="btn btn-success" href="<?php echo BASE_URL ?>add/<?php echo $theParent.'/'.$theLevel; ?>"><i class="icon-plus icon-white"></i> Add <?php echo $itemName; ?></a>