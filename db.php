<?php
	// Connect to the database
    function connectDB() 
    {
        $config = parse_ini_file("db.ini");
        $dbh = new PDO($config['dsn'], $config['username'], $config['password']);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $dbh;
    }

    //return number of rows matching the given user and passwd.  
    function authenticate($user, $passwd) 
    {
        try 
        {
            $dbh = connectDB();
            $sqlstmt = "SELECT count(*) FROM
                        (SELECT stu_acc AS username, stu_pwd AS password FROM Student
                        UNION
                        SELECT instr_acc AS username, instr_pwd AS password FROM Instructor) combined ";

            $statement = $dbh->prepare($sqlstmt.
                                        " where username = :username and password = sha2(:passwd,256) ");
            $statement->bindParam(":username", $user);
            $statement->bindParam(":passwd", $passwd);
            $result = $statement->execute();
            $row=$statement->fetch();
            $dbh=null;

            return $row[0];
        }
        catch (PDOException $e) 
        {
            print "Error! " . $e->getMessage() . "<br/>";
            die();
        }
    }

	// Helper function to determine if they are a student
    function isStudent($user)
    {
        try 
        {
            $dbh = connectDB();
            $sqlstmt = "SELECT COUNT(*) FROM
                        (SELECT stu_acc AS username FROM Student) students ";

            $statement = $dbh->prepare($sqlstmt.
                                        " where username = :username ");
            $statement->bindParam(":username", $user);
            $result = $statement->execute();
            $row=$statement->fetch();
            $dbh=null;

            return $row[0];
        }
        catch (PDOException $e) 
        {
            print "Error! " . $e->getMessage() . "<br/>";
            die();
        }
    }

	// Helper function to determine if they are an instructor
    function isInstructor($user)
    {
        try 
        {
            $dbh = connectDB();
            $sqlstmt = "SELECT COUNT(*) FROM
                        (SELECT instr_acc AS username FROM Instructor) instructors ";

            $statement = $dbh->prepare($sqlstmt.
                                        " where username = :username ");
            $statement->bindParam(":username", $user);
            $result = $statement->execute();
            $row=$statement->fetch();
            $dbh=null;

            return $row[0];
        }
        catch (PDOException $e) 
        {
            print "Error! " . $e->getMessage() . "<br/>";
            die();
        }
    }
    
	// Determine whether a student has registered for a course with a valid course_id
	function checkStudentCourseID($studentAccount, $courseID)
    {
        try
        {
            $dbh = connectDB();
            $sqlstmt = "SELECT course_id FROM Course ";

            $statement = $dbh->prepare($sqlstmt.
                                        " where course_id = :courseID");
            $statement->bindParam(":courseID", $courseID);
            $result = $statement->execute();
            $row=$statement->fetch();
            return $row;
        }
        catch (PDOException $e) 
        {
            print "Error! " . $e->getMessage() . "<br/>";
            die();
        }
    }

    // Have a student register for a course.
    function registerForCourse($studentAccount, $courseID)
    {
        try 
        {
            // First check if the course ID is invalid.
            if(checkStudentCourseID($studentAccount, $courseID) == null)
            {
                print("ERROR: Invalid course ID $courseID<br/>");
                return;
            }
            else
            {
                $dbh = connectDB();

                // Then check if the Student has already registered for the course.
                $sqlstmt = "SELECT stu_acc as Account, course_id as Course FROM Takes ";

                $statement = $dbh->prepare($sqlstmt.
                                        " where stu_acc = :studentAccount and course_id = :courseID");
                $statement->bindParam(":studentAccount", $studentAccount);
                $statement->bindParam(":courseID", $courseID);
                $result = $statement->execute();
                $row=$statement->fetch();

                if($row == null)
                {
                    $statement = $dbh->prepare("INSERT INTO Takes VALUES(:studentAccount, :courseID, null)");
                    $statement->bindParam(":studentAccount", $studentAccount);
                    $statement->bindParam(":courseID", $courseID);
                    $result = $statement->execute();
                    $row=$statement->fetch();
                    print("SUCCESS: You have successfully registered for course $courseID.");
                }
                else
                {
                    print("ERROR: You have already registered for course $courseID.");
                }
            }

            $dbh=null;
            return;
        }
        catch (PDOException $e) 
        {
            print "Error! " . $e->getMessage() . "<br/>";
            die();
        }
    }

    // Determine whether an Instructor has entered a valid course_id.
    function checkInstructorCourseID($instructorAccount, $courseID)
    {
        try 
        {
            $dbh = connectDB();
            $sqlstmt = "SELECT count(*) FROM
                        (SELECT course_id FROM Course) courses ";

            $statement = $dbh->prepare($sqlstmt.
                                        " where course_id = :courseID");
            $statement->bindParam(":courseID", $courseID);
            $result = $statement->execute();
            $row=$statement->fetch();
            $dbh=null;

            if($row[0] == null)
            {
                print("ERROR: Invalid course ID $courseID<br/>");
                return;
            }
            else
            {
                return $row[0];
            }
        }
        catch (PDOException $e) 
        {
            print "Error! " . $e->getMessage() . "<br/>";
            die();
        }
    }

    // Check the survey status of the Student's registered courses.
    function checkSurveyStatus($studentAccount)
    {
        try 
        {
            $dbh = connectDB();
            $sqlstmt = "SELECT stu_acc as Account, course_id as Course, survey_completion as CompletionStatus from Takes ";

            $statement = $dbh->prepare($sqlstmt.
                                        " where stu_acc = :studentAccount");
            $statement->bindParam(":studentAccount", $studentAccount);
            $result = $statement->execute();
            $courses = $statement->fetchAll();

            ?>
            <table>
            <tr>
            <th>Course      </th>
            <th>Survey Completion Status</th>
            </tr>
            <?php

            foreach($courses as $row) 
            {
                echo "<tr>";
                echo "<td>" . $row[1] . "       </td>";

                if(is_null($row[2]))
                    echo "<td>Incomplete</td>";
                else
                    echo "<td>" . $row[2] . "</td>";
                    
                echo "</tr>";
            }
            echo "<table>";

            $dbh=null;

            return;
        }
        catch (PDOException $e) 
        {
            print "Error! " . $e->getMessage() . "<br/>";
            die();
        }
    }

    // List the class roster for a specific course.
    function listClassRoster($course)
    {
        try 
        {
            $dbh = connectDB();
            $sqlstmt = "SELECT stu_acc as Account, course_id as Course from Takes";

            $statement = $dbh->prepare($sqlstmt.
                                        " where course_id = :course");
            $statement->bindParam(":course", $course);
            $result = $statement->execute();
            $rows=$statement->fetch();
            $dbh=null;

            return $rows;
        }
        catch (PDOException $e) 
        {
            print "Error! " . $e->getMessage() . "<br/>";
            die();
        }
    }

    // Show the course evaluations for a specific course.
    function courseEvaluations($course)
    {
        try 
        {
            $dbh = connectDB();
            $sqlstmt = "SELECT * FROM Course_Question_Responses";

            $statement = $dbh->prepare($sqlstmt.
                                        " where course_id = :course");
            $statement->bindParam(":course", $course);
            $result = $statement->execute();
            $rows=$statement->fetch();
            $dbh=null;

            return $rows;
        }
        catch (PDOException $e) 
        {
            print "Error! " . $e->getMessage() . "<br/>";
            die();
        }
    }

    // Record the date and time at which a Student completed the survey.
    function recordSurveyCompletion($studentAccount, $courseID)
    {
        try 
        {
            $dbh = connectDB();
            $sqlstmt = "UPDATE Takes SET survey_completion = CURRENT_TIMESTAMP()";

            $statement = $dbh->prepare($sqlstmt.
                                        " where stu_acc = :studentAccount AND course_id = :courseID");
            $statement->bindParam(":studentAccount", $studentAccount);
            $statement->bindParam(":courseID", $courseID);
            $result = $statement->execute();
            $rows=$statement->fetch();
            $dbh=null;

            return $rows;
        }
        catch (PDOException $e) 
        {
            print "Error! " . $e->getMessage() . "<br/>";
            die();
        }
    }
    

