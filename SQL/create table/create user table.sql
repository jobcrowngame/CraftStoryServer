use test;
drop table userdata;
CREATE TABLE userdata (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    acc VARCHAR(20) NOT NULL,
    pw VARCHAR(20) NOT NULL,
	token VARCHAR(30),
    state INT DEFAULT 0,
    nickname VARCHAR(20),
    coin1 INT DEFAULT 0,
    coin2 INT DEFAULT 0,
    coin3 INT DEFAULT 0,
    myShopLv INT DEFAULT 0,
    subscriptionLv01 BOOL DEFAULT 0,
    subscriptionLv02 BOOL DEFAULT 0,
    subscriptionLv03 BOOL DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
