<?php
include_once('include/application_top.php');

$user_logged_in = $_core->isUserLoggedIn();
if(!$user_logged_in){
    $_SESSION['messege'] = array('type'=>'fail','text'=>'please login first to access your account');
    $_core->redirect('index.php');
    $_core->close();
    exit;
}
//print_r($_POST);
if(array_key_exists('id',$_GET)){
    $id = $_GET['id'];
    $delete_activity = $_user->deleteActivity($id);
    if($delete_activity == 1){
        $_SESSION['messege'] = array('type'=>'success','text'=>'activity row is deleted');
        $_core->redirect('activity.php');
        $_core->close();
        exit;
    }
    else{
        $_SESSION['messege'] = array('type'=>'fail','text'=>'not able to delete row');
        $_core->redirect('activity.php');
        $_core->close();
        exit;
    }
}
$submit = '';
if(array_key_exists('submit',$_POST))
	$submit = $_POST['submit'];
//echo $submit; exit;

$date = null;
$from = null;
$to = null;
$activity_type_id = null;
$activity_desc = null;
//print_r($_POST); exit;
if($submit){
    $date = date("m/d/Y");
    $from = date("H:i:s", strtotime($_POST['from']));
    $to = date("H:i:s", strtotime($_POST['to']));
    $activity_type_id = $_core->implodeArray($_POST['activity_type_id']);
    $activity_desc = $_POST['activity_desc'];

    if($activity_desc!='' && $from!='' && $to!='' && $activity_type_id!='' && $from<$to){
        $inserted = $_user->insertActivity($from, $to, $activity_type_id, $activity_desc);
    }
    else{
        $_SESSION['messege'] = array('type'=>'fail','text'=>'please insert valid data');
        $_core->redirect('activity.php');
        $_core->close();
        exit;
    }

    if($inserted){
        $_SESSION['messege'] = array('type'=>'success','text'=>'activity is inserted');
        $_core->redirect('activity.php');
        $_core->close();
        exit;
    }
    else{
        $_SESSION['messege'] = array('type'=>'fail','text'=>'activity is not inserted');
        $_core->redirect('activity.php');
        $_core->close();
        exit;
    }
}

