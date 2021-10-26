SET SQL_SAFE_UPDATES = 0;
use mydb;


CREATE TABLE equipment (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    item_guid INT NOT NULL,
    skills TEXT,
    isDiscard BOOL DEFAULT 0
);

ALTER TABLE  userdata ADD lv INT DEFAULT 1 AFTER email;
ALTER TABLE  userdata ADD exp INT DEFAULT 0 AFTER Lv;