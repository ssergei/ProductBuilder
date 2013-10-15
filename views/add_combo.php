<form action="<?php echo INSTALL_PATH ."/index.php/add_combo_process" ?>" method="post">
    <div>
        <label >Product Reference ID:</label>
        <input type="text" name="comboid" id="comboid" value="<?php echo $meta['comboid']; ?>" tabindex="1" />
    </div>
    <div>
        <label >Quantity:</label>
        <input type="text" name="quantity" id="quantity" value="<?php echo $data->quantity; ?>" tabindex="1" />
    </div>
    <div>
        <label >Price:</label>
        <input type="text" name="price" id="price" value="<?php echo $data->price; ?>" tabindex="1" />
    </div>
    <div class="actionButtons">
        <input type="submit" value="Add" class="btn btn-success" />
        <input type="button" class="btn" onClick="window.location = '<?php echo INSTALL_PATH."/index.php/prices"; ?>'; " value="Cancel" />
    </div>
</form>