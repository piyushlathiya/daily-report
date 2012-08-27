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
if (array_key_exists('id', $_GET) && array_key_exists('status', $_GET)){
    $user_id = $_GET['id'];
    $status=$_GET['status'];
    
    $status_edit = $_user->userStatusEdit($user_id,$status);
    if($status_edit>0){
        $_SESSION['messege'] = array('type'=>'success','text'=>'\&bull;/ user status is updated');
	$_core->redirect('admin/users.php');
	$_core->close();
	exit;
    }
    else{
        $_SESSION['messege'] = array('type'=>'fail','text'=>'admin user cannot be disable.');
	$_core->redirect('admin/users.php');
	$_core->close();
	exit;
    }
}

$admin_logged_in = $core->isAdminLoggedIn();
if(!$admin_logged_in){
//	var_dump(array_reverse(debug_backtrace())); exit;
	//$_SESSION['msg']['error']="";
	$_SESSION['messege'] = array('type'=>'fail','text'=>'please login first to access your account');
	$_core->redirect('admin/index.php');
	$_core->close();
	exit;
}