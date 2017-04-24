<?php

if ($_SESSION['id'] == 0) {
    errormsg(_NOPERMISSION, "back");
} else {

    //CHECK OB BENUTZER DER EIGENTÜMER DES SERVERS
    $id = $_GET['id'];
    $isowner = $db->query("SELECT `owner` FROM `sl_servers` WHERE `id` = $id");
    $owner = $isowner->fetch_object();
    if ($_SESSION['username'] == $owner->owner or is_mod($_SESSION['username'])) {

        $userip = $_SERVER['HTTP_CF_CONNECTING_IP'];
        $minecraftname = $owner->owner;

        $serverdataq = $db->query("SELECT votifier, votifierip, votifierport, votifierkey FROM sl_servers WHERE id = '$id'");
        $serverdata = $serverdataq->fetch_object();

        $votifier = Votifier($serverdata->votifierkey, $serverdata->votifierip, $serverdata->votifierport, $minecraftname, $userip, true);

        if ($votifier == false) {
            echo '<div class="alert alert-error">Die Votifier Anbindung deines Servers konnte nicht erreicht werden. Möglicherweise wurden die Daten falsch eingegeben. Sollte dies nicht der Fall sein, kontaktiere einen Administrator.</div>';
        } else {
            echo '<div class="alert alert-success">Votifier konnte erreicht werden! Sollte dennoch ein Fehler vorliegen, liegt das an deinem Server.<br>Antwort des Servers: <code>' . $votifier . '</code></div>';
        }
    }
}
?>