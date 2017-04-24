<?php 
$db->query("DELETE FROM `sl_comments` WHERE `id` = " . $_GET['id']);

if (!empty($db->error)) {
	errormsg("Fehler!", "back");
} else {
	successmsg("Kommentar erfolgreich entfernt!", "back");
}
?>