<?php
include_once('include/application_top.php');

$user_logged_in = $_core->isUserLoggedIn();
if(!$user_logged_in){
    $_SESSION['messege'] = array('type'=>'fail','text'=>'please login first to access your account');
    $_core->redirect('index.php');
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
    //echo $dbdest; exit;
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

    $profile_photo='';
    if(array_key_exists('profile_photo',$_POST))
        $profile_photo = $_POST['profile_photo'];
    
        //echo $update_user = "UPDATE user SET user_fname='$user_fname', user_mname='$user_mname', user_lname='$user_lname', user_email='$user_email', user_gender='$user_gender', dob='$dob', user_address1='$user_address1', user_address2='$user_address2', user_country='$user_country', user_state='$user_state', user_city='$user_city', user_postcode='$user_postcode', user_phone='$user_phone', user_role='$user_role', user_pass='$user_pass' WHERE user_id=$user_id"; exit;
    
	//echo $query = "INSERT INTO user (user_fname, user_mname, user_lname, user_email, user_pass, user_gender, dob, user_address1, user_address2, user_country, user_state, user_city, user_postcode, user_phone, user_role, created_time, updated_time) VALUES ('$user_fname', '$user_mname', '$user_lname', '$user_email', $user_pass, '$user_gender', '$dob', "."\"$user_address_1\"".", "."\"$user_address_2\"".", '$user_country', '$user_state', '$user_city', $user_postcode, '$user_phone', '$user_role', NOW(), NOW()) "; exit;

    $update_user = '';
    if(is_numeric($user_phone) && (filter_input(INPUT_POST, "user_email", FILTER_VALIDATE_EMAIL))){
        //update user information
        if($user_id>0 && $user_pass==$user_repass){
            $update_user = $_user->updateUserDetail($user_id, $user_fname, $user_mname, $user_lname, $user_email, $user_pass, $user_gender, $dob, $user_address1, $user_address2, $user_country, $user_state, $user_city, $user_postcode, $user_phone, $dbdest, $profile_photo);
        //echo $update_user; exit;
        }
    }
    if($update_user){
        $_SESSION['messege'] = array('type' => 'success', 'text' => '\&bull;/ user information is updated');
        $_core->redirect('manage-user.php'); exit;
    }
    else{
        $_SESSION['messege'] = array('type' => 'fail', 'text' => 'is there something wrong? try again.');
        $_core->redirect('manage-user.php'); exit;
    } 
}
?>