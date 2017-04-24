<?php
$cfgkey = "MineServers.EuisteinfachHamma";
require_once '../config.php';
require_once '../includes/functions.php';
require_once('../includes/recaptchalib.php');

if(!is_id($_GET['id'])){
	echo "Error 404: Server not found";
	exit();
}

$id = !empty($_GET['id']) ? $_GET['id'] : 0;

$serverq = $db->query("SELECT votifier FROM sl_servers WHERE id = " . $id);
if($serverq->num_rows != 1){
	echo "Error 404: Server not found";
	exit();
}
$serverinfos = $serverq->fetch_object();

$height = ($serverinfos->votifier == "true") ? "338" : "249";
header('Content-Type: text/javascript');
echo "document.write('<iframe src=\"http://www.mineservers.eu/widgets/form.php?id=" . $_GET['id'] . "\" width=\"412\" height=\"" . $height . "\" frameborder=\"0\" scrolling=\"no\"></iframe>');";
?>
