<?php
require "db.php";
session_start();
echo "<pre>";
print_r($_SESSION);
print_r($_POST);
echo "</pre>";

// user clicked the login button
if (isset($_POST['submit'])) {
        //check the username and passwd, if correct, redirect to main.php page

        if (authenticate($_POST['username'], $_POST['password']) == 1){
                $_SESSION['username']=$_POST['username'];
				isFirstLogin();		
			//	print_r($_SESSION);
              //  print_r($_POST);
                return;
        } else {
                echo '<p style=\"color:red\">incorrect username and password</p>';
                echo "<pre>";
                print_r($_POST);
        }
}

// user clicked the logout button */
if ( isset($_POST["logout"]) ) {
   session_destroy();
}
?>

<html>
    <link rel="stylesheet" href="login.css">
    <body>
        <form class="form" action="login.php" method="POST">
            <div class="login-form">
                <label for="uname" class="label"><b>Username</b></label><br>
                <input type="text" id="uname" name="username" class="text"value="" require>
                <br><br>
                <label for="pwd" class="label"><b>Password</b></label><br>
                <input type="password" id="pwd" name="password" class="text" value="" require>
                <br><br>
                <input type="submit" id="submit" name="submit" value="Submit">
            </div>
        </form>
<!--            <form class="createPwd" id="hiddenForm"style="display:none">
               <div class="innerPwd">
                    <label for="new_pwd" class="label"><b>New Password</b></label><br>
                    <input type="text" id=old_pwd name="old_pwd" class="text" value="" require>
                    <br/>
                    <label for="new_pwd" class="label"><b>Confirm New Password</b></label><br>
                    <input type="text" id=new_pwd name="new_pwd" class="text" value="" require>
                    <br><br>
                    <input type="submit" id="submit" name="submit" value="submit">
                </div>
             </form>
-->
    </body>
</html>

