<?php
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

    // Currently working on detecting if password reset should occur
    function resetPassword($user, $newPass) 
    {
        $dbh = connectDB();
        $sql = "SELECT ";
    }

    // Determine whether a Student has registered for a course with a valid course_id.
    function checkCourseID($studentAccount, $courseID)
    {
        try 
        {
            $dbh = connectDB();
            $sqlstmt = "SELECT count(*) FROM
                        (SELECT course_id FROM Course)";

            $statement = $dbh->prepare($sqlstmt.
                                        " where course_id = :courseID");
            $statement->bindParam(":courseID", $courseID);
            $result = $statement->execute();
            $row=$statement->fetch();
            $dbh=null;

            if($row[0] == null)
            {
                print("Invalid course ID <br/>");
                return;
            }
            else
            {
                $dbh = connectDB();
                $statement = $dbh->prepare("INSERT INTO TAKES VALUES(:studentAccount, :courseID, null)");
                $statement->bindParam(":studentAccount", $studentAccount);
                $statement->bindParam(":courseID", $courseID);
                $result = $statement->execute();
                $row=$statement->fetch();
                $dbh=null;
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
            $sqlstmt = "SELECT stu_acc as Account, course_id as Course, survey_completion as CompletionStatus from Takes";

            $statement = $dbh->prepare($sqlstmt.
                                        " where stu_acc = :studentAccount");
            $statement->bindParam(":studentAccount", $studentAccount);
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
?>