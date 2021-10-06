SET SQL_SAFE_UPDATES = 0;
use mydb;


ALTER TABLE  items ADD textureName varchar(40) AFTER relationData;
ALTER TABLE  myshop ADD sellNum INT DEFAULT 0 AFTER price;
ALTER TABLE  myshop ADD icon varchar(50) AFTER newname;
ALTER TABLE  limited ADD goodNum_total INT DEFAULT 0;
ALTER TABLE  limited ADD goodNum_daily INT DEFAULT 0;
ALTER TABLE  limited ADD guide_end2 INT DEFAULT 0 AFTER guide_end;


