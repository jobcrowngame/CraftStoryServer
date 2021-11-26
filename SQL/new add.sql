SET SQL_SAFE_UPDATES = 0;
use mydb;

alter table notice add column priority int after activedate;
alter table notice add column pickup int after priority;

alter table loginbonus add column type int after active;
alter table loginbonus add column items Text after type;
alter table loginbonus add column itemCounts Text after items;
alter table loginbonus drop column loginbonusid;

alter table limited drop column loginbonus_added;
alter table limited add column loginBonus Text after logined;
alter table limited add column loginBonusStep Text after loginBonus;