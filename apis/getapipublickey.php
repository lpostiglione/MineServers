<?php
error_reporting(0);

$cfgkey = "MineServers.EuisteinfachHamma";
require_once '../config.php';
require_once '../includes/functions.php';

session_start('mineservers');

if (!isset($_SESSION["username"])) {
    $_SESSION["id"] = "0";
    $_SESSION["username"] = "Gast";
}

if(!empty($_GET['id']) && !is_id($_GET['id']) or !empty($_GET['page']) && !is_id($_GET['page'])){
	header("HTTP/1.0 404 Not Found");
	exit();
}

if ($_SESSION['id'] == 0) {
	header("HTTP/1.0 404 Not Found");
	exit();
} else {
    $id = $_GET['id'];
	$serverdataquery = $db->query("SELECT * FROM `sl_servers` WHERE `id` = $id");
	$serverdata = $serverdataquery->fetch_object();
	
	if ($_SESSION['username'] == $serverdata->owner) {
	
		$apidataq = $db->query("SELECT * FROM sl_voteapi WHERE id = $id");
		if($apidataq->num_rows == 1){
			$apidata = $apidataq->fetch_object();
		} else {
			$apidata = false;
		}
		
		if($apidata == false){
   			errormsg("Bitte generiere zuerst einen Public Key", "back");
		} else {
			header('Content-Type: text/plain; charset=UTF-8');

			header('Content-Disposition: attachment; filename="public.key"');
			
			$pubkey = str_replace(array("\r\n", "\r", "\n", "-----BEGIN PUBLIC KEY-----", "-----END PUBLIC KEY-----"), '', $apidata->publickey);
			
			echo $pubkey;

		}
		
	} else {
	header("HTTP/1.0 404 Not Found");
	exit();
	}
}