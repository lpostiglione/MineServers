<?php

if (empty($_GET['key']) or empty($_GET['user']) or ($_SESSION['id'] != 0)) {
    errormsg(_NOPERMISSION, "back");
} else {
    $key = $db->real_escape_string($_GET['key']);
    $user = $db->real_escape_string($_GET['user']);

    $query = $db->query("SELECT activatecode, activated FROM sl_users WHERE username = '" . $user . "'");
    $mysqlreadkey = $query->fetch_object();

    if ($mysqlreadkey->activated == "1") {
        errormsg("Du bist schon aktiviert!");
    } elseif ($mysqlreadkey->activatecode == $key) {
        doLog($user . " hat sich aktiviert", "activation");
        $db->query("UPDATE sl_users SET activated = '1' WHERE username = '" . $user . "'");

        successmsg('Du hast dich erfolgreich aktiviert ' . $user . '!<br>Nun kannst du dich einloggen.', "login");
    } else {
        errormsg("Der Key ist Falsch!");
    }
}
?>
