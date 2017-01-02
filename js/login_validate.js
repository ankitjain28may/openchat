var valLogin = 1;
var valPass = 1;
var errorText = '{"font-size":"12px","color":"#a94442","display":"inline-block","padding": "5px","font-weight": "700"}';
errorText = JSON.parse(errorText);
var errorInput = '{"outline":"none","border-color":"#a94442"}';
errorInput = JSON.parse(errorInput);
var removeError = '{"outline":"none","border-color":"#ccc"}';
removeError = JSON.parse(removeError);

function initLogin()
{
    login();
    passwordLogin();
}

$("#login").blur(function()
{
    login();
});


$("#passLogin").blur(function()
{
    passwordLogin();
});


function validate_email(val)
{
    var re = /^\S+@\w+\.\w+$/;
    return re.test(val);
}

function loginCheck()
{
    var login = $("#login").val();
    var password = $("#passLogin").val();
    initLogin();
    // console.log(login);
    if(valLogin === 0 && valPass === 0)
    {
        var q = {"login":login,"password":password};
        q = "q=" + JSON.stringify(q);
        // console.log(q);
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function()
        {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
            {
                var result = JSON.parse(xmlhttp.responseText);
                if(result['location'])
                {
                    location.href = result['location'];
                }
                if(result['login'])
                {
                    $("input#login").prev("span").remove()
                    showLoginError(result['login']);
                }
                if(result['password'])
                {
                    $("input#passLogin").prev("span").remove()
                    showPassErrorLogin(result['password']);
                }
            }
        };
        xmlhttp.open("POST", "ajax/validate_login.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send(q);
    }
    else
    {
        // alert("Enter correct details");
        $("#myModal").modal()

    }
}

function showLoginError(txt)
{
    $("input#login").prev("span").remove()
    $("#login").css(errorInput);
    var txt1 = $("<span></span>").text(txt).css(errorText);
    $("#login").before(txt1);
}

function showPassErrorLogin(txt)
{
    $("input#passLogin").prev("span").remove()
    $("#passLogin").css(errorInput);
    var txt1 = $("<span></span>").text(txt).css(errorText);
    $("#passLogin").before(txt1);
}

function login()
{
    var re = /^\S+@/;
    var val = $("#login").val();
    $("input#login").prev("span").remove()
    // console.log(val);
    if(val === "")
    {
        valLogin = 1;
        showLoginError(" *Please enter your email or username");
    }
    else if(re.test(val))
    {
        var ret = validate_email(val);
        if(!ret)
        {
            valLogin = 1;
            showLoginError(" *Invalid Email");
        }
        else
        {
            $("#login").css(removeError);
            valLogin = 0;
        }
    }
    else
    {
        $("#login").css(removeError);
        valLogin = 0;
    }
}

function passwordLogin()
{
    var val = $("#passLogin").val();
    $("input#passLogin").prev("span").remove()
    if(val === "")
    {
        valLogin = 1;
        showPassErrorLogin(" *Enter Password");
    }
    else
    {
        $("#passLogin").css(removeError);
        valPass = 0;
    }
}