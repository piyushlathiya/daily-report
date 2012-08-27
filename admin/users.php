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

$limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : LIMIT;
$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : PAGE;

$employee_id = null;
$user_name = null;
$lastlogin_at = null;
$current_project = null;
$column = '';
if(array_key_exists('search_column',$_POST))
	extract($_POST);
if(is_array($column))
    extract($column);
$user_data = $_admin->getUserGridDetail($page, $limit, $column);
//echo count($user_data); exit;
//var_dump($user_data); exit;

//var_dump($user_data); exit; search_query

//$user_ids=array();
//$counter=0;
//if(count($user_data)>0){
//    $user_ids = array_keys($user_data);
//    $counter = count($user_ids);
//}
$page_flag='users';
//print_r($user_data);
//print_r($user_ids); exit;

$no_records = count($user_data);
$total_records = $_core->getTotalRecords('user');
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Aureate - Users</title>
    </head>

    <body class="dashboard">
        <?php include_once ('include/header.php'); ?>
        <div class="admin-wrapper">
            <div class="alert">
                <?php
                if (array_key_exists('messege', $_SESSION)): $type = ''; $text = '';
                    extract($_SESSION['messege']);
                    if ($type && $text): ?>
                        <div class="message-<?php echo $type ?>"><?php echo $text ?></div>
                        <?php $_SESSION['messege'] = array(); ?>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <div class="paging">
                <form action="" name="pagging" method="GET">
                    <strong>Items per Page :</strong>
                    <select onchange="pagging.submit()" name="limit">
                        <option selected="selected" value="10">10</option>
                        <option value="20" <?php if($limit==20):?>selected="selected"<?php endif;?> >20</option>
                        <option value="50" <?php if($limit==50):?>selected="selected"<?php endif;?>>50</option>
                        <option value="150">All</option>
                    </select>
                    <strong>Page :</strong>
                    <select onchange="pagging.submit()" name="page">
                        <?php $no_of_pages=$_core->getNumberOfPages($limit);
                            $i=1;
                            while($i<=$no_of_pages):
                        ?>
                        <option value="<?php echo $i;?>" <?php if($i==$page):?> selected="selected"<?php endif;?>><?php echo $i;?></option>
                        <?php $i++; endwhile;?>
                    </select>
                    <strong>Total Records : <?php echo $no_records?> out of <?php echo $total_records?></strong>
                </form>
            </div>
            <div class="users-list clear">
                <div class="manage-user">
                    <h1 class="heading">All users <a class="s-button" href="<?php echo $_core->getUrl('manage-user.php')?>" title="Add new user"><span>Add New</span></a></h1>
                    <div class="user-form table-grid">
                        <table>
                            <colgroup></colgroup>
                            <colgroup span="3"></colgroup>
                            <colgroup></colgroup>
                            <thead>
                                <tr class="thead">
                                    <th>Em. Id</th>
                                    <th>Name</th>
                                    <th>Last-login</th>
                                    <th>Current Project</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=0; foreach ($user_data as $data):
                                    extract($data);
                                    $name = $user_fname.' '.$user_lname;
                                ?>
                                    <tr class="<?php if($i%2==0) echo 'even'; else echo 'odd';?>">
                                        <td><?php echo $employee_id?></td>
                                        <td><?php echo $name?></td>
                                        <td><?php echo $lastlogin_at?></td>
                                        <td></td>
                                        <td class="edit-delete-user">
                                            <a href="<?php echo $_core->getUrl("admin/manage-user.php?id=$user_id ") ?>"><img class="ico" src="<?php echo $_core->getSkinUrl('images/edit.png') ?>" title="edit user" /></a>
                                            <?php if($user_status==1):?>
                                            <a href="<?php echo $_core->getUrl("admin/user-status-update.php?id=$user_id&status=0") ?>"><img class="ico" alt="disable user" src="<?php echo $_core->getSkinUrl('images/disable-user.png') ?>" title="disable user" /></a>
                                            <?php else:?>
                                            <a href="<?php echo $_core->getUrl("admin/user-status-update.php?id=$user_id&status=1") ?>"><img class="ico" alt="enable user" src="<?php echo $_core->getSkinUrl('images/enable-user.png') ?>" title="enable user" /></a>
                                            <?php endif;?>
                                        </td>
                                    </tr>
                                    <?php $i++; endforeach;?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <form action="" name="search_query" method="post">
                                        <td><input type="text" name="column[employee_id]" value="" /></td>
                                        <td><input type="text" name="column[user_name]" value="" /></td>
                                        <td><input type="text" name="column[lastlogin_at]" value="" /></td>
                                        <td><input type="text" name="column[current_project]" value="" /></td>
                                        <td><input type="submit" name="search_column" value="Submit" /></td>
                                    </form>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>