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
    $entity_id = $_POST['id'];

if($entity_id>0){
    $project_desc = $_admin->getProjectDesc($type_id);
    extract($project_desc);
}
?>
<img class="close" src="<?php echo $_core->getSkinUrl('images/close_button.png')?>" />
<h1 class="heading">Edit Project</h1>
<div class="form edit-project">
    <form method="post" name="save_type" action="">
        <div class="field-row">
            <div class="label">
                <label for="project_name">Project Name :</label>
            </div>
            <input type="text" value="<?php echo $project_name?>" title="Please enter project name" class="field required" id="project_name" name="project_name">
        </div>
        <div class="field-row">
            <div class="label">
                <label for="meta_description">Meta Description :</label>
            </div>
            <input type="text" value="<?php echo $meta_description?>" title="Please enter description for project" class="field required" id="meta_description" name="meta_description">
        </div>
        <div class="field-row odd">
            <div class="label"><label for="meta_description">Assign To :</label></div>
            
            <select multiple="multiple" class="required" name="assign_to[]">
                <?php $ids = $_core->explodeArray($assign_to);
                foreach($ids as $id): ?>
                <option value="<?php echo $id?>"><?php echo $_admin->getAssignedUserName($id)?></option>
                <?php endforeach;?>
            </select>
        </div>
        <div class="field-row odd">
            <div class="label"><label for="start_at">Start at :</label></div>
            <input type="text" id="start_at" class="field required" name="start_at">
        </div>
        <div class="field-row even">
            <div class="label"><label for="end_at">End at :</label></div>
            <input type="text" id="end_at" class="field required" name="end_at">
        </div>
        <div class="field-row even">
            <div class="label"><label for="deadline">Deadline :</label></div>
            <input type="text" id="deadline" class="field required" name="deadline">
        </div>
        <input type="hidden" value="<?php echo $type_id?>" name="type_id" />
        <input type="submit" title="Submit" value="Update" class="submit" name="submit">
    </form>
</div>
<script type="text/javascript">
    $(".close").click(function(){
        $(".edit-project-desc").fadeOut('fast');
        $("#blanket").hide();
    });
</script>