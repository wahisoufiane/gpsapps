<?php
//print_r($_POST);
?>
<script type="text/javascript" language="javascript">
/*$(function() {
	$("#nav a").click(function()
	{
		//$("#current").removeAttr("id");
		//$(this).attr("id", "current");
		$("#content").load($(this).attr("id") + '.php?txtDeviceId=<?php echo $_POST[txtDeviceId];?>&txtClientId=<?php echo $_POST[txtClientId];?>');
		// Prevent click from jumping to the top of the page
		return false;
	});
});*/
function manageDev(subKey)
{
	document.frmSubmit.action = '?ch=manageDevice&devCh='+subKey;
	document.frmSubmit.submit();
}

</script>
<style type="text/css">
.leftMenu
{
margin-top:0px;
border: 1px solid #B5E2FE; /*THEME CHANGE HERE*/
border-bottom-width: 0;
font:normal 14px Verdana, Arial, Helvetica, sans-serif;
line-height:18px;
background-color: white;
}

.leftMenu a{
width: 100%;
display: block;
text-indent: 10px;
border-bottom: 1px solid #B5E2FE; /*THEME CHANGE HERE*/
padding: 5px 0;
text-decoration: none;
font-weight: bold;
color: black;
}
</style>
<table width="100%" border="0" class="gridform_final">
<tr><th>Menu</th><th>Form</th></tr>
  <tr valign="top">
    <td width="25%" align="left" valign="top">
    	<div id="nav" class="leftMenu">
        	<a href="#" onclick="manageDev('insurance');">Insurance</a>
            <a href="#" id="index">Stop Report</a>
        </div>
    </td>
    <td width="75%" valign="top">
    	<span id="content">
        <?php
			switch($_GET[devCh])
			{
				case "insurance";
					include("insurance.php");
					break;
			}
		?>
        </span>
    </td>
  </tr>
</table>
<form name="frmSubmit" id="frmSubmit" method="post">
	<input type="hidden" name="txtDeviceId" id="txtDeviceId" value="<?php echo $_POST[txtDeviceId];?>" />
    <input type="hidden" name="txtClientId" id="txtClientId" value="<?php echo $_POST[txtClientId];?>" />
</form>
 
	
<script language="javascript">
//manageDev('insurance');
</script>
