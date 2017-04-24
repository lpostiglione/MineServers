<?php

if ($_SESSION['id'] == 0) {
    errormsg(_NOPERMISSION, "back");
} else {

    if (!empty($_POST['servername']) && !empty($_POST['serveradress']) && !empty($_POST['gametype']) && !empty($_POST['beschreibung']) && !empty($_POST['onlinemode'])) {

        //SETZT UND ESCAPED VARIABLEN
        $servername = $db->real_escape_string(htmlspecialchars($_POST['servername']));
        $serveradress = $db->real_escape_string(htmlspecialchars($_POST['serveradress']));
        $serverport = (empty($_POST['serverport'])) ? "25565" : $db->real_escape_string($_POST['serverport']);
        $onlinemode = $db->real_escape_string($_POST['onlinemode']);
        $gmraw = $db->real_escape_string($_POST['gametype']);
        $description = $db->real_escape_string($_POST['beschreibung']);
        $created = $db->real_escape_string(time());
        $voiceadress = !empty($_POST['voiceadress']) ? $db->real_escape_string(htmlspecialchars($_POST['voiceadress'])) : 0;
        $owner = $db->real_escape_string(htmlspecialchars($_SESSION['username']));
        $mapadress = str_replace(array("http://", "https://"), "", $_POST['mapadress']);
        $mapadress = (!empty($_POST['mapadress'])) ? $db->real_escape_string(htmlspecialchars($mapadress)) : 0;
        $website = str_replace(array("http://", "https://"), "", $_POST['website']);
        $website = (!empty($_POST['website'])) ? $db->real_escape_string(htmlspecialchars($website)) : 0;
        $features = $_POST['eigenschaften'];


        //PRÜFT OB SERVERNAME DEN VORGABEN ENTSPRICHT
        if (!(strlen($servername) >= 5 and strlen($servername) <= 48)) {
            errormsg('Der Servername "' . $servername . '" entspricht nicht den Vorgaben! Er muss zwischen 5 und 48 Zeichen enthalten.');
            exit();
        }

        //PRÜFT OB GÜLTIGE SERVERADRESSE
        if (!checkifvalidip($serveradress)) {
            errormsg("Die Server Adresse ist ungültig!");
            exit();
        }

        //PRÜFT OB DER SERVER PORT VALIDE IST
        if (!preg_match("/^[0-9]{2,8}$/i", $serverport)) {
            errormsg("Der Port ist ungültig!");
            exit();
        }

        //PRÜFT OB DER ONLINE MODE GÜLTIG IST
        if ($onlinemode != "true" && $onlinemode != "false") {
            errormsg("Der Online Mode ist fehlerhaft!");
            exit();
        }

        //PRÜFT OB DER GAMETYPE GÜLTIG IST UND WEIßT ENTSPRECHEND DEM WERT EINEN STRING ZU
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
            errormsg("Der Spieltyp entspricht nicht den Vorgaben!");
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

        //PRÜFT DIE BESCHREIBUNG AUF IHRE RICHTIGKEIT
        if (strlen($description) < 100) {
            errormsg("Die Beschreibung entspricht nicht den Vorgaben! Sie muss mindestens 100 Zeichen enthalten.");
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

        $country = $db->real_escape_string(strtolower(file_get_contents('https://api.wipmania.com/' . $ip)));

        //PRÜFT OB BEREITS EIN SERVER MIT DER SELBEN IP/PORT KOMBINATION EXISTIERT
        $exists = $db->query("SELECT * FROM `sl_servers` WHERE `ip` = '" . $ip . "' AND `serverport` = '" . $serverport . "'");
        if ($exists->num_rows != 0) {
            errormsg("Der Server ist bereits eingetragen!");
            exit();
        }

        //RUFT DIE SERVER INFOS AB
        require_once '/var/customers/webs/mineservers/includes/MinecraftServerInfo.class.php';
        $serverinfo = new MinecraftServerStatus();
        $data = $serverinfo->getStatus($ip, $serverport);

        //PRÜFT OB SERVER ONLINE
        if (!$data) {
            errormsg("Der Server ist nicht erreichbar!");
            exit();
        } else {
            //DEFINIERE VARIABLEN
            $motd = $db->real_escape_string(htmlspecialchars(str_replace("§", "#", $data['motd'])));
            $version = $db->real_escape_string(htmlspecialchars($data['version']));
            $player = $db->real_escape_string(htmlspecialchars($data['players']));
            $playerMax = $db->real_escape_string(htmlspecialchars($data['slots']));

            $db->query("INSERT INTO `sl_servers` (`servername`, `serveradress`, `serverport`, `ip`, `voiceadress`, `mapadress`, `website`, `gametype`, `features`, `description`, `owner`, `created`, `country`, `onlineMode`, `version`, `motd`, `player`, `playerMax`, `online`, `lastonline`, `lastcheck`, `votifierkey`) VALUES ('" . $servername . "', '" . $serveradress . "', '" . $serverport . "', '" . $ip . "', '" . $voiceadress . "', '" . $mapadress . "', '" . $website . "', '" . $gametype . "', '" . $features . "', '" . $description . "', '" . $owner . "', '" . $created . "', '" . $country . "', '" . $onlinemode . "', '" . $version . "', '" . $motd . "', '" . $player . "', '" . $playerMax . "', 'true', '" . $created . "', '" . $created . "', 'Insert Public Key here')");

            if (!empty($db->error)) {
                errormsg("Fehler! Bitte überprüfe deine Eingaben oder kontaktiere einen Administrator.");
            } else {
                successmsg('Der Server "' . $servername . '" wurde erfolgreich eingetragen!', "myservers");
            }
        }
    } else {
        errormsg("Bitte fülle alle benötigten Felder aus!");
    }
}
?>
