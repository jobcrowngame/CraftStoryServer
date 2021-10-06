const select = document.form1.color1;
    select.onchange = clickBtn1;
        

    function clickBtn1(){

        alert("onchange");

        const color1 = document.form1.color1;

        // 値(数値)を取得
        const num = color1.selectedIndex;
        //const num = document.form1.color1.selectedIndex;

        // 値(数値)から値(value値)を取得
        const str = color1.options[num].value;
        //const str = document.form1.color1.options[num].value;

        document.getElementById("span1").textContent = str; 
    }