<?php

echo "Test local<br>";
ini_set('display_errors',1);
error_reporting(E_ALL);

require_once 'ClassLoader.php';

$dsn = 'mysql:dbname=mydb;host=database-4.cuhxsjr7puaa.ap-northeast-1.rds.amazonaws.com:3306;charset=utf8';
$user = 'admin';
$pw = 'Nozomu_0037';

$pdo = new PDO($dsn, $user, $pw);
$pdo -> query("SET NAMES utf8");
$pdo -> setAttribute(PDO::ATTR_EMULATE_PREPARES, 0);

$file_path = "/var/www/html/Cron/Output/";
$file_name = $file_path.date("YmdHis")."_customer.csv";
$export_csv_title = ["id", "item_guid", "skills", "isDiscard"];
$export_sql = "SELECT * FROM equipment";

// encoding title into SJIS-win
foreach( $export_csv_title as $key => $val ){
    $export_header[] = mb_convert_encoding($val, 'SJIS-win', 'UTF-8');
}

if(touch($file_name)){
     $file = new SplFileObject($file_name, "w");

    // write csv header
    $file->fputcsv($export_header);

    // query database
    $stmt = $pdo->query($export_sql);

    // create csv sentences
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $file->fputcsv($row);
    }

    // close database connection
    $dbh = null;
}
