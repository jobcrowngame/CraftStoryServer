use mydb;
drop table homedata;
CREATE TABLE homedata (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    acc VARCHAR(20) NOT NULL,
    homedata LONGTEXT
);