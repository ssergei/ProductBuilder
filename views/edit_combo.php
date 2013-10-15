<form action="<?php echo INSTALL_PATH ."/index.php/edit_combo_process" ?>" method="post">
    <input type="hidden" name="id" id="id" value="<?php echo $meta['id']; ?>"/>
        <div>
            <label >Product Reference ID:</label>
            <input type="text" name="comboid" id="comboid" value="<?php echo $data->itemCombo; ?>" tabindex="1" />
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
        <input type="submit" value="Save" class="btn btn-success" />
        <input type="button" class="btn" onClick="window.location = '<?php echo INSTALL_PATH."/index.php/pricelist/".$data->itemCombo; ?>'; " value="Cancel" />
    </div>
</form>