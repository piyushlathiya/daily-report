<?php
include_once 'include/application_top.php';
include_once 'class/Class_Core.php';
$core = new Class_Core();
include_once 'class/Class_user.php';
$user = new Class_user();

$is_user_logged_in = $core->isUserLoggedIn();
if(!$is_user_logged_in){
    header('Location: http://127.0.0.1/cReport/login.php?msg="login_first"');
}

$user_id = $user->getUserId();
//print_r($_POST); exit;
if($_POST){
	$slot_id = $_POST['slot_id'];
	$activity_id = $_POST['activity_id'];
	$activity_desc = $_POST['activity_desc'];

$inserted = $user->insertActivity($user_id, $slot_id, $activity_id, $activity_desc);
}
if($inserted)
    echo 'done!';
else
    echo 'oops!';
//header('Location : http://127.0.0.1/cReport/edit-activity.php');
?>