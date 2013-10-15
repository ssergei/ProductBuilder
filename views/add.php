<form action="<?php echo INSTALL_PATH ."/index.php/add_process" ?>" method="post">
	<?php 
    if($meta['theParent'] == 0){
        $name = "Product";
    }else{
        $name = "Attribute";	
    }
    if(!$meta['theParent']){
        $meta['theParent'] = 0; 
    }
    ?>
    <div>
        <label ><?php echo $name; ?> Name:</label>
        <input type="text" name="name" id="name" value="<?php echo $data->name; ?>" tabindex="1" />
    </div>
    <input type="hidden" name="parentid" id="parentid" value="<?php echo $meta['theParent']; ?>" />
    <input type="hidden" name="level" id="level" value="<?php echo $meta['theLevel']; ?>" />
    <input type="hidden" name="islabel" id="islabel" value="<?php if ($meta['theLevel'] < 3){ echo '1'; } ?>" />
    <div class="actionButtons">
        <input type="submit" value="Save" class="btn btn-success" />
        <input type="button" class="btn" onClick="window.location = '<?php echo INSTALL_PATH."/index.php/home/".$meta['theParent']."/".$meta['theLevel']; ?>'; " value="Cancel" />
    </div>
</form>