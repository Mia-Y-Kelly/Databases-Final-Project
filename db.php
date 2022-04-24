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
function isFirstLogin($username) {
    try {
		
		$dbh = connectDB();
		$sqlstmt = "select username, pwd_set from
        	                (select stu_acc as username, pwd_set from Student
            	            union
                	        select instr_acc as username, pwd_set from Instructor) combined where username = :username";
		$statement = $dbh->prepare($sqlstmt);		
		$statement->bindParam("username", $username, PDO::PARAM_STR);
		$statement->bindValue(":username","%$username%");
		$executedStmt = $statement.execute();
		$result = $statement->fetch();
		$dbh = null;
		return;
	} catch (PDOException $e) {
		print "Error! ". $e->getMessage() . "<br/>";
		die();
	}
}
?>
