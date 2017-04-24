<?php 
$db->query("UPDATE `sl_users` SET `activated` = '1' WHERE `id` = " . $_GET['id']);

if (!empty($db->error)) {
	errormsg("Fehler!", "back");
} else {
	successmsg("Benutzer erfolgreich aktiviert!", "back");
}
?>