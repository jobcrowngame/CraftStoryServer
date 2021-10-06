use mydb;
drop table subscription;
CREATE TABLE subscription (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    acc VARCHAR(20) NOT NULL,
    productId VARCHAR(20) NOT NULL,
    money INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
	isDiscard BOOLEAN DEFAULT 0
);