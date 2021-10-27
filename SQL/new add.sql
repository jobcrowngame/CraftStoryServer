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


create table statistics_user_gacha (
  id int auto_increment primary key,
  acc varchar(20) not null,
  gacha int not null,
  gachaGroup int not null
);

truncate table statistics_user_gacha;
alter table statistics_user drop column gacha;
alter table statistics_user add column maxArrivedFloor int default 0;
alter table statistics_user add column lastFloorCount int default 0;

