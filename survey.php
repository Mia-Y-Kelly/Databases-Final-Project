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
						// Use html code if apostrophe exists in string
						$c1 = "";
						if(is_int(strpos($choice[2], "'"))){
							$position = strpos($choice[2], "'");
							$c1 = substr($choice[2], 0, $position);
							$c2 = substr($choice[2], $position + 1);
							$c1 = $c1."&#39".$c2;
						}
						
						echo("<input type='radio' id='multipleChoice' name='" . $choice[0] . "' value='$c1'> ");
                        echo("<label for='multipleChoice'>" . $choice[1]. ": ". $choice[2] . "</label><br>");
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
      
	echo("<input type='submit' value='Submit Survey' name='submitSurvey'>");
    if(isset($_POST['submitSurvey'])) 
	{
	
		try
		{	
			// Retrieve the course_id from the session	
			$course_id = strtoupper($_SESSION['COURSE_ID']);
			
			// Get all the questions
			$dbh = connectDB();
			$sql = "SELECT question_type, question_number, question FROM Question";
			$statement = $dbh->prepare($sql);
			$result = $statement->execute();
			$all_questions= $statement->fetchAll();			
			$dbh = null;
			
			foreach($all_questions as $current_question) 
			{
				// Get question number
				$q_num = (int) $current_question["question_number"];
				print "<br/><br/>";
				
				// If the question number does not exists as a key in the array,
				// then it had not response and continue to next question
				if(!array_key_exists($q_num, $_POST)) {
					continue;
				}
				
				// Get current question
				$question = $current_question["question"];
				
				// Get user response
				$answer = $_POST[$q_num];
				
				$dbh = connectDB();
				
				// Add FR if its not an empty string
				if($current_question["question_type"] == "FR" && $answer != "") 
				{
					// Insert the FR response
					$sql = "INSERT INTO Course_Question_Responses(course_id, question_number, choice_string, freq, essay) VALUES('$course_id', '$q_num', '$question', NULL, '$answer')"; 
					$statement = $dbh->prepare($sql);
					$result = $statement->execute();
				} 
				else if ($current_question["question_type"] == "FR" && $answer == "")
				{
					// Continue if the FR is empty
					continue;
				}
				else 
				{
					// Insert escape character if there is an apostrophe
					if(is_int(strpos($answer, "'"))) {
						$answer = addslashes($answer);
						echo $answer;
					}

					$sql = "INSERT INTO Course_Question_Responses(course_id,question_number, choice_string, freq, essay) VALUES('$course_id', '$q_num', '$answer', 1, 'N/A')";
					$statement = $dbh->prepare($sql);
					$result = $statement->execute();
					$dbh = NULL;
				}
			}
			header("LOCATION:student.php"); 
		}
		catch(PDOException $e)
		{
			print "Error! " . $e->getMessage() . "<br/>";
			die();
		}
	}
		
		?>
        </form>

