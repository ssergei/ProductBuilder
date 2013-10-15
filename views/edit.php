<ul class="breadcrumb">
	<?php if (!$theParent){?>
        <li><a href="<?php echo BASE_URL ?>">Home</a> <span class="divider">></span></li>
    <?php }
    
    foreach($meta['bread'] as $k){
		echo '<li>';
			echo '<a href="'.BASE_URL.'home/'.$k['id'].'/'.$k['level'].'">';
			echo $k['name']; 
			echo '</a> <span class="divider">></span>';
		echo '</li>';	
    }
    ?>
</ul>
<form action="<?php echo INSTALL_PATH ."/index.php/edit_process" ?>" method="post">
    <input type="hidden" name="id" id="id" value="<?php echo $data->id; ?>" tabindex="99" />
    <div>
        <label >Attribute Name:</label>
        <input type="text" name="name" id="name" value="<?php echo $data->name; ?>" tabindex="1" />
    </div>
    <div class="actionButtons">
        <input type="submit" value="Save" class="btn btn-success" />
        <input type="button" class="btn" onClick="window.location = '<?php echo INSTALL_PATH."/index.php"; ?>'; " value="Cancel" />
    </div>
</form>