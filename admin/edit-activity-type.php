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

if(array_key_exists('id',$_POST))
    $type_id = $_POST['id'];
$type_name='';
$type_desc='';
if($type_id>0){
    $activity_type = $_admin->getActivityType($type_id);
    extract($activity_type);
    //print_r($activity_type); exit;
}
?>
<img class="close" src="<?php echo $_core->getSkinUrl('images/close_button.png')?>" />
<h1 class="heading">Edit Activity</h1>
<div class="form">
    <form method="post" name="save_type" action="">
        <div class="field-row">
            <div class="label">
                <label for="type_name">Activity Type :</label>
            </div>
            <input type="text" value="<?php echo $type_name?>" title="Please enter activity type" class="field required" id="type_name" name="type_name">
        </div>
        <div class="field-row">
            <div class="label">
                <label for="type_desc">Activity Description :</label>
            </div>
            <input type="text" value="<?php echo $type_desc?>" title="Please enter description for activity" class="field required" id="type_desc" name="type_desc">
        </div>
        <input type="hidden" value="<?php echo $type_id?>" name="type_id" />
        <input type="submit" title="Submit" value="Update" class="submit" name="submit">
    </form>
</div>
<script type="text/javascript">
    $(".close").click(function(){
        $(".edit-activity-type").fadeOut('fast');
        $("#blanket").hide();
    });
</script>