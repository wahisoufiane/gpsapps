<?php
//echo "<pre>";
//print_r($_POST);
//echo "</pre>";
function full_copy( $source, $target ) {
	if ( is_dir( $source ) ) {
		@mkdir( $target );
		$d = dir( $source );
		while ( FALSE !== ( $entry = $d->read() ) ) {
			if ( $entry == '.' || $entry == '..' ) {
				continue;
			}
			$Entry = $source . '/' . $entry; 
			if ( is_dir( $Entry ) ) {
				full_copy( $Entry, $target . '/' . $entry );
				continue;
			}
			copy( $Entry, $target . '/' . $entry );
		}
 
		$d->close();
	}else {
		copy( $source, $target );
	}
}
//	ADD USER INFORMATION
if(isset($_POST[cmdSubmitAddClient]) && $_POST[cmdSubmitAddClient]!='')
{
$client_data['ci_clientName'] = $_POST[txtClientName];
$client_data['ci_clientType'] = "Client";
$client_data['ci_phoneNumber'] = $_POST[txtCLandline];
$client_data['ci_mobileNumer'] = $_POST[txtCMobile];
$client_data['ci_email1'] = $_POST[txtCEmail1];
$client_data['ci_email2'] = $_POST[txtCEmail2];
$client_data['ci_website'] = $_POST[txtCWebsite];
$client_data['ci_address'] = $_POST[txtCAddress];

/*$clientFolder = str_replace(" ","",$_POST[txtClientName]);
$source ='src/';
$destination = '../../in/'.$clientFolder;
full_copy($source, $destination);
*/

$client_primary_key = $db->query_insert("tb_clientinfo", $client_data);
//exit;
if($client_primary_key)
{
	$admin_data['ui_isAdmin'] = 1;
	$admin_data['ui_clientId'] = $client_primary_key;
	$admin_data['ui_firstname'] = "admin";
	$admin_data['ui_lastname'] = $_POST[txtClientName];
	$admin_data['ui_username'] = $_POST[txtUsername];
	$admin_data['ui_password'] = $_POST[txtPassword];
	$admin_data['ui_mobile'] = $_POST[txtCMobile];
	$admin_data['ui_email'] = $_POST[txtCEmail1];
	$admin_data['ui_accessFlag'] = 1;
	
	if($db->query_insert("tb_userinfo", $admin_data))
		$res = 1;
	else $res = 0;
	
	
}	
else $res = 0;

//exit;
header("location:?ch=status&au=1&msg=".$res);
exit;

}


//		UPDATE USER INFORMATION
if(isset($_POST[cmdSubmitUpdateClient]) && $_POST[cmdSubmitUpdateClient]!='')
{
//$client_data['ci_clientName'] = $_POST[txtClientName];
$client_data['ci_clientType'] = "Client";
$client_data['ci_phoneNumber'] = $_POST[txtCLandline];
$client_data['ci_mobileNumer'] = $_POST[txtCMobile];
$client_data['ci_email1'] = $_POST[txtCEmail1];
$client_data['ci_email2'] = $_POST[txtCEmail2];
$client_data['ci_website'] = $_POST[txtCWebsite];
$client_data['ci_address'] = $_POST[txtCAddress];
$client_data['ci_updatedDate'] = "NOW()";

//print_r($client_data);
//exit;

//$client_primary_key = $db->query_insert("tb_clientinfo", $client_data);
//exit;
if($db->query_update("tb_clientinfo", $client_data , "ci_id=".$_POST[txtClientId]))
{
	$admin_data['ui_isAdmin'] = 1;
	//$admin_data['ui_username'] = $_POST[txtUsername];
	//$admin_data['ui_password'] = $_POST[txtPassword];
	$admin_data['ui_mobile'] = $_POST[txtCMobile];
	$admin_data['ui_email'] = $_POST[txtCEmail1];
	$admin_data['ui_accessFlag'] = 1;
	
	if($db->query_update("tb_userinfo", $admin_data, "ui_clientId=".$_POST[txtClientId]))
	$res = 1;
	else $res = 0;
		
}	
else $res = 0;

header("location:?ch=status&au=2&msg=".$res);
exit;

}


?>