<?php
    // Require database functionality.
    require "db.php";

    // Join the session.
    session_start();

    // Student clicked the Register button
    if(isset($_POST['register'])) 
    {
        // Check the course ID; if correct, redirect to the same page so the user may register for more courses.
        if(registerForCourse($_SESSION['username'], $_POST['registerCourseID']) == 1)
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
		// Determine whether the Student can take the survey.
        if(checkStudentCanTakeSurvey($_SESSION['username'], $_POST['surveyCourseID']) == TRUE)
        {
            $_SESSION['COURSE_ID']= $_POST['surveyCourseID'];
            header("LOCATION:survey.php");
			//print_r($_POST);
			completeSurvey($_SESSION['username'], $_POST['surveyCourseID']);
			print "After completeSurvery";
			print_r($_POST);
            recordSurveyCompletion($_SESSION['username'], $_POST['surveyCourseID']);
            print("SUCCESS: You have successfully completed the survey for this course.\n");
        }
    }
?>

<html>
    <body>
        <form class="form" action="student.php" method="POST">
            <div class="register-form">
                <label for="registerCourseID" class="label"><b>Register for Course ID</b></label><br>
                <input type="text" id="registerCourseID" name="registerCourseID" class="text" value="" require>
                <br><br>

                <input type="submit" id="register" name="register" value="Register">
            </div>
        </form>

        <form class="form" action="student.php" method="POST">
            <div class="check-survey-status-form">
                <input type="submit" id="checkSurveyStatus" name="checkSurveyStatus" value="Check Survey Status">
            </div>
        </form>

        <form class="form" action="student.php" method="POST">
            <div class="take-survey-form">
                <label for="surveyCourseID" class="label"><b>Take Survey for a Course ID</b></label><br>
                <input type="text" id="surveyCourseID" name="surveyCourseID" class="text" value="" require>
                <br><br>

                <input type="submit" id="takeSurvey" name="takeSurvey" value="Take Course Survey">
            </div>
        </form>
    </body>
</html>
