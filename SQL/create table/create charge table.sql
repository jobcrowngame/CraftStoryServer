use mydb;
drop table charge;
CREATE TABLE charge (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    acc VARCHAR(20) NOT NULL,
    productId VARCHAR(20) NOT NULL,
    money INT NOT NULL,
    transactionID VARCHAR(20) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);