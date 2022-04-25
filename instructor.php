<?php
    // Require database functionality.
    require "db.php";

    // Join the session.
    session_start();

    // Instructor clicked the List Enrolled Students button.
    if(isset($_POST['listStudents'])) 
    {
            echo "List Enrolled Students pressed \n";

            // Check the course ID; if correct, show the class roster.
            if (checkInstructorCourseID($_SESSION['username'], $_POST['courseID']) == 1)
            {
                print_r($_SESSION);
                print_r($_POST);
                listClassRoster($_POST['courseID']);
                return;
            } 
            else 
            {
                echo '<p style=\"color:red\">Invalid course ID</p>';
                echo "<pre>";
                print_r($_POST);
            }
    }

    // Instructor clicked the See Course Evaluation Result button.
    if(isset($_POST['listStudents'])) 
    {
            echo "List Enrolled Students pressed \n";

            // Check the course ID; if correct, show the class roster.
            if (checkInstructorCourseID($_SESSION['username'], $_POST['courseID']) == 1)
            {
                print_r($_SESSION);
                print_r($_POST);
                courseEvaluations($_POST['courseID']);
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
        <form class="form" action="instructor.php" method="POST">
            <div class="list-students-form">
            <label for="courseID" class="label"><b>Enter a Valid Course ID</b></label><br>
                <input type="text" id="courseID" name="courseID" class="text" value="" require>
                <br><br>

                <input type="submit" id="listStudents" name="listStudents" value="List Enrolled Students">
            </div>
        </form>

        <form class="form" action="instructor.php" method="POST">
            <div class="evaluation-result-form">
            <label for="courseID" class="label"><b>Enter a Valid Course ID</b></label><br>
                <input type="text" id="courseID" name="courseID" class="text" value="" require>
                <br><br>
                
                <input type="submit" id="evaluationResult" name="evaluationResult" value="See Course Evaluation Result">
            </div>
        </form>
    </body>
</html>
