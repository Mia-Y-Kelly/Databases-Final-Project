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
	// View class roster
	// View survery results
    require "db.php";
	session_start();
>>>>>>> issue1
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
						var_dump($_POST);
						$class = $_POST['current_class'];
						$dbh = connectDB();
						$statement = $dbh->prepare("SELECT stu_acc, course_id FROM Takes where course_id='$class';");
						$result = $statement->execute();
						$rows = $statement->fetchAll(PDO::FETCH_COLUMN);
						$dbh = null;
						
						
						// Print out all the names in a list
						//echo "<ul>";
						//foreach($rows as $name) {
						//	echo "<li>$name</li>";
						//}
					//	echo "</ul>";
						
	                } catch (PDOException $e) {
                        print "<br/>ERROR: ". $e->getMessage()."<br/>";
                        die();
    	            }
				}
			?>
		</form>
		<form id="survey" name="survey" method="instructor.php" method="post">
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
		</form>
    </body>
</html>
>>>>>>> issue1
