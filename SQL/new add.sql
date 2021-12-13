SET SQL_SAFE_UPDATES = 0;
use mydb;

alter table statistics_user add column totalUploadBlueprintCount int default 0;
