SET SQL_SAFE_UPDATES = 0;
USE mydb;

drop event test;

DELIMITER |
CREATE EVENT test
ON SCHEDULE EVERY 1 minute
DO
BEGIN

CALL statistics;
call clear_myshop;

END | 
DELIMITER ;