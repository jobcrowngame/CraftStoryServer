use mydb;
drop table loginbonus;
CREATE TABLE loginbonus (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    loginbonusid  INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    active BOOL DEFAULT 1,
    start_at DATETIME NOT NULL,
	end_at DATETIME NOT NULL
);