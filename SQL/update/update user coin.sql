use mydb;
select coin3 from userdata where acc = 'CMLTJWC9F7iZ';
UPDATE userdata 
SET 
    coin3 = coin3 - 5000
WHERE
    acc = 'CMLTJWC9F7iZ';