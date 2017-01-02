var valName = 1;
var valEmail = 1;
var valUser = 1;
var valMob = 1;
var valPassRegister = 1;
var errorText = '{"font-size":"12px","color":"#a94442","display":"inline-block","padding": "5px","font-weight": "700"}';
errorText = JSON.parse(errorText);
var errorInput = '{"outline":"none","border-color":"#a94442"}';
errorInput = JSON.parse(errorInput);
var removeError = '{"outline":"none","border-color":"#ccc"}';
removeError = JSON.parse(removeError);

function initRegister()
{
    name();
    email();
    username();
    mob();
    passwordRegister();
}

// Name validation

$("#name").blur(function()
{
    name();
});

// Email validation

$("#email").keyup(function() {
    email();
});

$("#email").blur(function() {
    email();
});


// username validation

$("#username").blur(function() {
    username();
});

// Mobile validation

$("#mob").blur(function() {
    mob();
});

//Password validation

$("#passRegister").blur(function() {
    passwordRegister();

});

function registerCheck() {
    var name = $("#name").val();
    var email = $("#email").val();
    var username = $("#username").val();
    var mob = $("#mob").val();
    var password = $("#passRegister").val();

    initRegister();

    if(valName === 0 && valEmail === 0 && valUser === 0 && valMob === 0 && valPassRegister === 0)
    {
        var q = {"name":name,"email":email,"username":username,"mob":mob,"password":password};
        q = "q=" + JSON.stringify(q);
        // console.log(q);
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function()
        {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
            {
                var result = JSON.parse(xmlhttp.responseText);
                // console.log(result);
                if(result['location'])
                {
                    location.href = result['location'];
                }
                if(result['name'])
                {
                    showNameError(result['name']);
                }
                if(result['password'])
                {
                    showPassErrorRegister(result['password']);
                }
                if(result['email'])
                {
                    showEmailError(result['email']);
                }
                if(result['username'])
                {
                    showUsernameError(result['username']);
                }
                if(result['mob'])
                {
                    showMobError(result['mob']);
                }
            }
        };
        xmlhttp.open("POST", "ajax/validate_register.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send(q);
    }
    else
    {
        // alert("Please Fill correct details");
        $("#myModal").modal()
    }
}

function showNameError(txt)
{
    $("input#name").prev("span").remove();
    $("#name").css(errorInput);
    var txt1 = $("<span></span>").text(txt).css(errorText);
    $("#name").before(txt1);
}

function showEmailError(txt)
{
    $("input#email").prev("span").remove();
    $("#email").css(errorInput);
    var txt1 = $("<span></span>").text(txt).css(errorText);
    $("#email").before(txt1);
}
function showUsernameError(txt)
{
    $("input#username").prev("span").remove();
    $("#username").css(errorInput);
    var txt1 = $("<span></span>").text(txt).css(errorText);
    $("#username").before(txt1);
}

function showMobError(txt)
{
    $("input#mob").prev("span").remove();
    $("#mob").css(errorInput);
    var txt1 = $("<span></span>").text(txt).css(errorText);
    $("#mob").before(txt1);
}

function showPassErrorRegister(txt)
{
    $("input#passRegister").prev("span").remove();
    $("#passRegister").css(errorInput);
    var txt1 = $("<span></span>").text(txt).css(errorText);
    $("#passRegister").before(txt1);
}

function name()
{
    var name = $("#name").val();
    $("#name + span").text("");
    if(name === "")
    {
        valName = 1;
        showNameError(" *Please input your name");
    }
    else
    {
        $("#name").css(removeError);
        valName = 0;
    }
}

function email()
{
    var val = $("#email").val();
    var ret = validate_email(val);
    $("input#email").prev("span").remove();
    if(val === "")
    {
        valEmail = 1;
        showEmailError(" *Enter Your email address");
    }
    else if(!ret)
    {
        valEmail = 1;
        showEmailError(" *Invalid Email");
    }
    else
    {
        $("#email").css(removeError);
        valEmail = 0;
    }
}

function username()
{
    var val = $("#username").val();
    var re = /^\S+@/;

    $("input#username").prev("span").remove();
    if(val === "")
    {
        valUser = 1;
        showUsernameError(" *Enter Your username");
    }
    else if(re.test(val))
    {
        valUser = 1;
        showUsernameError(" *Invalid username");
    }
    else
    {
        $("#username").css(removeError);
        valUser = 0;
    }
}

function mob()
{
    var mob = $("#mob").val();
    var re = /^[0-9]{10}$/;
    $("input#mob").prev("span").remove();
    if(mob === "")
    {
        valMob = 1;
        showMobError(" *Enter your mobile no.");
    }
    else if(!re.test(mob))
    {
        valMob = 1;
        showMobError(" *Enter 10 digit mobile no.");
    }
    else
    {
        $("#mob").css(removeError);
        valMob = 0;
    }
}

function passwordRegister()
{
    var pass = $("#passRegister").val();
    $("input#passRegister").prev("span").remove();
    if(pass === "")
    {
        valPassRegister = 1;
        showPassErrorRegister(" *Enter your password");
    }
    else
    {
        $("#passRegister").css(removeError);
        valPassRegister = 0;
    }
}

function validate_email(val)
{
    var re = /^\S+@\w+\.\w+$/;
    return re.test(val);
}