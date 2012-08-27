<script type="text/javascript" charset="utf-8">
        $().ready(function() {
                var opts = {
                        cssClass : 'el-rte',
                        // lang     : 'ru',
                        height   : 160,
                        toolbar  : 'complete',
                        cssfiles : ['css/elrte-inner.css']
                }
                $('.editor').elrte(opts);
        })
</script>
<?php
/* Create object of user class */

include_once 'class/Class_user.php';
$report = new Class_user();

/* Get Slots from 0700 AM to 1000 PM */
$slots = $report->getSlots();
$slot_ids = array_keys($slots);
$slot_count = count($slots);
//print_r($slots); exit;

/* Get Activity Types  */
$activity_types = $report->getActivityTypes();
$activity_counter = count($activity_types);
$activity_type_id = array_keys($activity_types);

$today_date = date("m/d/Y");

if(array_key_exists('date',$_POST)){
	if($_POST['date']!='')
		$selected_date = $_POST['date'];
}
else
	$selected_date = $today_date;
$_SESSION['selected_date'] = $selected_date;
?>

    <script type="text/javascript">
        $(".tab-field").hide();
        
        function displayTab(id){
            $("#tabs-content-"+id).show();
        }
        function hideTab(id){
            $("#tabs-content-"+id).hide();
        }
        function activityEntry(id){
            var slot_id = id;
            var activity_id = $("#activity-type-"+id).children(":selected").attr("id");
            var activity_desc = $("#activity-"+id).val();
			//alert(slot_id + activity_id + activity_desc);
            var request = $.ajax({
                url: "http://127.0.0.1/cReport/activity-entry.php",
                type: "POST",
                data: {slot_id : slot_id, activity_id : activity_id, activity_desc : activity_desc},
                dataType: "html"
                }).done( function(data){
					$(".message").html(data);
				});
        }
    </script>