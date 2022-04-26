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
                        echo("<input type='radio' id='multipleChoice' name='" . $choice[0] . "'value='" . $choice[2] . "'>");
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
		{	$course_id = $_SESSION['COURSE_ID'];
			$dbh = connectDB();
			$sql = "SELECT * FROM Question";
			$statement = $dbh->prepare($sql);
			$result = $statement->execute();
			$all_questions= $statement->fetchAll();			
			$dbh = null;
			$counter = 0;

			foreach($_POST as $answer) 
			{
				$dbh = connectDB();
				// Retrieve all the MC options from the choice table, if it returns a boolean, it must be FR
				$sql = "SELECT choice_char FROM Choice WHERE choice_string= '$answer'";
				$statement = $dbh->prepare($sql);
				$result = $statement->execute();
				$question_choice = $statement->fetch();
				
				// Get type				
				$type = strval(gettype($question_choice));
				
				//print_r($all_questions[$counter]);	
				if($counter > count($all_questions) - 1) 
				{
					break;
				}
				
				// Retrieve current question
				$q = $all_questions[$counter];
				
				if($type == "boolean") 
				{
					// Insert the FR response
					//print_r($_GET);
					//var_dump($_GET[($counter-1)]);
					print "<br/>text field ";
					var_dump($answer);
					//print_r($answer[0]);
					$sql = "INSERT INTO Course_Question_Responses(course_id, question_number, choice_string, freq, essay) VALUES('$course_id', '$q[1]', '$q[2]', NULL, '$answer')"; 
					$statement = $dbh->prepare($sql);
					$result = $statement->execute();
				} 
				else 
				{
					// Insert the MC response	
					$sql = "INSERT INTO Course_Question_Responses(course_id,question_number, choice_string, freq, essay) VALUES('$course_id', '$q[1]', '$answer', 1, 'N/A')";
					$statement = $dbh->prepare($sql);
					$result = $statement->execute();
					$dbh = NULL;
				}
				$counter++;
			}
			header("LOCATION:student.php"); 
		} catch(PDOException $e)
	   	{
        	print "Error! " . $e->getMessage() . "<br/>";
       		die();
    	}

	}		
		?>
        </form>
