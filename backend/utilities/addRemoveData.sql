/* Add users for the testing; the names are the same as the ones we will need in the final project */
-- use mykelly;
use annikapr;

-- Clear Student table
delete from Question limit 100;
delete from Takes limit 100;
delete from Course limit 10;
delete from Student limit 6;
delete from Instructor limit 4;

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
call createCourse("CS1121", "Introduction to Programming 1", 3);
call createCourse("CS1122", "Introduction to Programming 2", 3);
call createCourse("CS1131", "Accelerated Introduction to Programming", 3);
call createCourse("CS1142", "Programming at the HW/SW Interface", 3);

-- Add Questions.
call createQuestion('MC', 1, "The pace of this course is", 'A', "Too slow");
call createQuestion('MC', 1, "The pace of this course is", 'B', "Too fast");
call createQuestion('MC', 1, "The pace of this course is", 'C', "Just right");

call createQuestion('MC', 2, "I understand the objectives of this course", 'A', "Strongly agree");
call createQuestion('MC', 2, "I understand the objectives of this course", 'B', "Agree");
call createQuestion('MC', 2, "I understand the objectives of this course", 'C', "Neutral");
call createQuestion('MC', 2, "I understand the objectives of this course", 'D', "Disagree");
call createQuestion('MC', 2, "I understand the objectives of this course", 'E', "Strongly disagree");

call createQuestion('FR', 3, "What did you enjoy about this course?", "", "");

call createQuestion('FR', 4, "Any suggestions to improve this course?", "", "");

-- TBH I'M NOT SURE IF I HAD TO DO THIS STEP
-- grant select, insert, update, delete on Student to 'mykelly'@'%';
-- grant select, insert, update, delete on Student to 'mykelly'@'localhost';
-- grant select, insert, update, delete on Instructor to 'mykelly'@'%';
-- grant select, insert, update, delete on Instructor to 'mykelly'@'localhost';
