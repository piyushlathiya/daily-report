<?php
include_once('include/application_top.php');

$user_logged_in = $_core->isUserLoggedIn();

if(!$user_logged_in){
    $_SESSION['messege'] = array('type' => 'fail', 'text' => 'please login first to access your account');
    $_core->redirect('index.php');
    $_core->close();
    exit;
}
//print_r($_POST); exit;
$submit = '';
if(array_key_exists('day',$_POST))
	$submit = $_POST['day'];
//echo $submit; exit;

$employee_id = null;
$date = null;
$morning_intime = null;
$morning_outtime = null;
$evening_intime = null;
$evening_outtime = null;
$rate = null;
if($submit){
    $employee_id=$_user->getEmployeeId();
    //echo $employee_id; exit;
    $today_date = $_POST['today_date'];
    $date = date('Y-m-d', strtotime($today_date));
    $morning_intime = $_POST['morning_intime'];
    $morning_outtime = $_POST['morning_outtime'];
    $evening_intime = $_POST['evening_intime'];
    $evening_outtime = $_POST['evening_outtime'];
    $rate = $_POST['rate'];
}
if($employee_id!=null){
    $day_entry = $_user->insertDayInOutRate($employee_id, $date, $morning_intime, $morning_outtime, $evening_intime, $evening_outtime, $rate);
    if($day_entry){
        $_SESSION['messege'] = array('type'=>'success','text'=>'rate and day in-out timinig is updated');
	$_core->redirect('activity.php');
	exit;
    }
    else{
        $_SESSION['messege'] = array('fail'=>'fail','text'=>'please insert proper timing');
        $_core->redirect('activity.php');
	exit;
    }
}