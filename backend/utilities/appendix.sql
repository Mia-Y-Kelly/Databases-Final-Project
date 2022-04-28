delete from Course_Question_Responses limit 100;
delete from Teaches limit 100;

-- Assign instructors
call assignInstructor("Alice", "CS2311");
call assignInstructor("Aaron", "CS1142");
call assignInstructor("Al", "CS2321");

-- Bob
INSERT INTO Course_Question_Responses(course_id,question_number, choice_string, freq, essay) VALUES('CS2311', 1, 'Too slow', 1, 'N/A');
INSERT INTO Course_Question_Responses(course_id,question_number, choice_string, freq, essay) VALUES('CS2311', 2, 'Too few', 1, 'N/A');

INSERT INTO Course_Question_Responses(course_id,question_number, choice_string, freq, essay) VALUES('CS1142', 1, 'Too slow', 1, 'N/A');
INSERT INTO Course_Question_Responses(course_id,question_number, choice_string, freq, essay) VALUES('CS1142', 2, 'Too few', 1, 'N/A');

INSERT INTO Course_Question_Responses(course_id,question_number, choice_string, freq, essay) VALUES('CS2321', 1, 'Too slow', 1, 'N/A');
INSERT INTO Course_Question_Responses(course_id,question_number, choice_string, freq, essay) VALUES('CS2321', 2, 'Too few', 1, 'N/A');

-- Ben
INSERT INTO Course_Question_Responses(course_id,question_number, choice_string, freq, essay) VALUES('CS2311', 1, 'Too slow', 1, 'N/A');
INSERT INTO Course_Question_Responses(course_id,question_number, choice_string, freq, essay) VALUES('CS2311', 2, 'Sufficient', 1, 'N/A');
INSERT INTO Course_Question_Responses(course_id, question_number, choice_string, freq, essay) VALUES('CS2311', 3, 'Anything you like about the teaching of the course?', NULL, 'everything');

INSERT INTO Course_Question_Responses(course_id,question_number, choice_string, freq, essay) VALUES('CS1142', 1, 'Too slow', 1, 'N/A');
INSERT INTO Course_Question_Responses(course_id,question_number, choice_string, freq, essay) VALUES('CS1142', 2, 'Sufficient', 1, 'N/A');
INSERT INTO Course_Question_Responses(course_id, question_number, choice_string, freq, essay) VALUES('CS1142', 3, 'Anything you like about the teaching of the course?', NULL, 'everything');

INSERT INTO Course_Question_Responses(course_id,question_number, choice_string, freq, essay) VALUES('CS2321', 1, 'Too slow', 1, 'N/A');
INSERT INTO Course_Question_Responses(course_id,question_number, choice_string, freq, essay) VALUES('CS2321', 2, 'Sufficient', 1, 'N/A');
INSERT INTO Course_Question_Responses(course_id, question_number, choice_string, freq, essay) VALUES('CS2321', 3, 'Anything you like about the teaching of the course?', NULL, 'everything');

-- Brook
INSERT INTO Course_Question_Responses(course_id,question_number, choice_string, freq, essay) VALUES('CS2321', 1, 'Too slow', 1, 'N/A');
INSERT INTO Course_Question_Responses(course_id,question_number, choice_string, freq, essay) VALUES('CS2321', 2, 'Sufficient', 1, 'N/A');
INSERT INTO Course_Question_Responses(course_id, question_number, choice_string, freq, essay) VALUES('CS2321', 3, 'Anything you like about the teaching of the course?', NULL, 'No exam is the "best" part');

-- Brian
INSERT INTO Course_Question_Responses(course_id,question_number, choice_string, freq, essay) VALUES('CS1142', 1, 'Too slow', 1, 'N/A');
INSERT INTO Course_Question_Responses(course_id,question_number, choice_string, freq, essay) VALUES('CS1142', 2, 'Sufficient', 1, 'N/A');
INSERT INTO Course_Question_Responses(course_id, question_number, choice_string, freq, essay) VALUES('CS1142', 3, 'Anything you like about the teaching of the course?', NULL, 'No exam is the "best" part');

INSERT INTO Course_Question_Responses(course_id,question_number, choice_string, freq, essay) VALUES('CS2321', 1, 'Too slow', 1, 'N/A');
-- This has to be inserted by someone in the frontend submitting a survey
-- INSERT INTO Course_Question_Responses(course_id,question_number, choice_string, freq, essay) VALUES('CS2321', 2, 'I don\'t know', 1, 'N/A');

-- Bethany
INSERT INTO Course_Question_Responses(course_id,question_number, choice_string, freq, essay) VALUES('CS2321', 1, 'Too slow', 1, 'N/A');
-- This has to be inserted by someone in the frontend submitting a survey
-- INSERT INTO Course_Question_Responses(course_id,question_number, choice_string, freq, essay) VALUES('CS2321', 2, 'I don\'t know', 1, 'N/A');
INSERT INTO Course_Question_Responses(course_id, question_number, choice_string, freq, essay) VALUES('CS2321', 3, 'Anything you like about the teaching of the course?', NULL, 'SAM sessions');
