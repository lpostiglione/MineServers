<?php

if (isset($_COOKIE["StayLoggedIn"]) && !empty($_COOKIE["StayLoggedIn"])) {
    setcookie("StayLoggedIn", "", time() - 7200);
    $db->query("DELETE FROM `sl_sessions` WHERE `username` = '" . $_SESSION['username'] . "'");
}
session_unset('mineservers');

successmsg("Du hast dich erfolgreich abgemeldet!", "start");
?>
