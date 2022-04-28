/* Add users for the testing; the names are the same as the ones we will need in the final project */
-- use mykelly;
use annikapr;

-- Clear Student table
delete from Choice limit 100;
delete from Question limit 100;
delete from Takes limit 100;
delete from Teaches limit 100;
delete from Course_Question_Responses limit 100;
delete from Course limit 100;
delete from Student limit 100;
delete from Instructor limit 100;


-- Add Students
call createStudent("Bob", "Bob Alpha", "Password1");
call createStudent("Ben", "Ben Bravo", "Password2");
call createStudent("Brook", "Brook Charlie", "Password3");
call createStudent("Brian", "Brian Delta", "Password4");
call createStudent("Bethany", "Bethany Echo", "Password5");
call createStudent("Becky", "Becky Foxtrot", "Password6");

-- Add Instructors;
call createInstructor("Alice", "Alice Golf", "password1");
call createInstructor("Aaron", "Aaron Hotel", "password2");
call createInstructor("Abel", "Abel India", "password3");
call createInstructor("Al", "Al Hotel", "password4");

-- Add Courses.
call createCourse("CS2311", "Discrete Structures", 3);
call createCourse("CS2321", "Data Structures", 3);
call createCourse("CS3425", "Computer Organization", 3);
call createCourse("CS1142", "Programming at the HW/SW Interface", 3);

-- Add Questions.
call createQuestion('MC', 1, "The pace of this course");
call createChoice(1, 'A', "Too slow");
call createChoice(1, 'B', "Too fast");
call createChoice(1, 'C', "Just right");
call createChoice(1, 'D', "I don't know");

call createQuestion('MC', 2, "The feedback from homework assignment grading");
call createChoice(2, 'A', "Too few");
call createChoice(2, 'B', "Sufficient");
call createChoice(2, 'C', "I don't know");

call createQuestion('FR', 3, "Anything you like about the teaching of the course?");
call createChoice(3, NULL, "Anything you like about the teaching of the course?" );
-- call createQuestion('FR', 4, "Any suggestions to improve this course?");
-- call createChoice(4, NULL, "Any suggestions to improve this course?");

-- Assign instructors
call assignInstructor("Alice", "CS2311");
call assignInstructor("Aaron", "CS1142");
call assignInstructor("Al", "CS2321");

-- TBH I'M NOT SURE IF I HAD TO DO THIS STEP
-- grant select, insert, update, delete on Student to 'mykelly'@'%';
-- grant select, insert, update, delete on Student to 'mykelly'@'localhost';
-- grant select, insert, update, delete on Instructor to 'mykelly'@'%';
-- grant select, insert, update, delete on Instructor to 'mykelly'@'localhost';
