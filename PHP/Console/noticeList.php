<!DOCTYPE html>
<html lang="jp">
    <head>
        <meta charset="utf-8" />
    </head>
    <body>

    <table border="1">
    <tr>
    <th width="50" height="30">ID</th>
        <th width="500" height="30">title</th>
        <th width="100" height="30">category</th>
        <th width="100" height="30">newflag</th>
        <th width="200" height="30">activedate</th>
    </tr>
    <tr>

    <!-- <form method="post" action="noticeEdit.php" id="example">
        <input type="hidden" name="id" value="決まった値"> -->
<?php

    require_once 'autoload.php';

    // DB接続
    $pdo = new MySqlPDB();
    $pdo->connectDB();

    $result = NoticeClass::GetList();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)){
        echo
        "<tr>
            <td>".$row['id']."</td>
            <td>".$row['title']."</td>
            <td>".$row['category']."</td>
            <td>".$row['newflag']."</td>
            <td>".$row['activedate']."</td>
            <form method='post' action='noticeEdit.php' id='example'>
                <td><button type='submit' name='id' value='".$row['id']."'>改修</td>
            </form>
        </tr>";
    }

?>

  </tr>
</table>

<button onclick="location.href='noticeAdd.php'">新規</button>

    </body>
</html>
