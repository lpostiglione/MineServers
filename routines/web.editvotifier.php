<?php

if ($_SESSION['id'] == 0) {
    errormsg(_NOPERMISSION, "back");
} else {
    $id = $_POST['id'];
    if (ctype_digit($id)) {
        $isowner = $db->query("SELECT `owner`, `servername` FROM `sl_servers` WHERE `id` = " . $id);
        $owner = $isowner->fetch_object();
        $servername = $owner->servername;

        if ($_SESSION['username'] == $owner->owner) {
            if (!empty($_POST['votifier']) && !empty($_POST['votifierip']) && !empty($_POST['votifierport']) && !empty($_POST['votifierkey'])) {

                if ($_POST['votifier'] == "true" xor $_POST['votifier'] == "false") {
                    $votifier = $_POST['votifier'];
                } else {
                    errormsg("Der Wert \"Anbindung aktiv\" entspricht nicht den Vorgaben!");
                    exit();
                }

                if (checkifvalidip($_POST['votifierip'])) {

                    $votifierip = $db->real_escape_string($_POST['votifierip']);
                } else {

                    errormsg("Die IP entspricht nicht den Vorgaben!");
                    exit();
                }

                $votifierport = $db->real_escape_string($_POST['votifierport']);

                if (empty($votifierport))
                    $votifierport = "8192";

                if (!preg_match("/^[0-9]{2,8}$/i", $votifierport)) {
                    errormsg("Der Port entspricht nicht den Vorgaben!");
                    exit();
                }

                $votifierkey = $_POST['votifierkey'];
                $votifierkey = $db->real_escape_string($votifierkey);

                $db->query("
                    UPDATE  
                        `sl_servers` 
                    SET  
                        `votifier` = '" . $votifier . "',
                        `votifierip` = '" . $votifierip . "',
                        `votifierport` = '" . $votifierport . "',
                        `votifierkey` = '" . $votifierkey . "'
                    WHERE  
                        `id` = " . $id . ";
                        ");

                if (!empty($db->error)) {
                    errormsg("Fehler! Bitte überprüfe deine Eingaben oder kontaktiere einen Administrator!");
                } else {
                    successmsg("Deine Änderungen an  \"" . $servername . "\" wurde erfolgreich gespeichert!", "editvotifier", $id);
                }
            } else {
                errormsg("Bitte fülle alle Felder aus!");
            }
        } else {
            errormsg("Du bist nicht der Besitzer dieses Servers!");
        }
    } else {
        errormsg("Ungültige ID!");
    }
}
?>
