/* Add users for the testing; the names are the same as the ones we will need in the final project */
-- use mykelly;
use annikapr;

-- Wipe out data in tables.
delete from Teaches limit 100;
delete from Course_Question_Responses limit 100;
delete from Course limit 100;
delete from Student limit 100;
delete from Instructor limit 100;
delete from Choice limit 100;
delete from Question limit 100;

-- Add Students
call createStudent("Bob", "Bob Alpha", "Password1");
call createStudent("Ben", "Ben Bravo", "Password2");
call createStudent("Brook", "Brook Charlie", "Password3");
call createStudent("Brian", "Brian Delta", "Password4");
call createStudent("Bethany", "Bethany Echo", "Password5");
call createStudent("Becky", "Becky Foxtrot", "Password6");
call createStudent("Student1", "Becky Foxtrot", "Password7");

-- Add Instructors;
call createInstructor("Alice", "Alice Golf", "password1");
call createInstructor("Aaron", "Aaron Hotel", "password2");
call createInstructor("Abel", "Abel India", "password3");
call createInstructor("Al", "Al Hotel", "password4");

-- Add Courses.
call createCourse("CS1121", "Introduction to Programming 1", 3);
call createCourse("CS1122", "Introduction to Programming 2", 3);
call createCourse("CS1131", "Accelerated Introduction to Programming", 3);
call createCourse("CS1142", "Programming at the HW/SW Interface", 3);
call createCourse("CS2311", "Discrete Structures", 3);
call createCourse("CS2321", "Data Structures", 3);
call createCourse("CS3425", "Introduction to Database Systems", 3);

-- Add Questions.
call createQuestion('MC', 1, "The pace of this course is");
call createChoice(1, 'A', "Too slow");
call createChoice(1, 'B', "Too fast");
call createChoice(1, 'C', "Just right");

call createQuestion('MC', 2, "I understand the objectives of this course");
call createChoice(2, 'A', "Strongly agree");
call createChoice(2, 'B', "Agree");
call createChoice(2, 'C', "Neutral");
call createChoice(2, 'D', "Disagree");
call createChoice(2, 'E', "Strongly disagree");

call createQuestion('FR', 3, "Anything you like about the teaching of this course?");
call createChoice(3, NULL, "Anything you like about the teaching of this course?");

-- Assign instructors to courses.
call assignInstructor('Alice' , 'CS2311');
call assignInstructor('Aaron' , 'CS1142');
call assignInstructor('Abel' , 'CS3425');
call assignInstructor('Al' , 'CS2321');

-- TBH I'M NOT SURE IF I HAD TO DO THIS STEP
-- grant select, insert, update, delete on Student to 'mykelly'@'%';
-- grant select, insert, update, delete on Student to 'mykelly'@'localhost';
-- grant select, insert, update, delete on Instructor to 'mykelly'@'%';
-- grant select, insert, update, delete on Instructor to 'mykelly'@'localhost';
