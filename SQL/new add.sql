SET SQL_SAFE_UPDATES = 0;
use mydb;

alter table limited add column guide_end5 int default 0 after guide_end4;

alter table limited add column main_task int default 1;
alter table limited add column main_task_count int default 0;
