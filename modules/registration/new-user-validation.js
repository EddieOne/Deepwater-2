$(document).ready(function(){
	var name = $("#username");
	var nameInfo = $("#nameInfo");
	var mail = $("#mail");
	var mailInfo = $("#mailInfo");
	var pass = $("#password");
	var passInfo = $("#passInfo");
	
	name.blur(validateName);
	mail.blur(validateMail);
	pass.blur(validatePass);

	function validateName(){
		//if it's NOT valid
		if(name.val().length < 4){
			nameInfo.text("Usernames must be at least 4 characters.");
			nameInfo.removeClass("greentext"); 
			nameInfo.addClass("redtext");
			return false;
		}else{
			var n = $("#username").val();
			var filter = /^[a-zA-Z0-9]+$/;
			if(filter.test(n)){
				$.post("../includes/checkname.php", {name: name.val()}, function(data){
               		if(data == "true"){
				 		nameInfo.text("That username is available.");
						nameInfo.removeClass("redtext");
						nameInfo.addClass("greentext");
					}else if(data == "error"){
						nameInfo.text("An Error occurred. Please click join or try again later.");
						nameInfo.removeClass("greentext"); 
						nameInfo.removeClass("redtext");
			   		}else{
						nameInfo.text("Username taken, please try again.");
						nameInfo.removeClass("greentext"); 
						nameInfo.addClass("redtext");
			   		}
        		});
				nameInfo.text("What's your name?");
				nameInfo.removeClass("greentext"); 
				nameInfo.removeClass("redtext");
				return true;
			}else{
				nameInfo.text("Usernames may only contain letters and numbers.");
				nameInfo.removeClass("greentext"); 
				nameInfo.addClass("redtext");	
			}
		}
	}
	function validateMail(){
		var a = $("#mail").val();
		var filter = /^[a-zA-Z0-9]+[a-zA-Z0-9_.-]+[a-zA-Z0-9_-]+@[a-zA-Z0-9]+[a-zA-Z0-9.-]+[a-zA-Z0-9]+.[a-z]{2,4}$/;
		if(filter.test(a)){
			mailInfo.text("");
			mailInfo.removeClass("redtext");
			return true;
		}else{
			mailInfo.text("Your email must be valid.");
			mailInfo.addClass("redtext");
			return false;
		}
	}
	function validatePass(){
		//it's NOT valid
		if(pass.val().length <6){
			passInfo.text("Make your password 6 characters or longer.");
			passInfo.addClass("redtext");
			return false;
		}else{			
			passInfo.text("");
			passInfo.removeClass("redtext");
			return true;
		}
	}
});