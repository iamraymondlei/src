$(document).ready(function () {
    FullfillLoginDataByCookie();
    BindSigninGoEvent();
    $('#login-form-group').validator();

    $('#inputPassword').bind('keypress', function (event) {
        if (event.keyCode === 13) {
            SigninGo();
        }
    });
});

function FullfillLoginDataByCookie() {
    if ($.cookie('rememberMe') === "true") {
        $("#inputUsername").val($.cookie('username'));
        $("#inputPassword").val($.cookie('password'));
        $("#inputRememberMe").attr("checked", "checked");
    }
}

function BindSigninGoEvent() {
    $('#signinGo').unbind('click').keydown(function (event) {
        if (event.keyCode === 13) {
            event.stopPropagation();
            $('#signinGo').click();
        }
    });

    $('#signinGo').bind('click', function () {
        SigninGo();
    });
}

function SigninGo() {
    var username = $("#inputUsername").val(),
        password = $("#inputPassword").val();
    
    if (username.length && password.length) {
        var verifyUserResult = VerifyUser(username, password);
        if (verifyUserResult) {
            if (verifyUserResult.WebService.ResultCode === 200) {
                SetCookie();
                JumpPage();
            }
            else {
                $("#divUsername").addClass("has-error");
                $("#divPassword").addClass("has-error");
                ShowErrorInfo(verifyUserResult.WebService.ResultMessage);
            }
        }
    }
}

function VerifyUser(username, password) {
    var result = false;
    $.ajax({
        url: '/datawh/ws/VerifyUser.php',
        type: 'POST',
        data:{
            format: 'json',
            un: username,
            pw: password
        },
        dataType: 'json',
        async: false,
        success: function(json) {
            result = json;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            alert("访问VerifyUser服务异常!" + XMLHttpRequest.responseText);
        }
    });
    return result;
}

function SetCookie() {
    var expireDay = 7,
        username = $("#inputUsername").val(),
        password = $("#inputPassword").val(),
        isRememberMe = $("#inputRememberMe").prop("checked");

    $.cookie('username', username, {expires: expireDay});
    $.cookie('password', password, {expires: expireDay});
    $.cookie('rememberMe', isRememberMe, {expires: expireDay});

    return true;
}

function JumpPage() {
    location.href = "index.php?p=backend&c=Index&a=index";
}

function ShowErrorInfo(errorInfo) {
    $("#erroInfo").text(errorInfo);
    $("#erroInfo").show();
}