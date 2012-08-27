<?php
include_once('include/application_top.php');

$logout = $_core->userLogout();
//echo $logout; exit;
if($logout){
	$_SESSION['messege'] = array('type'=>'success','text'=>'you are succesfully logged out.');
	header('Location: '.$_core->getUrl('index.php'));
}
else{
	header('Location: '.$_core->getUrl('activity.php'));
}
?>