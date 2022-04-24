<?php
    session_start();
?>

<html>
    <link rel="stylesheet" href="main.css">
    <body>
        <form action="login.php" method="post">
            <?php
                if (!isset($_SESSION['username'])) {
            ?>
                <input type="submit" class="submit" value='login' name="login">
            <?php
                } else {
                    echo "Welcome ". $_SESSION['username'];
            ?>
                    <input type="submit" class="submit" value='logout' name="logout">
            <?php
                }
            ?>
        </form>
        <h1>Password already set; redirected to main.php</h1>
    </body>
</html>
