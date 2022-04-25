<?php
	// View class roster
	// View survery results
    require "db.php";
	session_start();
?>

<html>
    <body>
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
		<form id="survey-container" name="survey-container" action="post" method="/instructor.php">
            <p>View Courses Results</p>
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
                <input type="submit" id="show_survey_results" name="show_survey_results" value="Show Results" />
            <?php
                if(isset($_POST['show_survey_results'])){
                    try {
                        $class = $_POST['current_class'];
                        $dbh = connectDB();
                        //$questions = $dbh->prepare("SELECT * FROM Course_Has_Responses where course_id='$class';");
                        //$question_result = $questions->execute();
                        //$question_rows = $questions->fetchAll(PDO::FETCH_COLUMN);
                        $dbh = null;


                        // Print out all the names in a list
                        //echo "<ul>";
                        //foreach($rows as $name) {
                        //    echo "<li>$name</li>";
                       // }
                       // echo "</ul>";

                    } catch (PDOException $e) {
                        print "<br/>ERROR: ". $e->getMessage()."<br/>";
                        die();
                    }
                }
            ?>
		</form>
    </body>
</html>
