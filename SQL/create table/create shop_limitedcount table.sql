use mydb;
drop table shop_limitedcount;
CREATE TABLE shop_limitedcount (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    acc VARCHAR(20) UNIQUE,
    shopId INT,
    limitedCount INT
);