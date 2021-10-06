<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
    <script type="text/javascript">
        window.onload = function onLoad() {
          var param = GetQueryString();

          var target = document.getElementById("param_q_out");
          if (param == null || param["q"] == undefined) {
            target.innerHTML = "パラメーターはありません。";
          } else {
            target.innerHTML = param["q"];
          }

          var target = document.getElementById("param_mode_out");
          if (param == null || param["mode"] == undefined) {
            target.innerHTML = "パラメーターはありません。";
          }
          else {
            target.innerHTML = param["mode"];
          }
        }

        function GetQueryString() {
            if (1 < document.location.search.length) {
                var query = document.location.search.substring(1);
                var parameters = query.split('&');

                var result = new Object();
                for (var i = 0; i < parameters.length; i++) {
                    var element = parameters[i].split('=');

                    var paramName = decodeURIComponent(element[0]);
                    var paramValue = decodeURIComponent(element[1]);

                    result[paramName] = decodeURIComponent(paramValue);
                }
                return result;
            }
            return null;
        }
    </script>
</head>
<body>
  <div>パラメーター</div>
  <hr />
  <p>パラメーター q:</p>
  <div id="param_q_out"></div>

  <hr />
  <p>パラメーター mode:</p>
  <div id="param_mode_out"></div>
</body>
</html>