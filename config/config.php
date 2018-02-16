<?php

$file_error = 'src/appBundle/controller/404.php';
$bundle = 'app';		// base


$hostname_connAM = "localhost";
$database_connAM = "test";
$username_connAM = "test";
$password_connAM = "test.123";

$bundles = array('app' => false);

define("Email_Server","email-smtp.us-west-2.amazonaws.com");				//	amazonses.com
define("Email_Port",587);
define("Email_Username",'AKIAJ56S76L2BOWGGFDQ_');
define("Email_Password",'_Asn2f86WID9Sk9ZlEeQb7GHewz2RosTdMm14/gWUH+t4');

include_once('safePDO.php');


$dsn = "mysql:host={$hostname_connAM};dbname={$database_connAM};charset=utf8";
$opt = array(
	PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
	PDO::ATTR_EMULATE_PREPARES	 => false,
	PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
	PDO::ATTR_PERSISTENT		 => true
);
$pdo = new SafePDO($dsn,$username_connAM,$password_connAM);
$pdo->query("SET NAMES UTF8");

?>
