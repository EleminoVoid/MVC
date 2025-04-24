create database user; 

use user;

create table users (
	id varchar(10),
	name varchar(50),
    email varchar(50),
    password varchar(100) not null
);

insert into users (id, name, email, password) values 
(101, 'joseph', 'joseph123@gmail.com', 'wanji123'),
(102, 'alica', 'alics@gmail.com', 'alica123'),
(103, 'kyla', 'kyky@gmail.com', 'kyla123');


drop table users;

select * from users;

drop table users;

describe users;

