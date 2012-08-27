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
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Aureate - Dashboard</title>
        <script type="text/javascript" src="<?php echo $_core->getUrl('js/jQuery.min.js') ?>"></script>
            
        <script type="text/javascript" src="<?php echo $_core->getUrl('js/date.js') ?>"></script>
        <script type="text/javascript" src="<?php echo $_core->getUrl('js/jquery.datePicker.js') ?>"></script>
            
        <link rel="stylesheet" href="<?php echo $_core->getSkinUrl('datePicker.css') ?>" type="text/css" media="all" />
    </head>
        
    <body class="dashboard">
        <?php include_once 'include/header.php'; ?>
    </body>
</html>