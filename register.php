<?php
    // Require database functionality.
    require "db.php";

    // Join the session.
    session_start();

    // Student clicked the Register button
    if(isset($_POST['register'])) 
    {
            echo "Register pressed \n";

            // Check the course ID; if correct, redirect to register.php page so the user may register for more courses.
            if (checkCourseID($_GET['stu_acc'], $_POST['courseID']) == 1)
            {
                $_SESSION['courseID']=$_POST['courseID'];
                print_r($_SESSION);
                print_r($_POST);
                header("LOCATION:register.php");
                return;
            } 
            else 
            {
                echo '<p style=\"color:red\">Invalid course ID</p>';
                echo "<pre>";
                print_r($_POST);
            }
    }
?>

<html>
    <body>
        <form class="form" action="register.php" method="POST">
            <div class="register-form">
                <label for="courseID" class="label"><b>Enter a Valid Course ID</b></label><br>
                <input type="text" id="courseID" name="courseID" class="text" value="" require>
                <br><br>

                <input type="submit" id="register" name="register" value="Register">
            </div>
        </form>
    </body>
</html>