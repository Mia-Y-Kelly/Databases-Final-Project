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

    function checkFirstLogin($user, $passwd)
    {
        try 
        {
            $dbh = connectDB();
            $sqlstmt = "SELECT passwordSet FROM
                        (SELECT stu_acc AS username, stu_pwd AS password, pwd_set AS passwordSet FROM Student
                        UNION
                        SELECT instr_acc AS username, instr_pwd AS password, pwd_set AS passwordSet FROM Instructor) combined ";

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

    // Currently working on detecting if password reset should occur
    function resetPassword($user, $newPass) 
    {
        $dbh = connectDB();
        $sql = "SELECT ";
    }

    // Determine whether a Student has registered for a course with a valid course_id.
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
                print("Invalid course ID <br/>");
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
                print("Invalid course ID <br/>");
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
            <th>Course</th>
            <th>Survey Completion Status</th>
            </tr>
            <?php

            foreach($courses as $row) 
            {
                echo "<tr>";
                echo "<td>" . $row[1] . "</td>";

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
    function recordSurveyCompletion($studentAccount)
    {
        try 
        {
            $dbh = connectDB();
            $sqlstmt = "UPDATE Takes SET survey_completion = date() ";

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
