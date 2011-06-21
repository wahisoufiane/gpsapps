<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Login Document</title>
</head>

<body>
<div class="loginarea"><!-- loginarea div start here -->
<h2 class="login_head">Login</h2>
<form name="frmLogin" id="frmLogin" method="post" action="" autocomplete="off">
<table class="login_table">
	<tr>
    	<td>Username</td><td> : </td><td> <input type="text" name="txtUsername" id="txtUsername" class="input" onKeyPress="return submainLog(this,event)" /> </td>
    </tr>
	<tr>
    	<td>Password</td><td> : </td><td> <input type="password" name="txtPassword" id="txtPassword" class="input" onKeyPress="return submainLog(this,event)" /> </td>
    </tr>
    <tr>
    	<!--<td colspan="2"><input type="checkbox" name="remember" /> Remember Password <br /> <br />
        	<a href="#" style="color:#2f5f68">Forgot Password</a>
        </td>-->
        <td colspan="3"><input type="button" name="cmdLogin" id="cmdLogin" value="Login" class="login_btn" onclick="validateLogin(this);" /></td>
    
    </tr>


</table>
</form>

</div><!-- loginarea div END here -->
</body>
</html>
