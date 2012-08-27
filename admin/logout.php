<?php
include_once('include/application_top.php');

$logout = $_core->adminLogout();
//echo $logout; exit;
if($logout){
	$_SESSION['messege'] = array('type'=>'success','text'=>"\&bull;/ you are succesfully logged out.");
	header('Location: '.$_core->getUrl('admin/index.php'));
}
else{
	header('Location: '.$_core->getUrl('admin/dashboard.php'));
}
?>