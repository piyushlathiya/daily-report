<?php
include_once('include/application_top.php');

$admin_logged_in = $_core->isAdminLoggedIn();
if(!$admin_logged_in){
//	var_dump(array_reverse(debug_backtrace())); exit;
	//$_SESSION['msg']['error']="";
	$_SESSION['messege'] = array('type'=>'fail','text'=>'please login first to access your account');
	$_core->redirect('admin/index.php');
	$_core->close();
	exit;
}

$submit = '';
if(array_key_exists('submit',$_POST))
	$submit = $_POST['submit'];
//echo $submit; exit;
//print_r($_POST); exit;
if($submit){
    $project_name = $_POST['project_name'];
    $meta_description = $_POST['meta_description'];
    $start_at = $_core->convertDateStrtotime($_POST['start_at']);
    $end_at = $_core->convertDateStrtotime($_POST['end_at']);
    $deadline = $_core->convertDateStrtotime($_POST['deadline']);
    $assign_to = $_core->implodeArray($_POST['assign_to']);
    
    $save_project_over = $_admin->saveProjectOverview($project_name, $meta_description, $start_at, $end_at, $deadline, $assign_to);
    if($save_project_over){
        $_SESSION['messege'] = array('type'=>'success','text'=>'project overview is added successfully');
        $_core->redirect('admin/projects.php');
        $_core->close();
        exit;
    }
    else{
        $_SESSION['messege'] = array('type'=>'fail','text'=>'please insert valid data');
        $_core->redirect('admin/projects.php');
        $_core->close();
        exit;
    }
}
?>