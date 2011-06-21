//IT'S HAVING USER, PASSWORD, PROFILE, FLEET, DIRECTORY, SCHEDULE, TASK, TRIP SHEET, IMAGE AND MAXLENGTH VALIDATIONS
var client_name=/^[a-zA-Z][a-zA-Z ]*$/;
var location_name=/^[a-zA-Z][a-zA-Z0-9,. ]*$/;
var secure_ans = /^[ ]*$/
var vehicle_name_js=/^[a-zA-Z0-9][a-zA-Z0-9 ]*$/;


//ADDING TASK BY GPS USER
function addTaskValidation_gpsForm()
{
	if(document.add_task_form_gps.atfg_title.value=="")
	{
		document.getElementById('div_addtask_gps_error').innerHTML='Task Name Required';
		document.add_task_form_gps.atfg_title.focus();
		return false;
	}
	if(secure_ans.test(document.add_task_form_gps.atfg_title.value))
	{
		document.getElementById('div_addtask_gps_error').innerHTML='Task Name Should Not have Only Spaces';
		document.add_task_form_gps.atfg_title.select();
		return false;
	}
	else
	{
		document.getElementById('div_addtask_gps_error').innerHTML='';
	}
	
	if(document.add_task_form_gps.atfg_start_date.value == "select start date" || document.add_task_form_gps.atfg_start_date.value == "")
	{
		document.getElementById('div_addtask_gps_error').innerHTML='Select Start Date';
		document.add_task_form_gps.atfg_start_date.select();
		return false;
	}
	else
	{
		document.getElementById('div_addtask_gps_error').innerHTML='';
	}
	
	if(document.add_task_form_gps.atfg_start_date.value!='')
	{
		var check_date1 = document.add_task_form_gps.as_cur_date.value.split('-');
		var now_date1 = new Date(check_date1[0],check_date1[1]-1,check_date1[2]);
		
		var check_date2 = document.add_task_form_gps.atfg_start_date.value.split('-');
		var now_date2 = new Date(check_date2[0],check_date2[1]-1,check_date2[2]);
		
		var diff_date = now_date2 - now_date1;
		if(diff_date < 0)
		{
			document.getElementById('div_addtask_gps_error').innerHTML='Start Date Should Not be Prior to Current Date';
			document.add_task_form_gps.atfg_start_date.select();
			return false;
		}
	}
	
	if(document.add_task_form_gps.atfg_end_date.value == "select finish date" || document.add_task_form_gps.atfg_end_date.value == "")
	{
		document.getElementById('div_addtask_gps_error').innerHTML='Select Finish Date';
		document.add_task_form_gps.atfg_end_date.select();
		return false;
	}
	else
	{
		document.getElementById('div_addtask_gps_error').innerHTML='';
	}
	
	if(document.add_task_form_gps.atfg_start_date.value!='' && document.add_task_form_gps.atfg_end_date.value!='')
	{
		var check_date1 = document.add_task_form_gps.atfg_start_date.value.split('-');
		var now_date1 = new Date(check_date1[0],check_date1[1]-1,check_date1[2]);
		
		var check_date2 = document.add_task_form_gps.atfg_end_date.value.split('-');
		var now_date2 = new Date(check_date2[0],check_date2[1]-1,check_date2[2]);
		
		var diff_date = now_date2 - now_date1;
		if(diff_date < 0)
		{
			document.getElementById('div_addtask_gps_error').innerHTML='Finish Date Should Not be Prior to Start Date';
			document.add_task_form_gps.atfg_end_date.select();
			return false;
		}
	}
	
	if(document.add_task_form_gps.atfg_driver_id.value == 0)
	{
		document.getElementById('div_addtask_gps_error').innerHTML='Select Driver';
		document.add_task_form_gps.atfg_driver_id.focus();
		return false;		
	}
	else
	{
		document.getElementById('div_addtask_gps_error').innerHTML='';
	}
	
	if(document.add_task_form_gps.atfg_vehicle_id.value == 0)
	{
		document.getElementById('div_addtask_gps_error').innerHTML='Select Vehicle';
		document.add_task_form_gps.atfg_vehicle_id.focus();
		return false;		
	}
	else
	{
		document.getElementById('div_addtask_gps_error').innerHTML='';
	}
	
	if(document.getElementById('geo_radio_no').checked == true)
	{
		if(document.add_task_form_gps.atfg_starting_point.value=="")
		{
			document.getElementById('div_addtask_gps_error').innerHTML='Starting Point Required';
			document.add_task_form_gps.atfg_starting_point.focus();
			return false;
		}
		else if(!location_name.test(document.add_task_form_gps.atfg_starting_point.value))
		{
			document.getElementById('div_addtask_gps_error').innerHTML='Enter Valid Starting Point';
			document.add_task_form_gps.atfg_starting_point.select();
			return false;
		}
		else
		{
			document.getElementById('div_addtask_gps_error').innerHTML='';
		}
		
		if(document.add_task_form_gps.atfg_ending_point.value=="")
		{
			document.getElementById('div_addtask_gps_error').innerHTML='Ending Point Required';
			document.add_task_form_gps.atfg_ending_point.focus();
			return false;
		}
		else if(!location_name.test(document.add_task_form_gps.atfg_ending_point.value))
		{
			document.getElementById('div_addtask_gps_error').innerHTML='Enter Valid Ending Point';
			document.add_task_form_gps.atfg_ending_point.select();
			return false;
		}
		else
		{
			document.getElementById('div_addtask_gps_error').innerHTML='';
		}
		
		if(document.add_task_form_gps.atfg_starting_point.value == document.add_task_form_gps.atfg_ending_point.value)
		{
			document.getElementById('div_addtask_gps_error').innerHTML='Starting & Ending Points Should Not be Same';
			document.add_task_form_gps.atfg_ending_point.select();
			return false;
		}
		else
		{
			document.getElementById('div_addtask_gps_error').innerHTML='';
		}
	}
	else if(document.getElementById('geo_radio_yes').checked == true)
	{
		if(document.add_task_form_gps.txtGeoValue.value == "")
		{
			document.getElementById('div_addtask_gps_error').innerHTML='Select Limit Area';
			document.add_task_form_gps.txtGeoValue.focus();
			return false;
		}
		else
		{
			document.getElementById('div_addtask_gps_error').innerHTML='';
		}
	}
	
	return true;
}

