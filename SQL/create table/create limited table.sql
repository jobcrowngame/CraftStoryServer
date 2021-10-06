use mydb;
drop table limited;
CREATE TABLE limited (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    acc VARCHAR(20) UNIQUE,
    firstUseMyShop BOOLEAN default 1,
    guide_end BOOLEAN DEFAULT 0,
    subscription_mail_added01 BOOLEAN DEFAULT 0,
    subscription_mail_added02 BOOLEAN DEFAULT 0,
    subscription_mail_added03 BOOLEAN DEFAULT 0
);
