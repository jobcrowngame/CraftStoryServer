use mydb;
drop table friend;
CREATE TABLE friend (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    acc VARCHAR(20) NOT NULL,
    follow TEXT,
    follower TEXT
);

