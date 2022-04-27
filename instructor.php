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

						// Retrive choices and frequencies
						$questions = $dbh->prepare("SELECT DISTINCT question_number, choice_string, freq from Course_Question_Responses WHERE course_id='$class' AND essay='N/A';");
						$question_result = $questions->execute();						
						$questions_arr = $questions->fetchAll();
						$counter = 1;
						
						// Retrive essay questions
						$questions = $dbh->prepare("SELECT question_number, essay from Course_Question_Responses WHERE course_id='$class' AND essay!='N/A';");
                        $question_result = $questions->execute();
                        $essay = $questions->fetchAll();
							
						// Print all the question options
						foreach($all_q as $full_q) {
							// Print out the question
							echo "<h3>".$full_q[1]." ".$full_q[2]."</h3>";
							
							// Print out the Multiple Choice Questions
							if($full_q[0] == "MC") {
								echo "<table/><tr><th>Response Option</th><th>Frequency</th><th>Percent</th></tr>";
								foreach($questions_arr as $q) {
									// If the question number of the option is different than the question, it is the wrong question
									if($q[0] != $full_q[1]) {
										continue;
									}
									
									// Insert escape character if needed
									if(is_int(strpos($q[1], "'"))) {
                    					$q[1] = addslashes($q[1]);
									}                    				

									// Calculate thie total frequency for the question
									$sql = "SELECT sum(freq) as total from Course_Question_Responses WHERE question_number='$full_q[1]' AND course_id='$class'";
									$statement = $dbh->prepare($sql);
									$result = $statement->execute();
									$total_res = $statement->fetch(PDO::FETCH_COLUMN);
									
									// Calculate the total frequency for the option
                                    $sql = "SELECT sum(freq) as total_op from Course_Question_Responses WHERE question_number='$full_q[1]' AND course_id='$class' AND choice_string='$q[1]'";
                                    $statement = $dbh->prepare($sql);
                                    $result = $statement->execute();
                                    $total_op = $statement->fetch(PDO::FETCH_COLUMN);
									
									// Calculate the frequency of the option
									$freq = round(($total_op / $total_res * 100), 0);
									echo "<tr><td>".stripslashes($q[1])."</td><td>".$total_op."</td><td>".$freq.".00%<td></tr>";
								}
								echo "</table>";
							} else {
							// Print out the Free Response questions
								echo "<table>";
								foreach($essay as $response) {
									if($response[0] != $full_q[1]) {
										continue;
									}
									echo "<tr><td>".$response[1]."</td></tr>";
								}
								echo "</table>";
							}
						}
						$dbh = NULL;
                    } catch (PDOException $e) {
                        print "<br/>ERROR: ". $e->getMessage()."<br/>";
                        die();
                    }
                }
            ?>
		</form>
    </body>
</html>
