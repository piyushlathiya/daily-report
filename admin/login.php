<?php
include_once('include/application_top.php'); 

if($_POST['email'] && $_POST['password']){
	//print_r($_POST);
	$submit = $_POST['submit'];
    if($submit){
//	Validation
		$email = trim($_POST['email']);
		$password = $_POST['password'];
		$login_validate = $_core->adminLoginValidate($email, $password);
                //echo $login_validate; exit;
		if ($login_validate) {
			$_core->redirect('admin/dashboard.php');
			exit;
		}
		else {
			$_SESSION['messege'] = array('type'=>'fail','text'=>'Let\'s try again');
			$_core->redirect('admin/index.php');
			exit; 
		}
    }
}
// is user logged in ? if not then redirect to Login page
$admin_logged_in = $core->isAdminLoggedIn();
if(!$admin_logged_in){
//	var_dump(array_reverse(debug_backtrace())); exit;
	//$_SESSION['msg']['error']="";
	$_SESSION['messege'] = array('type'=>'fail','text'=>'please login first to access your account');
	$_core->redirect('admin/index.php');
	$_core->close();
	exit;
}
else{
    $_core->redirect('admin/dashboard.php');
    exit;
}
?>