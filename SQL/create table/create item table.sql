use mydb;
drop table items;
CREATE TABLE items (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    acc VARCHAR(20) NOT NULL,
    itemId INT NOT NULL,
    newName VARCHAR(20),
    count INT DEFAULT 0,
    equipSite INT DEFAULT 0,
    relationData LONGTEXT,
    islocked BOOLEAN DEFAULT 0,
    isDiscard BOOLEAN DEFAULT 0
);
