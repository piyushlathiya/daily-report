<?php
include_once('include/application_top.php');

$admin_logged_in = $_core->isAdminLoggedIn();
if (!$admin_logged_in) {
    //	var_dump(array_reverse(debug_backtrace())); exit;
    //$_SESSION['msg']['error']="";
    $_SESSION['messege'] = array('type' => 'fail', 'text' => 'please login first to access your account');
    $_core->redirect('admin/index.php');
    $_core->close();
    exit;
}

//print_r($_POST); exit;
$submit = '';
if(array_key_exists('submit',$_POST))
	$submit = $_POST['submit'];

if($submit){
    $type_name = trim($_POST['type_name']);
    $type_desc = trim($_POST['type_desc']);
    
    $save_activity_type = $_admin->saveActivityType($type_name, $type_desc);
    if($save_activity_type){
        $_SESSION['messege'] = array('type' => 'success', 'text' => '\&bull;/ new activity type is added');
        $_core->redirect('admin/activity-type.php'); exit;
    }
    else{
        $_SESSION['messege'] = array('type' => 'fail', 'text' => 'not able to add activity type');
        $_core->redirect('admin/activity-type.php'); exit;
    }
        
}

if(array_key_exists('id',$_GET))
	$type_id = $_GET['id'];

if($type_id>0){
    $delete_activity_type = $_admin->deleteActivityType($type_id);
    if($delete_activity_type){
        $_SESSION['messege'] = array('type' => 'success', 'text' => '\&bull;/ activity type is deleted');
        $_core->redirect('admin/activity-type.php'); exit;
    }
    else{
        $_SESSION['messege'] = array('type' => 'fail', 'text' => 'not able to delete activity type');
        $_core->redirect('admin/activity-type.php'); exit;
    }
}