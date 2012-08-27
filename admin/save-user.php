<?php
include_once('include/application_top.php');

$admin_logged_in = $_core->isAdminLoggedIn();
$user_logged_in = $_core->isUserLoggedIn();

if(!$admin_logged_in){
	$_SESSION['messege'] = array('type'=>'fail','text'=>'please login first to access your account');
        $_core->redirect('admin/index.php');
	$_core->close();
	exit;
}
/*
 * New User
 */
//print_r($_POST); exit;
$submit = '';
if(array_key_exists('submit',$_POST))
	$submit = $_POST['submit'];
//echo $submit; exit;

if($submit){
    $dbdest='';
    if( count($_FILES)>0 && $_FILES["upload_photo"]["error"]==0){
        $name = $_FILES["upload_photo"]["name"];
        $type = $_FILES["upload_photo"]["type"];
        $size = $_FILES["upload_photo"]["size"];
        $tmp_name = $_FILES["upload_photo"]["tmp_name"];
        //echo $name.' '.$tmp_name; exit;
        $max_size = POST_MAX_SIZE * 1024 * 1024;

        $_extension = explode(".",$name);
        $extension = $_extension[count($_extension)-1];
        if (($extension == "jpg" || $extension == "jpeg" || $extension == "gif" || $type == "image/gif" || $type == "image/jpeg" || $type == "image/jpg") && ($size < $max_size)){
            $dbdest = $_user->moveUploadedPhoto($name,$tmp_name);
        }
    //  else
    //  $msg = 'not uploaded.. try again..';
    }
    $user_id = null;
    if(array_key_exists('user_id',$_POST))
        $user_id = $_POST['user_id'];
    $user_fname = trim($_POST['user_fname']);
    $user_mname = trim($_POST['user_mname']);
    $user_lname = trim($_POST['user_lname']);
    $user_email = trim($_POST['user_email']);
    $user_pass = $_POST['user_pass'];
    $user_repass = $_POST['user_repass'];
    $user_gender = $_POST['user_gender'];
    $dob = $_POST['dob'];
    $user_address1 = trim($_POST['user_address1']);
    $user_address2 = trim($_POST['user_address2']);
    $user_country = $_POST['user_country'];
    $user_state = $_POST['user_state'];
    $user_city = trim($_POST['user_city']);
    $user_postcode = trim($_POST['user_postcode']);
    $user_phone = trim($_POST['user_phone']);
    $role = $_POST['user_role'];
    $user_status = $_POST['user_status'];
    $employee_id = $_POST['employee_id'];
    if ($role == 'Admin')
        $user_role = 1;
    else
        $user_role = 2;
    
    $profile_photo='';
    if(array_key_exists('profile_photo',$_POST))
        $profile_photo = $_POST['profile_photo'];
    
        //echo $update_user = "UPDATE user SET user_fname='$user_fname', user_mname='$user_mname', user_lname='$user_lname', user_email='$user_email', user_gender='$user_gender', dob='$dob', user_address1='$user_address1', user_address2='$user_address2', user_country='$user_country', user_state='$user_state', user_city='$user_city', user_postcode='$user_postcode', user_phone='$user_phone', user_role='$user_role', user_pass='$user_pass' WHERE user_id=$user_id"; exit;
    
	//echo $query = "INSERT INTO user (user_fname, user_mname, user_lname, user_email, user_pass, user_gender, dob, user_address1, user_address2, user_country, user_state, user_city, user_postcode, user_phone, user_role, created_time, updated_time) VALUES ('$user_fname', '$user_mname', '$user_lname', '$user_email', $user_pass, '$user_gender', '$dob', "."\"$user_address_1\"".", "."\"$user_address_2\"".", '$user_country', '$user_state', '$user_city', $user_postcode, '$user_phone', '$user_role', NOW(), NOW()) "; exit;

    $new_user = '';
    $update_user = '';
    if(is_numeric($user_phone) && (filter_input(INPUT_POST, "user_email", FILTER_VALIDATE_EMAIL))){

        //update user information
        if($user_id>0 && $user_pass==$user_repass){
            $update_user = $_admin->updateUserDetail($employee_id, $user_id, $user_fname, $user_mname, $user_lname, $user_email, $user_pass, $user_gender, $dob, $user_address1, $user_address2, $user_country, $user_state, $user_city, $user_postcode, $user_phone, $user_role, $user_status, $dbdest, $profile_photo);
        //echo $update_user; exit;
        }

        //add new user
        elseif ($user_id==null && $user_pass==$user_repass) {
            $new_user = $_admin->addNewUser($employee_id, $user_fname, $user_mname, $user_lname, $user_email, $user_pass, $user_gender, $dob, $user_address1, $user_address2, $user_country, $user_state, $user_city, $user_postcode, $user_phone, $user_role, $user_status, $src, $extension);
        //echo $new_user; exit;
        }
    }
    if ($new_user){
        $_SESSION['messege'] = array('type' => 'success', 'text' => '\&bull;/ new user is inserted');
        $_core->redirect('admin/users.php'); exit;
    } elseif($update_user){
        $_SESSION['messege'] = array('type' => 'success', 'text' => '\&bull;/ user information is updated');
        $_core->redirect('admin/users.php'); exit;
    }
    else{
        $_SESSION['messege'] = array('type' => 'fail', 'text' => 'is there something wrong? try again.');
        $_core->redirect('admin/users.php'); exit;
    } 
}
?>