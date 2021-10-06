use mydb;
select acc from charge 
where created_at >= '2021-07-04' && created_at < '2021-07-05';