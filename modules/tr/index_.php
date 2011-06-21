<?php
@ob_start();
@session_start();
require("checkSession.php");
error_reporting (E_ALL ^ E_NOTICE);
require_once("../../includes/GPSFunction.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>GPS Application</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script language="javascript">
function userLogout()
{
	location.href = 'userLogout.php';
}
</script>
</head>

<body>
<div class="wrapper"><!-- wrapper div start from here -->
	<div class="headerarea"><!-- headerarea div start here -->
    	<div class="logoarea"><img src="images/logo.gif" width="198" height="69" /></div>
        <div class="welcome_user">
        	<p>Welcome User | <a href="#" onclick="javascript:userLogout();">Logout</a></p><br />
            <p>Your last login is on dd/mm/yyyy, HH:MM</p>
        </div>
        
        <div class="menuarea">
        	 <ul>
        	   <li class="active">Home</li>  
               <li>Alerts</li>
               <li>Settings</li>
               <li><a href="#" onclick="location.href='myTrack.php';" target="_blank">Live</a></li>
               <li>External Link</li>
            </ul>
            
            <div class="datearea">Date : 11-10-2010 <img src="images/calender.gif" width="27" height="26" /></div>
            
        </div>
        
      <div class="clear"></div>  
    </div><!-- headerarea div END here -->
	<div class="pagearea"><!-- Pagearea div start here -->
    	<div align="center">
    	<table class="gridform">
     		<tr>
            	<th>Employee   </th>
            	<th >Division  </th> 
           	    <th>Suggestions</th>
                <th >Rating    </th>
           </tr>
           <tr>
            	<td>Pradeep Kumar   </td>
            	<td >Graphic </td> 
           	    <td>Creative Desings</td>
                <td >5/10    </td>
           </tr>
            <tr>
            	<td>Mahipal </td>
            	<td >Web </td> 
           	    <td>Creative Desings</td>
                <td >4/10    </td>
           </tr>
            <tr>
            	<td>Ajay </td>
            	<td >Admin </td> 
           	    <td>Proper Connections</td>
                <td >4/10    </td>
           </tr>
            <tr>
            	<td>Murali   </td>
            	<td >Developer </td> 
           	    <td>Functionalitys</td>
                <td >4/10   </td>
           </tr>
           
        </table>
        </div>
        
        
        <div align="center">
    	<table class="gridform">
     		<tr>
            	<th>Employee   </th>
            	<th >Division  </th> 
           	    <th>Suggestions</th>
                <th >Rating    </th>
           </tr>
           <tr>
            	<td>Pradeep Kumar   </td>
            	<td >Graphic </td> 
           	    <td>Creative Desings</td>
                <td >5/10    </td>
           </tr>
            <tr>
            	<td>Mahipal </td>
            	<td >Web </td> 
           	    <td>Creative Desings</td>
                <td >4/10    </td>
           </tr>
            <tr>
            	<td>Ajay </td>
            	<td >Admin </td> 
           	    <td>Proper Connections</td>
                <td >4/10    </td>
           </tr>
            <tr>
            	<td>Murali   </td>
            	<td >Developer </td> 
           	    <td>Functionalitys</td>
                <td >4/10   </td>
           </tr>
           
        </table>
        </div>
        
        <div align="center">
    	<table class="gridform">
     		<tr>
            	<th>Employee   </th>
            	<th >Division  </th> 
           	    <th>Suggestions</th>
                <th >Rating    </th>
           </tr>
           <tr>
            	<td>Pradeep Kumar   </td>
            	<td >Graphic </td> 
           	    <td>Creative Desings</td>
                <td >5/10    </td>
           </tr>
            <tr>
            	<td>Mahipal </td>
            	<td >Web </td> 
           	    <td>Creative Desings</td>
                <td >4/10    </td>
           </tr>
            <tr>
            	<td>Ajay </td>
            	<td >Admin </td> 
           	    <td>Proper Connections</td>
                <td >4/10    </td>
           </tr>
            <tr>
            	<td>Murali   </td>
            	<td >Developer </td> 
           	    <td>Functionalitys</td>
                <td >4/10   </td>
           </tr>
           
        </table>
        </div>
        
    </div><!-- PAGEarea END here -->
	<div class="footerarea"><!-- Footerarea div start here -->
    <p align="right">All Rights Reserved to KEYSTONE UNIQUE INFO PRIVATE LIMITED. Copyrights 2010</p>
    </div><!-- Footerarea div End here -->
<div class="clear"></div>
</div><!-- wrapper div END here -->
</body>
</html>
