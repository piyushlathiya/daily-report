<?php
include_once ('Core.php');
class Class_User extends Class_Core{

    public function getUserId() {
        $user_id = 0;
        $email = $_SESSION['user'];
        $password = $_SESSION['pass'];
        if ($email && $password) {
            $sql_log_user = "SELECT user_id FROM user WHERE user_email='$email' AND user_pass='$password' LIMIT 0,1";
            $result = mysql_query($sql_log_user);
            $log_fetch_row = mysql_fetch_row($result);
            if ($log_fetch_row[0] > 0) {
                $user_id = $log_fetch_row[0];
            }
        }
        return $user_id;
    }

    public function getEmployeeId(){
        $user_id = $this->getUserId();
        $employee_id = '';
        if($user_id>0){
            $query = "SELECT employee_id FROM user WHERE user_id=$user_id";
            //echo $query; exit;
            $result = mysql_query($query);
            $log_fetch_row = mysql_fetch_row($result);
            if ($log_fetch_row[0]!=null) {
                    $employee_id = $log_fetch_row[0];
            }
        }
        return $employee_id;
    }

    public function createDirectory(){
        $user_id = $this->getUserId();
        if($user_id>0){
            $path = UPLOAD_DIR.$user_id;
            if(!file_exists($path)){
                mkdir($path);
            }
            return true;
        }
        return false;
    }

    public function getSlots(){
        $slots = '';
        $i=0;
        $result = mysql_query('SELECT * FROM time_slot');
        while($row = mysql_fetch_array($result)){
            $slots[$row['slot_id']] = $row['slot'];
            $i++;
            }
        return $slots;
    }
    
    public function getActivities(){
        $selected_date = $_SESSION['selected_date'];
        $query = "SELECT * FROM activity WHERE date='$selected_date' ORDER BY from_time";
        $result = mysql_query($query);
        $activities = array();
        while($row = mysql_fetch_array($result)){
            $activities[] = $row;}
        //print_r($activities); exit;
        return $activities;
    }
    
    public function deleteActivity($id=0){
        $affected_row = 0;
        if($id>0){
            $user_id = $this->getUserId();
            $query = "DELETE FROM activity WHERE entity_id=$id AND user_id=$user_id";
            //echo $query; exit;
            $result = mysql_query($query);
            $affected_row = mysql_affected_rows();
        }
        return $affected_row;
    }
    
    public function getActivityTypes(){
        $result = mysql_query('SELECT * FROM activity_type');
        while($row = mysql_fetch_array($result)){
            $activity_types[$row['type_id']] = $row['type_name'];
            }
        return $activity_types;
    }
    
    public function getActivityTypeName($id){
        $activity_name = '';
        $query = "SELECT type_name FROM activity_type WHERE type_id =".$id;
        $result = mysql_query($query);
        $fetch_activity_name = mysql_fetch_row($result);
        if(count($activity_name)!=null)
            $activity_name = $fetch_activity_name[0];
        return $activity_name;
    }
    
    public function getActivity($slot_id, $selected_date){
        $selected_datetime = strtotime($selected_date);
        $start_date = date("Y-m-d H:i:s", $selected_datetime);
        $end_date = date("Y-m-d H:i:s", ($selected_datetime)+86400);
        $activity = '';
        if($slot_id>0){
            $query = "SELECT activity_desc FROM activity WHERE slot_id =".$slot_id." AND created_time BETWEEN '".$start_date ."' AND '". $end_date."' LIMIT 1";
            //echo $query; exit;
            $result = mysql_query($query);
            $row = mysql_fetch_row($result);
            $activity = $row[0];
        }
        return $activity;
    }
    
    public function getActivityTypeId($slot_id, $selected_date){
        $selected_datetime = strtotime($selected_date);
        $start_date = date("Y-m-d H:i:s", $selected_datetime);
        $end_date = date("Y-m-d H:i:s", ($selected_datetime)+86400);
        $activity_id = 0;
        if($slot_id>0){
            $query = "SELECT activity_id FROM activity WHERE slot_id =".$slot_id." AND created_time BETWEEN '".$start_date ."' AND '". $end_date."' LIMIT 1";
            //echo $query; exit;
            $result = mysql_query($query);
            $row = mysql_fetch_row($result);
            $activity_id = $row[0];
            //echo $activity_id; exit;
        }
        return $activity_id;
    }
    
