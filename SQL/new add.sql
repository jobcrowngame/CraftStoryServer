SET SQL_SAFE_UPDATES = 0;
use mydb;

alter table exchange_points add column guid varchar(8) after id;
