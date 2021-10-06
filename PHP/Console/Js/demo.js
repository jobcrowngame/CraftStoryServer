// ext()を使って要素内に文字を表示させる方法。
$('#demo1').text('hoge1');

// html()で要素ごと表示させる方法。
$('#demo2').html('<b>hoge2</b>');

// append()で要素内の最後に表示。
$('#demo4').append('<span>hoge4</span>');

// prepend()で要素内の先頭に表示。
$('#demo5').prepend('<span>hoge5</span>');

// before()で自要素の前に表示。
$('#demo6').before('<span>hoge6</span>');

// after()で自要素の後ろに表示。
$('#demo7').after('<span>hoge7</span>');

// wrap()で指定した特定の要素を囲みます。
$('#demo8').wrap('<div style="color: red;"></div>');

// wrapInner()で指定した子要素をまとめて囲みます。
//  <div class="sample-demo">
//      <p id="demo9-1">demo9-1</p>
//      <div id="demo9-2">demo9-2</div>
//  </div>
$('.sample-demo').wrap('<div style="color: red;"></div>');


$(function(){
    $(".demo11").click(function(){
        var i = 0;
        while(i < 3){
            alert(i);
            i++;
        }
    });
});

$(function(){
    var now = new Date();
    var h = now.getHours();
    if( 0 < h && h < 12 ){
        var mess = "眠い。";
    }else{
        var mess = "早く帰りたい。";
    }

    $(".demo12").text(mess);
});