//EDITING TASK BY GPS USER
function editTaskValidation_gpsForm()
{
	if(document.edit_task_form_gps.etfg_title.value=="")
	{
		document.getElementById('div_edittask_gps_error').innerHTML='Task Name Required';
		document.edit_task_form_gps.etfg_title.focus();
		return false;
	}
	if(secure_ans.test(document.edit_task_form_gps.etfg_title.value))
	{
		document.getElementById('div_edittask_gps_error').innerHTML='Task Name Should Not have Only Spaces';
		document.edit_task_form_gps.etfg_title.select();
		return false;
	}
	else
	{
		document.getElementById('div_edittask_gps_error').innerHTML='';
	}
	
	if(document.edit_task_form_gps.etfg_start_date.value == "select start date" || document.edit_task_form_gps.etfg_start_date.value == "")
	{
		document.getElementById('div_edittask_gps_error').innerHTML='Select Start Date';
		document.edit_task_form_gps.etfg_start_date.select();
		return false;
	}
	else
	{
		document.getElementById('div_edittask_gps_error').innerHTML='';
	}
	
	if(document.edit_task_form_gps.etfg_end_date.value == "select finish date" || document.edit_task_form_gps.etfg_end_date.value == "")
	{
		document.getElementById('div_edittask_gps_error').innerHTML='Select End Date';
		document.edit_task_form_gps.etfg_end_date.select();
		return false;
	}
	else
	{
		document.getElementById('div_edittask_gps_error').innerHTML='';
	}
	
	if(document.edit_task_form_gps.etfg_start_date.value!='' && document.edit_task_form_gps.etfg_end_date.value!='')
	{
		var check_date1 = document.edit_task_form_gps.etfg_start_date.value.split('-');
		var now_date1 = new Date(check_date1[0],check_date1[1]-1,check_date1[2]);
		
		var check_date2 = document.edit_task_form_gps.etfg_end_date.value.split('-');
		var now_date2 = new Date(check_date2[0],check_date2[1]-1,check_date2[2]);
		
		var diff_date = now_date2 - now_date1;
		if(diff_date < 0)
		{
			document.getElementById('div_edittask_gps_error').innerHTML='End Date Should Not be Prior to Start Date';
			document.edit_task_form_gps.etfg_end_date.select();
			return false;
		}
	}
	
	if(document.edit_task_form_gps.etfg_driver_id.value == 0)
	{
		document.getElementById('div_edittask_gps_error').innerHTML='Select Driver';
		document.edit_task_form_gps.etfg_driver_id.focus();
		return false;		
	}
	else
	{
		document.getElementById('div_edittask_gps_error').innerHTML='';
	}
	
	if(document.edit_task_form_gps.etfg_vehicle_id.value == 0)
	{
		document.getElementById('div_edittask_gps_error').innerHTML='Select Vehicle';
		document.edit_task_form_gps.etfg_vehicle_id.focus();
		return false;		
	}
	else
	{
		document.getElementById('div_edittask_gps_error').innerHTML='';
	}
	
	if(document.getElementById('geo_radio_no').checked == true)
	{
		if(document.edit_task_form_gps.etfg_starting_point.value=="")
		{
			document.getElementById('div_edittask_gps_error').innerHTML='Starting Point Required';
			document.edit_task_form_gps.etfg_starting_point.focus();
			return false;
		}
		else if(!location_name.test(document.edit_task_form_gps.etfg_starting_point.value))
		{
			document.getElementById('div_edittask_gps_error').innerHTML='Enter Valid Starting Point';
			document.edit_task_form_gps.etfg_starting_point.select();
			return false;
		}
		else
		{
			document.getElementById('div_edittask_gps_error').innerHTML='';
		}
		
		if(document.edit_task_form_gps.etfg_ending_point.value=="")
		{
			document.getElementById('div_edittask_gps_error').innerHTML='Ending Point Required';
			document.edit_task_form_gps.etfg_ending_point.focus();
			return false;
		}
		else if(!location_name.test(document.edit_task_form_gps.etfg_ending_point.value))
		{
			document.getElementById('div_edittask_gps_error').innerHTML='Enter Valid Ending Point';
			document.edit_task_form_gps.etfg_ending_point.select();
			return false;
		}
		else
		{
			document.getElementById('div_edittask_gps_error').innerHTML='';
		}
		
		if(document.edit_task_form_gps.etfg_starting_point.value == document.edit_task_form_gps.etfg_ending_point.value)
		{
			document.getElementById('div_edittask_gps_error').innerHTML='Starting & Ending Points Should Not be Same';
			document.edit_task_form_gps.etfg_ending_point.select();
			return false;
		}
		else
		{
			document.getElementById('div_edittask_gps_error').innerHTML='';
		}
	}
	else if(document.getElementById('geo_radio_yes').checked == true)
	{
		if(document.edit_task_form_gps.txtGeoValue.value == "")
		{
			document.getElementById('div_edittask_gps_error').innerHTML='Select Geo Location';
			document.edit_task_form_gps.txtGeoValue.focus();
			return false;
		}
		else
		{
			document.getElementById('div_edittask_gps_error').innerHTML='';
		}
	}
	
	return true;
}


//CLEARING ERROR MSGS IN ADDING TASK BY ACCOUNT MANAGER
function clearAddTaskDivs_gpsForm()
{
	document.getElementById('div_addtask_gps_error').innerHTML='&nbsp;';
	document.add_task_form_gps.start_point_button.disabled = true;
	document.add_task_form_gps.start_point_checkbox.disabled = true;
	document.add_task_form_gps.start_point_checkbox.checked = false;
	document.add_task_form_gps.start_point_select.disabled = true;
	document.add_task_form_gps.start_point_map.disabled = false;
	document.add_task_form_gps.atfg_starting_point.disabled = false;
	document.add_task_form_gps.atfg_starting_point.value = "";
	
	document.add_task_form_gps.end_point_map.disabled = false;
	document.add_task_form_gps.atfg_ending_point.disabled = false; 
	document.add_task_form_gps.atfg_ending_point.value = "";
	document.add_task_form_gps.txtGeoValue.value = "";
	document.getElementById('map_load_symbol').innerHTML = "";
	document.getElementById('driver_name').innerHTML='<select name="name" size="1" disabled="disabled" class="list_boxes"></select>';
	document.getElementById('vehicle_name').innerHTML='<select name="name" size="1" disabled="disabled" class="list_boxes"></select>';
}

//EDIT TASK
function editTask_gps(task_id)
{
	document.edit_task_gps.task_id.value = task_id;
	document.edit_task_gps.submit();
}

//VIEW TASK
function viewTask_gps(task_id)
{
	window.open("viewTaskDetails.php?task_id="+task_id,"taskDetails","resizable=1,width=775,height=500,scrollbars=1");
}

//EDIT DRIVER
function editDriver_gps(driver_id)
{
	document.edit_driver_gps.driver_id.value = driver_id;
	document.edit_driver_gps.submit();
}

//VIEW DRIVER
function viewDriver_gps(driver_id)
{
	window.open("viewDriverGPSDetails.php?driver_id="+driver_id,"driverDetails","resizable=1,width=775,height=500,scrollbars=1");
}

