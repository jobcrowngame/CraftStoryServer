<?php
    require_once 'autoload.php';

    // DB接続
    $pdo = new MySqlPDB();
    $pdo->connectDB();

    $result = NoticeClass::Get($_POST)->fetch(PDO::FETCH_ASSOC);
?>

<form action='noticeEditFunction.php' method='post'>

<label for="id">ID:</label>
<input type="text" name="id" value="<?php echo $result['id']; ?>" readonly="readonly">
<br>

<label for="category">カテゴリー:</label>
<select name="category" value = "<?php echo $result['category']; ?>">
    <option value="1">お知らせ</option>
    <option value="2">重要</option>
    <option value="3">メンテナンス</option>
    <option value="4">予告</option>
    <option value="5">イベント</option>
</select>
<br>

<label for="newflag">Newフラグ：</label>
<select name="newflag" value="<?php echo $result['newflag']; ?>">
    <option value="1">ON</option>
    <option value="2">OFF</option>
</select>
<br>

<label>日付：</label>
<input type="date" name="activedate" style="width:160px" value='<?php echo $result['activedate']; ?>'>
<br>

<label>タイトル：</label>
<input type='text' name='title' value='<?php echo $result['title']; ?>'>
<br>

<label for="text">内容:</label></br>
<textarea name="text" rows="10" cols="50">
<?php echo $result['text']; ?>
</textarea>

</br><lable>暗号キー：</lable>
<input type='text' id="key" name="key">
</br><td><button type='submit' name='type' value=1>改修</td>
</br><td><button type='submit' name='type' value=2>削除</td>

</form> 