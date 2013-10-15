<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo $meta['title']; ?> | Print Estimator Management</title>
    <?php 
    
    echo "<!-- CSS includes -->\n"; 
	
    //load all of the css files
    foreach (glob("layout/css/*.css") as $css_filename){ 
		echo  '<link rel="stylesheet" href="'.INSTALL_PATH.'/'.$css_filename.'">';
		echo "\n";
    }
    
    echo "\n\n<!-- Javascript includes -->\n";
	
    //load all of the js files 
    foreach (glob("layout/js/*.js") as $js_filename){ 
		echo '<script src="'.INSTALL_PATH.'/'.$js_filename.'"></script>';
		echo "\n";
    }
    ?>
    <style>
    body {
	    padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
    }
    </style>
    
    <script>
    $(document).ready(function(){
    <?php if ($meta['notify']){ ?>						   
    	$('[class^=notify]').delay(7000).slideUp('slow'); 					   		   
    <?php } ?>
    }); 
    </script>
</head>
<body>
	<?php 
    //only show this if they are logged in 
    if ($_SESSION['logged_in']){
    ?>
        
        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="brand" href="#">Print Estimator Dashboard</a>
                    <div class="nav-collapse collapse">
                        <ul class="nav">
                            <li><a href="<?php echo BASE_URL ?>">Items</a></li>
                            <li><a href="<?php echo BASE_URL ?>prices">Prices</a></li>
                            <li><a href="<?php echo BASE_URL ?>logout">Logout</a></li>
                        </ul><!-- end nav -->
                    </div><!-- end nav-collapse -->
                </div><!-- end container -->
            </div><!-- end navbar-inner -->
        </div><!-- end navbar -->
    <?php } ?>
    <div class="container">
