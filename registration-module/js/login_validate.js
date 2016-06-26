var val_login=1;
var val_pass=1;

function init()
{
	login();
	password();
}

$("#login").blur(function() 
{
	login();
});


$("#password").blur(function()
{
	password();
});


function validate_email(val)
{
	var re=/^\S+@\w+\.\w+$/;
	return re.test(val);
}

function login_check()
{
	var login=$("#login").val();
	var password=$("#password").val();
	init();
	// console.log(login);
	if(val_login==0 && val_pass==0)
	{
		var q={"login":login,"password":password};
	  	q="q="+JSON.stringify(q);
	  	// console.log(q);
	  	var xmlhttp = new XMLHttpRequest();
	  	xmlhttp.onreadystatechange = function() 
	  	{
		    if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
		    {
		      	arr=JSON.parse(xmlhttp.responseText);
		        if(arr['location'])
		        {
		        	location.href=arr['location'];
		        }
		        if(arr['login'])
		        {
		        	$("#login_label p").remove("p");
					show_login_error(arr['login']);
		        }
		        if(arr['password'])
		        {
		        	$("#pass_label p").remove("p");
					show_pass_error(arr['password']);
		        }
		    }
	  	};
		xmlhttp.open("POST", "registration-module/ajax/validate_login.php", true);
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.send(q); 
	}
	else
	{
		alert("Enter correct details");
	}
}

function show_login_error(txt)
{
	$("#login").css({"outline":"none","border-color":"red"});
	var txt1=$("<p></p>").text(txt);
	$("#login_label").append(txt1);
}

function show_pass_error(txt)
{
	$("#password").css({"outline":"none","border-color":"red"});
	var txt1=$("<p></p>").text(txt);
	$("#pass_label").append(txt1);
}

function login()
{
	var re=/^\S+@/;
	var val=$("#login").val();
	$("#login_label p").remove("p");
	// console.log(val);
	if(val=="")
	{
		show_login_error("Please enter your email or username");
	}
	else if(re.test(val))
	{
		var ret=validate_email(val);
		if(!ret)
		{
			show_login_error("Invalid Email");
		}
		else
		{
			$("#login").css({"outline":"none","border-color":"green"});
			val_login=0;
		}
	}
	else
	{
		$("#login").css({"outline":"none","border-color":"green"});
		val_login=0;
	}
}

function password()
{
	var val=$("#password").val();
	$("#pass_label p").remove("p");
	if(val=="")
	{
		show_pass_error("Enter Password");
	}
	else
	{
		$("#password").css({"outline":"none","border-color":"green"});
		val_pass=0;
	}
}