<?php 
// this isnt the ideal code for passing info, but cUrl just isn't a suitable solution becuase 
// of the cross-domain cookie issues.



//get the data
	$username = $data->wp_username; 
	$password = $data->wp_password; 
	$url = $data->site_url; 
	$admin_url = $data->site_admin_url; 


?> <?php if (1 == 2){ ?>
<form name="loginform" id="loginform" action="<?php echo $url.$admin_url; ?>" method="post">
		<input type="text" name="log" id="user_login" class="input" value="<?php echo $username; ?>" size="20" tabindex="10"></label>
		<input type="password" name="pwd" id="user_pass" class="input" value="<?php echo $password; ?>" size="20" tabindex="20"></label>
		<input name="rememberme" type="checkbox" id="rememberme" value="forever" tabindex="90">
		<input type="submit" name="wp-submit" id="wp-submit" class="button-primary" value="Log In" tabindex="100">
		<input type="hidden" name="redirect_to" value="<?php echo $url; ?>wp-admin/">
		<input type="hidden" name="testcookie" value="1">

</form>
<?php } ?>
<noscript>
<div class="notify-error">You must have javascript enables for this function to work!</div>
</noscript>
<script type="text/javascript">
$(document).ready(function(){
	//$('#loginform').submit ();   
	 });
	
	
	function post_to_url(path, params, method) {
    method = method || "post"; // Set method to post by default, if not specified.

    // The rest of this code assumes you are not using a library.
    // It can be made less wordy if you use one.
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);

    for(var key in params) {
        var hiddenField = document.createElement("input");
        hiddenField.setAttribute("type", "hidden"); 
        hiddenField.setAttribute("name", key);
        hiddenField.setAttribute("value", params[key]);

        form.appendChild(hiddenField);
    }

    document.body.appendChild(form); 
    form.submit();
}

//set the paramerter
$path = "<?php echo $url.$admin_url; ?>";
$params = new Array(); 
$params['log'] = "<?php echo $username; ?>"; 
$params['pwd'] = "<?php echo $password; ?>"; 
$params['redirect_to'] = "<?php echo $url; ?>wp-admin/"; 
$params['wp-submit'] = true; 

//post_to_url($path, $params); 

						



</script>
