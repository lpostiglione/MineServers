<?php

if ($_SESSION['id'] == 0) {
    errormsg(_NOPERMISSION, "back");
} elseif ($_POST['pw1'] != $_POST['pw2']) {
    errormsg("Die Passwörter stimmen nicht überein!");
} elseif (empty($_POST['oldpw']) or empty($_POST['pw1'])) {
    errormsg("Bitte fülle alle benötigten Felder aus!");
} elseif (!preg_match("/^[a-zA-Z0-9]{3,32}$/i", $_POST['pw1'])) {
    errormsg("Das Passwort entspricht nicht den Vorgaben! Das Passwort darf nur aus Buchstaben und Zahlen bestehen, muss min. 3 und max. 32 Zeichen lang sein.");
} else {
    $oldpass = md5(_SALT . md5($_POST['oldpw']));
    $pass = md5(_SALT . md5($_POST['pw1']));

    $query = $db->query("SELECT password FROM sl_users WHERE username = '" . $_SESSION['username'] . "'");
    $data = $query->fetch_object();

    if ($data->password == $oldpass) {

        $db->query("UPDATE sl_users SET password = '" . $pass . "' WHERE username = '" . $_SESSION['username'] . "'");

        successmsg('Dein Passwort wurde geändert ' . $_SESSION['username'] . '!', "mysettings");
    } else {
        errormsg("Das alte Passwort ist falsch!");
    }
}
?>
