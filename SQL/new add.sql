SET SQL_SAFE_UPDATES = 0;
use mydb;


ALTER TABLE  items ADD textureName varchar(40) AFTER relationData;
ALTER TABLE  myshop ADD sellNum INT DEFAULT 0 AFTER price;
ALTER TABLE  myshop ADD icon varchar(50) AFTER newname;
ALTER TABLE  limited ADD goodNum_total INT DEFAULT 0;
ALTER TABLE  limited ADD goodNum_daily INT DEFAULT 0;
ALTER TABLE  limited ADD guide_end2 INT DEFAULT 0 AFTER guide_end;

ALTER TABLE  notice ADD titleIcon varchar(40) AFTER title;
ALTER TABLE  notice ADD detailIcon varchar(40) AFTER titleIcon;
ALTER TABLE  notice ADD url varchar(40) AFTER detailIcon;