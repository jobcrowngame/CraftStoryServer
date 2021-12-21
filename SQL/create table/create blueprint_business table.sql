use test;
drop table blueprint_business;
CREATE TABLE blueprint_business (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    from_user VARCHAR(30) NOT NULL,
    to_user VARCHAR(30) NOT NULL,
    blueprint_name VARCHAR(30) NOT NULL,
    price int  NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);