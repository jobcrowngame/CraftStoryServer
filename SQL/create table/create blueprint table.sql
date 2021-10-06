use mydb;
drop table blueprint;
CREATE TABLE blueprint (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    data VARCHAR(20) NOT NULL,
    isDiscard BOOL DEFAULT FALSE
);