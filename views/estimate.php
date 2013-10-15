<form>
    <fieldset>
        <legend>Print Job Estimator</legend>
        <div class="control-group">
            <label>Product</label>
            <select class="span3" id="firstone">
                <option value="">-- Select Project</option>
                <?php 
                while ($result = $data->fetchObject()) { 
					echo '<option value='.$result->id.' data-level='.$result->level.' data-order=1>'.$result->name.'</option>'; 
                }
                ?>
            </select>
        </div>
        <div id="dynamicboxes"></div><!-- end dynamic boxes -->
        <div id="loading-image" style="opacity: 0;"><img src="<?php echo INSTALL_PATH; ?>/assets/img/loading.gif" /></div>
        <div id="pricebox"></div><!-- end price box -->
    </fieldset>
</form>
<div class="well well-small " style="text-align:right;">$ <span id="price">--</span></div> 
<small>Product Reference Number: <?php if($_SESSION['logged_in']){echo "<a id='combolink' href=''>";}?><span id="productnumber"></span><?php if($_SESSION['logged_in']){echo "</a>";}?></small>