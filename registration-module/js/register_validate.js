var val_name=1;
var val_email=1;
var val_user=1;
var val_mob=1;
var val_pass=1;

function init()
{
	name();
	email();
	username();
	mob();
	password();
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

$("#password").blur(function() {
	password();

});

function register_check() 
{
	var name=$("#name").val();
	var email=$("#email").val();
	var username=$("#username").val();
	var mob=$("#mob").val();
	var password=$("#password").val();

	init();
	
	if(val_name==0 && val_email==0 && val_user==0 && val_mob==0 && val_pass==0)
	{
		var q={"name":name,"email":email,"username":username,"mob":mob,"password":password};
  		q="q="+JSON.stringify(q);
  		// console.log(q);
  		var xmlhttp = new XMLHttpRequest();
  		xmlhttp.onreadystatechange = function() 
  		{
		    if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
		    {
		      	arr=JSON.parse(xmlhttp.responseText);
		      	// console.log(arr);
		        if(arr['location'])
		        {
		        	location.href=arr['location'];
		        }
		        if(arr['name'])
		        {
					show_name_error(arr['name']);
		        }
		        if(arr['password'])
		        {
					show_pass_error(arr['password']);
		        }
		        if(arr['email'])
		        {
		        	show_email_error(arr['email']);
		        }
		        if(arr['username'])
		        {
		        	show_username_error(arr['username']);
		        }
		        if(arr['mob'])
		        {
		        	show_mob_error(arr['mob']);
		        }
		    }
  		};
		xmlhttp.open("POST", "registration-module/ajax/validate_register.php", true);
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.send(q);
	}
	else
	{
		alert("Please Fill correct details");
	}
}

function show_name_error(txt)
{
	$("#name_label p").remove("p");
	$("#name").css({"outline":"none","border-color":"red"});
	var txt1=$("<p></p>").text(txt);
	$("#name_label").append(txt1);
}

function show_email_error(txt)
{
	$("#email_label p").remove("p");
	$("#email").css({"outline":"none","border-color":"red"});
	var txt1=$("<p></p>").text(txt);
	$("#email_label").append(txt1);
}
function show_username_error(txt)
{
	$("#user_label").remove("p");
	$("#username").css({"outline":"none","border-color":"red"});
	var txt1=$("<p></p>").text(txt);
	$("#user_label").append(txt1);
}

function show_mob_error(txt)
{
	$("#mob_label").remove("p");
	$("#mob").css({"outline":"none","border-color":"red"});
	var txt1=$("<p></p>").text(txt);
	$("mob_label").append(txt1);
}

function show_pass_error(txt)
{
	$("#pass_label p").remove("p");
	$("#password").css({"outline":"none","border-color":"red"});
	var txt1=$("<p></p>").text(txt);
	$("pass_label").append(txt1);
}

function name()
{
	var name=$("#name").val();
	$("#name_label p").remove("p");
	if(name=="")
	{
		show_name_error("Please input your name");
	}
	else
	{
		$("#name").css({"outline":"none","border-color":"green"});
		val_name=0;
	}
}

function email()
{
	var val=$("#email").val();
	var ret=validate_email(val);
	$("#email_label p").remove("p");
	if(val=="")
	{
		show_email_error("Enter Your email address");
	}
	else if(!ret)
	{
		show_email_error("Invalid Email");
	}
	else
	{
		$("#email").css({"outline":"none","border-color":"green"});
		val_email=0;
	}
}

function username()
{
	var val=$("#username").val();
	var re=/^\S+@/;

	$("#user_label p").remove("p");
	if(val=="")
	{
		show_username_error("Enter Your username");
	}
	else if(re.test(val))
	{
		show_username_error("Invalid username");
	}
	else
	{
		$("#username").css({"outline":"none","border-color":"green"});
		val_user=0;
	}
}

function mob()
{
	var mob=$("#mob").val();
	var re=/^[0-9]{10}$/;
	$("#mob_label p").remove("p");
	if(mob=="")
	{
		show_mob_error("Enter your mobile no.");
	}
	else if(!re.test(mob))
	{
		show_mob_error("Enter 10 digit mobile no.");
	}
	else
	{
		$("#mob").css({"outline":"none","border-color":"green"});
		val_mob=0;
	}
}

function password()
{
	var pass=$("#password").val();
	$("#pass_label p").remove("p");
	if(pass=="")
	{
		show_pass_error("Enter your password");
	}
	else
	{
		$("#password").css({"outline":"none","border-color":"green"});
		val_pass=0;
	}
}

function validate_email(val)
{
	var re=/^\S+@\w+\.\w+$/;
	return re.test(val);
}