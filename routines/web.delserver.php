<?php

if ($_SESSION['id'] == 0) {
    errormsg(_NOPERMISSION, "back");
} else {
    $id = $_GET['id'];
    if (ctype_digit($id)) {
        $isowner = $db->query("SELECT `owner` FROM `sl_servers` WHERE `id` = " . $id);
        $owner = $isowner->fetch_object();

        if ($_SESSION['username'] == $owner->owner) {
            $db->query("DELETE FROM `sl_servers` WHERE `id` = " . $id);

            $db->query("DELETE FROM `sl_comments` WHERE `serverid` = " . $id);

            if (!empty($db->error)) {
                errormsg("Fehler! Bitte kontaktiere einen Administrator!");
            } else {
                successmsg('Der Server wurde erfolgreich gelöscht!', "myservers");
            }
        } else {
            errormsg("Du bist nicht der Besitzer dieses Servers!");
        }
    } else {
        errormsg("Ungültige ID!");
    }
}
?>
