<?php

if ($_SESSION['id'] == 0) {
    errormsg(_NOPERMISSION, "back");
} else {
    if (!is_numeric($_GET['id'])) {
        errormsg("Fehlerhafte ID!");
    } else {
        $db->query("UPDATE `sl_comments` SET `reported` = 'true' WHERE `id` = " . $_GET['id']);

        if (!empty($db->error)) {
            errormsg("Fehler! Bitte kontaktiere einen Administrator!");
        } else {
            successmsg('Kommentar erfolgreich gemeldet!', "serverview", $_GET["id"]);
        }
    }
}
?>