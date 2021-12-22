SET SQL_SAFE_UPDATES = 0;
use mydb;

alter table notice add column active bool default 1 after id;

CREATE TABLE blueprint_business (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    from_user VARCHAR(30) NOT NULL,
    to_user VARCHAR(30) NOT NULL,
    blueprint_name VARCHAR(30) NOT NULL,
    price int  NOT NULL
);
