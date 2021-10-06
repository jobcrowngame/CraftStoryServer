use mydb;
drop table myshop;
CREATE TABLE myshop (
    myshopid INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    acc VARCHAR(20),
    nickname VARCHAR(20),
    itemid INT,
    newname VARCHAR(20),
    data LONGTEXT,
    site INT,
    price INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    isDiscard BOOLEAN DEFAULT 0
);