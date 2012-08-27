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

define('THUMB_WIDTH',50);
define('THUMB_HEIGHT',50);

$page_flag='';

include_once ('Class/User.php');
$_user = new Class_User();
$_core = clone $_user;
?>