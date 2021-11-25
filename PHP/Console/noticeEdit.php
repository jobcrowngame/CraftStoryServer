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
<select name="category">
    <option value="1" <?php if ( $result['category'] === '1' ) { echo ' selected'; } ?>>お知らせ</option>
    <option value="2" <?php if ( $result['category'] === '2' ) { echo ' selected'; } ?>>重要</option>
    <option value="3" <?php if ( $result['category'] === '3' ) { echo ' selected'; } ?>>イベント</option>
</select>
<br>

<label for="newflag">Newフラグ：</label>
<select name="newflag">
    <option value="1" <?php if ( $result['newflag'] === '1' ) { echo ' selected'; } ?>>ON</option>
    <option value="2" <?php if ( $result['newflag'] === '2' ) { echo ' selected'; } ?>>OFF</option>
</select>
<br>

<label>日付：</label>
<input type="date" name="activedate" style="width:160px" value='<?php echo date_format(new DateTime($result['activedate']), 'Y-m-d'); ?>'>
<br>

<label>優先度：</label>
<input type='text' name='priority' value='<?php echo $result['priority']; ?>'>
<br>

<label>強制表示：</label>
<input type='text' name='pickup' value='<?php echo $result['pickup']; ?>'>
<br>

<label>タイトル：</label>
<input type='text' name='title' value='<?php echo $result['title']; ?>'>
<br>

<label>タイトルアイコン:</label>
<input type='text' name='titleIcon'  value='<?php echo $result['titleIcon']; ?>'>
<br>

<label>詳細アイコン:</label>
<input type='text' name='detailIcon'  value='<?php echo $result['detailIcon']; ?>'>
<br>

<label>URL:</label>
<input type='text' name='url'  value='<?php echo $result['url']; ?>'>
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