//ADD DRIVER GPS VALIDATION
function addDriverGPSValidation()
{
	var empname=/^[a-zA-Z][a-zA-Z ]*$/;
	var pinphnum=/^[0-9]*$/;

//Drivers Id
	if(document.add_driver_form_gps.txtGPSDriverId.value=="")
	{
		document.getElementById('driGPS').innerHTML='Driver Id Required';
		document.add_driver_form_gps.txtGPSDriverId.focus();
		return false;
	}
  	else if (document.add_driver_form_gps.txtGPSDriverId.value.indexOf(' ') > -1) 
	{
		document.getElementById('driGPS').innerHTML='Spaces Not Allowed in Driver ID';
		document.add_driver_form_gps.txtGPSDriverId.focus();
		return false;
	}
	else
	{
		document.getElementById('driGPS').innerHTML='';
	}
	
//Drivers License
	if(document.add_driver_form_gps.txtGPSDriverLicense.value=="")
	{
		document.getElementById('driGPS').innerHTML='Driver Licence Required';
		document.add_driver_form_gps.txtGPSDriverLicense.focus();
		return false;
	}
  	else if(document.add_driver_form_gps.txtGPSDriverLicense.value.indexOf(' ') > -1) 
	{
		document.getElementById('driGPS').innerHTML='Spaces Not Allowed in Driver Licence';
		document.add_driver_form_gps.txtGPSDriverLicense.select();
		return false;
	}
	else if(!license.test(document.add_driver_form_gps.txtGPSDriverLicense.value))
	{
		document.getElementById('driGPS').innerHTML='Alphabets & Numbers Only Allowed in Driver Licence';
		document.add_driver_form_gps.txtGPSDriverLicense.select();
		return false;
	}
	else
	{
		document.getElementById('driGPS').innerHTML='';
	}
	
//First Name  
  	if(document.add_driver_form_gps.txtGPSDriverFirstName.value=="")
	{
		document.getElementById('driGPS').innerHTML='First Name Required';
		document.add_driver_form_gps.txtGPSDriverFirstName.focus();
		return false;
	}
  	else if (document.add_driver_form_gps.txtGPSDriverFirstName.value.indexOf(' ') > -1) 
	{
		document.getElementById('driGPS').innerHTML='Spaces Not Allowed in First Name';
		document.add_driver_form_gps.txtGPSDriverFirstName.focus();
		return false;
	}
	else if (!empname.test(document.add_driver_form_gps.txtGPSDriverFirstName.value)) 
	{
		document.getElementById('driGPS').innerHTML='Alphabets Only Allowed in First Name';
		document.add_driver_form_gps.txtGPSDriverFirstName.focus();
		return false;	
	}
	else
	{
		document.getElementById('driGPS').innerHTML='';
	}
//Last Name	

	if(document.add_driver_form_gps.txtGPSDriverLastName.value=="")
	{
		document.getElementById('driGPS').innerHTML='Last Name Required';
		document.add_driver_form_gps.txtGPSDriverLastName.focus();
		return false;
	}
	if (document.add_driver_form_gps.txtGPSDriverLastName.value.indexOf(' ') > -1) 
	{
		document.getElementById('driGPS').innerHTML='Spaces Not Allowed in Last Name';
		document.add_driver_form_gps.txtGPSDriverLastName.focus();
		return false;
	}
	if (!empname.test(document.add_driver_form_gps.txtGPSDriverLastName.value)) 
	{
		document.getElementById('driGPS').innerHTML='Alphabets Only Allowed in Last Name';
		document.add_driver_form_gps.txtGPSDriverLastName.focus();
		return false;	
	}
	else
	{
		document.getElementById('driGPS').innerHTML='';
	}

//Date of birth
if(document.add_driver_form_gps.txtGPSDriverDOB.value=="" || document.add_driver_form_gps.txtGPSDriverDOB.value=="Select a date here")
	{
		document.getElementById('driGPS').innerHTML='Choose Date Of Birth';
		document.add_driver_form_gps.txtGPSDriverDOB.focus();
		return false;
	}
	if(document.add_driver_form_gps.txtGPSDriverDOB.value!='')
	{
		var check_date1 = document.add_driver_form_gps.txtDriverCurDate.value.split('-');
		var now_date1 = new Date(check_date1[0],check_date1[1]-1,check_date1[2]);
		
		var check_date2 = document.add_driver_form_gps.txtGPSDriverDOB.value.split('-');
		var now_date2 = new Date(check_date2[0],check_date2[1]-1,check_date2[2]);
		
		var diff_date = now_date2 - now_date1;
		if(diff_date > 0)
		{
			document.getElementById('driGPS').innerHTML='Date of Birth Should Not be Prior to Current Date';
			document.add_driver_form_gps.txtGPSDriverDOB.select();
			return false;
		}
	}
	else
	{
		document.getElementById('driGPS').innerHTML='';
	}

//contact	
		if(document.add_driver_form_gps.txtGPSDriverPhone.value=='')
		{
			document.getElementById('driGPS').innerHTML='Contact No. Required';
			document.add_driver_form_gps.txtGPSDriverPhone.focus();
			return false;
		}
		else if (document.add_driver_form_gps.txtGPSDriverPhone.value.indexOf(' ') > -1)
		{
			document.getElementById('driGPS').innerHTML='Spaces Not Allowed in Contact No.';
			document.add_driver_form_gps.txtGPSDriverPhone.focus();
			return false;
		}
	
	   else if(document.add_driver_form_gps.txtGPSDriverPhone.value.charAt(0)==0 && 
		 document.add_driver_form_gps.txtGPSDriverPhone.value.charAt(1)==0)
		{
			
			document.getElementById('driGPS').innerHTML='Invalid Contact No.';
			document.add_driver_form_gps.txtGPSDriverPhone.focus();
			return false;
		}
		else if(!pinphnum.test(document.add_driver_form_gps.txtGPSDriverPhone.value))
		{
			document.getElementById('driGPS').innerHTML='Invalid Contact No.';
			document.add_driver_form_gps.txtGPSDriverPhone.focus();
			return false;
		}
		else if( document.add_driver_form_gps.txtGPSDriverPhone.value.length <10 || document.add_driver_form_gps.txtGPSDriverPhone.value.length >15 )
		{
			document.getElementById('driGPS').innerHTML='Contact No. Should be 10 to 15 digits';
			document.add_driver_form_gps.txtGPSDriverPhone.focus();
			return false;
		}
		else
		{
			document.getElementById('driGPS').innerHTML='';
		}
		
//Country
	
	
	if(document.add_driver_form_gps.txtGPSDriverCountry.selectedIndex==0)
	{
		document.getElementById('driGPS').innerHTML='Country Required';
		document.add_driver_form_gps.txtGPSDriverCountry.focus();
		return false;
	}
	
	else
	{
		document.getElementById('driGPS').innerHTML='';
	}
//State	
	if(document.add_driver_form_gps.txtGPSDriverState.selectedIndex==0)
	{
		document.getElementById('driGPS').innerHTML='State Required';
		document.add_driver_form_gps.txtGPSDriverState.focus();
		return false;
	}
	else
	{
		document.getElementById('driGPS').innerHTML='';
	}
//City	
	if(document.add_driver_form_gps.txtGPSDriverCity.selectedIndex==0)
	{
		document.getElementById('driGPS').innerHTML='City Required';
		document.add_driver_form_gps.txtGPSDriverCity.focus();
		return false;
	}
	
	else
	{
		document.getElementById('driGPS').innerHTML='';
	}
//Pincode	
	if(document.add_driver_form_gps.txtGPSDriverPincode.value == '')
	{
			document.getElementById('driGPS').innerHTML='Pincode Required';
			document.add_driver_form_gps.txtGPSDriverPincode.focus();
			return false;
	}
	if (document.add_driver_form_gps.txtGPSDriverPincode.value.indexOf(' ') > -1) {
		document.getElementById('driGPS').innerHTML='Spaces Not Allowed in Pincode';
		document.add_driver_form_gps.txtGPSDriverPincode.focus();
		return false;
	}
	
	if(!pinphnum.test(document.add_driver_form_gps.txtGPSDriverPincode.value))
	{
		document.getElementById('driGPS').innerHTML='Invalid Pincode';
		document.add_driver_form_gps.txtGPSDriverPincode.focus();
		return false;
	}
	else
	{
		if( verifyPincode('txtGPSDriverState','txtGPSDriverPincode','driGPS') == 0)
		{	
		    document.add_driver_form_gps.txtGPSDriverPincode.focus();
			return false;
		}
		else
		{
			document.getElementById('driGPS').innerHTML='';
		}
	}
	
//Date Of Joining
if(document.add_driver_form_gps.txtGPSDriverDOJ.value=="" || document.add_driver_form_gps.txtGPSDriverDOJ.value=="Select a date here")
	{
		document.getElementById('driGPS').innerHTML='Choose Date Of Joining';
		document.add_driver_form_gps.txtGPSDriverDOJ.focus();
		return false;
	}
	if(document.add_driver_form_gps.txtGPSDriverDOJ.value!='')
	{
		var check_date1 = document.add_driver_form_gps.txtGPSDriverDOJ.value.split('-');
		var now_date1 = new Date(check_date1[0],check_date1[1]-1,check_date1[2]);
		
		var check_date2 = document.add_driver_form_gps.txtGPSDriverDOB.value.split('-');
		var now_date2 = new Date(check_date2[0],check_date2[1]-1,check_date2[2]);
		
		var diff_date = now_date2 - now_date1;
		if(diff_date > 0)
		{
			document.getElementById('driGPS').innerHTML='Date of Joining Should Not be Prior to Date of Birth';
			document.add_driver_form_gps.txtGPSDriverDOJ.focus();
			return false;
		}
	}
	else
	{
		document.getElementById('driGPS').innerHTML='';
	}



//Description

	if(document.add_driver_form_gps.txtGPSDriverDesc.value)
	{
			var expatinpar=/^[  ]/;
			if((document.add_driver_form_gps.txtGPSDriverDesc.value.match(expatinpar)))
			{
				document.getElementById('driGPS').innerHTML='Invalid Description';
				document.add_driver_form_gps.txtGPSDriverDesc.focus();
				return false;		
			}
			else
			{
				document.getElementById('driGPS').innerHTML='';
			}
	}
	else
	{
		document.getElementById('driGPS').innerHTML='';
	}

//address

	if(document.add_driver_form_gps.txtGPSDriverAdd.value=="")
	{
		document.getElementById('driGPS').innerHTML='Address Required';
		document.add_driver_form_gps.txtGPSDriverAdd.focus();
		return false;
	}
	else
	{
		var expatinpar=/^[  ]/;
		if((document.add_driver_form_gps.txtGPSDriverAdd.value.match(expatinpar)))
		{
			document.getElementById('driGPS').innerHTML='Invalid Address';
			document.add_driver_form_gps.txtGPSDriverAdd.select()
            document.add_driver_form_gps.txtGPSDriverAdd.focus();
            return false;		
		}
		else
		{
			document.getElementById('driGPS').innerHTML='';
		}
	}





var OK = new Array ('.jpg', '.gif', '.jpeg', '.jpe', '.JPG', '.GIF', '.JPEG', '.JPE', '.png', '.PNG');

var theFile = document.add_driver_form_gps.dci_image.value; // i.e. the file name passed to the function	
	var fileOK = 0;
		for (i = 0; i < OK.length; i++) 
		{
			if (theFile.indexOf(OK[i]) != -1)
			{
			fileOK = 1; // one of the file extensions found
			}
		}
	/*if(document.add_driver_form_gps.dci_image.value == '')
	{
		document.getElementById('driGPS').innerHTML='Upload the Image';
		document.add_driver_form_gps.dci_image.focus();
		return false;
	}*/
	
	if(document.add_driver_form_gps.dci_image.value != '')
	{
		if (fileOK !=1) 
		{
			document.getElementById('driGPS').innerHTML='Only GIF/JPEG/PNG files supported';
			document.add_driver_form_gps.dci_image.focus();
			return false;
		}
		else
		{
			document.getElementById('driGPS').innerHTML='';
		}
	}
	
return true;
}

