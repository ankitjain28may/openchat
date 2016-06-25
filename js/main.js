function login()
{
	$(document).ready(function(){
		$(".login").show();
		$("#login").css("background-color","blue");
		$(".register").hide();
		$("#register").css("background-color","inherit");
	});
}
function register()
{
	$(document).ready(function(){
		$(".register").show();
		$("#register").css("background-color","blue");
		$(".login").hide();
		$("#login").css("background-color","inherit");
	});
}