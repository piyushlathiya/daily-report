<?php
include_once('include/application_top.php');

if(array_key_exists('email', $_POST) && array_key_exists('password', $_POST)){
	//print_r($_POST);
	$submit = $_POST['submit'];
    if ($submit) {
//	Validation
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $login_validate = $_core->userLoginValidate($email, $password);
        if ($login_validate) {
            $_core->redirect('activity.php');
            exit;
        } else {
            $_SESSION['messege'] = array('type' => 'fail', 'text' => 'Let\'s try again');
            $_core->redirect('index.php');
            exit;
        }
    }
}
// is user logged in ? if not then redirect to Login page
$user_logged_in = $_core->isUserLoggedIn();
if(!$user_logged_in){
	//$_SESSION['msg']['error']="";
	$_SESSION['messege'] = array('type'=>'fail','text'=>'please login first to access your account');
	$_core->redirect('index.php');
	$_core->close();
	exit;
}
else{
	$_core->redirect('activity.php');
	exit;
}
?>