    public function getActivityId($slot_id, $selected_date){
        $selected_datetime = strtotime($selected_date);
        $start_date = date("Y-m-d H:i:s", $selected_datetime);
        $end_date = date("Y-m-d H:i:s", ($selected_datetime)+86400);
        $activity_id = 0;
        if($slot_id>0){
            $query = "SELECT id FROM activity WHERE slot_id =".$slot_id." AND created_time BETWEEN '".$start_date ."' AND '". $end_date."' LIMIT 1";
            //echo $query; exit;
            $result = mysql_query($query);
            $row = mysql_fetch_row($result);
            if($row[0]>0)
                $activity_id = $row[0];
        }
        return $activity_id;
    }
    
    public function getTruncateString($slot_id, $selected_date){
        $activity = $this->getActivity($slot_id, $selected_date);
        $truncate_str = implode(' ', array_slice(explode(' ', $activity), 0, 10));
        //$truncate_str = substr($activity, 0, 25);
        return $truncate_str;
    }
    
    public function isActivityExist($from, $to, $selected_date){
        //$selected_date = date("Y-m-d",$selected_date);
        //echo $selected_date; exit;
        $query = "SELECT COUNT(*) FROM activity WHERE date='$selected_date' AND ((from_time BETWEEN '$from' AND '$to') OR (to_time BETWEEN '$from' AND '$to'))";
        //$query = "SELECT COUNT(*) FROM activity WHERE date='$selected_date' AND ((from_time BETWEEN '$from' AND '$to') OR (to_time BETWEEN '$from' AND '$to') OR from_time <> ('$from' AND '$to') OR to_time <> ('$from' AND '$to'))";
        //echo $query; exit;
        $result = mysql_query($query);
        $row = mysql_fetch_row($result);
        //print_r($row); exit;
        if($row[0]!=0)
            return true;
        return 0;
    }
    
