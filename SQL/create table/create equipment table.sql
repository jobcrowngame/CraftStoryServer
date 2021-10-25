use mydb;
drop table equipment;

CREATE TABLE equipment (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    item_guid INT NOT NULL,
    skills TEXT,
    isDiscard BOOL DEFAULT 0
);