<?php
    require "db.php";
    session_start();
    echo "<pre>";
    print_r($_SESSION);
    print_r($_POST);
    echo "</pre>";
?>

<html>
    <body>
        <h1>Please change your password</h1>
        <form>
            <label>New Password</label>
            <input type="text">
            <input type="submit" value="Submit">
        </form>
    </body>
</html>
