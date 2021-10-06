use mydb;
drop table statistics_user;
CREATE TABLE statistics_user (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    acc VARCHAR(20) NOT NULL,
    gacha INT DEFAULT 0
);