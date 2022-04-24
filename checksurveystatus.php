<?php
    // Require database functionality.
    require "db.php";

    // Join the session.
    session_start();

    // Check the survey status of the Student's registered courses.
    checkSurveyStatus($_GET['stu_acc']);
?>