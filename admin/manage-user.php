<?php
include_once('include/application_top.php');

$admin_logged_in = $_core->isAdminLoggedIn();
if(!$admin_logged_in){
    $_SESSION['messege'] = array('type' => 'fail', 'text' => 'please login first to access your account');
    $_core->redirect('admin/index.php');
    $_core->close();
    exit;
}
$page_flag='manage-user';

$id='';
if (array_key_exists('id', $_GET) && $admin_logged_in)
    $id = $_GET['id'];
if($id=='')
    $id = $_user->getUserId();

if($id>0){
    $user_data = $_user->getUserDetails($id);
    extract($user_data);
    $profile_photo_src = $_core->getSkinUrl('upload/'.$id.'/'.$photo_path);
    if($photo_path=='')
        $profile_photo_src = $_core->getSkinUrl('upload/default.png');
}
//print_r($user_data); exit;
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Aureate Labs - Profile</title>
        <script type= "text/javascript" src = "<?php echo $_core->getUrl('js/jQuery.min.js')?>"></script>
        <script type="text/javascript" src="<?php echo $_core->getUrl('js/date.js')?>"></script>
        <script type="text/javascript" src="<?php echo $_core->getUrl('js/jquery.datePicker.js')?>"></script>
        <script type="text/javascript" src="<?php echo $_core->getUrl('js/jquery.validate.js')?>"></script>

        <link rel="stylesheet" href="<?php echo $_core->getSkinUrl('datePicker.css')?>" type="text/css" media="all" />

        <script type="text/javascript">
            $(function(){
                $(".notice").hide();
            });
            
            $(document).ready(function(){
                $("#new_user").validate();
            });

            function validate()
            {
                //  Validation
                var err_flag=0;
                var err='';
                $(".field-row").find('p').remove();

                if(document.new_user.user_pass.value!=document.new_user.user_repass.value && document.new_user.user_pass.value!=''){
                    err += "Password is not matched \n";
                    err_flag = 1;
                }

                var reg = /^([0-9]{3,15})/;
                var user_phone = document.new_user.user_phone.value;
                if(reg.test(user_phone)==false && user_phone!=''){
                    err += "Please enter numeric mobile number";
                    err_flag = 1;
                }

                //  Norify Error
                if(err_flag){
                    if(err!='') alert(err);
                    return false;
                }
                return true;
            }
        </script>
        <script type= "text/javascript" src = "<?php echo $_core->getUrl('js/countries.js')?>"></script>
    </head>
<body>
<?php include_once ('include/header.php'); ?>
    <div class="admin-wrapper manage-user user-edit">
        <div class="user-form">
            <h1><?php if($id>0): ?>User Name : <?php echo $user_fname.' '.$user_mname.' '.$user_lname; else:?>Add new user<?php endif;?></h1>
            <div class="form">
                <form id="new_user" name="new_user" method="post" action="<?php echo $_core->getUrl('admin/save-user.php')?>" onsubmit="return validate();" enctype="multipart/form-data" >
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
                                <div class="label"><label for="user_pass">Password</label></div>
                                <input type="password" name="user_pass" id="user_pass" class="field" title="Please enter password" />
                            </div>
                            <div class="field-row odd">
                                <div class="label"><label for="user_repass">Re-Password</label></div>
                                <input type="password" name="user_repass" id="user_repass" class="field" title="Please enter password again" />
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
                            <div class="field-row odd">
                                <div class="label"><label for="user_role">Role<span class="require-field">*</span></label></div>
                                <select name="user_role" class="drop-down" id="user_role">
                                    <option selected="selected">Employee</option>
                                    <option <?php if($user_role==1): ?> selected="selected" <?php endif;?>>Admin</option>
                                </select>
                            </div>
                            <div class="field-row even">
                                <div class="label"><label for="user_status">Status<span class="require-field">*</span></label></div>
                                <select name="user_status" class="drop-down" id="user_status">
                                    <option selected="selected" value="1">Enable</option>
                                    <option <?php if($user_status==0): ?> selected="selected" <?php endif;?> value="0">Disable</option>
                                </select>
                            </div>
                            <div class="field-row odd">
                                <div class="label"><label for="employee_id">Employee Id<span class="require-field">*</span><span class="note">e.g: E-_ _ _</span></label></div>
                                <input type="text" id="employee_id" name="employee_id" class="field required" title="Please enter emplyoee id" value="<?php echo $employee_id?>" />
                            </div>
                            <div class="field-row even">
                                <div class="label"><label for="upload_photo">Upload Profile Picture</label></div>
                                <input type="file" id="upload_photo" name="upload_photo" class="field" title="Please upload profile photo" />
                            </div>
                            <img name="upload_photo" class="profile-photo left" src="<?php echo $profile_photo_src;?>" alt="Profile photo" />
                            <div class="field-row odd">
                                <input type="checkbox" id="profile_photo" name="profile_photo" />
                                <label for="profile_photo">Remove profile picture</label>
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
</body>
</html>
