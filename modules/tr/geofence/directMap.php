<?php 
//echo "<pre>";
//print_r($_GET);
//echo "</pre>";
//exit;
@ob_start();
require_once("../../Mainsite/db/MySQLDB.php");
?>
<form name="gpsData" id="gpsData" action="" method="post">
<input type="hidden" name="date_offline" id="date_offline" value="" />
<input type="hidden" name="sessionid" id="sessionid" />
<input type="hidden" name="vehicle_no" id="vehicle_no" />
</form>
<?php
if(isset($_GET[linkId]) && $_GET[linkId]!='')
{
	$sel_qry = "SELECT * FROM map_outside_link WHERE mol_md5code='".$_GET[linkId]."'";
	$rs_sel_qry = mysql_query($sel_qry);
	$no_of_rows = @mysql_num_rows($rs_sel_qry);
	if($no_of_rows == 0)
	{
		echo "No Link Exist";
		header("Location:linkMap.php?show=2");
		exit;
	}
	else
	{
		$fetch_sel_qry = @mysql_fetch_assoc($rs_sel_qry);
		if($fetch_sel_qry[mol_flag] == 2)
		{
			echo "Expired";
			header("Location:linkMap.php?show=1");
		}
		else if($fetch_sel_qry[mol_flag] == 0)
		{
			$upd_qry = "UPDATE map_outside_link SET mol_flag=1,mol_activatedDate='".date('Y-m-d H:i:s')."' WHERE mol_md5code='".$_GET[linkId]."'";
			$rs_upd_qry = mysql_query($upd_qry);	
			echo "Loading...";
			//exit;	
			//print_r($fetch_sel_qry);
			//echo "Show";
			?>
            <script type="text/javascript" language="javascript">
				document.gpsData.date_offline.value='<?php echo date('Y-m-d',strtotime($fetch_sel_qry[mol_activatedDate]));?>';
				document.gpsData.sessionid.value=<?php echo $fetch_sel_qry[mol_clientId];?>;
				document.gpsData.vehicle_no.value='<?php echo $fetch_sel_qry[mol_vehi_regno];?>';
				//document.gpsData.action='linkMap.php';
				//document.gpsData.submit();
			</script>
            <?php
			header("location:linkMap.php?linkId=".$_GET[linkId]);
		}
		else
		{
			$days = (strtotime(date('Y-m-d H:i:s')) - strtotime($fetch_sel_qry[mol_activatedDate]))/(24*60*60);
			if($fetch_sel_qry[mol_flag] == 1 && $days < 1)
			{
				echo "Loading...";
				//print_r($fetch_sel_qry);
				?>
            <script type="text/javascript" language="javascript">
				document.gpsData.date_offline.value='<?php echo date('Y-m-d',strtotime($fetch_sel_qry[mol_activatedDate]));?>';
				document.gpsData.sessionid.value=<?php echo $fetch_sel_qry[mol_clientId];?>;
				document.gpsData.vehicle_no.value='<?php echo $fetch_sel_qry[mol_vehi_regno];?>';
				//document.gpsData.action='linkMap.php';
				//document.gpsData.submit();
			</script>
            <?php
				header("location:linkMap.php?linkId=".$_GET[linkId]);
			}
			else
			{
				$upd_qry2 = "UPDATE map_outside_link SET mol_flag=2 WHERE mol_md5code='".$_GET[linkId]."'";
				$rs_upd_qry2 = mysql_query($upd_qry2);
				echo "Don't Show";
				header("Location:linkMap.php?show=1");
			}
		}
	}
}
?>

