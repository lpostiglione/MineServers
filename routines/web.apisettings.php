<?php

if ($_SESSION['id'] == 0) {
    errormsg(_NOPERMISSION, "back");
} else {
    $id = $_POST['id'];
    if (ctype_digit($id)) {
        $isowner = $db->query("SELECT `owner` FROM `sl_servers` WHERE `id` = $id");
        $owner = $isowner->fetch_object();

        if ($_SESSION['username'] == $owner->owner) {

            $serverdataquery = $db->query("SELECT * FROM `sl_servers` WHERE `id` = $id");
            $serverdata = $serverdataquery->fetch_object();

            $apidataq = $db->query("SELECT * FROM `sl_voteapi` WHERE `id` = $id");
            if ($apidataq->num_rows == 1) {
                $apidata = $apidataq->fetch_object();
            } else {
                $apidata = false;
            }

            if ($serverdata->api == $_POST['apienabled'] && $serverdata->api == "false" && $_POST['gennewapikey'] != "true") { // Wenn bereits deaktiviert
                errormsg("Die API ist bereits deaktiviert!");
            } elseif ($serverdata->api == $_POST['apienabled'] && $_POST['gennewapikey'] != "true") { // Wenn bereits aktiviert und kein neuer key
                errormsg("Die API ist bereits aktiviert!");
            } elseif ($apidata == false && $_POST['apienabled'] == "true") { //Wenn deaktiviert und kein Eintrag vorhanden
                $res = openssl_pkey_new();

                openssl_pkey_export($res, $privkey);
                $privkey = $db->real_escape_string($privkey);

                $pubkey = openssl_pkey_get_details($res);
                $pubkey = $db->real_escape_string($pubkey['key']);

                $apikey = $db->real_escape_string(random_str(24));

                $db->query("INSERT INTO `sl_voteapi` (`id`, `apikey`, `privatekey`, `publickey`) VALUES ('" . $id . "', '" . $apikey . "', '" . $privkey . "', '" . $pubkey . "')");
                $db->query("UPDATE sl_servers SET api = 'true' WHERE id = '" . $id . "'");

                successmsg('Die API wurde erfolgreich aktiviert!', "apisettings");
            } elseif ($apidata != false && $_POST['apienabled'] == "false") { //Wenn aktiviert und Eintrag vorhanden & deaktivieren
                if ($_POST['gennewapikey'] == "true") {
                    $apikey = $db->real_escape_string(random_str(24));
                    $db->query("UPDATE sl_voteapi SET apikey = '" . $apikey . "' WHERE id = '" . $id . "'");
                }

                $db->query("UPDATE sl_servers SET api = 'false' WHERE id = '" . $id . "'");

                successmsg('Die API wurde erfolgreich deaktiviert!', "apisettings");
            } elseif ($apidata != false && $_POST['apienabled'] == "true") { //Wenn neuer Key angefordert
                if ($_POST['gennewapikey'] == "true") {
                    $apikey = $db->real_escape_string(random_str(24));
                    $db->query("UPDATE sl_voteapi SET apikey = '" . $apikey . "' WHERE id = '" . $id . "'");
                }

                $db->query("UPDATE sl_servers SET api = 'true' WHERE id = '" . $id . "'");

                successmsg('Die API wurde erfolgreich aktiviert!', "apisettings");
            } elseif ($_POST['gennewapikey'] == "true") {

                $apikey = $db->real_escape_string(random_str(24));
                $db->query("UPDATE sl_voteapi SET apikey = '" . $apikey . "' WHERE id = '" . $id . "'");
            } else {
                errormsg("Fehlerhafte Anfrage!");
            }
        } else {
            errormsg("Dieser Server gehört dir nicht!");
        }
    } else {
        errormsg("Die ID ist ungültig!");
    }
}
?>
