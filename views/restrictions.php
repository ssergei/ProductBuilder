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
 
 	//Function for drag and drop functionality
	
  $(function() {
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
		$.get('<?php echo INSTALL_PATH; ?>/index.php/jsonOrder/'+itemOrder);
		console.log(itemOrder);
	
	}
}).disableSelection();


  });
  </script>
  
<?php //build the existing array 

$resarray = array(); 
foreach($meta['restrictions'] as $res){
	$resarray[] = $res['source'];
}
?>

<p>Previous selection for <?php echo $meta['lastItem']; ?> cannot be: </p>

<table width="100%" id="sortable" class="table table-striped table-hover" cellspacing="0">
  <tr class="heading">
    <th style="width: 40px;">&nbsp;</th>
    <th>Item</th>
  </tr>
<tbody>
<form action="<?php echo INSTALL_PATH ."/index.php/edit_restrictions" ?>" method="post">
<input type="hidden" name="canthave" id="canthave" value="<?php echo $meta['item']; ?>" />
<input type="hidden" name="parent" id="parent" value="<?php echo $meta['parent']; ?>" />

<?php  

$i=0;
$allItems = array();
while ($result = $data->fetchObject()) { 

array_push($allItems, $result->id); 

$i++;

if ($i%2 == 0){
	$class="even"; 
}else{
	$class=""; 
}
?>
	<tr class="<?php echo $class; ?>" id="item_<?php echo $result->id; ?>">
	<td>
    <?php // this makes sure that even unchecked fields are passed to the processor so we can delete them ?>
    <input type="hidden" name="source[<?php echo $result->id; ?>]" value="">
    <input type="checkbox" <?php if(in_array($result->id, $resarray)){ echo " checked=checked "; }?> name="source[<?php echo $result->id; ?>]" value="<?php echo $result->id; ?>">
    
    </td>
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
      <?php }else{ ?>
      		<?php echo $result->name; ?>
      <?php } ?>
      
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

<?php // the url is in form http://website/add/parentid/level  ?>

<input type="hidden" name="allItems" id="allItems" value="<?php echo json_encode($allItems); ?>"/>
<input type="submit" value="Save" class="btn btn-success" />
