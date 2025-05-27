create database UDB; 

use UDB;

create table users (
	id varchar(10),
	name varchar(50),
    email varchar(50),
    password varchar(100) not null
);

-- insert into users (id, name, email, password) values 
-- (101, 'joseph', 'joseph123@gmail.com', 'wanji123'),
-- (102, 'alica', 'alics@gmail.com', 'alica123'),
-- (103, 'kyla', 'kyky@gmail.com', 'kyla123');


-- Create the students table
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    user_id VARCHAR(10), -- or INT if you want to link to users
    -- add more fields as needed
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Example data
INSERT INTO students (name, email, user_id) VALUES
('Student One', 'student1@example.com', '101'),
('Student Two', 'student2@example.com', '102');


drop table users;

select * from users;

drop table users;

describe users;

