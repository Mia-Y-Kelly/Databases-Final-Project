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
?> 
	        <div class="createPwd">
                <div class="innerPwd">
                    <label for="old_pwd" class="label"><b>Old Password</b></label><br>
                    <input type="text" id=old_pwd name="old_pwd" class="text" value="" require>
                    <br/>
                    <label for="new_pwd" class="label"><b>New Password</b></label><br>
                    <input type="text" id=new_pwd name="new_pwd" class="text" value="" require>
                    <br><br>
                    <input type="submit" id="submit" name="submit" value="submit">
                </div>
    	      </div>	
<?php	
		return;
		} else {
			header("LOCATION:main.php");
		}
		
		$dbh = null;
		return;
	} catch (PDOException $e) {
		print "Error! ". $e->getMessage() . "<br/>";
		die();
	}
}
?>
