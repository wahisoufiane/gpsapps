// JavaScript Document
var ajax1=new sack();

//CHECKING UNIQUE DRIVER ID FOR ADMIN

function uniqueDriverGPSId(driver_id)
{
	ajax1.requestFile = 'ajax_server.php?uniqueDriverGPSId='+driver_id;
	ajax1.onCompletion = function(){executeuniqueDriverGPSId()};
	ajax1.runAJAX();
}

function executeuniqueDriverGPSId()
{
  eval(ajax1.response);
}

function getStatesDriverGPS(country,stateDivId,stateListId,statetabindex,cityDivId,cityListId,citytabindex)
{

	ajax1.requestFile = 'ajax_server.php?GPSCountrydriver='+country+'&stateDivId='+stateDivId+'&stateListId='+stateListId+'&cityDivId='+cityDivId+'&cityListId='+cityListId+'&statetabindex='+statetabindex+'&citytabindex='+citytabindex;
	ajax1.onCompletion = function(){executeStatesGPS()};
	ajax1.runAJAX();

}

function executeStatesGPS()
{
	eval(ajax1.response);
}

function getCitiesDriverGPS(state,cityDivId,cityListId,citytab)
{
	
	ajax1.requestFile = 'ajax_server.php?GPSStatedriver='+state+'&cityDivId='+cityDivId+'&cityListId='+cityListId+'&citytab='+citytab;
	ajax1.onCompletion = function(){executeCitiesGPS()};
	ajax1.runAJAX();

}

function executeCitiesGPS()
{	
  eval(ajax1.response);
}


//UNIQUE TASK NAME IN ADD TASK BY GPS USER
function uniqueTaskName_gps(task_name)
{
	ajax1.requestFile = 'ajax_server.php?task_name_gps='+task_name;
	ajax1.onCompletion = function(){executeUniqueTaskName()};
	ajax1.runAJAX();
}

function executeUniqueTaskName()
{	
  eval(ajax1.response);
}

//UNIQUE TASK NAME IN EDIT TASK BY GPS USER
function uniqueTaskNameEdit_gps(task_name,task_id)
{
	ajax1.requestFile = 'ajax_server.php?task_name_edit_gps='+task_name+'&task_id='+task_id;
	ajax1.onCompletion = function(){executeUniqueTaskName_edit()};
	ajax1.runAJAX();
}

function executeUniqueTaskName_edit()
{	
  eval(ajax1.response);
}

//GETTING ALL DRIVERS BETWEEN GIVEN DATES IN ADD TASK BY GPS USER
function getDurationDrivers_gps(date1,date2,driverDivId,driverListId,drivertabindex,vehicleDivId,vehicleListId,vehicletabindex)
{
	if(date1 == 'select start date' || date1 == '')
	{
		document.getElementById('div_addtask_gps_error').innerHTML = 'Select Start Date';
	}
	else if(date2 == 'select finish date' || date2 == '')
	{
		document.getElementById('div_addtask_gps_error').innerHTML = 'Select End Date';
	}
	else if(date1!='' && date2!='')
	{
		var check_date1 = date1.split('-');
		var now_date1 = new Date(check_date1[0],check_date1[1]-1,check_date1[2]);
		
		var check_date2 = date2.split('-');
		var now_date2 = new Date(check_date2[0],check_date2[1]-1,check_date2[2]);
		
		var diff_date = now_date2 - now_date1;
		if(diff_date < 0)
		{
			document.getElementById('div_addtask_gps_error').innerHTML='End Date Should Not be Prior to Start Date';
			document.add_task_form_gps.atfg_end_date.select();
			return false;
		}
		else
		{
			document.getElementById('div_addtask_gps_error').innerHTML = '';
			ajax1.requestFile = 'ajax_server.php?date1='+date1+'&date2='+date2+'&driverDivId='+driverDivId+'&driverListId='+driverListId+'&drivertabindex='+drivertabindex+'&vehicleDivId='+vehicleDivId+'&vehicleListId='+vehicleListId+'&vehicletabindex='+vehicletabindex;
			ajax1.onCompletion = function(){executeGetDurationDrivers()};
			ajax1.runAJAX();
		}
	}
}

function executeGetDurationDrivers()
{
	eval(ajax1.response);
}