    public function insertActivity($from, $to, $activity_type_id, $activity_desc){
        $selected_date = $_SESSION['selected_date'];
        $user_id = $this->getUserId();
        $is_activity_exist = $this->isActivityExist($from, $to, $selected_date);
        $activity_desc = mysql_escape_string($activity_desc);
        //echo $is_activity_exist; exit;
        $inserted=0;

        if($is_activity_exist==0){
            $query = "INSERT INTO `activity` (
                                    `user_id` ,
                                    `date` ,
                                    `from_time` ,
                                    `to_time` ,
                                    `activity_type_id` ,
                                    `activity_desc` ,
                                    `created_time` ,
                                    `updated_time`
                                    )
                                VALUES (
                                '$user_id', '$selected_date', '$from', '$to', '$activity_type_id', '$activity_desc', NOW(), NOW()) ";
            //echo $query; exit;
            $inserted = mysql_query($query);
        }
        if($inserted)
            return true;
        return false;
    }
    
    public function getUserDetails($id){
        $query = "SELECT * FROM user WHERE user_id=$id LIMIT 0,1" ;
        //echo $query; exit;
        $result = mysql_query($query);
		while($row = mysql_fetch_array($result)){
			return $row;
			break;
		}
        $row = mysql_fetch_row($result);
        return false;
    }
    
    public function getUserName(){
        $user_id = $this->getUserId();
        $query = "SELECT user_fname, user_lname FROM user WHERE user_id=$user_id LIMIT 0,1" ;
        //echo $query; exit;
        $result = mysql_query($query);
        $result = mysql_fetch_array($result);
        $user_name = $result['user_fname'].' '.$result['user_lname'];
        //print_r($result); exit;
        return $user_name;
    }
    
    public function getUserEmail(){
        $user_id = $this->getUserId();
        $query = "SELECT user_email FROM user WHERE user_id=$user_id LIMIT 0,1" ;
        //echo $query; exit;
        $result = mysql_query($query);
        $result = mysql_fetch_array($result);
        $user_email = $result['user_email'];
        //print_r($result); exit;
        return $user_email;
    }

    public function getUserProfileThumbnailImage(){
       $user_id = $this->getUserId();
        $query = "SELECT photo_path FROM user WHERE user_id=$user_id LIMIT 0,1" ;
        //echo $query; exit;
        $result = mysql_query($query);
        $result = mysql_fetch_array($result);
        if($result['photo_path']!='')
            $photo_path = 'upload/thumbnail/'.$user_id.'/'.$result['photo_path'];
        else
            $photo_path = 'upload/default.png';
        //print_r($result); exit;
        return $photo_path; 
    }

    public function getUserProfileImage(){
        $user_id = $this->getUserId();
        $query = "SELECT photo_path FROM user WHERE user_id=$user_id LIMIT 0,1" ;
        //echo $query; exit;
        $result = mysql_query($query);
        $result = mysql_fetch_array($result);
        if($result['photo_path']!='')
            $photo_path = 'upload/'.$user_id.'/'.$result['photo_path'];
        else
            $photo_path = 'upload/default.png';
        //print_r($result); exit;
        return $photo_path;
    }

    /*    
	public function getUserGridDetails($start_limit=0, $end_limit=10){
		$user_data = array();
		$query = "SELECT user_id, employee_id, user_fname, user_lname, lastlogin_at FROM user LIMIT $start_limit, $end_limit";
		$result = mysql_query($query);
		//print_r($query); exit;
        while($row = mysql_fetch_array($result)){
			$id = $row['user_id'];
            $employee_id = $row['employee_id'];
			$name = ucwords($row['user_fname']).' '.ucwords($row['user_lname']);
            $lastlogin_at = $row['lastlogin_at'];
			$user_data[$id] = array('employee_id'=>$employee_id, 'name'=>$name, 'lastlogin_at'=>$lastlogin_at);
        }
        var_dump($user_data); exit;
        return $user_data;
	}
    */

    public function updateUserDetail($user_id, $user_fname, $user_mname, $user_lname, $user_email, $user_pass, $user_gender, $dob, $user_address_1, $user_address_2, $user_country, $user_state, $user_city, $user_postcode, $user_phone, $dbdest, $profile_photo){

        $user_address1 = mysql_real_escape_string($user_address_1);
        $user_address2 = mysql_real_escape_string($user_address_2);
        if($user_pass=='' && $dbdest=='' && $profile_photo=='on'){
            $query = "UPDATE user SET user_fname='$user_fname', user_mname='$user_mname', user_lname='$user_lname', user_email='$user_email', user_gender='$user_gender', dob='$dob',  user_address1="."\"$user_address1\"".", user_address2="."\"$user_address2\"".", user_country='$user_country', user_state='$user_state', user_city='$user_city', user_postcode='$user_postcode', user_phone='$user_phone',photo_path='', updated_time=NOW() WHERE user_id=$user_id";
        }
        else if($user_pass=='' && $dbdest!=''){
            $query = "UPDATE user SET user_fname='$user_fname', user_mname='$user_mname', user_lname='$user_lname', user_email='$user_email', user_gender='$user_gender', dob='$dob',  user_address1="."\"$user_address1\"".", user_address2="."\"$user_address2\"".", user_country='$user_country', user_state='$user_state', user_city='$user_city', user_postcode='$user_postcode', user_phone='$user_phone', photo_path='$dbdest', updated_time=NOW() WHERE user_id=$user_id";
        //echo $query; exit;
        }
        else if($user_pass=='' && $dbdest==''){
            $query = "UPDATE user SET user_fname='$user_fname', user_mname='$user_mname', user_lname='$user_lname', user_email='$user_email', user_gender='$user_gender', dob='$dob',  user_address1="."\"$user_address1\"".", user_address2="."\"$user_address2\"".", user_country='$user_country', user_state='$user_state', user_city='$user_city', user_postcode='$user_postcode', user_phone='$user_phone', updated_time=NOW() WHERE user_id=$user_id";
        }
        else{
            $pass=  md5($user_pass);
            $query = "UPDATE user SET user_fname='$user_fname', user_mname='$user_mname', user_lname='$user_lname', user_email='$user_email', user_gender='$user_gender', dob='$dob',  "."\"$user_address1\"".", "."\"$user_address2\"".", user_country='$user_country', user_state='$user_state', user_city='$user_city', user_postcode='$user_postcode', user_status=$user_status, user_phone='$user_phone', photo_path='$dbdest', user_pass='$pass', updated_time=NOW() WHERE user_id=$user_id";
        }
        //echo $query; exit;
        $query = mysql_query($query);
        if($query)
            return true;
        return false;
    }
    
    public function moveThumbnailImage($dbdest){
        $nw = THUMB_WIDTH;    //New Width
        $nh = THUMB_HEIGHT;    //new Height
        $user_id = $this->getUserId();
        $source = UPLOAD_DIR.$user_id.DS.$dbdest;
        if(!is_dir($source))
            mkdir(UPLOAD_DIR.'thumbnail'.DS.$user_id);
        $dest = UPLOAD_DIR.'thumbnail'.DS.$user_id.DS.$dbdest;
        $stype = explode(".", $source);
        $stype = $stype[count($stype)-1];
        $size = getimagesize($source);
        $w = $size[0];    //Images width
        $h = $size[1];    //Images height
        
        switch($stype) {
            case 'gif':
            $simg = imagecreatefromgif($source);
            break;
            case 'jpg':
            $simg = imagecreatefromjpeg($source);
            break;
            case 'png':
            $simg = imagecreatefrompng($source);
            break;
        }
        $dimg = imagecreatetruecolor($nw, $nh);
        $wm = $w/$nw;
        $hm = $h/$nh;
        $h_height = $nh/2;
        $w_height = $nw/2;
        
        if ($w > $h) {
            $adjusted_width = $w / $hm;
            $half_width = $adjusted_width / 2;
            $int_width = $half_width - $w_height;
            imagecopyresampled($dimg, $simg, -$int_width, 0, 0, 0, $adjusted_width, $nh, $w, $h);
        } elseif (($w < $h) || ($w == $h)) {
            $adjusted_height = $h / $wm;
            $half_height = $adjusted_height / 2;
            $int_height = $half_height - $h_height;
            imagecopyresampled($dimg, $simg, 0, -$int_height, 0, 0, $nw, $adjusted_height, $w, $h);
        } else {
            imagecopyresampled($dimg, $simg, 0, 0, 0, 0, $nw, $nh, $w, $h);
        }
        imagejpeg($dimg, $dest, 100);
        return true;
    }
    
    public function moveUploadedPhoto($name, $tmp_name){
        $user_id = $this->getUserId();
        $createDirectory = $this->createDirectory();
        if($createDirectory){
            $_extension = explode(".",$name);
            $extension = $_extension[count($_extension)-1];
            $time = time();
            $dest = UPLOAD_DIR.$user_id.DS.$time.'.'.$extension;
            $dbdest =$time.'.'.$extension;
            if(move_uploaded_file($tmp_name,$dest)){
                $move_thumbnail_image = $this->moveThumbnailImage($dbdest);
                return $dbdest;
            }
        }
        return false;
    }
    
    public function userStatusEdit($user_id,$status){
        if($status==1)
            $query = "UPDATE user SET user_status=1 WHERE user_id=$user_id AND user_status=0 AND user_role=2";
        else
            $query = "UPDATE user SET user_status=0 WHERE user_id=$user_id AND user_status=1 AND user_role=2";
        $result = mysql_query($query);
        $affected_row = mysql_affected_rows();
        if($result)
            return $affected_row;
        return false;
    }
    
    public function isAlreadyExistDayRecord($employee_id, $date){
        $exist=0;
        $query = "SELECT COUNT(*) AS no_of_rows FROM day WHERE employee_id='$employee_id' AND date='$date'";
        //echo $query; exit;
        $result = mysql_query($query);
        $row = mysql_fetch_row($result);
        $exist = $row['0'];
        //echo $exist; exit;
        return $exist;
    }
    
    public function insertDayInOutRate($employee_id, $date, $m_intime, $m_outtime, $e_intime, $e_outtime, $rate){
        $morning_intime='';
        $morning_outtime='';
        $evening_intime='';
        $evening_outtime='';
        if($m_intime)
            $morning_intime = date("Y-m-d H:i:s", strtotime($m_intime));
        if($m_outtime)
            $morning_outtime = date("Y-m-d H:i:s", strtotime($m_outtime));
        if($e_intime)
            $evening_intime = date("Y-m-d H:i:s", strtotime($e_intime));
        if($e_outtime)
            $evening_outtime = date("Y-m-d H:i:s", strtotime($e_outtime));
        if(strtotime($m_intime) < strtotime($m_outtime) && strtotime($e_intime) < strtotime($e_outtime)){
            $already_exist = $this->isAlreadyExistDayRecord($employee_id, $date);
            if($already_exist==1)
                $query = "UPDATE day SET morning_intime='$morning_intime', morning_outtime='$morning_outtime', evening_intime='$evening_intime', evening_outtime='$evening_outtime', rate='$rate', updated_time=NOW() WHERE employee_id='$employee_id' AND date='$date'";

            else
                $query = "INSERT INTO day (employee_id, date, morning_intime, morning_outtime, evening_intime, evening_outtime, rate, created_time, updated_time) VALUES ('$employee_id', '$date', '$morning_intime', '$morning_outtime', '$evening_intime', '$evening_outtime', $rate, NOW(), NOW())";

            //echo $query; exit;
            $result = mysql_query($query);
            if($result)
                return true;
        }
        return false;
    }
    
    public function getCurrentDayInOutRate($selected_date){
        $date = date('Y-m-d', strtotime($selected_date));
        $employee_id = $this->getEmployeeId();
        $query = "SELECT morning_intime, morning_outtime, evening_intime, evening_outtime, rate FROM day WHERE employee_id='$employee_id' AND date='$date'";
        //echo $query; exit;
        $result = mysql_query($query);
        $row = mysql_fetch_array($result);
        //print_r($row); exit;
        return $row;
    }
}