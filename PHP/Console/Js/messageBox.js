
 $('#hint01').css("color","red");

 $('form').submit(function() {
    var title = $('#title').val();
    if(!title){
        alert("タイトルを入力してください。");
        return false;
    }

    var text = $('#text').val();
    if(!text){
        alert("本文を入力してください。");
        return false;
    }

    var userOption = $('#userOption').val();
    var userId = $('#userId').val();
    if (userOption == 2 && !userId){
        alert("ユーザーIDを入力してください。");
        return false;
    }

    var itemOption = $('#itemOption').val();
    var items = $('#items').val();
    var itemCount = $('#itemCount').val();
    if (itemOption == 2 && !items || itemOption == 2 && !itemCount){
        alert("アイテムID、数を入力してください。");
        return false;
    }

    if (itemOption == 2){
        var item = items.split(",");
        var count = itemCount.split(",");
        if (item.length != count.length){
            alert("アイテムID、数を入力が正しいかを確認してください。");
            return false;
        }
    }
});