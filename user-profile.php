<?php
include_once('include/application_top.php');

$user_logged_in = $_core->isUserLoggedIn();
if (!$user_logged_in) {
//	var_dump(array_reverse(debug_backtrace())); exit;
    //$_SESSION['msg']['error']="";
    $_SESSION['messege'] = array('type' => 'fail', 'text' => 'please login first to access your account');
    $_core->redirect('index.php');
    $_core->close();
    exit;
}
$user_fname = null;
$user_mname = null;
$user_lname = null;
$user_email = null;
$user_gender = null;
$dob = null;
$user_address1 = null;
$user_address2 = null;
$user_country = null;
$user_state = null;
$user_city = null;
$user_postcode = null;
$user_status = null;
$user_phone = null;
$user_role = null;
$employee_id = null;

$id = $_user->getUserId();
if ($id>0){
    $user_data = $_user->getUserDetails($id);
    extract($user_data);
    $profile_photo_src = $_core->getSkinUrl('upload/'.$id.'/'.$photo_path);
    if($photo_path=='')
        $profile_photo_src = $_core->getSkinUrl('upload/default.png');
}
//print_r($user_data); exit;
?>

<script type= "text/javascript" src = "<?php echo $_core->getUrl('js/jQuery.min.js')?>"></script>
<script type="text/javascript" src="<?php echo $_core->getUrl('js/date.js')?>"></script>
<script type="text/javascript" src="<?php echo $_core->getUrl('js/jquery.datePicker.js')?>"></script>

<link rel="stylesheet" href="<?php echo $_core->getSkinUrl('datePicker.css')?>" type="text/css" media="all" />
<script type= "text/javascript" src = "<?php echo $_core->getUrl('js/countries.js')?>"></script>

