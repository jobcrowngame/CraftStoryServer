use mydb;
DROP PROCEDURE refresh;
DELIMITER | 
CREATE PROCEDURE refresh()
begin

update limited set 
subscription_mail_added01=0, 
subscription_mail_added02=0, 
subscription_mail_added03=0,
loginbonus_added = 0,
logined = 0,
goodNum_daily = 0;

end | 
DELIMITER ;