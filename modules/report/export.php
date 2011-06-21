<?php
require("../../includes/config.inc.php"); 
require("../../includes/Database.class.php"); 
error_reporting (E_ALL ^ E_NOTICE);

$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE); 
$db->connect(); 
//
//print"<pre>";
//print_r($_POST);
//print"<pre/>";

if(isset($_POST[txtGeofenceData]) && $_POST[txtGeofenceData]!='') 
{
 
$filename = 'Geofence_Report_'.$_POST[txtDeviceName].'_'.$_POST[txtStartDateTime].'_'.$_POST[txtEndDateTime].'_.csv';
$csvData=str_replace("@","\n",$_POST[txtGeofenceData]);
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . strlen($csvData));
header('Connection: close');
header('Content-Type: text/x-csv; name=' . $filename);
echo ($csvData);
}

if(isset($_POST[txtMonthKiloMtr]) && $_POST[txtMonthKiloMtr]!='') 
{
 
$filename = 'Monthly_Report_'.$_POST[txtVehino].'_'.$_POST[txtMonth].'_'.$_POST[txtYear].'_.csv';
$csvData=str_replace("@","\n",$_POST[txtMonthKiloMtr]);
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . strlen($csvData));
header('Connection: close');
header('Content-Type: text/x-csv; name=' . $filename);
echo ($csvData);
}

if(isset($_POST[txtComplData]) && $_POST[txtComplData]!='') 
{ 

$filename ='CompleteReport_'.$_POST[txtDate].'.csv';
$csvData=str_replace("@","\n",$_POST[txtComplData]);
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . strlen($csvData));
header('Connection: close');
header('Content-Type: text/x-csv; name=' . $filename);
echo ($csvData);
}

if(isset($_POST[overAllTripData]) && $_POST[overAllTripData]!='') 
{ 

$filename = $_POST[txtVehino].'_'.$_POST[txtDate].'.csv';
$csvData=str_replace("@","\n",$_POST[overAllTripData]);
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . strlen($csvData));
header('Connection: close');
header('Content-Type: text/x-csv; name=' . $filename);
echo ($csvData);
}
if(isset($_POST[txtCSVData]) && $_POST[txtCSVData]!='') 
{
 
$filename = $_POST[txtVehino].'_'.$_POST[txtDate].'_'.$_POST[txtFromTime].'_'.$_POST[txtToTime].'.csv';
$csvData=str_replace("@","\n",$_POST[txtCSVData]);
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . strlen($csvData));
header('Connection: close');
header('Content-Type: text/x-csv; name=' . $filename);
echo ($csvData);
}


if(isset($_POST[txtOverSpeed]) && $_POST[txtOverSpeed]!='') 
{
$filename = 'overSpeedReport_'.$_POST[txtVehino].'_'.$_POST[txtDate].'_.csv';
$csvData=str_replace("@","\n",$_POST[txtOverSpeed]);
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . strlen($csvData));
header('Connection: close');
header('Content-Type: text/x-csv; name=' . $filename);
echo ($csvData);
}


if(isset($_POST[txtStopData]) && $_POST[txtStopData]!='') 
{
 
$filename = $_POST[txtVehino].'_'.$_POST[txtDate].'_'.$_POST[txtFromTime].'_'.$_POST[txtToTime].'.csv';
$csvData=str_replace("@","\n",$_POST[txtStopData]);
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . strlen($csvData));
header('Connection: close');
header('Content-Type: text/x-csv; name=' . $filename);
echo ($csvData);
}


if(isset($_POST[txtTripData]) && $_POST[txtTripData]!='') 
{
 
$filename = $_POST[txtVehino].'_'.$_POST[txtDate].'.csv';
$csvData=str_replace("@","\n",$_POST[txtTripData]);
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . strlen($csvData));
header('Connection: close');
header('Content-Type: text/x-csv; name=' . $filename);
echo ($csvData);
}


if(isset($_POST[txtKiloData]) && $_POST[txtKiloData]!='') 
{
 
$filename = 'kilometerReport_'.$_POST[txtVehino].'_'.$_POST[txtDate1].'_'.$_POST[txtDate2].'_.csv';
$csvData=str_replace("@","\n",$_POST[txtKiloData]);
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . strlen($csvData));
header('Connection: close');
header('Content-Type: text/x-csv; name=' . $filename);
echo ($csvData);
}

if(isset($_POST[txtGeoExcep]) && $_POST[txtGeoExcep]!='') 
{
$filename = 'Geo-ExceptionReport_'.$_POST[txtVehino].'_'.$_POST[txtDate].'_.csv';
$csvData=str_replace("@","\n",$_POST[txtGeoExcep]);
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . strlen($csvData));
header('Connection: close');
header('Content-Type: text/x-csv; name=' . $filename);
echo ($csvData);
}

if(isset($_POST[txtFuelMileage]) && $_POST[txtFuelMileage]!='') 
{
 
$filename = 'fuelConsumptionReport_'.$_POST[fuel_vehicle_id].'_'.$_POST[trip_month].'_'.$_POST[trip_year].'_.csv';
$csvData=str_replace("@","\n",$_POST[txtFuelMileage]);
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . strlen($csvData));
header('Connection: close');
header('Content-Type: text/x-csv; name=' . $filename);
echo ($csvData);
}
?>