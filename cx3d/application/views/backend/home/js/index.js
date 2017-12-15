$(document).ready(function () {
    var result = false;
    $.ajax({
        url: 'ws/VerifyUser.php',
        type: 'POST',
        data:{
            format: 'json',
            code: $.urlParams("get", "code"),
            appid: $.urlParams("get", "appid"),
            api: "getUserInfo"
        },
        dataType: 'json',
        async: false,
        success: function(json) {
            result = json;
            if(result["WebService"]["ResultCode"] == 200){//&& result["WebService"]["User"]["Role"] === "系统管理员"
                location.href = "index.php?p=backend&c=history&a=index&ci=5";
            }
            else{
                location.href = "404.php";
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            alert("访问VerifyUser服务异常!" + XMLHttpRequest.responseText);
        }
    });
});