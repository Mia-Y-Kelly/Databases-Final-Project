<?php
    require "db.php";
    session_start();
    $_SESSION['COURSE_ID'] = "";

    // user clicked the login button
    if (isset($_POST['submit'])) 
    {
        //check the username and passwd, if correct, redirect to main.php page
        if (authenticate($_POST['username'], $_POST['password']) == 1)
        {
            $_SESSION['username']=$_POST['username'];
			isFirstLogin();		
            return;
        } 
        else 
        {
            echo '<p style=\"color:red\">incorrect username and password</p>';
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

