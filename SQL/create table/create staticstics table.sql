use mydb;
drop table statistics;
CREATE TABLE statistics (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    login INT DEFAULT 0,
    charge INT DEFAULT 0,
    subscription_charge INT default 0
);