function AddDriverGPSReset()
{
	document.getElementById('driGPS').innerHTML='&nbsp;';
}

function editDriverGPSValidation()
{
	

var empname=/^[a-zA-Z][a-zA-Z ]*$/;
var pinphnum=/^[0-9]*$/;

//Drivers License


	if(document.edit_driver_form_gps.txteditGPSDriverLicense.value=="")
	{
		document.getElementById('editdriGPS1').innerHTML='Driver Licence Required';
		document.edit_driver_form_gps.txteditGPSDriverLicense.focus();
		return false;
	}
  	else if (document.edit_driver_form_gps.txteditGPSDriverLicense.value.indexOf(' ') > -1) 
	{
		document.getElementById('editdriGPS1').innerHTML='Spaces Not Allowed in Licence';
		document.edit_driver_form_gps.txteditGPSDriverLicense.select();
		return false;
	}
	else
	{
		document.getElementById('editdriGPS1').innerHTML='';
	}
	
//First Name  
  if(document.edit_driver_form_gps.txteditGPSDriverFirstName.value=="")
	{
		document.getElementById('editdriGPS1').innerHTML='First Name Required';
		document.edit_driver_form_gps.txteditGPSDriverFirstName.focus();
		return false;
	}
  else if (document.edit_driver_form_gps.txteditGPSDriverFirstName.value.indexOf(' ') > -1) 
	{
		document.getElementById('editdriGPS1').innerHTML='Spaces Not Allowed in First Name';
		document.edit_driver_form_gps.txteditGPSDriverFirstName.focus();
		document.edit_driver_form_gps.txteditGPSDriverFirstName.select();
		return false;
	}
	else if (!empname.test(document.edit_driver_form_gps.txteditGPSDriverFirstName.value)) 
	{
		document.getElementById('editdriGPS1').innerHTML='Alphabets Only Allowed in First Name';
		document.edit_driver_form_gps.txteditGPSDriverFirstName.focus();
		document.edit_driver_form_gps.txteditGPSDriverFirstName.select();
		return false;	
	}
	else
	{
		document.getElementById('editdriGPS1').innerHTML='';
	}
//Last Name	

	if(document.edit_driver_form_gps.txteditGPSDriverLastName.value=="")
	{
		document.getElementById('editdriGPS1').innerHTML='Last Name Required';
		document.edit_driver_form_gps.txteditGPSDriverLastName.focus();
		return false;
	}
	if (document.edit_driver_form_gps.txteditGPSDriverLastName.value.indexOf(' ') > -1) 
	{
		document.getElementById('editdriGPS1').innerHTML='Spaces Not Allowed in Last Name';
		document.edit_driver_form_gps.txteditGPSDriverLastName.focus();
		document.edit_driver_form_gps.txteditGPSDriverLastName.select();
		return false;
	}
	if (!empname.test(document.edit_driver_form_gps.txteditGPSDriverLastName.value)) 
	{
		document.getElementById('editdriGPS1').innerHTML='Alphabets Only Allowed in Last Name';
		document.edit_driver_form_gps.txteditGPSDriverLastName.focus();
		document.edit_driver_form_gps.txteditGPSDriverLastName.select();
		return false;	
	}
	else
	{
		document.getElementById('editdriGPS1').innerHTML='';
	}

//Date of birth
if(document.edit_driver_form_gps.txteditGPSDriverDOB.value=="" || document.edit_driver_form_gps.txteditGPSDriverDOB.value=="Select a date here")
	{
		document.getElementById('editdriGPS1').innerHTML='Choose Date Of Birth';
		document.edit_driver_form_gps.txteditGPSDriverDOB.focus();
		return false;
	}
	else
	{
		document.getElementById('editdriGPS1').innerHTML='';
	}

//contact	
	if(document.edit_driver_form_gps.txteditGPSDriverPhone.value=='')
	{
		document.getElementById('editdriGPS1').innerHTML='Contact No. Required';
		document.edit_driver_form_gps.txteditGPSDriverPhone.select();
		document.edit_driver_form_gps.txteditGPSDriverPhone.focus();
		return false;
	}
	else if (document.edit_driver_form_gps.txteditGPSDriverPhone.value.indexOf(' ') > -1)
	{
		document.getElementById('editdriGPS1').innerHTML='Spaces Not Allowed in Contact No.';
		document.edit_driver_form_gps.txteditGPSDriverPhone.select();
		document.edit_driver_form_gps.txteditGPSDriverPhone.focus();
		return false;
	}

	else if(document.edit_driver_form_gps.txteditGPSDriverPhone.value.charAt(0)==0 && 
		document.edit_driver_form_gps.txteditGPSDriverPhone.value.charAt(1)==0)
	{
		
		document.getElementById('editdriGPS1').innerHTML='Invalid Contact No.';
		document.edit_driver_form_gps.txteditGPSDriverPhone.focus();
		return false;
	}
	else if(!pinphnum.test(document.edit_driver_form_gps.txteditGPSDriverPhone.value))
	{
		document.getElementById('editdriGPS1').innerHTML='Invalid Contact No.';
		document.edit_driver_form_gps.txteditGPSDriverPhone.select();
		document.edit_driver_form_gps.txteditGPSDriverPhone.focus();
		return false;
	}
	else if( document.edit_driver_form_gps.txteditGPSDriverPhone.value.length <10 || document.edit_driver_form_gps.txteditGPSDriverPhone.value.length >15 )
	{
		document.getElementById('editdriGPS1').innerHTML='Contact No. Should be 10 to 15 digits';
		document.edit_driver_form_gps.txteditGPSDriverPhone.select();
		document.edit_driver_form_gps.txteditGPSDriverPhone.focus();
		return false;
	}
	else
	{
		document.getElementById('editdriGPS1').innerHTML='';
	}
		
//Country
	
	
	if(document.edit_driver_form_gps.txtGPSDriverCountry.selectedIndex==0)
	{
		document.getElementById('editdriGPS1').innerHTML='Country Required';
		document.edit_driver_form_gps.txtGPSDriverCountry.focus();
		return false;
	}
	
	else
	{
		document.getElementById('editdriGPS1').innerHTML='';
	}
//State	
	if(document.edit_driver_form_gps.txtGPSDriverState.selectedIndex==0)
	{
		document.getElementById('editdriGPS1').innerHTML='State Required';
		document.edit_driver_form_gps.txtGPSDriverState.focus();
		return false;
	}
	
	else
	{
		document.getElementById('editdriGPS1').innerHTML='';
	}
//City	
	if(document.edit_driver_form_gps.txtGPSDriverCity.selectedIndex==0)
	{
		document.getElementById('editdriGPS1').innerHTML='City Required';
		document.edit_driver_form_gps.txtGPSDriverCity.focus();
		return false;
	}
	
	else
	{
		document.getElementById('editdriGPS1').innerHTML='';
	}
//Pincode	
	if(document.edit_driver_form_gps.txteditGPSDriverPincode.value == '')
	{
			document.getElementById('editdriGPS1').innerHTML='Pincode Required';
			document.edit_driver_form_gps.txteditGPSDriverPincode.focus();
			return false;
	}
	if (document.edit_driver_form_gps.txteditGPSDriverPincode.value.indexOf(' ') > -1) {
		document.getElementById('editdriGPS1').innerHTML='Spaces Not Allowed in Pincode';
		document.edit_driver_form_gps.txteditGPSDriverPincode.focus();
		return false;
	}
	
	if(!pinphnum.test(document.edit_driver_form_gps.txteditGPSDriverPincode.value))
	{
		document.getElementById('editdriGPS1').innerHTML='Invalid Pincode';
		document.edit_driver_form_gps.txteditGPSDriverPincode.focus();
		return false;
	}
	else
	{
		if( verifyPincode('txtGPSDriverState','txteditGPSDriverPincode','editdriGPS1') == 0)
		{	
		    document.edit_driver_form_gps.txteditGPSDriverPincode.focus();
			return false;
		}
		else
		{
			document.getElementById('editdriGPS1').innerHTML='';
		}
	}
	
//Date Of Joining

	if(document.edit_driver_form_gps.txteditGPSDriverDOJ.value=="" || document.edit_driver_form_gps.txteditGPSDriverDOJ.value=="Select a date here")
	{
		document.getElementById('editdriGPS1').innerHTML='Choose Date Of Joining';
		document.edit_driver_form_gps.txteditGPSDriverDOJ.focus();
		return false;
	}
	if(document.edit_driver_form_gps.txteditGPSDriverDOJ.value!='')
	{
		var check_date1 = document.edit_driver_form_gps.txteditGPSDriverDOJ.value.split('-');
		var now_date1 = new Date(check_date1[0],check_date1[1]-1,check_date1[2]);
		
		var check_date2 = document.edit_driver_form_gps.txteditGPSDriverDOB.value.split('-');
		var now_date2 = new Date(check_date2[0],check_date2[1]-1,check_date2[2]);
		
		var diff_date = now_date2 - now_date1;
		if(diff_date > 0)
		{
			document.getElementById('editdriGPS1').innerHTML='Date of Joining Should Not be Prior to Date of Birth';
			document.edit_driver_form_gps.txteditGPSDriverDOJ.focus();
			return false;
		}
	}
	else
	{
		document.getElementById('editdriGPS1').innerHTML='';
	}


//Description
	if(document.edit_driver_form_gps.txtGPSDriverDesc.value)
	{
		

		//var expatinpar=/^[a-zA-Z0-9._#@][a-zA-Z0-9._#@]/;
		var expatinpar=/^[  ]/;
		if((document.edit_driver_form_gps.txtGPSDriverDesc.value.match(expatinpar)))
		{
			document.getElementById('editdriGPS1').innerHTML='Invalid Description';
            		document.edit_driver_form_gps.txtGPSDriverDesc.focus();
            		return false;		
		}
		else
		{
			document.getElementById('editdriGPS1').innerHTML='';
		}
	}
	else
	{
		document.getElementById('editdriGPS1').innerHTML='';
	}

//address

if(document.edit_driver_form_gps.txteditGPSDriverAdd.value=="")
	{
		document.getElementById('editdriGPS1').innerHTML='Address Required';
		document.edit_driver_form_gps.txteditGPSDriverAdd.focus();
		return false;
	}
	else
	{
		var expatinpar=/^[  ]/;
		if((document.edit_driver_form_gps.txteditGPSDriverAdd.value.match(expatinpar)))
		{
			document.getElementById('editdriGPS1').innerHTML='Invalid Address';
		        document.edit_driver_form_gps.txteditGPSDriverAdd.focus();
            		return false;		
		}
		else
		{
			document.getElementById('editdriGPS1').innerHTML='';
		}
	}

var OK = new Array ('.jpg', '.gif', '.jpeg', '.jpe', '.JPG', '.GIF', '.JPEG', '.JPE', '.png', '.PNG');

var theFile = document.edit_driver_form_gps.editdci_image.value; // i.e. the file name passed to the function	
	var fileOK = 0;
		for (i = 0; i < OK.length; i++) 
		{
			if (theFile.indexOf(OK[i]) != -1)
			{
			fileOK = 1; // one of the file extensions found
			}
		}

	
	if(document.edit_driver_form_gps.editdci_image.value != '')
	{
		if (fileOK !=1) 
		{
			document.getElementById('editdriGPS1').innerHTML='Only GIF/JPEG/PNG files supported';
			document.edit_driver_form_gps.editdci_image.focus();
			return false;
		}
		else
		{
			document.getElementById('editdriGPS1').innerHTML='';
		}
	}

return true;
	
}

