<?php
<<<<<<< HEAD
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
=======
    require "db.php";
	session_start();
>>>>>>> 8a5698244366148239faef734d4487495c956f17
?>

<html>
    <body>
<<<<<<< HEAD
        <form class="form" action="instructor.php" method="POST">
            <div class="list-students-form">
            <label for="courseID" class="label"><b>Enter a Valid Course ID</b></label><br>
                <input type="text" id="courseID" name="courseID" class="text" value="" require>
                <br><br>

                <input type="submit" id="listStudents" name="listStudents" value="List Enrolled Students">
            </div>
        </form>

        <form class="form" action="instructor.php" method="POST">
            <div class="evaluation-result-form">
            <label for="courseID" class="label"><b>Enter a Valid Course ID</b></label><br>
                <input type="text" id="courseID" name="courseID" class="text" value="" require>
                <br><br>
                
                <input type="submit" id="evaluationResult" name="evaluationResult" value="See Course Evaluation Result">
            </div>
        </form>
    </body>
</html>
=======
        <form action="login.php" method="post">
            <?php
                if (!isset($_SESSION['username'])) {
            ?>
                <input type="submit" class="submit" value='login' name="login">
            <?php
                } else {
                    echo "Welcome ". $_SESSION['username'];
            ?>
                    <input type="submit" class="submit" value='logout' name="logout">
            <?php
                }
            ?>
		</form>

		<form id="roster" name="roster" method="post" action="instructor.php">  
			<p>View Class Roster</p>
			<select Emp Name='current_class'>  
            <option value="">--- Select Class ---</option>  
			<?php
				try {
						$dbh = connectDB();
						$account = $_SESSION['username'];
						$sql = "SELECT course_id FROM Teaches WHERE instr_acc='$account'"; 
						$statement = $dbh->prepare($sql);
						$result = $statement->execute();
						$row = $statement->fetchAll(PDO::FETCH_COLUMN);
        				$dbh = null;
						
						// Iterating through the product array
        				foreach($row as $item){
            				echo "<option value='$item'>$item</option>";
        				}
				} catch (PDOException $e) {
						print "<br/>ERROR: ". $e->getMessage()."<br/>";
						die();
				}
			?>
				<input type="submit" id="show_roster" name="show_roster" value="Select Class" />
        	<?php 
				if(isset($_POST['show_roster'])){
					try {
						$class = $_POST['current_class'];
						$dbh = connectDB();
						$statement = $dbh->prepare("SELECT stu_acc, course_id FROM Takes where course_id='$class';");
						$result = $statement->execute();
						$rows = $statement->fetchAll(PDO::FETCH_COLUMN);
						$dbh = null;
						
						
						// Print out all the names in a list
						echo "<ul>";
						foreach($rows as $name) {
							echo "<li>$name</li>";
						}
						echo "</ul>";
	                } catch (PDOException $e) {
                        print "<br/>ERROR: ". $e->getMessage()."<br/>";
                        die();
    	            }
				}
			?>
		</form>
		
		<!--Show survey results -->
		<form id="survey" name="survey" method="post" method="instructor.php">
            <p>View Courses Results</p>
            <select Emp Name='current_survey'>
            <option value="">--- Select Class ---</option>
            <?php
                try {
                        $dbh = connectDB();
                        $account = $_SESSION['username'];
                        $sql = "SELECT course_id FROM Teaches WHERE instr_acc='$account'";
                        $statement = $dbh->prepare($sql);
                        $result = $statement->execute();
                        $row = $statement->fetchAll(PDO::FETCH_COLUMN);
                        $dbh = null;
                       	var_dump($row); 
						// Iterating through the product array
                        foreach($row as $item){
                            echo "<option value='$item'>$item</option>";
                        }
                } catch (PDOException $e) {
                        print "<br/>ERROR: ". $e->getMessage()."<br/>";
                        die();
                }
            ?>
                <input type="submit" id="show_survey_results" name="show_survey_results" value="Show Results" />
			<?php
                if(isset($_POST['show_survey_results'])){
                    try {
						$class = $_POST['current_survey'];
						$dbh = connectDB();
						
						// Retrieve questions
						$questions = $dbh->prepare("SELECT * FROM Question;");
						$question_result = $questions->execute();
						$all_q = $questions->fetchAll();
						// var_dump($all_q);

						// Retrive choices and frequencies
						$questions = $dbh->prepare("SELECT question_number, choice_string, freq from Course_Question_Responses WHERE course_id='$class' AND essay='';");
						$question_result = $questions->execute();						
						$questions_arr = $questions->fetchAll();
						$question_total_responses[] = 1;
						$counter = 1;
						$total =0;
						
						// Calculate total frequency per question
						for($i = 0; $i < count($questions_arr); $i++) {
							// Add to total frequency
							$q = $questions_arr[$i];
							if($q[0] == $counter) {
								$total = $total + $q[2];
							} else {
								// Push total
								array_push($question_total_responses, $total);
								
								// Start summing new frequency
								$total = 0;
								$total = $total + $q[2];
								$counter++;
							}
								
						}
						
						// Push last element in the array
						array_push($question_total_responses, $total);	
						array_shift($question_total_responses); 	// This was set to 1 to create array but is not needed anymore
						

						// Retrive essay questions
						$questions = $dbh->prepare("SELECT question_number, essay from Course_Question_Responses WHERE course_id='$class' AND essay!='';");
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
									$freq = round((($q[2] / $question_total_responses[$q[0] - 1]) * 100), 0);
									echo "<tr><td>".$q[1]."</td><td>".$q[2]."</td><td>".$freq.".00%<td></tr>";
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
						/*$counter = 0;
						foreach($questions_arr as $q) {
							print "<br/>";
                        	var_dump($questions_arr[$counter]);
							$counter++;
						}*/
						
                    } catch (PDOException $e) {
                        print "<br/>ERROR: ". $e->getMessage()."<br/>";
                        die();
                    }
                }
            ?>
		</form>
    </body>
</html>

>>>>>>> 8a5698244366148239faef734d4487495c956f17
