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

$type_id = null;
$type_name = null;
$type_desc = null;
$created_time = null;
$updated_time = null;
$column = '';
if(array_key_exists('search_column',$_POST))
	extract($_POST);
if(is_array($column))
    extract($column);
$activity_types = $_admin->getActivityTypes($page, $limit, $column);
$no_records = count($activity_types);
$total_records = $_core->getTotalRecords('activity_type');
//print_r($activity_types); exit;
$page_flag='activity-type';


/* Update Activity type */
$submit='';
if(array_key_exists('submit',$_POST))
	$submit = $_POST['submit'];
if($submit){
    extract($_POST);
    $update_activity_type = $_admin->updateActivityType($type_id, $type_name, $type_desc);
    if($update_activity_type){
        $_SESSION['messege'] = array('type'=>'success','text'=>'activity type is successfully updated');
	$_core->redirect('admin/activity-type.php');
	$_core->close();
	exit;
    }
    else{
        $_SESSION['messege'] = array('type'=>'fail','text'=>'activity type is not updated');
	$_core->redirect('admin/activity-type.php');
	$_core->close();
	exit;
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Aureate - Users</title>
        <script type= "text/javascript" src = "<?php echo $_core->getUrl('js/jQuery.min.js')?>"></script>
        <script type="text/javascript" src="<?php echo $_core->getUrl('js/jquery.validate.js')?>"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                $("#save_activity_type").validate();
            });
            
            function confirmAction(){
                var r=confirm("Are you sure?");
                if (r==true)
                    return true;
                else
                    return false;
            }
        </script>
    </head>

    <body class="dashboard">
        <?php include_once ('include/header.php'); ?>
        <div class="paging">
            <form action="" name="pagging" method="GET">
                <strong>Items per Page :</strong>
                <select onchange="pagging.submit()" name="limit">
                    <option selected="selected" value="10">10</option>
                    <option value="20" <?php if($limit==20):?>selected="selected"<?php endif;?> >20</option>
                    <option value="50" <?php if($limit==50):?>selected="selected"<?php endif;?>>50</option>
                    <option value="100">All</option>
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
        <div class="manage-activity-type clear">
            <h1 class="heading">Activity Types</h1>
            <?php if(count($activity_types)>0):?>
            <table class="table-grid">
                <colgroup width="6%"></colgroup>
                <colgroup width="10%"></colgroup>
                <colgroup width="30%"></colgroup>
                <colgroup span="2" width="10%"></colgroup>
                <colgroup width="6%"></colgroup>
                <thead>
                    <tr class="thead">
                        <th>Sr. No</th>
                        <th>Activity Type</th>
                        <th>Activity Description</th>
                        <th>Created Time</th>
                        <th>Updated Time</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=0; foreach($activity_types as $activity_type):
                    extract($activity_type);
                    ?>
                    <tr class="<?php if($i%2==0) echo 'even'; else echo 'odd';?>">
                        <td><?php echo $i+1?></td>
                        <td><?php echo $type_name?></td>
                        <td><?php if($type_desc=='') echo 'description is not available'; else echo $type_desc;?></td>
                        <td><?php echo $created_time?></td>
                        <td><?php echo $updated_time?></td>
                        <td>
                            <a href="javascript:popUpActivityType(<?php echo $type_id?>)" title="edit activity type" ><img class="ico" src="<?php echo $_core->getSkinUrl('images/edit.png')?>" alt="edit" /></a>
                            <a class="delete-activity-type" onclick="return confirmAction()" href="<?php echo $_core->getUrl('admin/manage-activity-type.php?id='.$type_id)?>" title="delete activity type" ><img class="ico" src="<?php echo $_core->getSkinUrl('images/delete.png')?>" alt="delete" /></a>
                        </td>
                    </tr>
                    <?php $i++; endforeach;?>
                </tbody>
                <tfoot>
                    <tr>
                        <form action="" name="search_query" method="post">
                            <td><input type="text" name="column[type_id]" value="" /></td>
                            <td><input type="text" name="column[type_name]" value="" /></td>
                            <td><input type="text" name="column[type_desc]" value="" /></td>
                            <td><input type="text" name="column[created_time]" value="" /></td>
                            <td><input type="text" name="column[updated_time]" value="" /></td>
                            <td><input type="submit" name="search_column" value="Submit" /></td>
                        </form>
                    </tr>
                </tfoot>
            </table>
            <?php else:?>
            <p class="no-found">No data found</p>
            <?php endif?>

            <div id="blanket"></div>
            <div class="edit-activity-type">
                <div class="content"></div>
            </div>

            <script  type="text/javascript">
                function popUpContent(id){
                        var request = $.ajax({
                            type: "POST",
                            url: "<?php echo $_core->getUrl('admin/edit-activity-type.php')?>",
                            data: {id : id},
                            dataType: "html"
                            }).done( function(content){
                            $(".content").html(content);
                        });
                }
                $("#blanket").hide();
                $(".edit-activity-type").hide();
                $(".edit-activity-type").css({"position" : "fixed" ,"top" : $(window).height()/2-150, "left": $(window).width()/2-162 });
                function popUpActivityType(id){
                    popUpContent(id);
                    $(".edit-activity-type").fadeIn('fast');
                    $("#blanket").show();
                }
                
                $(document).mouseup(function (e)
                {
                    var container = $(".edit-activity-type");
                    if (container.has(e.target).length === 0){
                        container.fadeOut('fast');
                        $("#blanket").hide();
                    }
                });
            </script>
            
            <div class="new-activity-type form">
                <form id="save_activity_type" action="<?php echo $_core->getUrl('admin/manage-activity-type.php')?>" name="save_activity_type" method="post">
                    <div id="type_name">
                        <div class="type_name field-row">
                            <div class="label"><label for="type_name">Activity Type :</label></div>
                            <input type="text" name="type_name" id="type_name" class="field required" title="Please enter activity type" value="" />
                        </div>
                        <div class="type_desc field-row clear">
                            <div class="label"><label for="type_desc">Activity Description :</label></div>
                            <input type="text" name="type_desc" id="type_desc" class="field required" title="Please enter description for activity" value="" />
                        </div>
                        <input type="submit" name="submit" class="submit" value="Save" title="Submit" />
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>