function delDriverGPSinfo(driver_delId)
{
	t=confirm("Are You sure to delete this account ?");
	if(t)
	{	
	document.getElementById('driver_delId').value=driver_delId;
	document.del_DriverGPS_details.submit();
	}
}

//EDIT DRIVER
function editVehicle_gps(vehicle_id)
{
	document.edit_vehicle_gps.vehicle_id.value = vehicle_id;
	document.edit_vehicle_gps.submit();
}

//VIEW DRIVER
function viewVehicle_gps(vehicle_id)
{
	window.open("viewVehicleGPSDetails.php?vehicle_id="+vehicle_id,"vehicleDetails","resizable=1,width=775,height=500,scrollbars=1");
}


function delVehicleGPSinfo(vehicle_delId)
{
	t=confirm("Are You sure to delete this account ?");
	if(t)
	{	
	document.getElementById('vehicle_delId').value=vehicle_delId;
	document.del_VehicleGPS_details.submit();
	}
}

//validation for vehicle 

function addVehicleGPSValidation()
{
	var empname=/^[a-zA-Z][a-zA-Z ]*$/;
	var pinphnum=/^[0-9.]*$/;



//Reg No
	if(document.add_vehcile_form_gps.txtGPSVehRegdno.value=="")
	{
		document.getElementById('vehicleGPS').innerHTML='Registration No. Required';
		document.add_vehcile_form_gps.txtGPSVehRegdno.focus();
		return false;
	}
	else if (document.add_vehcile_form_gps.txtGPSVehRegdno.value.indexOf(' ') > -1) 
	{
		document.getElementById('vehicleGPS').innerHTML='Spaces Not Allowed in Registration No.';
		document.add_vehcile_form_gps.txtGPSVehRegdno.focus();
		return false;
	}
	else
	{
		document.getElementById('vehicleGPS').innerHTML='';
	}
//Reg Date


	if(document.add_vehcile_form_gps.txtGPSVehRegdDate.value=="" || document.add_vehcile_form_gps.txtGPSVehRegdDate.value=="Select a date here")
	{
		document.getElementById('vehicleGPS').innerHTML='Choose Date Of Registration';
		document.add_vehcile_form_gps.txtGPSVehRegdDate.focus();
		return false;
	}
	else if(document.add_vehcile_form_gps.txtGPSVehRegdDate.value!='')
	{
		var check_date1 = document.add_vehcile_form_gps.txtVehicleCurDate.value.split('-');
		var now_date1 = new Date(check_date1[0],check_date1[1]-1,check_date1[2]);
		
		var check_date2 = document.add_vehcile_form_gps.txtGPSVehRegdDate.value.split('-');
		var now_date2 = new Date(check_date2[0],check_date2[1]-1,check_date2[2]);
		
		var diff_date = now_date2 - now_date1;
		if(diff_date > 0)
		{
			document.getElementById('vehicleGPS').innerHTML='Registration Date Should Not be Prior to Current Date';
			document.getElementById('vehicleGPS').style.display = '';
			document.add_vehcile_form_gps.txtGPSVehRegdDate.select();
			return false;
		}
	}
	else
	{
		document.getElementById('vehicleGPS').innerHTML='';
	}
//Owner Name	

	if(document.add_vehcile_form_gps.txtGPSVehOwnerName.value=="")
	{
		document.getElementById('vehicleGPS').innerHTML='Owner Name Required';
		document.add_vehcile_form_gps.txtGPSVehOwnerName.focus();
		return false;
	}
	else if (!empname.test(document.add_vehcile_form_gps.txtGPSVehOwnerName.value)) 
	{
		document.getElementById('vehicleGPS').innerHTML='Enter Only Alphabets in Owner Name';
		document.add_vehcile_form_gps.txtGPSVehOwnerName.focus();
		return false;	
	}
	else
	{
		document.getElementById('vehicleGPS').innerHTML='';
	}


//Vehicle's Type
	if(document.add_vehcile_form_gps.txtGPSVehType.selectedIndex==0)
	{
		document.getElementById('vehicleGPS').innerHTML='Type Required';
		document.add_vehcile_form_gps.txtGPSVehType.focus();
		return false;
	}
	else
	{
		document.getElementById('vehicleGPS').innerHTML='';
	}

//Vehicle Name

	if(document.add_vehcile_form_gps.txtGPSVehName.value=="")
	{
		document.getElementById('vehicleGPS').innerHTML='Vehicle Name Required';
		document.add_vehcile_form_gps.txtGPSVehName.focus();
		return false;
	}
	else if (!vehicle_name_js.test(document.add_vehcile_form_gps.txtGPSVehName.value)) 
	{
		document.getElementById('vehicleGPS').innerHTML='Alphabets & Numbers Only Allowed in Vehicle Name';
		document.add_vehcile_form_gps.txtGPSVehName.focus();
		return false;	
	}
	else
	{
		document.getElementById('vehicleGPS').innerHTML='';
	}

//Vehicle Color

	if(document.add_vehcile_form_gps.txtGPSVehColor.value!='')
	{
		if (!empname.test(document.add_vehcile_form_gps.txtGPSVehColor.value)) 
		{
			document.getElementById('vehicleGPS').innerHTML='Alphabets Only Allowed in Vehicle Color';
			document.add_vehcile_form_gps.txtGPSVehColor.focus();
			return false;	
		}
		else
		{
			document.getElementById('vehicleGPS').innerHTML='';
		}
}

//Mileage	
	if(document.add_vehcile_form_gps.txtGPSVehMileage.value)
	{
		if (document.add_vehcile_form_gps.txtGPSVehMileage.value.indexOf(' ') > -1)
		{
			document.getElementById('vehicleGPS').innerHTML='Spaces Not Allowed in Mileage';
			document.add_vehcile_form_gps.txtGPSVehMileage.focus();
			return false;
		}
		else if(!pinphnum.test(document.add_vehcile_form_gps.txtGPSVehMileage.value))
		{
			document.getElementById('vehicleGPS').innerHTML='Invalid Mileage';
			document.add_vehcile_form_gps.txtGPSVehMileage.focus();
			return false;
		}
		else
		{
			document.getElementById('vehicleGPS').innerHTML='';
		}
	}
	
//Fuel Capacity
	if(document.add_vehcile_form_gps.txtGPSVehFuelCap.value)
	{
		if (document.add_vehcile_form_gps.txtGPSVehFuelCap.value.indexOf(' ') > -1)
		{
			document.getElementById('vehicleGPS').innerHTML='Spaces Not Allowed in Fuel Capacity';
			document.add_vehcile_form_gps.txtGPSVehFuelCap.focus();
			return false;
		}
		else if(!pinphnum.test(document.add_vehcile_form_gps.txtGPSVehFuelCap.value))
		{
			document.getElementById('vehicleGPS').innerHTML='Invalid Fuel Capacity';
			document.add_vehcile_form_gps.txtGPSVehFuelCap.focus();
			return false;
		}
		else
		{
			document.getElementById('vehicleGPS').innerHTML='';
		}
	}



//model number should not be greater than registration no.
var regno=document.add_vehcile_form_gps.txtGPSVehRegdDate.value;
var reg_no=regno.substring(0,4);

var model_no=document.add_vehcile_form_gps.txtGPSVehPolicyModel.value;

	if(model_no > reg_no)
	{
		document.getElementById('vehicleGPS').innerHTML='Registration Date should be prior to Year of Model';
    		document.add_vehcile_form_gps.txtGPSVehPolicyModel.focus();
      		return false;
	}
	else
	{
		document.getElementById('vehicleGPS').innerHTML='';
	}

//Description

	if(document.add_vehcile_form_gps.txtGPSVehDesc.value)
	{
		//var expatinpar=/^[a-zA-Z0-9._#@][a-zA-Z0-9._#@]/;
		var expatinpar=/^[  ]/;
		if((document.add_vehcile_form_gps.txtGPSVehDesc.value.match(expatinpar)))
		{
			document.getElementById('vehicleGPS').innerHTML='Invalid Description';
            		document.add_vehcile_form_gps.txtGPSVehDesc.focus();
           		return false;		
		}
		else
		{
			document.getElementById('vehicleGPS').innerHTML='';
		}
	}
	else
	{
		document.getElementById('vehicleGPS').innerHTML='';
	}

//for image

var OK = new Array ('.jpg', '.gif', '.jpeg', '.jpe', '.JPG', '.GIF', '.JPEG', '.JPE', '.png', '.PNG');

var theFile = document.add_vehcile_form_gps.vci_image.value; // i.e. the file name passed to the function	
	var fileOK = 0;
		for (i = 0; i < OK.length; i++) 
		{
			if (theFile.indexOf(OK[i]) != -1)
			{
			fileOK = 1; // one of the file extensions found
			}
		}
	/*if(document.add_driver_form_gps.dci_image.value == '')
	{
		document.getElementById('driGPS').innerHTML='Upload the Image';
		document.add_driver_form_gps.dci_image.focus();
		return false;
	}*/
	
	if(document.add_vehcile_form_gps.vci_image.value != '')
	{
		if (fileOK !=1) 
		{
			document.getElementById('vehicleGPS').innerHTML='Only GIF/JPEG/PNG files supported';
			document.add_vehcile_form_gps.vci_image.focus();
			return false;
		}
		else
		{
			document.getElementById('vehicleGPS').innerHTML='';
		}
	}
return true;

}

