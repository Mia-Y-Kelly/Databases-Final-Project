<?php
    require "db.php";
    session_start();
    echo "<pre>";
    print_r($_SESSION);
    print_r($_POST);
    echo "</pre>";

    // user clicked the login button
    if(isset($_POST['submit'])) 
    {
            echo "Submit pressed \n";

            //check the username and passwd, if correct, redirect to main.php page
            if (authenticate($_POST['username'], $_POST['password']) == 1)
            {
                $_SESSION['username']=$_POST['username'];
                print_r($_SESSION);
                print_r($_POST);
                header("LOCATION:resetpwd.php");
                return;
            } 
            else 
            {
                echo '<p style=\"color:red\">incorrect username and password</p>';
                echo "<pre>";
                print_r($_POST);
            }
    }

    // user clicked the logout button */
    if ( isset($_POST["logout"]) ) 
    {
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
    </body>
</html>

