<?php

echo "Test<br>";
ini_set('display_errors',1);
error_reporting(E_ALL);

require_once 'ClassLoader.php';
$pdo = MySqlPDB::connectDB();

$data = '{"token":"3csq6hmeeglsgpqge1a16darql","acc":"Z7qV0B8DTA0b","taskId":1}';
$data = json_decode($data);

CMD1051_1060::MainTaskEnd_1059($data);