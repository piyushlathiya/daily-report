<?php
include_once ('Core.php');

class Class_Admin extends Class_Core {
    
    public function getActivityTypes($page, $limit, $column){
        $activity_types = array();
        if(!is_numeric($page) && !$page && $page<0)
            $page = PAGE; // global defined
        if(!is_numeric($limit) && !$limit && $limit<0)
            $limit = LIMIT; // global defined

        $offset = ($page*$limit)-$limit;
        $filter_result = '';
        if(is_array($column)){
            extract($column);
            if($type_id!='')
                $filter_result = "type_id LIKE $type_id AND ";
            if($type_name!='')
                $filter_result .= " type_name LIKE '$type_name' AND ";
            if($type_desc!='')
                $filter_result .= " type_desc LIKE '$type_desc' AND ";
            if($created_time!='')
                $filter_result .= " current_project LIKE '$created_time' AND ";
            if($updated_time!='')
                $filter_result .= " current_project LIKE '$updated_time' AND ";
        }
        $filter_result .= " 1=1";
        
        $query = "SELECT * FROM activity_type LIMIT $limit OFFSET $offset";
        $result = mysql_query($query);

        while($row = mysql_fetch_array($result))
            $activity_types[] = $row;
        
        //print_r($activities); exit;
        return $activity_types;
    }
    
    public function getActivityType($type_id){
        $query = "SELECT type_name, type_desc FROM activity_type WHERE type_id=$type_id";
        $result = mysql_query($query);
        if($result){
            $activity_type = mysql_fetch_array($result);
            return $activity_type;
        }
        return false;
    }
    
    public function getUserGridDetail($page, $limit, $column){
        $user_data = array();

        //echo $user_name; exit;
        if(!is_numeric($page) && !$page && $page<0)
            $page = PAGE; // global defined
        if(!is_numeric($limit) && !$limit && $limit<0)
            $limit = LIMIT; // global defined

        $offset = ($page*$limit)-$limit;
        $filter_result = '';

        if(is_array($column)){
            extract($column);
            if($user_name!='')
                $filter_result = "user_fname LIKE '$user_name' OR user_lname LIKE '$user_name' OR user_mname LIKE '$user_name' AND ";
            if($employee_id!='')
                $filter_result .= " employee_id LIKE '$employee_id' AND ";
            if($lastlogin_at!='')
                $filter_result .= " lastlogin_at LIKE '$lastlogin_at' AND ";
            if($current_project!='')
                $filter_result .= " current_project LIKE '$current_project' AND ";
        }
        $filter_result .= " 1=1";
        
        //echo $filter_result; exit;
        
        $query = "SELECT user_id, employee_id, user_fname, user_lname, lastlogin_at, user_status FROM user WHERE $filter_result LIMIT $limit OFFSET $offset";
        //echo $query; exit;
        $result = mysql_query($query);
        
        while($row = mysql_fetch_array($result))
            $user_data[] = $row;
        //var_dump($user_data); exit;
        return $user_data;
    }
    
//    while($row = mysql_fetch_array($result)){
//            $id = $row['user_id'];
//            $employee_id = $row['employee_id'];
//            $name = ucwords($row['user_fname']).' '.ucwords($row['user_lname']);
//            $lastlogin_at = $row['lastlogin_at'];
//            $user_status = $row['user_status'];
//            $user_data[$id] = array('employee_id'=>$employee_id, 'name'=>$name, 'lastlogin_at'=>$lastlogin_at, 'user_status'=>$user_status);
//        }
    
    public function updateActivityType($type_id, $type_name, $type_desc){
        $query = "UPDATE activity_type SET type_name='$type_name', type_desc='$type_desc', updated_time=NOW() WHERE type_id=$type_id";
        $result = mysql_query($query);
        if($result)
            return true;
        return false;
    }
    
    public function saveActivityType($type_name, $type_desc){
        $query = "INSERT INTO activity_type (type_name, type_desc, created_time, updated_time) VALUES ('$type_name', '$type_desc', NOW(), NOW())";
        $result = mysql_query($query);
        if($result)
            return true;
        return false;
    }
    
    public function deleteActivityType($type_id=0){
        if($type_id>0){
            $query = "DELETE FROM activity_type WHERE type_id=$type_id";
            $result = mysql_query($query);
            if($result)
                return true;
            return false;
        }
    }
    
