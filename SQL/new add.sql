SET SQL_SAFE_UPDATES = 0;
use mydb;


CREATE TABLE equipment (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    item_guid INT NOT NULL,
    skills TEXT,
    isDiscard BOOL DEFAULT 0
);