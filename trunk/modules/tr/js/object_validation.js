function ValidateAll()
{
	var name=document.getElementById('username').value;
	var password=document.getElementById('password').value;
	var confirm_pwd=document.getElementById('confirm_password').value;
	var email=document.getElementById('email').value;
	
	alert(name);
	if(name == "")
	{
	document.getElementById('usererror').innerHTML = 'Please Enter atleast Five Characters';		
	return false;
	}
	else if(password=="")
	{
	document.getElementById('passerror').innerHTML = 'Please Enter atleast Five Characters';		
	return false;
	}
	
}


function validfields(formname,elemname,type,diverror,optelename)
{

var formname = document.forms[formname].elements[elemname];
var formname_value = formname.value;

if(optelename)
var optValue = formname_value;	

if(formname_value == '' || formname_value == null)
{
	document.getElementById(diverror).innerHTML = 'Please Fill the Fields'
}	
else
{

	switch(type)
	{
		case "UserName" :
		
			if(formname_value.length <5 )
			{
				document.getElementById(diverror).innerHTML = 'Please Enter atleast Five Characters';
				return false;
			}
			else
			{
				document.getElementById(diverror).innerHTML = 'UserName Available';
				return true;
			}
		
		break;
		case "PassWord" :
		

			if(formname_value.length <5 )
			{
				document.getElementById(diverror).innerHTML = 'Password week';	
			}
			else
			{
				document.getElementById(diverror).innerHTML = 'Good Strength';
			}
		
		break;
		case "ConfirmPassword" :
			
			if(formname_value != optValue)
			{
				document.getElementById(diverror).innerHTML = "Password not matching";	
			}
			else
			{
				document.getElementById(diverror).innerHTML = "password Match";	
			}
			
		break;
		case "Email" :
			var illegalcharacters = /^[a-z0-9A-Z._%-]+@[A-Za-z.-]+\.[a-zA-Z]{2,4}$/;
			 if(!(illegalcharacters.test(formname_value)))
			 {
				document.getElementById(diverror).innerHTML = "Please Enter Proper Email ID"; 
			 }
			else
			{
				document.getElementById(diverror).innerHTML = "Email ID Valid";	
			}
		break;
		case "DropDownMenu" :
			var dropdown = formname.selectedIndex;
			if(dropdown = 0)
			{
				document.getElementById(diverror).innerHTML = "Please Choose Atleast One Here";	
			}
			else
			{
				document.getElementById(diverror).innerHTML = " Thanks for choosing";	
			}
			
		default:
		break;
	}
}	
}