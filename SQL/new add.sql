SET SQL_SAFE_UPDATES = 0;
use mydb;

alter table statistics_user add column totalSetBlockCount int default 0;
alter table limited add column guide_end3 int default 0 after guide_end2;
alter table limited add column guide_end4 int default 0 after guide_end3;

