<?php

if (empty($_POST['requestkey']) or ($_SESSION['id'] != 0)) {
    errormsg(_NOPERMISSION, "back");
} elseif ($_POST['pw1'] != $_POST['pw2']) {
    errormsg("Die Passwörter stimmen nicht überein!");
} else {
    $key = $db->real_escape_string($_POST['requestkey']);

    if (preg_match("/^[a-zA-Z0-9]{3,32}$/i", $_POST['pw1'])) {


        $pass = md5(_SALT . md5($_POST['pw1']));

        $query = $db->query("SELECT * FROM sl_users WHERE requestkey = '" . $key . "'");

        if ($query->num_rows == 1) {

            $mysqlreadkey = $query->fetch_object();

            if ($mysqlreadkey->openrequest != 1) {
                errormsg("Der Key ist abgelaufen!");
            } elseif ($mysqlreadkey->requestkey == $key) {

                $db->query("UPDATE sl_users SET openrequest = '0', password = '" . $pass . "' WHERE requestkey = '" . $key . "'");

                successmsg('Dein Passwort wurde geändert ' . $mysqlreadkey->username . '!', "login");
            } else {
                errormsg("Der Key ist falsch!");
            }
        } else {
            errormsg("Der Key ist falsch!");
        }
    } else {
        errormsg("Das Passwort entspricht nicht den Vorgaben! Das Passwort darf nur aus Buchstaben und Zahlen bestehen und muss min. 3 und max. 32 Zeichen lang sein.");
    }
}
?>
