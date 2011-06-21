var ajax1=new sack();
function submainLog(myfield,e)
{
	var keycode;
	if (window.event) keycode = window.event.keyCode;
	else if (e) keycode = e.which;
	else return true;
	
	if (keycode == 13)
	{
		validateLogin();
		return false;
	}
	else
		return true;
}
function validateLogin(form)
{
	//alert(document.frmLogin.txtUsername..value.indexOf(" "));
	/*var illegalChars = /\W/; // allow letters, numbers, and underscores
	if(document.frmLogin.txtUsername.value == '')
	{
		alert("Enter Username");
		document.frmLogin.txtUsername.focus();
		return false;
	}
	else if(illegalChars.test(document.frmLogin.txtUsername.value))
	{
		alert("Only etters, numbers, and underscores allowed in Username");
		return false;
	}*/
	
	var usrValid = validateUsername(document.frmLogin.txtUsername);
	if(usrValid)
	{
		alert(usrValid);
		return false;
	}
	var passValid = validatePassword(document.frmLogin.txtPassword);
	if(passValid)
	{
		alert(passValid);
		return false;
	}
	else 
	{
		var username = document.frmLogin.txtUsername.value;
		var password = document.frmLogin.txtPassword.value;
		ajax1.requestFile = '../../includes/ajax_server.php?username='+username+'&password='+password;
		//document.write(ajax1.requestFile);
		ajax1.onCompletion = function(){executeLogin()};
		ajax1.runAJAX();
	}
}
function executeLogin()
{
	if(ajax1.response==0)
	{
		alert("User not valid");
		location.href='.';
	}
	else if(ajax1.response==2)
	{
		alert("Account has been blocked. Please check Admin ");
		location.href='.';
	}
	else
	{
		eval(ajax1.response);
		//$("#content").load(ajax1.response);
	}
}
function validateUsername(fld) {
    var error = "";
    var illegalChars = /\W/; // allow letters, numbers, and underscores
 
    if (fld.value == "") {
		fld.focus();
         
        error = "You didn't enter a username.\n";
    } else if ((fld.value.length < 4) || (fld.value.length > 25)) {
		fld.focus();
         
        error = "The username is the wrong length.\n";
    } else if (illegalChars.test(fld.value)) {
    	fld.focus();
	     
        error = "The username contains illegal characters.\n";
    } else {
        fld.style.background = 'White';
		error = '';
    }
    return error;
}
function validatePassword(fld) {
    var error = "";
    var illegalChars = /[\W_]/; // allow only letters and numbers 
 
    if (fld.value == "") {
		fld.focus();
        
        error = "You didn't enter a password.\n";
    } else if ((fld.value.length < 7) || (fld.value.length > 25)) {
		fld.focus();
        error = "The password is the wrong length. \n";
        
    } else if (illegalChars.test(fld.value)) {
		fld.focus();
        error = "The password contains illegal characters.\n";
        
    } else if (!((fld.value.search(/(a-z)+/)) && (fld.value.search(/(0-9)+/)))) {
		fld.focus();
        error = "The password must contain at least one numeral.\n";
        
    } else {
        fld.style.background = 'White';
		error = '';
    }
   return error;
}   