//validation for edit vehicle 
//validation for edit vehicle 

function editVehicleGPSValidation()
{
	var empname=/^[a-zA-Z][a-zA-Z ]*$/;
	var pinphnum=/^[0-9.]*$/;



//Reg No
	if(document.edit_vehcile_form_gps.txteditGPSVehRegdno.value=="")
	{
		document.getElementById('editvehicleGPS').innerHTML='Registration No. Required';
		document.edit_vehcile_form_gps.txteditGPSVehRegdno.focus();
		return false;
	}
	else if (document.edit_vehcile_form_gps.txteditGPSVehRegdno.value.indexOf(' ') > -1) 
	{
		document.getElementById('editvehicleGPS').innerHTML='Spaces Not Allowed in Registration No.';
		document.edit_vehcile_form_gps.txteditGPSVehRegdno.focus();
		return false;
	}
	else
	{
		document.getElementById('editvehicleGPS').innerHTML='';
	}
//Reg Date


	if(document.edit_vehcile_form_gps.txteditGPSVehRegdDate.value=="" || document.edit_vehcile_form_gps.txteditGPSVehRegdDate.value=="Select a date here")
	{
		document.getElementById('editvehicleGPS').innerHTML='Choose Date Of Registration';
		document.edit_vehcile_form_gps.txteditGPSVehRegdDate.focus();
		return false;
	}
	else if(document.edit_vehcile_form_gps.txteditGPSVehRegdDate.value!='')
	{
		var check_date1 = document.edit_vehcile_form_gps.txtVehicleCurDate.value.split('-');
		var now_date1 = new Date(check_date1[0],check_date1[1]-1,check_date1[2]);
		
		var check_date2 = document.edit_vehcile_form_gps.txteditGPSVehRegdDate.value.split('-');
		var now_date2 = new Date(check_date2[0],check_date2[1]-1,check_date2[2]);
		
		var diff_date = now_date2 - now_date1;
		if(diff_date > 0)
		{
			document.getElementById('editvehicleGPS').innerHTML='Registration Date Should Not be Prior to Current Date';
			document.getElementById('editvehicleGPS').style.display = '';
			document.edit_vehcile_form_gps.txteditGPSVehRegdDate.select();
			return false;
		}
	}
	else
	{
		document.getElementById('editvehicleGPS').innerHTML='';
	}
//Owner Name	

	if(document.edit_vehcile_form_gps.txteditGPSVehOwnerName.value=="")
	{
		document.getElementById('editvehicleGPS').innerHTML='Owner Name Required';
		document.edit_vehcile_form_gps.txteditGPSVehOwnerName.focus();
		return false;
	}
	else if (!empname.test(document.edit_vehcile_form_gps.txteditGPSVehOwnerName.value)) 
	{
		document.getElementById('editvehicleGPS').innerHTML='Enter Only Alphabets in Owner Name';
		document.edit_vehcile_form_gps.txteditGPSVehOwnerName.focus();
		return false;	
	}
	else
	{
		document.getElementById('editvehicleGPS').innerHTML='';
	}


//Vehicle's Type
	if(document.edit_vehcile_form_gps.txteditGPSVehType.selectedIndex==0)
	{
		document.getElementById('editvehicleGPS').innerHTML='Type Required';
		document.edit_vehcile_form_gps.txteditGPSVehType.focus();
		return false;
	}
	else
	{
		document.getElementById('editvehicleGPS').innerHTML='';
	}

//Vehicle Name

	if(document.edit_vehcile_form_gps.txteditGPSVehName.value=="")
	{
		document.getElementById('editvehicleGPS').innerHTML='Vehicle Name Required';
		document.edit_vehcile_form_gps.txteditGPSVehName.focus();
		return false;
	}
	else if (!vehicle_name_js.test(document.edit_vehcile_form_gps.txteditGPSVehName.value)) 
	{
		document.getElementById('editvehicleGPS').innerHTML='Alphabets & Numbers Only Allowed in Vehicle Name';
		document.edit_vehcile_form_gps.txteditGPSVehName.focus();
		document.edit_vehcile_form_gps.txteditGPSVehName.select();
		return false;	
	}
	else
	{
		document.getElementById('editvehicleGPS').innerHTML='';
	}

//Vehicle Color

	if(document.edit_vehcile_form_gps.txteditGPSVehColor.value!='')
	{
		if (!empname.test(document.edit_vehcile_form_gps.txteditGPSVehColor.value)) 
		{
			document.getElementById('editvehicleGPS').innerHTML='Alphabets Only Allowed in Vehicle Color';
			document.edit_vehcile_form_gps.txteditGPSVehColor.focus();
			document.edit_vehcile_form_gps.txteditGPSVehColor.select();
			return false;	
		}
		else
		{
			document.getElementById('editvehicleGPS').innerHTML='';
		}
}

//Mileage	
	if(document.edit_vehcile_form_gps.txteditGPSVehMileage.value)
	{
		if (document.edit_vehcile_form_gps.txteditGPSVehMileage.value.indexOf(' ') > -1)
		{
			document.getElementById('editvehicleGPS').innerHTML='Spaces Not Allowed in Mileage';
			document.edit_vehcile_form_gps.txteditGPSVehMileage.select();
			document.edit_vehcile_form_gps.txteditGPSVehMileage.focus();
			return false;
		}
		else if(!pinphnum.test(document.edit_vehcile_form_gps.txteditGPSVehMileage.value))
		{
			document.getElementById('editvehicleGPS').innerHTML='Invalid Mileage';
			document.edit_vehcile_form_gps.txteditGPSVehMileage.select();
			document.edit_vehcile_form_gps.txteditGPSVehMileage.focus();
			return false;
		}
		else
		{
			document.getElementById('editvehicleGPS').innerHTML='';
		}
	}
	
//Fuel Capacity
	if(document.edit_vehcile_form_gps.txteditGPSVehFuelCap.value)
	{
		if (document.edit_vehcile_form_gps.txteditGPSVehFuelCap.value.indexOf(' ') > -1)
		{
			document.getElementById('editvehicleGPS').innerHTML='Spaces Not Allowed in Fuel Capacity';
			document.edit_vehcile_form_gps.txteditGPSVehFuelCap.select();
			document.edit_vehcile_form_gps.txteditGPSVehFuelCap.focus();
			return false;
		}
		else if(!pinphnum.test(document.edit_vehcile_form_gps.txteditGPSVehFuelCap.value))
		{
			document.getElementById('editvehicleGPS').innerHTML='Invalid Fuel Capacity';
			document.edit_vehcile_form_gps.txteditGPSVehFuelCap.select();
			document.edit_vehcile_form_gps.txteditGPSVehFuelCap.focus();
			return false;
		}
		else
		{
			document.getElementById('editvehicleGPS').innerHTML='';
		}
	}



//model number should not be greater than registration no.
var regno=document.edit_vehcile_form_gps.txteditGPSVehRegdDate.value;
var reg_no=regno.substring(0,4);

var model_no=document.edit_vehcile_form_gps.txteditGPSVehModel.value;

	if(model_no > reg_no)
	{
		document.getElementById('editvehicleGPS').innerHTML='Registration Date should be prior to Year of Model';
    		document.edit_vehcile_form_gps.txteditGPSVehModel.focus();
      		return false;
	}
	else
	{
		document.getElementById('editvehicleGPS').innerHTML='';
	}

//Description

	if(document.edit_vehcile_form_gps.txteditGPSVehDesc.value)
	{
		//var expatinpar=/^[a-zA-Z0-9._#@][a-zA-Z0-9._#@]/;
		var expatinpar=/^[  ]/;
		if((document.edit_vehcile_form_gps.txteditGPSVehDesc.value.match(expatinpar)))
		{
			document.getElementById('editvehicleGPS').innerHTML='Invalid Description';
            		document.edit_vehcile_form_gps.txteditGPSVehDesc.focus();
           		return false;		
		}
		else
		{
			document.getElementById('editvehicleGPS').innerHTML='';
		}
	}
	else
	{
		document.getElementById('editvehicleGPS').innerHTML='';
	}

//for image

var OK = new Array ('.jpg', '.gif', '.jpeg', '.jpe', '.JPG', '.GIF', '.JPEG', '.JPE', '.png', '.PNG');

var theFile = document.edit_vehcile_form_gps.editvci_image.value; // i.e. the file name passed to the function	
	var fileOK = 0;
		for (i = 0; i < OK.length; i++) 
		{
			if (theFile.indexOf(OK[i]) != -1)
			{
			fileOK = 1; // one of the file extensions found
			}
		}
	/*if(document.add_driver_form_gps.dci_image.value == '')
	{
		document.getElementById('driGPS').innerHTML='Upload the Image';
		document.add_driver_form_gps.dci_image.focus();
		return false;
	}*/
	
	if(document.edit_vehcile_form_gps.editvci_image.value != '')
	{
		if (fileOK !=1) 
		{
			document.getElementById('editvehicleGPS').innerHTML='Only GIF/JPEG/PNG files supported';
			document.edit_vehcile_form_gps.editvci_image.focus();
			return false;
		}
		else
		{
			document.getElementById('editvehicleGPS').innerHTML='';
		}
	}
return true;

}

function EditVehicleGPSReset()
{
	document.getElementById('editvehicleGPS').innerHTML='';
}

function AddVehicleGPSReset()
{
	document.getElementById('vehicleGPS').innerHTML='&nbsp;';
}

function getImgSize(imgSrc)
{
var newImg = new Image();
newImg.src = imgSrc;
var height = newImg.height;
var width = newImg.width;
alert ('The image size is '+width+'*'+height);
}	


function EditDriverGPSReset()
{
	document.getElementById('editdriGPS1').innerHTML='';
}