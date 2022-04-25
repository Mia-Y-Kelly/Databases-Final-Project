<?php
    // Require database functionality.
    require "db.php";

    // Join the session.
    session_start();

    // Access global variable from within a function.
    // global $STORED_COURSE_ID;

    print("SUCCESS: You may complete the survey for this course.\n");
    print_r($_SESSION);
    print("-------------------------\n");
    print_r($STORED_COURSE_ID);
    print("-------------------------\n");

    // When the Student submits the survey, update the completion of the survey and redirect to student.php.
    if(isset($_POST['submitSurvey'])) 
    {
        recordSurveyCompletion($_SESSION['username'], $STORED_COURSE_ID);
        header("LOCATION:student.php");
        print("SUCCESS: You have successfully completed the survey for this course.\n");
    }

    // Have the student complete the survey.
    try 
    {
        $dbh = connectDB();
        $sqlstmt = "SELECT * FROM Question ORDER BY question_number";
        $statement = $dbh->prepare($sqlstmt);
        $result = $statement->execute();
        $questions = $statement->fetchAll();
        ?>
        <form class="survey-form" action="survey.php" method="POST">
            <?php
            foreach($questions as $question)
            {
                echo("<p>$question[1]. $question[2]</p>"); 

                if($question[0] == "MC")
                {
                    $sqlstmt = "SELECT * FROM Choice WHERE question_number = $question[1] ORDER BY choice_char";
                    $statement = $dbh->prepare($sqlstmt);
                    $result = $statement->execute();
                    $choices = $statement->fetchAll();
                    foreach($choices as $choice)
                    {
                        echo("<input type='radio' id='multipleChoice' name=" . $choice[1] . "value=" . $choice[2] . ">");
                        echo("<label for='multipleChoice'>" . $choice[1]. ": ". $choice[2] . "</label><br>");
                    }
                }
                else if($question[0] == "FR")
                {
                    echo("<input type='text' id='freeResponse' name=" . $question[1] . "<br>");
                }
            }

            echo("<input type='submit' value='Submit Survey' name='submitSurvey'>");
            ?>
        </form>
        <?php

        $dbh=null;

        return;
    }
    catch(PDOException $e) 
    {
        print "Error! " . $e->getMessage() . "<br/>";
        die();
    }
?>