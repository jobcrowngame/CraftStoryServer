use mydb;
drop table notice;
CREATE TABLE notice (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    category INT,
    newflag INT,
    activedate DATETIME,
    title VARCHAR(50),
    text TEXT
);