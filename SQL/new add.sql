SET SQL_SAFE_UPDATES = 0;
use mydb;

alter table limited add column from_good_point int default 0;
alter table limited add column from_gooded_point int default 0;

