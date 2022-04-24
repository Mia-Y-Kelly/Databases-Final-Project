/* Add users for the testing; the names are the same as the ones we will need in the final project */
use mykelly;
-- use annikapr;

-- Clear Student table
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

-- TBH I'M NOT SURE IF I HAD TO DO THIS STEP
-- grant select, insert, update, delete on Student to 'mykelly'@'%';
-- grant select, insert, update, delete on Student to 'mykelly'@'localhost';
-- grant select, insert, update, delete on Instructor to 'mykelly'@'%';
-- grant select, insert, update, delete on Instructor to 'mykelly'@'localhost';
