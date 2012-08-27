<?php
/**
/**
 * Description of Class_Core
 *
 * @ method __construct()
 * @ method __destruct()
 * @ method setPassword($password)
 * @ method userLoginValidate($email, $password)
 * @ method adminLoginValidate($email, $password)
 * @ method isUserLoggedIn()
 * @ method isAdminLoggedIn()
 * @ method getNumberOfPages()
 * @ method userLogout()
 * @ method adminLogout()
 *
 * @author PM
 */

class Class_Core {
    
    public $con = NULL;
	

    function __construct() {
        try {
            $this->con = mysql_connect(HOST, DB_USERNAME, DB_PASSWORD);
            mysql_select_db(DB_NAME, $this->con);
            if (!$this->con)
                throw new Exception('Database connection is not established');
        } catch (Exception $e) {
            echo $e->getMessage() . ' in file ' . $e->getFile() . ' in line ' . $e->getLine();
        }
    }

    function __destruct() {
        //mysql_close($this->con);
    }

    function redirect($path = '') {
        header('Location: ' . $this->getUrl($path));
        return;
    }

    function close() {
        mysql_close($this->con);
        return;
    }

    function getSkinUrl($path = '') {
        return SKIN . $path;
    }

    function getUrl($path = '') {
        return ROOT_URL . $path;
    }
	
    function setPassword($password){
	$ency_password = md5($password);
	return $ency_password;
    }
    
    function userLoginValidate($email, $password){
        $enct_password = $this->setPassword($password);
        $sql_check_user = "SELECT COUNT(*) AS no_of_records FROM user WHERE user_email='$email' AND user_pass='$enct_password' AND user_id>0 AND user_status=1 LIMIT 0,1";
        $res_check_user = mysql_query($sql_check_user);
        $row_check_user = mysql_fetch_row($res_check_user);
        $no_of_rows = $row_check_user[0];
        if ($no_of_rows==1) {
            $sql_update = "UPDATE `user` SET lastlogin_at = NOW() WHERE user_email='$email' AND user_pass='$enct_password' AND user_id>0";
            mysql_query($sql_update);
	// Set Session
            $_SESSION['user']=$email;
            $_SESSION['pass']=$this->setPassword($password);
            return true;
        }
        return false;
    }
    
    function adminLoginValidate($email, $password){
        $enct_password = $this->setPassword($password);
        $sql_check_user = "SELECT COUNT(*) AS no_of_records FROM user WHERE user_email='$email' AND user_pass='$enct_password' AND user_id>0 AND user_role=1 LIMIT 0,1";
        //echo $sql_check_user; exit;
        $res_check_user = mysql_query($sql_check_user);
        $row_check_user = mysql_fetch_row($res_check_user);
        $no_of_rows = $row_check_user[0];
        //echo $no_of_rows; exit;
        if ($no_of_rows==1) {
            $sql_update = "UPDATE `user` SET lastlogin_at = NOW() WHERE user_email='$email' AND user_pass='$enct_password' AND user_id>0 AND user_role=1 AND user_status=1";
            mysql_query($sql_update);
	// Set Session
            $_SESSION['admin_user']=$email;
            $_SESSION['admin_pass']=$this->setPassword($password);
            return true;
        }
        return false;
    }
    
    function isUserLoggedIn(){
        if (array_key_exists('user', $_SESSION) && array_key_exists('pass', $_SESSION)) {
            $email = $_SESSION['user'];
            $password = $_SESSION['pass'];
            if ($email && $password) {
                $sql_log_user = "SELECT COUNT(*) AS no_of_records FROM user WHERE user_email='$email' AND user_pass='$password' AND user_id>0 AND user_status=1 LIMIT 0,1";
                //echo $sql_log_user; exit;
                $result = mysql_query($sql_log_user);
                $log_fetch_row = mysql_fetch_row($result);
                if ($log_fetch_row['0'] == 1)
                    return true;
            }
        }
        return false;
    }
    
    function isAdminLoggedIn(){
        if (array_key_exists('admin_user', $_SESSION) && array_key_exists('admin_pass', $_SESSION)) {
            $email = $_SESSION['admin_user'];
            $password = $_SESSION['admin_pass'];
            if ($email && $password) {
                $sql_log_user = "SELECT COUNT(*) AS no_of_records FROM user WHERE user_email='$email' AND user_pass='$password' AND user_id>0 AND user_role=1 AND user_status=1 LIMIT 0,1";
                //echo $sql_log_user; exit;
                $result = mysql_query($sql_log_user);
                $log_fetch_row = mysql_fetch_row($result);
                if ($log_fetch_row['0'] == 1)
                    return true;
            }
        }
        return false;
    }
    
    function getNumberOfPages($row_per_page=10){
        $query = "SELECT COUNT(*) AS no_of_rows FROM user";
        $result = mysql_query($query);
        $row = mysql_fetch_array($result);
        $total_rows = $row['no_of_rows'];
        $no_of_pages=ceil($total_rows/$row_per_page);
        return $no_of_pages;
    }
    
    public function getTotalRecords($tablename){
        $query = "SELECT COUNT(*) AS no_of_rows FROM $tablename";
        $result = mysql_query($query);
        $row = mysql_fetch_array($result);
        $total_records = $row['no_of_rows'];
        return $total_records;
    }
    
    public function implodeArray($arr){
        return implode(",", $arr);
    }
    
    public function explodeArray($arr){
        return explode(",", $arr);
    }
    
    /*convert Date : dd/mm/yy to strtotime
     */
    public function convertDateStrtotime($date){
        return strtotime(str_replace("/", "-", $date));
    }
    
    /*get frist 10 words from string
     */
    public function getShortString($string){
        $short_str = implode(' ', array_slice(explode(' ', $string), 0, 10));
        //$truncate_str = substr($activity, 0, 25);
        return $short_str;
    }
    
    public function convertDateInLocalFormat($date){
        return date('d/m/Y',  strtotime($date));
    }


    function userLogout(){
        unset($_SESSION['user']);
        unset($_SESSION['pass']);
            return true;
    }
    
    function adminLogout(){
        unset($_SESSION['admin_user']);
        unset($_SESSION['admin_pass']);
            return true;
    }
}
?>