use mydb;
DROP PROCEDURE clear;
DELIMITER | 
CREATE PROCEDURE clear()
begin

update myshop set isDiscard=1 where created_at < NOW() - interval 7 day;


#サブスクリプションの更新
SET @clearDay = NOW() - interval 29 day;
UPDATE subscription INNER JOIN userdata ON subscription.acc = userdata.acc
SET subscription.isDiscard=1,userdata.subscriptionLv01=0
WHERE subscription.created_at < @clearDay and subscription.type=1 and isDiscard=0;

UPDATE subscription INNER JOIN userdata ON subscription.acc = userdata.acc
SET subscription.isDiscard=1,userdata.subscriptionLv02=0
WHERE subscription.created_at < @clearDay and subscription.type=2 and isDiscard=0;

UPDATE subscription INNER JOIN userdata ON subscription.acc = userdata.acc
SET subscription.isDiscard=1,userdata.subscriptionLv03=0
WHERE subscription.created_at < @clearDay and subscription.type=3 and isDiscard=0;

end | 
DELIMITER ;