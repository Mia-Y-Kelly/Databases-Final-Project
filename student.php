<?php
    // Require database functionality.
    require "db.php";

    // Join the session.
    session_start();

    // Student clicked the Register button
    if(isset($_POST['register'])) 
    {
        // Check the course ID; if correct, redirect to the same page so the user may register for more courses.
        if(registerForCourse($_SESSION['username'], $_POST['courseID']) == 1)
        {
            header("LOCATION:student.php");
        } 
    }

    // Student clicked the Check Survey Status button.
    if(isset($_POST['checkSurveyStatus']))
    {
        // Check the survey status of the Student's registered courses.
        checkSurveyStatus($_SESSION['username']);
    }

    // Student clicked the Take Course Survey button.
    if(isset($_POST['takeSurvey']))
    {
        // Check the course ID; if correct, let the Student take the survey.
        if(checkStudentCourseID($_SESSION['username'], $_POST['courseID']) == 1)
        {
            header("LOCATION:survey.php");
        }
    }
?>

<html>
    <body>
        <form class="form" action="student.php" method="POST">
            <div class="register-form">
                <label for="courseID" class="label"><b>Register for Course ID</b></label><br>
                <input type="text" id="courseID" name="courseID" class="text" value="" require>
                <br><br>

                <input type="submit" id="register" name="register" value="Register">
            </div>
        </form>

        <form class="form" action="student.php" method="POST">
            <div class="check-survey-status-form">
                <input type="submit" id="checkSurveyStatus" name="checkSurveyStatus" value="Check Survey Status">
            </div>
        </form>

        <form class="form" action="survey.php" method="POST">
            <div class="take-survey-form">
                <label for="courseID" class="label"><b>Take Survey for a Course ID</b></label><br>
                <input type="text" id="courseID" name="courseID" class="text" value="" require>
                <br><br>

                <input type="submit" id="takeSurvey" name="takeSurvey" value="Take Course Survey">
            </div>
        </form>
    </body>
</html>