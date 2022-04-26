<?php
    // Require database functionality.
    require "db.php";

    // Join the session.
    session_start();

    print("SUCCESS: You may complete the survey for this course.\n");

    // Have the student complete the survey.
    try 
    {
        $dbh = connectDB();
        $sqlstmt = "SELECT * FROM Question ORDER BY question_number, choice_char";
        $statement = $dbh->prepare($sqlstmt);
        $result = $statement->execute();
        $questions = $statement->fetchAll();

        ?>
        <form class="survey-form" action="survey.php" method="POST">
            <?php
                foreach($questions as $question)
                {
                    echo("<p>$question[1]. $question[2]</p>"); 

                    if($question[0] == 'MC')
                    {
                        $sqlstmt = "SELECT * FROM Question WHERE question_number = $question[1] ORDER BY choice_char";
                        $statement = $dbh->prepare($sqlstmt);
                        $result = $statement->execute();
                        $choices = $statement->fetchAll();
                        foreach($choices as $choice)
                        {
                            echo("<p>$choice[3]: $choice[4]</p>");
                            ?>
                            <input type="radio" id="multipleChoice" name="multipleChoice" value="multipleChoice">
                            <?php
                        }
                    }
                    else if($question[0] == 'FR')
                    {
                        ?>
                        <input type="text" id="freeResponse" name="freeResponse"><br>
                        <?php
                    }
                }
                ?>

                <input type="submit" id="submitSurvey" name="submitSurvey" value="Submit Course Survey">
            </form>
            <?php

            $dbh=null;

            // When the Student submits the survey, update the completion of the survey and redirect to student.php.
            if(isset($_POST['submitSurvey'])) 
            {
                recordSurveyCompletion($_SESSION['username'], $_POST['surveyCourseID']);
                print("SUCCESS: You have successfully completed the survey for this course.\n");
                header("LOCATION:student.php");
            }

            return;
    }
    catch(PDOException $e)
    {   
        print "Error! " . $e->getMessage() . "<br/>";
        die();
    }
?>
