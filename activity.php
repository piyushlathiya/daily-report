<?php
include_once('include/application_top.php');
$user_logged_in = $_core->isUserLoggedIn();
if(!$user_logged_in){
	$_SESSION['messege'] = array('type'=>'fail','text'=>'please login first to access your account');
	$_core->redirect('index.php');
	$_core->close();
	exit;
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Aureate - User Report</title>
        <link href="<?php echo $_core->getSkinUrl('jquery-ui.css')?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo $_core->getSkinUrl('jquery.ui.timepicker.css')?>" rel="stylesheet" type="text/css" />
        <script src="js/jQuery.min.js" type="text/javascript"></script>
        <script src="js/jquery-ui-1.8.13.custom.min.js" type="text/javascript" charset="utf-8"></script>
        <script type="text/javascript" src="<?php echo $_core->getUrl('js/jquery.ui.timepicker.js')?>"></script>
        <script type="text/javascript" src="<?php echo $_core->getUrl('js/tiny_mce/tiny_mce.js')?>"></script>
        <script type="text/javascript">
            // Initialize TinyMCE with the new plugin and listbox
            tinyMCE.init({
                plugins : '-example', // - tells TinyMCE to skip the loading of the plugin
                mode : "textareas",
                theme : "advanced",
                theme_advanced_buttons1 : "mylistbox,mysplitbutton,bold,italic,underline,separator,strikethrough,justifyleft,justifycenter,justifyright,justifyfull,bullist,numlist,undo,redo,link,unlink",
                theme_advanced_buttons2 : "",
                theme_advanced_buttons3 : "",
                theme_advanced_toolbar_location : "top",
                theme_advanced_toolbar_align : "left",
                theme_advanced_statusbar_location : "bottom"
            });
        </script>

        <script type="text/javascript">
            $(function() {
                $(".notice-activity").hide();
                $(".notice-day").hide();
                $( "#datepicker" ).datepicker();
            });

//            function displayTab(id){
//                    $("#tabs-content-"+id).show();
//            }
//            function hideTab(id){
//                    $("#tabs-content-"+id).hide();
//            }
            
            function validateActivityForm(){
                var err = '';
                if(document.save_activity.from_time.value==''){
                    err += "Please select from time <br />";
                }
                if(document.save_activity.to_time.value==''){
                    err += "Please select to time <br />";
                }
                if(document.save_activity.activity_type.value==''){
                    err += "Please select activity type <br />";
                }
                if(err!=''){
                    $(".notice-activity").show();
                    $(".notice-activity").html(err);
                    return false;
                }
                return true;
            }
            
            function validateDayInOut(){
                var err='';
                if(document.day_in_out.morning_intime.value==''){
                    err += "Please select morning in time <br />";
                }
                if(document.day_in_out.morning_outtime.value==''){
                    err += "Please select morning out time <br />";
                }
                if(document.day_in_out.evening_intime.value==''){
                    err += "Please select evening in time <br />";
                }
                if(document.day_in_out.evening_outtime.value==''){
                    err += "Please select evening out time";
                }
                if(err!=''){
                    $(".notice-day").show();
                    $(".notice-day").html(err);
                    return false;
                }
                return true;
            }
            
            function confirmAction(){
                var r=confirm("Are you sure?");
                if (r==true)
                    return true;
                else
                    return false;
            }
        </script>
    </head>
<?php
/* Create object of user class */

include_once 'Class/User.php';
$_user = new Class_User();

$today_date = date("Y-m-d");
//print_r($_POST); exit;
if (array_key_exists('submit', $_POST)) {
    if ($_POST['submit']){
        $date_format = $_core->convertDateStrtotime($_POST['selected_date']);
        $selected_date = date('Y-m-d',$date_format);
    }
}
else
    $selected_date = $today_date;

$_SESSION['selected_date'] = $selected_date;

/* Get Activities */
$activities = $_user->getActivities();
//echo count($activities); exit;
//print_r($activities); exit;

/* Get Activity Types  */
$activity_types = $_user->getActivityTypes();
$activity_counter = count($activity_types);
$activity_type_id = array_keys($activity_types);
$morning_intime=null;
$evening_intime=null;
$morning_outtime=null;
$evening_outtime=null;

$getDayInOutRate = $_user->getCurrentDayInOutRate($selected_date);
//print_r($getDayInOutRate); exit;

if(count($getDayInOutRate)>0){
    if($getDayInOutRate['morning_intime']==date("Y-m-d H:i:s", strtotime($getDayInOutRate['morning_intime'])))
        $morning_intime=date("h:i A",strtotime($getDayInOutRate['morning_intime']));
    if($getDayInOutRate['evening_intime']==date("Y-m-d H:i:s", strtotime($getDayInOutRate['evening_intime'])))
        $evening_intime=date("h:i A",strtotime($getDayInOutRate['evening_intime']));
    if($getDayInOutRate['morning_outtime']==date("Y-m-d H:i:s", strtotime($getDayInOutRate['morning_outtime'])))
        $morning_outtime=date("h:i A",strtotime($getDayInOutRate['morning_outtime']));
    if($getDayInOutRate['evening_outtime']==date("Y-m-d H:i:s", strtotime($getDayInOutRate['evening_outtime'])))
        $evening_outtime=date("h:i A",strtotime($getDayInOutRate['evening_outtime']));
    $rate = $getDayInOutRate['rate'];
}
?>
<body>
    <?php include_once 'include/header.php'; ?>
    <div class="activity-page">
        <div class="notice-day notice"></div>
        <div class="in-out-timing-form">
            <form id="day_in_out" name="day_in_out" method="post" action="<?php echo $_core->getUrl('day.php')?>" onsubmit="return validateDayInOut();">
                <div class="field-row even">
                    <div class="label"><label for="morning_intime">Morning In-time</label></div>
                    <input type="text" name="morning_intime" id="morning_intime" class="field timepicker" title="Please enter morning in time" value="<?php echo $morning_intime?>" />
                    <script>
                        $('#morning_intime').timepicker({
                                showPeriod: true,
                                showLeadingZero: true
                        });
                    </script>
                </div>
                <div class="field-row odd">
                    <div class="label"><label for="morning_outtime">Morning Out-time</label></div>
                    <input type="text" name="morning_outtime" id="morning_outtime" class="field timepicker" title="Please enter morning out time" value="<?php echo $morning_outtime?>" />
                    <script>
                        $('#morning_outtime').timepicker({
                                showPeriod: true,
                                showLeadingZero: true
                        });
                    </script>
                </div>
                <div class="field-row odd">
                    <div class="label"><label for="evening_intime">Evening In-time</label></div>
                    <input type="text" name="evening_intime" id="evening_intime" class="field timepicker" title="Please enter evening in time" value="<?php echo $evening_intime;?>" />
                    <?php // if($evening_intime==date("Y-m-d H:i:s",  strtotime($evening_intime))):echo $evening_intime;endif;?>
                   
                    <script>
                        $('#evening_intime').timepicker({
                                showPeriod: true,
                                showLeadingZero: true
                        });
                    </script>
                </div>
                <div class="field-row odd">
                    <div class="label"><label for="evening_outtime">Evening Out-time</label></div>
                    <input type="text" name="evening_outtime" id="evening_outtime" class="field timepicker" title="Please enter evening out time" value="<?php echo $evening_outtime?>" />
                    <script>
                        $('#evening_outtime').timepicker({
                                showPeriod: true,
                                showLeadingZero: true
                        });
                    </script>
                </div>
                <div class="field-row odd">
                    <div class="label"><label for="rate">Rate yourself for today</label></div>
                    <select id="rate" class="drop-down" name="rate">
                        <?php $i=0; while($i<=10):?>
                        <option <?php if($i==$rate):?>selected="selected"<?php endif?>><?php echo $i?></option>
                        <?php $i++; endwhile;?>
                    </select>
                </div>
                <div class="field-row odd submit">
                        <input type="hidden" name="today_date" value="<?php echo $today_date?>" />
                    <input type="submit" name="day" value="submit" />
                </div>
            </form>
        </div>
        <div class="demo">
            <form name="select-date" method="post" action="">
                <p>Date: <input type="text" id="datepicker" name="selected_date"></p>
                <p class="calender-submit"><input type="submit" name="submit" value="Show"></p>
            </form>
        </div>
        <div class="home-timeline">
            <h4 class="selected-date">Date : <?php echo $selected_date?></h4>
            <div class="activities">
                <?php if(count($activities)>0):?>
                <table class="table-grid">
                    <colgroup width="8%"></colgroup>
                    <colgroup width="15%"></colgroup>
                    <colgroup width="14%"></colgroup>
                    <colgroup width="38%"></colgroup>
                    <colgroup width="6%"></colgroup>
                    <thead>
                        <tr class="thead">
                            <th>Sr. No</th>
                            <th>Time</th>
                            <th>Activity type</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=0; foreach($activities as $activity):?>
                        <tr <?php if($i%2==0) echo 'even'; else echo 'odd';?>>
                            <td><?php echo $i+1?></td>
                            <td><?php echo date("h:i A", strtotime($activity['from_time']))?> to <?php echo date("h:i A", strtotime($activity['to_time']))?></td>
                            <td><?php $activity_type_ids = $_core->explodeArray($activity['activity_type_id']);
                                foreach($activity_type_ids as $type_id)
                                    echo $_user->getActivityTypeName($type_id)."<br />";
                                ?></td>
                            <td><?php echo $activity['activity_desc']?></td>
                            <td><a class="delete-activity" onclick="return confirmAction()" href="<?php echo $_core->getUrl('save-activity.php?id='.$activity['entity_id'])?>" ><img src="<?php echo $_core->getSkinUrl('images/delete.png')?>" alt="delete" /></a></td>
                        </tr>
                        <?php $i++; endforeach;?>
                    </tbody>
                </table>
                <?php else:?>
                <p class="no-found">No data found</p>
                <?php endif?>
            </div>
            <?php if($selected_date == $today_date):?>
            <h1 class="add-new-activity heading">Add new activity</h1>
            <div class="new-activity">
                <div class="notice-activity notice"></div>
                <form id="save_activity" action="<?php echo $_core->getUrl('save-activity.php')?>" name="save_activity" method="post" onsubmit="return validateActivityForm()">
                    <div class="from-to-timing" id="from_to_time">
                        <div class="left from">
                            <label for="from_time">From :</label>
                            <input type="text" name="from" id="from_time" class="field timepicker" title="Please enter morning in time" value="" />
                            <script>
                                $('#from_time').timepicker({
                                        showPeriod: true,
                                        showLeadingZero: true
                                });
                            </script>
                        </div>
                        <div class="left to">
                            <label for="to_time">To :</label>
                            <input type="text" name="to" id="to_time" class="field timepicker" title="Please enter morning out time" value="" />
                            <script>
                                $('#to_time').timepicker({
                                        showPeriod: true,
                                        showLeadingZero: true
                                });
                            </script>
                        </div>
                        <div class="activity-type">
                            <label for="activity_type" class="activity-type">Select Activity Type :</label>
                            <select multiple="multiple" name="activity_type_id[]" id="activity_type" class="activity_type">
                                <?php
                                $i=0;
                                //$selected_activity_id=$_user->getActivityTypeId($slot_id, $selected_date);
                                while ($i < $activity_counter):
                                ?>
                                <option value="<?php echo $activity_type_id[$i]; ?>" id="<?php echo $activity_type_id[$i]; ?>"><?php echo $activity_types[$activity_type_id[$i]]; ?></option>
                                <?php $i++; endwhile;?>
                            </select>
                        </div>
                        <div class="activity-desc clear">
                            <label for="activity_desc">Add Activity :</label>
                            <textarea name="activity_desc" class="editor" id="activity_desc" cols="50" rows="15"></textarea>
                        </div>
                        <input type="submit" name="submit" class="submit" value="Submit" title="Submit" />
                    </div>
                </form>
            </div>
            <?php endif?>
        </div>
    </div>
</body>
</html>