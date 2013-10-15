<form action="<?php echo INSTALL_PATH ."/index.php/login_process" ?>" method="post">
    <div>
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" value="" tabindex="1" />
    </div>
    <div>
        <label for="site_url">Password:</label>
        <input type="password" name="password" id="password" value="" tabindex="2" />
    </div>
    <div style="position: absolute; left: -999px; ">
        <label for="city">Bot catcher, leave this blank.</label>
        <input type="text" name="city" id="city" value="" tabindex="99" />
    </div>
    <div>
        <input class="btn btn-success" type="submit" value="Login" />
    </div>
</form>