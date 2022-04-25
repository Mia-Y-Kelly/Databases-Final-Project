<?php
    // Require database functionality.
    require "db.php";

    // Join the session.
    session_start();

    // Have the student complete the survey.
    try 
    {
        $dbh = connectDB();
        $sqlstmt = "SELECT * FROM Question";
        $statement = $dbh->prepare($sqlstmt);
        $result = $statement->execute();
        $questions = $statement->fetchAll();

        ?>
        <form class="survey-form" action="student.php" method="POST">
            <?php
            foreach($questions as $question)
            {
                echo("<p>$question[1]. $question[2]</p>"); 

                if($question[0] = "MC")
                {
                    $sqlstmt = "SELECT * FROM Question WHERE question_number = $question[1]";
                    $statement = $dbh->prepare($sqlstmt);
                    $result = $statement->execute();
                    $choices = $statement->fetchAll();
                    foreach($choices as $choice)
                    {
                        ?>
                        <input type="radio" id="multipleChoice" name="multipleChoice" value="multipleChoice">
                        <label for="multipleChoice">
                            <?php>
                                echo("$choice[3]: $choice[4]");
                            ?>
                        </label><br>
                        <?php
                    }
                }
                else if($question[0] = "FR")
                {
                    ?>
                    <input type="text" id="freeResponse" name="freeResponse"><br>
                    <?php
                }
            }
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

    // When the Student submits the survey, update the completion of the survey and redirect to student.php.
    if(isset($_POST['submitSurvey'])) 
    {
        recordSurveyCompletion($_SESSION['username']);
        print("SUCCESS: You have successfully completed the survey for this course.");
        header("LOCATION:student.php");
    }
?>