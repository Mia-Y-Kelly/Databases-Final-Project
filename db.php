<?php
function connectDB() {

 $config = parse_ini_file("db.ini");
    $dbh = new PDO($config['dsn'], $config['username'], $config['password']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $dbh;
}

//return number of rows matching the given user and passwd.  
function authenticate($user, $passwd) {
    try {
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
	//	print $row[0];
		
        return $row[0];
    }catch (PDOException $e) {
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
			header("LOCATION:instructor.php");
		}
		
		$dbh = null;

		return;
	} catch (PDOException $e) {
		print "Error! ". $e->getMessage() . "<br/>";
		die();
	}
}


function isStudent() {
	try {

		$acc = $_POST['username'];
		$dbh = connectDB();
		$sql = "SELECT stu_acc FROM Student WHERE stu_acc = '$acc'";
		$statement = $dbh->prepare($sql);
		$result = $statement->execute();
		$row = $statement->fetch();
		$dbh = null;
		return $row[0] ?? NULL;
	} catch(PDOException $e) {
            print "Error: ". $e->getMessage() . "<br/>";
            die();
    }

}

function resetPwd($user, $pwd, $pwd2){
        try {
			$dbh = connectDB();
        	echo "1";
        	$isStudent = isStudent();
        	echo "2";
        
			// If it is a student and the passwords match; change pwd
			if($isStudent == 1 && ($pwd == $pwd2)) {
				$sql = "UPDATE Student SET stu_pwd=sha2(:password, 256) WHERE stu_acc = :account";
			} else if ($isStudent != 1 && ($pwd == $pwd2)){
				//If it is an instructor and the passwords match; change pwd
            	$sql = "UPDATE Instructor SET instr_pwd=sha2(:password, 256) WHERE instr_acc = :account";
        	} else {
            	print "<p style='color:red;'>Passwords must match</p>";
        	}
        
			echo "3";
			print $user;
			print $pwd;
        	$statement = $dbh->prepare($sql);
        	$statement->bindParam(":account", $user);
        	$statement->bindParam(":password", $pwd);
        	print "3.5";
			$result = $statement->execute();
        	print "3.75";
			$row = $statement->fetch();
        	echo "4";
        	print_r($row);
			$dbh=null;
			header("LOCATION:main.php");
		} catch(PDOException $e) {
			print "Error: ". $e->getMessage() . "<br/>";
			die();
		}
	return;
}
?>
