use mydb;
DROP PROCEDURE statistics;
DELIMITER | 
CREATE PROCEDURE statistics()
begin

select count(*) from userdata where updated_at >= NOW() - interval 1 day into @loginCount;
select SUM(money) from charge where created_at >= NOW() - interval 1 day into @money;
select SUM(money) from subscription where created_at >= NOW() - interval 1 day into @money2;

INSERT INTO statistics (login, charge, subscription_charge) VALUES (@loginCount,@money,@money2);

end | 
DELIMITER ;
