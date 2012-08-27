<?php
session_start();

define('SKIN', "http://" . $_SERVER['HTTP_HOST'] ."/cReport/skin/");
define('DR',$_SERVER['DOCUMENT_ROOT'].'cReport');
define('DS', DIRECTORY_SEPARATOR);
define('ROOT_URL', "http://" . $_SERVER['HTTP_HOST'] ."/cReport/");
define('UPLOAD_DIR',DR.'\skin\upload'.DS);
define("POST_MAX_SIZE",ini_get('post_max_size'));

define('DB_NAME','c_report');
define('HOST','localhost');
define('DB_USERNAME','root');
define('DB_PASSWORD','');
define('PAGE','1');
define('LIMIT','10');

include_once ('../Class/Admin.php');
$_admin = new Class_Admin();
$_core = clone $_admin;

include_once ('../Class/User.php');
$_user = new Class_User();

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 3600)) {
    // last request was more than 2 minates ago
    session_destroy();   // destroy session data in storage
    session_unset();     // unset $_SESSION variable for the runtime
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

if (!isset($_SESSION['CREATED'])) {
    $_SESSION['CREATED'] = time();
} else if (time() - $_SESSION['CREATED'] > 3600) {
    // session started more than 2 minates ago
    session_regenerate_id(true);    // change session ID for the current session an invalidate old session ID
    $_SESSION['CREATED'] = time();  // update creation time
}

?>