SET SQL_SAFE_UPDATES = 0;
use mydb;

alter table userdata add column totalPoint int default 0 after coin3;

