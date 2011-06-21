<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css/style.css" rel="stylesheet" type="text/css" />
<title>Login Document</title>
<script type="text/javascript" src="../js/ajax.js"></script>
<script language="javascript">
var ajax1=new sack();
function validateLogin(form)
{
alert('sss');
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
		ajax1.requestFile = 'includes/ajax_server.php?username='+username+'&password='+password;
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
		eval(ajax1.response);
}
function validateUsername(fld) {
    var error = "";
    var illegalChars = /\W/; // allow letters, numbers, and underscores
 
    if (fld.value == "") {
		fld.focus();
        fld.style.background = 'Yellow'; 
        error = "You didn't enter a username.\n";
    } else if ((fld.value.length < 4) || (fld.value.length > 25)) {
		fld.focus();
        fld.style.background = 'Yellow'; 
        error = "The username is the wrong length.\n";
    } else if (illegalChars.test(fld.value)) {
    	fld.focus();
	    fld.style.background = 'Yellow'; 
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
        fld.style.background = 'Yellow';
        error = "You didn't enter a password.\n";
    } else if ((fld.value.length < 7) || (fld.value.length > 25)) {
		fld.focus();
        error = "The password is the wrong length. \n";
        fld.style.background = 'Yellow';
    } else if (illegalChars.test(fld.value)) {
		fld.focus();
        error = "The password contains illegal characters.\n";
        fld.style.background = 'Yellow';
    } else if (!((fld.value.search(/(a-z)+/)) && (fld.value.search(/(0-9)+/)))) {
		fld.focus();
        error = "The password must contain at least one numeral.\n";
        fld.style.background = 'Yellow';
    } else {
        fld.style.background = 'White';
		error = '';
    }
   return error;
}   
</script>
</head>

<body>
<div class="loginarea"><!-- loginarea div start here -->
<h2 class="login_head">Login</h2>
<table class="login_table">
	<tr>
    	<td>Username</td><td> : </td><td> <input type="text" name="txtUsername" id="txtUsername" class="input" /> </td>
    </tr>
	<tr>
    	<td>Password</td><td> : </td><td> <input type="password" name="txtPassword" id="txtPassword" class="input" /> </td>
    </tr>
    <tr>
    	<!--<td colspan="2"><input type="checkbox" name="remember" /> Remember Password <br /> <br />
        	<a href="#" style="color:#2f5f68">Forgot Password</a>
        </td>-->
        <td colspan="3"><input type="button" name="cmdLogin" id="cmdLogin" value="Login" class="login_btn" onclick="validateLogin(this);" /></td>
    
    </tr>


</table>


</div><!-- loginarea div END here -->
</body>
</html>