// Currently working on detecting if password reset should occur
function isFirstLogin() {
    try {
		$acc = $_POST['username'];
		$dbh = connectDB();
		$sql = "SELECT username, pwd_set FROM
        	                (SELECT stu_acc AS username, pwd_set AS pwd_set FROM Student
            	            UNION
                	        SELECT instr_acc AS username, pwd_set AS pwd_set FROM Instructor) combined WHERE username = '$acc'";
		$statement = $dbh->prepare($sql);		
		$result = $statement->execute();
		$row = $statement->fetch(PDO::FETCH_BOTH);
		$result = (int) $row['pwd_set'];
		
		if($result == 0) { 
			header("LOCATION:resetpwd.php");			
			return;
		} else {
			if(isStudent($_POST['username']) == 1) {
				header("LOCATION:student.php");
			} else {
				header("LOCATION:instructor.php");
			}
		}
		
		$dbh = null;

		return;
	} catch (PDOException $e) {
		print "Error! ". $e->getMessage() . "<br/>";
		die();
	}
}


    function resetPwd($user, $pwd, $pwd2){
        try {
			$dbh = connectDB();
        	$isStudent = isStudent($user);
        
			// If it is a student and the passwords match; change pwd
			if($isStudent == 1 && ($pwd == $pwd2)) {
				$sql = "UPDATE Student SET stu_pwd=sha2(:password, 256) WHERE stu_acc = :account";
			} else if ($isStudent != 1 && ($pwd == $pwd2)){
				//If it is an instructor and the passwords match; change pwd
            	$sql = "UPDATE Instructor SET instr_pwd=sha2(:password, 256) WHERE instr_acc = :account";
        	} else {
            	print "<p style='color:red;'>Passwords must match</p>";
        	}
        
        	$statement = $dbh->prepare($sql);
        	$statement->bindParam(":account", $user);
        	$statement->bindParam(":password", $pwd);
			$result = $statement->execute();
            
			//Set pwd_set to 1
			if($isStudent == 1) {
	            $sql = "UPDATE Student SET pwd_set=1 WHERE stu_acc = :account";
            } else {
                $sql = "UPDATE Instructor SET pwd_set=1 WHERE instr_acc = :account";
            }
			$statement = $dbh->prepare($sql);
			$statement->bindParam(":account", $user);
			$result = $statement->execute();
			$dbh = null;

			if(isStudent($user) == 1) {
				header("LOCATION:student.php");
			} else {
				header("LOCATION:instructor.php");
			}
		} catch(PDOException $e) {
			print "Error: ". $e->getMessage() . "<br/>";
			die();
		}
	return;
}

    // Determine whether a Student can take the survey.
    function checkStudentCanTakeSurvey($studentAccount, $courseID)
    {
        try 
        {
            $dbh = connectDB();
            $sqlstmt = "SELECT * FROM Takes ";

            $statement = $dbh->prepare($sqlstmt.
                                        " WHERE stu_acc = :studentAccount AND course_id = :courseID");
            $statement->bindParam(":studentAccount", $studentAccount);
            $statement->bindParam(":courseID", $courseID);
            $result = $statement->execute();
            $row=$statement->fetch();
            $dbh=null;

            if($row == null)
            {
                print("ERROR: Invalid course ID $courseID");
                return FALSE;
            }
            else if($row[2] != null)
            {
                print("ERROR: You have already completed the survey for $courseID at $row[2]");
                return FALSE;
            }

            return TRUE;
        }
        catch (PDOException $e) 
        {
            print "Error! " . $e->getMessage() . "<br/>";
            die();
        }
    }

    // Have the student complete the survey.
    function completeSurvey($studentAccount, $courseID)
    {
        try 
        {
            $dbh = connectDB();
            $sqlstmt = "SELECT * FROM Question ORDER BY question_number";
            $statement = $dbh->prepare($sqlstmt);
            $result = $statement->execute();
            $questions = $statement->fetchAll();
            
			?>
            <form class="survey-form" action="student.php" method="POST">
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

                echo("<input type='submit' value='Submit_Survey' name='submitSurvey'>");
				?>
            </form>
            <?php
            $dbh=null;

        }
        catch(PDOException $e) 
        {
            print "Error! " . $e->getMessage() . "<br/>";
            die();
        }
}    
?>
