SET SQL_SAFE_UPDATES = 0;
USE mydb;

delimiter |
CREATE EVENT clearMyShopItems
ON SCHEDULE EVERY 1 DAY
STARTS '2021-07-14 00:00:00'
DO
BEGIN
UPDATE myshop 
	SET 
		isDiscard = 1
	WHERE
		created_at < NOW() - INTERVAL 7 DAY;
END
| delimiter ;