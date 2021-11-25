SET SQL_SAFE_UPDATES = 0;
use mydb;

alter table notice add column priority int after activedate;
alter table notice add column pickup int after priority;

