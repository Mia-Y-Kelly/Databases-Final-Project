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
                    echo("<input type='text' id='freeResponse' name=" . $question[1] . "<br>");
                }
            }
    }
    catch(PDOException $e)
    {   
        print "Error! " . $e->getMessage() . "<br/>";
        die();
    }
      
	echo("<input type='submit' value='Submit_Survey' name='submitSurvey'>");
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
				$sql = "SELECT choice_char FROM Choice WHERE choice_string= '$answer'";
				$statement = $dbh->prepare($sql);
				$result = $statement->execute();
				$question_choice = $statement->fetch();
				
				// If it is of type boolean, then its FR				
				$type = strval(gettype($question_choice));
				
				//print_r($all_questions[$counter]);	
				if($counter > count($all_questions) - 1) 
				{
					break;
				}
				$q = $all_questions[$counter];
				
				if($type == "boolean") 
				{
					$sql = "INSERT INTO Course_Question_Responses VALUES('$course_id', '$q[1]', '$q[2]', NULL, '$answer')"; 
					$statement = $dbh->prepare($sql);
					$result = $statement->execute();
				} 
				else 
				{
					// Determine if its the first time the MC is inserted
					$sql = "SELECT * FROM Course_Question_Responses WHERE freq=0 AND choice_string='$answer'";
					$statement = $dbh->prepare($sql);
					$result = $statement->execute();
					$isFirst = $statement->fetch();
					if($isFirst != 1) 
					{
						$sql = "INSERT INTO Course_Question_Responses VALUES('$course_id', '$q[1]', '$answer', 1, 'N/A')";
						$statement = $dbh->prepare($sql);
						$result = $statement->execute();
					} 
					else 
					{
						$sql = "UPDATE Course_Question_Responses SET freq = freq + 1 WHERE choice_string='$answer'";
						$statement = $dbh->prepare($sql);
						$result = $statement->execute();
					}
				}
				$counter++;
				$dbh = null;
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
<?php
$dbh=null;
