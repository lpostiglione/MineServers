<?php 
$id = $_GET['id'];

$userinfo = $db->query("SELECT * FROM `sl_servers` WHERE `id` = " . $_GET['id']);

$serverinfo = $userinfo->fetch_object();

$db->query("DELETE FROM `sl_servers` WHERE `id` = " . $id);         
$db->query("DELETE FROM `sl_comments` WHERE `serverid` = " . $id);

if (!empty($db->error)) {
	errormsg("Fehler!", "back");
} else {
	successmsg("Server " . $serverinfo->servername . " erfolgreich entfernt!", "back");
}
?>