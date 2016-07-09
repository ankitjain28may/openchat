function isText(type)
{
	if(type==="text")
	{
		return true;
	}
}
function isEmail(type)
{
	if(type==="email")
	{
		return true;
	}
}
function isNumber(type)
{
	if(type==="number")
	{
		return true;
	}
}
function isPassword(type)
{
	if(type==="password")
	{
		return true;
	}
}
function isTel(type)
{
	if(type==="tel")
	{
		return true;
	}
}
function isURL(type)
{
	if(type==="url")
	{
		return true;
	}
}

var ele=$(":input");
var len=ele.length;

for (var i = 0; i <len; i++) {
	var type=ele[i].type;
	if(!ele[i].placeholder)
	{
		if(isText(type))
		{			
			ele[i].placeholder="Enter the Text";
		}
		else if(isEmail(type))
		{
			ele[i].placeholder="Enter the Email Address";
		}
		else if(isNumber(type))
		{
			ele[i].placeholder="Enter the Number";
		}
		else if(isPassword(type))
		{
			ele[i].placeholder="Enter the Password";
		}
		else if(isTel(type))
		{
			ele[i].placeholder="Enter the Mobile No.";
		}
		else if(isURL(type))
		{
			ele[i].placeholder="Enter the URL";
		}
	}
}

$("textarea").attr("placeholder","Enter the message");

