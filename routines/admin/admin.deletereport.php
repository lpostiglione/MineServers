<?php 
$db->query("UPDATE `sl_comments` SET `reported` = 'false' WHERE `id` = " . $_GET['id']);

if (!empty($db->error)) {
	errormsg("Fehler!", "back");
} else {
	successmsg("Meldung erfolgreich entfernt!", "back");
}
?>