SET SQL_SAFE_UPDATES = 0;
use mydb;

alter table notice add column active bool default 1 after id;
