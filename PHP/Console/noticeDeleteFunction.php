
<?php

require_once 'autoload.php';

// DB接続
$pdo = new MySqlPDB();
$pdo->connectDB();

NoticeClass::Delete($_POST);

include 'noticeList.php';

    