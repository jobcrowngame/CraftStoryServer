use mydb;
select sum(money) from charge 
where created_at >= '2021-06-28' && created_at < '2021-07-05';