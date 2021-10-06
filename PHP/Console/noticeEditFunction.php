
<?php

require_once 'autoload.php';

// DB接続
$pdo = new MySqlPDB();
$pdo->connectDB();

NoticeClass::Edit($_POST);

include 'noticeList.php';

    