//GETTING ALL DRIVERS BETWEEN GIVEN DATES IN ADD TASK BY GPS USER
function getDurationDriversEdit_gps(date1_edit,date2_edit,driverDivId,driverListId,drivertabindex,vehicleDivId,vehicleListId,vehicletabindex,driver_id,vehicle_id,task_id)
{
	if(date1_edit == 'select start date' || date1_edit == '')
	{
		document.getElementById('div_edittask_gps_error').innerHTML = 'Select Start Date';
	}
	else if(date2_edit == 'select finish date' || date2_edit == '')
	{
		document.getElementById('div_edittask_gps_error').innerHTML = 'Select End Date';
	}
	else if(date1_edit!='' && date2_edit!='')
	{
		var check_date1 = date1_edit.split('-');
		var now_date1 = new Date(check_date1[0],check_date1[1]-1,check_date1[2]);
		
		var check_date2 = date2_edit.split('-');
		var now_date2 = new Date(check_date2[0],check_date2[1]-1,check_date2[2]);
		
		var diff_date = now_date2 - now_date1;
		if(diff_date < 0)
		{
			document.getElementById('div_edittask_gps_error').innerHTML='End Date Should Not be Prior to Start Date';
			document.add_task_form_gps.atfg_end_date.select();
			return false;
		}
		else
		{
			document.getElementById('div_edittask_gps_error').innerHTML = '';
			ajax1.requestFile = 'ajax_server.php?date1_edit='+date1_edit+'&date2_edit='+date2_edit+'&driverDivId='+driverDivId+'&driverListId='+driverListId+'&drivertabindex='+drivertabindex+'&vehicleDivId='+vehicleDivId+'&vehicleListId='+vehicleListId+'&vehicletabindex='+vehicletabindex+'&exist_driver_id='+driver_id+'&exist_vehicle_id='+vehicle_id+'&task_id='+task_id;
			ajax1.onCompletion = function(){executeGetDurationDrivers_edit()};
			ajax1.runAJAX();
		}
	}
}

function executeGetDurationDrivers_edit()
{
	eval(ajax1.response);
}

//CHECKING UNIQUE USER EMAIL IN EDIT USER
function uniqueUserEmail_editGps(user_email,userID)
{
	ajax1.requestFile = 'ajax_server.php?uniqueUserEmail_edit='+user_email+'&userID='+userID;
	ajax1.onCompletion = function(){executeUniqueUserEmail_editGps()};
	ajax1.runAJAX();
}

function executeUniqueUserEmail_editGps()
{
  eval(ajax1.response);
}

//CHECKING UNIQUE USER CONTACT NO. IN EDIT USER
function uniqueUserPhone_editGps(user_phone1_edit,userID)
{
	ajax1.requestFile = 'ajax_server.php?uniqueUserPhone_edit='+user_phone1_edit+'&userID='+userID;
	ajax1.onCompletion = function(){executeUniqueUserPhone_editGps()};
	ajax1.runAJAX();
}

function executeUniqueUserPhone_editGps()
{	
  eval(ajax1.response);
}

//ADD VEHICLE UNIQUE REGISTRATION NO.
function uniqueGPSVehRegdNo(veh_RegNo)
{
	ajax1.requestFile = 'ajax_server.php?uniqueGPSVehRegdNo='+veh_RegNo;
	ajax1.onCompletion = function(){executeuniqueGPSVehRegdNo()};
	ajax1.runAJAX();
}

function executeuniqueGPSVehRegdNo()
{
  eval(ajax1.response);
}


//UNIQUE REG NO FOR EDIT VEHICLE

function uniqueeditGPSVehRegdNo(veh_RegNo,veh_id)
{
	ajax1.requestFile = 'ajax_server.php?uniqueEditGPSVehRegdNo='+veh_RegNo+'&veh_id='+veh_id;
	ajax1.onCompletion = function(){executeuniqueEditGPSVehRegdNo()};
	ajax1.runAJAX();
}

function executeuniqueEditGPSVehRegdNo()
{ 
  eval(ajax1.response);
}


//GETTING VEHICLE STATUS IN GEOFENCE
function checkGeofenceStatus(latval,lngval,task_id,row_id)
{
	//alert(latval+","+lngval+","+task_id+","+row_id);
	ajax1.requestFile = '../GPSTracker/ajax_server.php?latval='+latval+'&lngval='+lngval+'&task_id='+task_id+'&row_id='+row_id;
	//document.write(ajax1.requestFile);
	ajax1.onCompletion = function(){executecheckGeofenceStatus()};
	ajax1.runAJAX();
}

function executecheckGeofenceStatus()
{ 
	eval(ajax1.response);
}

//CHECKING UNIQUE DRIVER'S LICENSE
function uniqueGPSDriverLicense(driver_license)
{
	ajax1.requestFile = 'ajax_server.php?uniqueGPSDriverLicense='+driver_license;
	ajax1.onCompletion = function(){executeUniqueGPSDriverLicense()};
	ajax1.runAJAX();
}

function executeUniqueGPSDriverLicense()
{	
	eval(ajax1.response);
}

//EDIT DRIVER'S UNIQUE LICENSE

function uniqueEditGPSDriverLicense(editGPSDriver_license,drive_id)
{
	ajax1.requestFile = 'ajax_server.php?uniqueEditGPSDriverLicense='+editGPSDriver_license+'&drive_id='+drive_id;
	ajax1.onCompletion = function(){UniqueEditGPSDriverLicense()};
	ajax1.runAJAX();

}

function UniqueEditGPSDriverLicense()
{
	eval(ajax1.response);
}

/////////////////////////////////////////////////////////