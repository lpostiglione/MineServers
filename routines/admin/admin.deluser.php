<?php 
$userinfo = $db->query("SELECT * FROM `sl_users` WHERE `id` = " . $_GET['id']);

$serverinfo = $userinfo->fetch_object();

$db->query("DELETE FROM `sl_servers` WHERE `owner` = '" . $serverinfo->username."'");
$db->query("DELETE FROM `sl_users` WHERE `id` = " . $_GET['id']);

if (!empty($db->error)) {
	errormsg("Fehler!", "back");
} else {
	successmsg("Benutzer erfolgreich entfernt!", "back");
}
?>