<div class="manage-user user-edit">
    <div class="user-form">
        <h1><?php if($id>0): ?>User Name : <?php echo $user_fname.' '.$user_mname.' '.$user_lname; else:?>Add new user<?php endif;?></h1>
        <div class="form">
            <form id="new_user" name="new_user" method="post" action="<?php echo $_core->getUrl('save-user.php')?>" onsubmit="return validate();">
                <div class="field-group g1">
                    <div class="form-caption clear">User Personal Information</div>
                    <div class="fields-collection">
                        <div class="field-row even">
                            <div class="label"><label for="user_fname">First Name<span class="require-field">*</span></label></div>
                            <input type="text" name="user_fname" id="user_fname" class="field required" title="Please enter first name" value="<?php echo $user_fname?>" />
                        </div>
                        <div class="field-row odd">
                            <div class="label"><label for="user_mname">Middle Name</label></div>
                            <input type="text" name="user_mname" id="user_mname" class="field required" title="Please enter middle name" value="<?php echo $user_mname?>" />
                        </div>
                        <div class="field-row odd">
                            <div class="label"><label for="user_lname">Last Name<span class="require-field">*</span></label></div>
                            <input type="text" name="user_lname" id="user_lname" class="field required" title="Please enter last name" value="<?php echo $user_lname?>" />
                        </div>
                        <div class="field-row even">
                            <div class="label"><label for="user_email">Email<span class="require-field">*</span></label></div>
                            <input type="text" name="user_email" id="user_email" class="field required" title="Please enter email address" value="<?php echo $user_email?>" />
                        </div>
                        <div class="field-row odd">
                            <div class="label"><label for="user_pass">Password<span class="require-field">*</span></label></div>
                            <input type="password" name="user_pass" id="user_pass" class="field required" title="Please enter password" />
                        </div>
                        <div class="field-row odd">
                            <div class="label"><label for="user_repass">Re-Password<span class="require-field">*</span></label></div>
                            <input type="password" name="user_repass" id="user_repass" class="field required" title="Please enter password again" />
                        </div>
                        <div class="field-row even">
                            <div class="label"><label for="user_gender">Gender<span class="require-field">*</span></label></div>
                            <select name="user_gender" class="drop-down" id="user_gender">
                                <option value="" selected="selected">Select Gender</option>
                                <option <?php if($user_gender!=null): ?> selected="selected" <?php endif; ?>>Male</option>
                                <option <?php if($user_gender==1):?> selected="selected" <?php endif; ?>>Female</option>
                            </select>
                        </div>
                        <div class="field-row odd">
                            <div class="label"><label for="dob">Date of birth<span class="require-field">*</span><span class="note">yyyy/mm/dd</span></label></div>
                            <input type="text" id="date1" name="dob" class="field required date-pick" title="yyyy/mm/dd" value="<?php echo $dob?>" />
                        </div>
                        <div class="field-row even">
                            <div class="label"><label for="user_role">Role<span class="require-field">*</span></label></div>
                            <select name="user_role" class="drop-down" id="user_role">
                                <option selected="selected">Employee</option>
                                <option <?php if($user_role==1): ?> selected="selected" <?php endif;?>>Admin</option>
                            </select>
                        </div>
                        <div class="field-row odd">
                            <div class="label"><label for="employee_id">Employee Id<span class="require-field">*</span><span class="note">e.g: E-_ _ _</span></label></div>
                            <input type="text" id="employee_id" name="employee_id" class="field required" title="Please enter emplyoee id" value="<?php echo $employee_id?>" />
                        </div>
                        <div class="field-row odd">
                            <div class="label"><label for="user_status">Status<span class="require-field">*</span></label></div>
                            <select name="user_status" class="drop-down" id="user_status">
                                <option selected="selected" value="1">Enable</option>
                                <option <?php if($user_status==0): ?> selected="selected" <?php endif;?> value="0">Disable</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="field-group g2">
                    <div class="form-caption clear">User Address Information</div>
                    <div class="field-collection">
                        <div class="field-row even">
                            <div class="label"><label for="user_address1">Address 1<span class="require-field">*</span></label></div>
                            <input type="text" name="user_address1" id="user_address1" class="field required" title="enter  block/street name" value="<?php echo $user_address1?>" />
                        </div>
                        <div class="field-row odd">
                            <div class="label"><label for="user_address2">Address 2</label></div>
                            <input type="text" name="user_address2" id="user_address2" class="field required" title="Please enter nearest landmark" value="<?php echo $user_address2?>" />
                        </div>
                        <div class="field-row even">
                            <div class="label"><label for="user_country">Country<span class="require-field">*</span></label></div>
                            <select class="drop-down country" onchange="print_state('state',this.selectedIndex);" id="country" name ="user_country"></select>
                        </div>
                        <div class="field-row odd">
                            <div class="label"><label for="user_state">State<span class="require-field">*</span></label></div>
                            <select class="drop-down" name="user_state" id="state"></select>
                            <script language="javascript">print_country("country");</script>
                        </div>
                        <div class="field-row even">
                            <div class="label"><label for="user_city">City<span class="require-field">*</span></label></div>
                            <input type="text" name="user_city" id="user_city" class="field required" title="Please enter city" value="<?php echo $user_city?>" />
                        </div>
                        <div class="field-row odd">
                            <div class="label"><label for="user_postcode">Post Code<span class="require-field">*</span></label></div>
                            <input type="text" name="user_postcode" id="user_postcode" class="field required" title="Please enter post code" value="<?php echo $user_postcode?>" />
                        </div>
                        <div class="field-row">
                            <div class="label"><label for="user_phone">Contact No.<span class="require-field">*</span></label></div>
                            <input type="text" name="user_phone" id="user_phone" class="field required" title="Please enter mobile number" value="<?php echo $user_phone?>" />
                        </div>
                    </div>
                </div>
                <div class="submit clear">
                    <input type="hidden" name="user_id" value="<?php echo $id?>" />
                    <input class="button" type="submit" name="submit" value="submit" />  
                </div>
            </form>
        </div>
    </div>
    <script type="text/javascript" charset="utf-8">
        
        $(function(){
            //document.getElementbyValue getElementById("India").selected=true;
            $('#country').val('<?php echo $user_country?>');
            var state_index=document.getElementById("country").selectedIndex;
            //alert(state_index);
            print_state('state', state_index);
            $('#state').val('<?php echo $user_state?>');
        });
        $(function()
        {
            $('.date-pick').datePicker({startDate:'01/01/1996'});
        });
    </script>
</div>