<?php
	require "db.php";
	SESSION_START();
	$course_id = $_SESSION['COURSE_ID'];
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
						echo("<input type='radio' id='multipleChoice' name='" . $choice[0] . "'value='" . $choice[2]. "'>");
                        echo("<label for='multipleChoice'>" . $choice[1]. ": ". $choice[2] . "</label><br>");
                    }
                }
                else if($question[0] == "FR")
                {
						echo("<input type='text' id='freeResponse' name='" . $question[1] . "'><br/>");
                }
            }
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
			$course_id = $_SESSION['COURSE_ID'];
			
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
					// Insert the MC response
					$sql = "INSERT INTO Course_Question_Responses(course_id,question_number, choice_string, freq, essay) VALUES('$course_id', '$q_num', '$answer', 1, 'N/A')";
					$statement = $dbh->prepare($sql);
					$result = $statement->execute();
					$dbh = NULL;
				}
			}
		}
			header("LOCATION:student.php"); 
		 catch(PDOException $e)
	   	{
        	print "Error! " . $e->getMessage() . "<br/>";
       		die();
    	}
	}
		
		?>
        </form>
