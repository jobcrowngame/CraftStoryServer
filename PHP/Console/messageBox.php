
<head>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
</head>
<body>
<form name="form" action='messageBoxFunction.php' method='post'>
    <lable>タイトル：</lable><br>
    <input type="text" id="title" name="title"><br>

    <lable>内容：</lable><br>
    <textarea id="text" name="text" rows="10" cols="72"></textarea><br>

    <lable>送信対象：</lable>
    <select id="userOption" name="userOption">
            <option value="1">全ユーザー</option>
            <option value="2">指定ユーザー</option>
    </select><br>
    <lable>ユーザーID：</lable><br>
    <input type='text' id="userId" name="userId"><br><br>

    <lable>添付アイテム：</lable>
    <select id="itemOption" name="itemOption">
        <option value="1">なし</option>
        <option value="2">あり</option>
    </select><br>

    <lable>アイテムリスト：（例: 101,102,103）</lable><br>
    <input type='text' id='items' name="items"><br>
    <label>アイテム数：（例: 10,15,3）</label><br>
    <label id="hint01">注意：アイテムリストと数が同じ件数にする必要があります。</label><br>
    <input type='text' id='itemCount' name="itemCount"><br>

    <input type="submit" value="送信">
    <lable>暗号キー：</lable>
    <input type='text' id="key" name="key">

    <script type="text/javascript" src="Js/messageBox.js"></script>

</form>
</body>