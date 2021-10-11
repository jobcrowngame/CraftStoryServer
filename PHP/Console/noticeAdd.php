<form action='noticeAddFunction.php' method='post'>

<label for="category">カテゴリー:</label>
<select name="category">
    <option value="1">お知らせ</option>
    <option value="2">重要</option>
    <option value="3">イベント</option>
</select>
<br>

<label for="newflag">Newフラグ：</label>
<select name="newflag">
    <option value="1">ON</option>
    <option value="2">OFF</option>
</select>
<br>

<label>日付：</label>
<input type="date" name="activedate" style="width:160px">
<br>

<label>タイトル：</label>
<input type='text' name='title'>
<br>

<label>タイトルアイコン:</label>
<input type='text' name='titleIcon'>
<br>

<label>詳細アイコン:</label>
<input type='text' name='detailIcon'>
<br>

<label>URL:</label>
<input type='text' name='url'>
<br>

<label for="text">内容:</label></br>
<textarea name="text" rows="10" cols="50">
It was a dark and stormy night...
</textarea>

<br><input type='submit' value='新規'>
<lable>暗号キー：</lable>
<input type='text' id="key" name="key"></p>

</form> 