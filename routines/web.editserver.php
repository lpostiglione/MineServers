<?php

if ($_SESSION['id'] == 0) {
    errormsg(_NOPERMISSION, "back");
} else {

    //CHECK OB BENUTZER DER EIGENTÜMER DES SERVERS
    $id = $_POST['id'];
    $isowner = $db->query("SELECT `owner`, `mcquery` FROM `sl_servers` WHERE `id` = $id");
    $owner = $isowner->fetch_object();
    if ($_SESSION['username'] == $owner->owner) {

        //CHECK OB ALLE FELDER AUSGEFÜLLT
        if (!empty($_POST['servername']) && !empty($_POST['serveradress']) && !empty($_POST['gametype']) && !empty($_POST['beschreibung']) && !empty($_POST['onlinemode'])) {

            //SETZT UND ESCAPED VARIABLEN
            $servername = $db->real_escape_string(htmlspecialchars($_POST['servername']));
            $serveradress = $db->real_escape_string(htmlspecialchars($_POST['serveradress']));
            $serverport = (empty($_POST['serverport'])) ? "25565" : $db->real_escape_string($_POST['serverport']);
            $onlinemode = $db->real_escape_string($_POST['onlinemode']);
            $gmraw = $db->real_escape_string($_POST['gametype']);
            $description = $db->real_escape_string($_POST['beschreibung']);
            $voiceadress = !empty($_POST['voiceadress']) ? $db->real_escape_string(htmlspecialchars($_POST['voiceadress'])) : 0;
            $mapadress = str_replace(array("http://", "https://"), "", $_POST['mapadress']);
            $mapadress = (!empty($_POST['mapadress'])) ? $db->real_escape_string(htmlspecialchars($mapadress)) : 0;
            $website = str_replace(array("http://", "https://"), "", $_POST['website']);
            $website = (!empty($_POST['website'])) ? $db->real_escape_string(htmlspecialchars($website)) : 0;
            $features = $_POST['eigenschaften'];
            if ($owner->mcquery == "true") {
                $showplugins = $db->real_escape_string($_POST['showplugins']);
            }

            //CHECK OB SERVERNAME DEN VORGABEN ENTSPRICHT
            if (!(strlen($servername) >= 5 and strlen($servername) <= 48)) {
                errormsg('Der Servername "' . $servername . '" entspricht nicht den Vorgaben! Er muss zwischen 5 und 48 Zeichen enthalten.');
                exit();
            }

            //PRÜFT OB DIE SERVERADRESSE VALIDE IST	
            if (!checkifvalidip($serveradress)) {
                errormsg("Die Server Adresse ist ungültig!");
                exit();
            }

            //CHECK OB SERVERPORT VALIDE
            if (!preg_match("/^[0-9]{2,8}$/i", $serverport)) {
                errormsg("Der Port ist ungültig!");
                exit();
            }

            if ($onlinemode != "true" && $onlinemode != "false") {
                errormsg("Der Online Mode ist fehlerhaft!");
                exit();
            }

            if ($owner->mcquery == "true" && $showplugins != "true" && $showplugins != "false") {
                errormsg("Die Angabe bei \"Plugins anzeigen\" ist fehlerhaft!");
                exit();
            }

            //BILDET EINE IP AUS DER SERVERADRESSE
            if (ip2long($serveradress) !== false) {
                $ip = $serveradress;
            } elseif (getDNS("_minecraft._tcp." . $serveradress, "srv") !== false) {
                $srvrecord = explode(":", getDNS("_minecraft._tcp." . $serveradress, "srv"));
                $ip = $srvrecord[0];
            } else {
                $ip = gethostbyname($serveradress);
            }

            //PRÜFT OB BEREITS EIN SERVER MIT DER IP EINGETRAGEN IST UND OB DAS DER SELBE IST ODER NICHT
            $exists = $db->query("SELECT * FROM `sl_servers` WHERE `ip` = '" . $ip . "' AND `serverport` = '" . $serverport . "'");
            $existsf = $exists->fetch_object;
            if ($exists->num_rows != 0 && $existsf->id != $_GET['id']) {
                errormsg("Es ist bereits ein Server mit dieser IP eingetragen.");
                exit();
            }

            //WEIST DEM GAMETYPE DEN ENTSPTRECHENDEN NAMEN ZU UND PRÜFT OB VALIDE
            if (preg_match("/^[1-6]{1}$/i", $gmraw)) {
                switch ($gmraw) {
                    case $gmraw == "1": $gametype = "Survival";
                        break;
                    case $gmraw == "2": $gametype = "PVP";
                        break;
                    case $gmraw == "3": $gametype = "Citybuild";
                        break;
                    case $gmraw == "4": $gametype = "Creative";
                        break;
                    case $gmraw == "5": $gametype = "Anderes";
                        break;
                    case $gmraw == "6": $gametype = "RPG";
                        break;
                }
            } else {
                errormsg("Die Server Typ entspricht nicht den Vorgaben!");
                exit();
            }

            //FEATURES ÜBERPRÜFEN UND IN EINEN STRING UMWANDELN
            if (is_array($features) && count($features) <= 21) {
                $featurestring = "";
                foreach ($features as $value) {
                    if (preg_match("/^[1-5][0-9][0-9]$/", $value)) {
                        $featurestring .= $value . " ";
                    } else {
                        errormsg("Die Server Eigenschaften entsprechen nicht den Vorgaben! Es dürfen maximal 21 Eigenschaften ausgewählt werden.");
                        exit();
                    }
                }
                $features = substr($featurestring, 0, -1);
            } else {
                errormsg("Die Server Eigenschaften entsprechen nicht den Vorgaben! Es dürfen maximal 21 Eigenschaften ausgewählt werden.");
                exit();
            }

            //PRÜFT OB BESCHREIBUNG VALIDE
            if (strlen($description) < 100) {
                errormsg("Die Beschreibung entspricht nicht den Vorgaben! Sie muss mindestens 100 Zeichen enthalten.");
                exit();
            }

            if ($owner->mcquery == "true") {
                $db->query("UPDATE `sl_mcquery` SET `showplugins` = '" . $showplugins . "' WHERE `id` = " . $id);
            }

            $db->query("UPDATE `sl_servers` SET `servername` = '" . $servername . "', `serveradress` = '" . $serveradress . "', `serverport` = '" . $serverport . "', `ip` = '" . $ip . "', `voiceadress` = '" . $voiceadress . "', `mapadress` = '" . $mapadress . "', `website` = '" . $website . "', `gametype` = '" . $gametype . "', `features` = '" . $features . "', `description` = '" . $description . "', `onlineMode` = '" . $onlinemode . "' WHERE `id` = " . $id);

            if (!empty($db->error)) {
                errormsg("Fehler! Bitte überprüfe deine Eingaben oder kontaktiere einen Administrator.");
            } else {
                successmsg("Deine Änderungen an  \"" . $servername . "\" wurde erfolgreich gespeichert!", "editserver", $id);
            }
        } else {
            errormsg("Bitte fülle alle Felder aus!");
        }
    } else {
        errormsg("Du bist nicht der Besitzer dieses Servers!");
    }
}
?>
