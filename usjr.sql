create database usjr; 

use usjr;

create table colleges (
	collegeID int primary key not null,
	collegefullname varchar(255),
    collegeshortname varchar(255) unique
);

create table courses (
	courseID int primary key not null,
	coursefullname varchar(255),
    courseshortname varchar(255) unique,
    collegeID int,
    foreign key (collegeID) references colleges (collegeID)
);

create table students (
	studID int primary key,
    studlname varchar(255),
    studmname varchar(255),
    studfname varchar(255),
	year int,
    courseshortname varchar(255),
    collegeshortname varchar(255),
    foreign key (courseshortname) references courses (courseshortname),
	foreign key (collegeshortname) references colleges (collegeshortname)
);


INSERT INTO colleges (collegeID, collegefullname, collegeshortname) 
VALUES (1, 'School of Computer Studies', 'SCS');

INSERT INTO courses (courseID, coursefullname, courseshortname, collegeID) 
VALUES (1, 'Bachelor of Science in Information Technology', 'BSIT', 1);


INSERT INTO students (studID, studlname, studmname, studfname, year, courseshortname, collegeshortname) VALUES
(101, 'burgos', 'bolander', 'joseph', '3', 'BSIT', 'SCS'),
(102, 'tabada', 'martinez', 'alica', '4', 'BSIT', 'SCS');


select * from students;
select * from colleges;
select * from courses;

