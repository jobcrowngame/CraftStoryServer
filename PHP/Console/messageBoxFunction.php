
<?php

require_once 'autoload.php';

// DB接続
$pdo = new MySqlPDB();
$pdo->connectDB();

MessageBoxClass::Send($_POST);

include 'index.html';