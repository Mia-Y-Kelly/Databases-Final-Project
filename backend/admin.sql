-- use mykelly;
use annikapr;

drop table if exists Teaches;
drop table if exists Takes;
drop table if exists Course_Question_Responses;
drop table if exists Course;
drop table if exists Student;
drop table if exists Instructor;
drop table if exists Choice;
drop table if exists Question;

drop procedure if exists createInstructor;
drop procedure if exists createStudent;
drop procedure if exists updateInstructorPassword;
drop procedure if exists updateStudentPassword;
drop procedure if exists createCourse;
drop procedure if exists createChoice;
drop procedure if exists createQuestion;
drop procedure if exists assignInstructor;

/* Create Table SQL Statements */
create table Student (
	stu_acc varchar(20) primary key,
    stu_name varchar(30) not null,
    stu_pwd varchar(256) default null,
    pwd_set int default 0
);

/* Store all the instructors in this table */
create table Instructor(
	instr_acc varchar(20) primary key,
    instr_name varchar(30) not null,
    instr_pwd varchar(256) default null,
    pwd_set int default 0
);

/* Store all courses in this table*/
create table Course(
	course_id varchar(10) primary key,
    title varchar(50) not null,
    credits int
);

/* Track which instructor teach which courses in this table */
create table Teaches(
	instr_acc varchar(20),
    course_id varchar(10),
    primary key(instr_acc, course_id)
);

/* Store who takes which course in this table */
create table Takes (
	stu_acc varchar(20),
    course_id varchar(10),
	survey_completion timestamp,
    
    primary key(stu_acc, course_id),
    foreign key (stu_acc) references Student(stu_acc),
    foreign key (course_id) references Course(course_id)
);

/* Store all questions and options in this table */
create table Question(
	question_type char(2),
	question_number int,
	question varchar(200),
    
    primary key(question_number)
);

/*Store all options in this table*/
create table Choice
(
	question_number int,
	choice_char char(1),
    choice_string varchar(200),
    
    primary key(question_number, choice_string),
    foreign key(question_number) references Question(question_number)
);

/* Store all responses to the questions in this table */
create table Course_Question_Responses (
	id int AUTO_INCREMENT primary key,
	course_id varchar(10),
    question_number int,
    choice_string char(200),
    freq int default 0,
    essay varchar(255) default "", 
    
    foreign key (course_id) references Course(course_id),
    foreign key (question_number, choice_string) references Choice(question_number, choice_string)
);


/* create procedure sql statements */
-- createInstructor procedure
delimiter //
create procedure createInstructor(instr_acc varchar(20), instr_name varchar(30), instr_pwd varchar(256))
    begin
        insert into Instructor values(instr_acc, instr_name, sha2(instr_pwd, 256), 0);
    end//
delimiter ;

-- createStudent procedure
delimiter //
create procedure createStudent(stu_acc varchar(20), stu_name varchar(30), stu_pwd varchar(256))
    begin
        insert into Student values(stu_acc, stu_name, sha2(stu_pwd, 256), 0);
    end//
delimiter ;

-- assignInstructor procedure
delimiter //
create procedure assignInstructor(instr_acc varchar(20), course_id varchar(10))
    begin
        insert into Teaches values(instr_acc, course_id);
    end//
delimiter ;

-- Create procedure updateStudentPassword.
delimiter //
create procedure updateStudentPassword(update_stu_acc varchar(30), new_password varchar(30))
    begin
		declare isSet int default null;
        
		select pwd_set into isSet from Student where update_stu_acc = stu_acc;
        
		if isSet = 0 then
			update Student set stu_pwd = sha2(new_password, 256) where stu_acc = update_stu_acc;
            update Student set pwd_set = 1 where stu_acc = update_stu_acc;
		end if;     
    end //
delimiter ;

-- Create procedure updateInstructorPassword.
delimiter //
create procedure updateInstructorPassword(update_intr_acc varchar(30), new_password varchar(30))
    begin
		if pwd_set = 0 then
			update Instructor set instr_pwd = sha2(new_password, 256) where instr_acc = update_instr_acc;
            update Instructor set pwd_set = 1 where stu_acc = update_instr_acc;
		end if;	        
    end//
delimiter ;

-- createCourse procedure
delimiter //
create procedure createCourse(course_id varchar(10), title varchar(50), credits integer)
    begin
        insert into Course values(course_id, title, credits);
    end//
delimiter ;

-- createQuestion procedure
delimiter //
create procedure createQuestion(question_type char(2), question_number int, question varchar(200))
	begin
		insert into Question values(question_type, question_number, question);
	end//
delimiter ;

-- createChoice procedure
delimiter //
create procedure createChoice(question_number int, choice_char char(1), choice varchar(200))
	begin
		insert into Choice values(question_number, choice_char, choice);
	end//