    public function updateUserDetail($employee_id, $user_id, $user_fname, $user_mname, $user_lname, $user_email, $user_pass, $user_gender, $dob, $user_address_1, $user_address_2, $user_country, $user_state, $user_city, $user_postcode, $user_phone, $user_role, $user_status, $dbdest, $profile_photo){

        $user_address1 = mysql_real_escape_string($user_address_1);
        $user_address2 = mysql_real_escape_string($user_address_2);
        if($user_pass=='' && $dbdest=='' && $profile_photo=='on'){
            $query = "UPDATE user SET employee_id='$employee_id', user_fname='$user_fname', user_mname='$user_mname', user_lname='$user_lname', user_email='$user_email', user_gender='$user_gender', dob='$dob',  user_address1="."\"$user_address1\"".", user_address2="."\"$user_address2\"".", user_country='$user_country', user_state='$user_state', user_city='$user_city', user_postcode='$user_postcode',user_status=$user_status, user_phone='$user_phone', user_role='$user_role', photo_path='', updated_time=NOW() WHERE user_id=$user_id";
        }
        else if($user_pass=='' && $dbdest!=''){
            $query = "UPDATE user SET employee_id='$employee_id', user_fname='$user_fname', user_mname='$user_mname', user_lname='$user_lname', user_email='$user_email', user_gender='$user_gender', dob='$dob',  user_address1="."\"$user_address1\"".", user_address2="."\"$user_address2\"".", user_country='$user_country', user_state='$user_state', user_city='$user_city', user_postcode='$user_postcode',user_status=$user_status, user_phone='$user_phone', user_role='$user_role', photo_path='$dbdest', updated_time=NOW() WHERE user_id=$user_id";
        //echo $query; exit;
        }
        else if($user_pass=='' && $dbdest==''){
            $query = "UPDATE user SET employee_id='$employee_id', user_fname='$user_fname', user_mname='$user_mname', user_lname='$user_lname', user_email='$user_email', user_gender='$user_gender', dob='$dob',  user_address1="."\"$user_address1\"".", user_address2="."\"$user_address2\"".", user_country='$user_country', user_state='$user_state', user_city='$user_city', user_postcode='$user_postcode',user_status=$user_status, user_phone='$user_phone', user_role='$user_role', updated_time=NOW() WHERE user_id=$user_id";
        }
        else{
            $pass=  md5($user_pass);
            $query = "UPDATE user SET employee_id='$employee_id', user_fname='$user_fname', user_mname='$user_mname', user_lname='$user_lname', user_email='$user_email', user_gender='$user_gender', dob='$dob',  "."\"$user_address1\"".", "."\"$user_address2\"".", user_country='$user_country', user_state='$user_state', user_city='$user_city', user_postcode='$user_postcode', user_status=$user_status, user_phone='$user_phone', user_role='$user_role', photo_path='$dbdest', user_pass='$pass', updated_time=NOW() WHERE user_id=$user_id";
        }

        //echo $query; exit;
        $query = mysql_query($query);
        if($query)
            return true;
        return false;
    }
    
    public function addNewUser($employee_id, $user_fname, $user_mname, $user_lname, $user_email, $user_pass, $user_gender, $dob, $user_address_1, $user_address_2, $user_country, $user_state, $user_city, $user_postcode, $user_phone, $user_role, $user_status, $src, $extension){
        
        $user_pass = md5($user_pass);
        $user_address1 = mysql_real_escape_string($user_address_1);
        $user_address2 = mysql_real_escape_string($user_address_2);
        
        $move_photo = $this->moveUploadedPhoto($src, $extension);
        $photo_path = null;
        if($move_photo)
            $photo_path = $time.'.'.$extension;
        $query = "INSERT INTO user (employee_id, user_fname, user_mname, user_lname, photo_path, user_email, user_pass, user_gender, dob, user_address1, user_address2, user_country, user_state, user_city, user_postcode, user_status, user_phone, user_role, created_time, updated_time) VALUES ('$employee_id', '$user_fname', '$user_mname', '$user_lname', '$photo_path', '$user_email', '$user_pass', '$user_gender', '$dob', "."\"$user_address1\"".", "."\"$user_address2\"".", '$user_country', '$user_state', '$user_city', '$user_postcode', $user_status, '$user_phone', '$user_role', NOW(), NOW()) ";
        //echo $query; exit;
        $insert_user = mysql_query($query);
        if($insert_user)
            return true;
        return false;
    }
    
    public function getUsers(){
        $users=array();
        $query = "SELECT user_id, user_fname, user_lname FROM user";
        $result = mysql_query($query);
        $i=0;
        while($row = mysql_fetch_array($result)){
            $users[$i]['user_id']=$row['user_id'];
            $users[$i]['user_name'] = $row['user_fname'].' '.$row['user_lname'];
            $i++;
        }
        //var_dump($users); exit;
        return $users;
    }
    
    public function saveProjectOverview($project_name, $meta_description, $start_at, $end_at, $deadline, $assign_to){
        if($start_at<$end_at && $project_name!=''){
            $start_at = date("Y-m-d H:i:s", $start_at);
            $end_at = date("Y-m-d H:i:s", $end_at);
            $deadline = date("Y-m-d H:i:s", $deadline);
            $query = "INSERT INTO project_overview (project_name, meta_description, assign_to, start_at, end_at, deadline, created_time, updated_time) VALUES ('$project_name', $meta_description', '$assign_to', '$start_at', '$end_at', '$deadline', NOW(), NOW())";
            $result = mysql_query($query);
            if($result)
                return true;
        }
        return false;
    }
    
    public function getProjectGridDetail($page, $limit, $column){
        $project_data = array();
        
        if(!is_numeric($page) && !$page && $page<0)
            $page = PAGE; // global defined
        if(!is_numeric($limit) && !$limit && $limit<0)
            $limit = LIMIT; // global defined

        $offset = ($page*$limit)-$limit;
        $filter_result = '';
        
        $query = "SELECT * FROM project_overview LIMIT $limit OFFSET $offset";
        $result = mysql_query($query);
        if($result)
            while($row = mysql_fetch_array($result))
                $project_data[] = $row;
        
        return $project_data;
    }
    
    public function getProjectDesc($entity_id){
        $query = "SELECT * FROM project WHERE entity_id=$entity_id";
        $result = mysql_query($query);
        if($result){
            $project_desc = mysql_fetch_row($result);
            return $project_desc;
        }
        return false;
    }
    
    public function getAssignedUserName($id){
        $query = "SELECT user_fname, user_lname FROM user WHERE user_id = $id";
        $result = mysql_query($query);
        $row = mysql_fetch_array($result);
        $user_name = $row['user_fname'].' '.$row['user_lname'];
        return $user_name;
    }
}