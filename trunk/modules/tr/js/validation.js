function ValidateAll()
{
	var name=document.getElementById('username').value;
	var name=document.getElementById('username').value;
	var name=document.getElementById('username').value;
	var name=document.getElementById('username').value;
	
	if(userName(name) == false) return false;
	if(passWord(pass.value) == false) return false;
	if(confirmpassWord(confirmpass.value) != passWord(pass.value)) return false;
	if(passWord(email.value) == false) return false;
	
	return true;
	window.location="gridlayout1.html" ;
}






function userName(formName,fieldName,type,errorDiv)
{
	restricted=/^[a-z0-9A-Z]+$/;
	switch(type)
	{
		case "USERNAME":
			fld1 = document.forms[formName].elements[fieldName];
			name=fld1.value;	
			fldl_len=fld1.value.length;
			if(name == '' || name == null)
			{
				document.getElementById(errorDiv).innerHTML = 'Please Enter UserName';
				fld1.focus();
				return false;
			}
			else if(!(restricted.test(name)))
			{
				document.getElementById(errorDiv).innerHTML = 'Special Characters not allowed';	
			}
			
			else if(fldl_len < 5 )
			{
					document.getElementById(errorDiv).innerHTML = 'Please Enter atleast five Characters';	
			}
			else if(fldl_len > 15)
			{
					document.getElementById(errorDiv).innerHTML = 'Please Enter up to fifteen Characters only';	
			}
			else
			{
				 document.getElementById(errorDiv).innerHTML = 'User Name Available';	
			}
			break;
		default:
			break;
	}
	
	
	
	
}
function passWord()
{
	pass = document.getElementById('password');
	pass_len = pass.value.length;
	passrestricted = /^[a-z0-9A-Z]+ $/;
	if(pass.value == '' || pass.value == null)
	{
		document.getElementById('passerror').innerHTML = "Please Enter Password";
		pass.focus();
		return false;
	}
	else if(pass_len < 6)
	{
		document.getElementById('passerror').innerHTML = "Please Enter Atleast 6 Charecters";	
	}
	else
	{
		document.getElementById('passerror').innerHTML = "Good Strength"	
	}
}
function confirmpassWord()
{
	pass = document.getElementById('password'); 
	confirmpass = document.getElementById('confirm_password');
	confirmpass_len = confirmpass.value.length;
	if( confirmpass.value == '' || confirmpass.value == null )
	{
		document.getElementById('confirmerror').innerHTML = 'Please Enter confirm Password';
		confirmpass.focus();
		return false;
	}
	else if(confirmpass.value != pass.value )
	{
		document.getElementById('confirmerror').innerHTML = 'Password Is Not Match with above';	
	}
	else
	{
		document.getElementById('confirmerror').innerHTML = 'Password Match';
	}
}

function Email()
{
	email = document.getElementById('email');
	email_len = email.value.length;
	emailrestricted = /^[a-z0-9A-Z._%-]+@[A-Za-z.-]+\.[a-zA-Z]{2,4}$/;
	//emailrestricted = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
	if(email.value == '' || email.value == null)
	{
		document.getElementById('emailerror').innerHTML = 'Please Enter Email here';
		email.value.focus();
		return false;
	}
	else if(!(emailrestricted.test(email.value)))
	{
			document.getElementById('emailerror').innerHTML = 'Please Enter Proper email ID';
	}
	else
	{
		document.getElementById('emailerror').innerHTML = 'Email ID Valid';
	}
}
function Radio()
{
	radio1 = document.getElementById('radio');
	radio2 = document.getElementById('radio');
	if(radio1.checked == true)
	{
		document.getElementById('radioerror').innerHTML = 'You Checked Male';	
	}
	else if(radio2.checked == true)
	{
		document.getElementById('radioerror').innerHTML = 'You Checked Female';	
	}
}

function dropdownmenu()
{
	var myselect = document.getElementById('menu').selectedIndex;
	
	if(myselect == 0)
	{
		document.getElementById('selecterror').innerHTML = 'Please Select Atleast One From Drop down Menu';
		myselect.focus();
	}
	else
	{
		document.getElementById('selecterror').innerHTML = 'Thanks For choosing';	
	}
}

/*******************************   Date Format ********************************************/
function datestr()
{
var now = new Date();
var dateString = now.getDate() + "-" + now.getMonth() + "-" + now.getFullYear();
document.getElementById('DateDisplay').innerHTML = 'Date :' +dateString;
}


