<?php
include_once '../include/application_top.php';
include_once '../class/Class_Core.php';
include_once '../class/Class_user.php';

$core = new Class_Core();
$user = new Class_user();

$is_admin_logged_in = $core->isAdminLoggedIn();
if(!$is_admin_logged_in){
    header('Location: http://127.0.0.1/cReport/admin/login.php?msg="login_first"');
}

$users = $user->getUserIdNames();
$user_counter = array_keys($users);
$counter = count($user_counter);

if(array_key_exists('msg',$_GET))
	$msg = $_GET['msg'];
else
	$msg = '';
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Aureate - Dashboard</title>
        <script type="text/javascript" src="../js/jquery.min.js"></script>
        <link rel="stylesheet" href="../skin/style.css" type="text/css" media="all" />

        <script type="text/javascript" src="../js/date.js"></script>
        <script type="text/javascript" src="../js/jquery.datePicker.js"></script>
        <link rel="stylesheet" type="text/css" media="screen" href="../skin/datePicker.css" />

    </head>

    <body class="dashboard">
        <?php include_once '../include/header.php'; ?>
        <?php if($msg=="success"): ?>
            <p>succeed.</p>
        <?php elseif($msg=="fail"): ?>
            <p>Failed.</p>
        <?php endif; ?>
        
        <div class="left-panel">
            <ul>
                <li id="manage-user"><a href="dashboard.php"><span>Manage User</span></a></li>
                <li id="analyze-report"><a href="#"><span>Analyze Report</span></a></li>
            </ul>
        </div>
        <div class="right-panel">
            
        </div>
    </body>
</html>