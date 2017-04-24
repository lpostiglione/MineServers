<?php

$resp = recaptcha_check_answer(PRIVATEKEY, $_SERVER["HTTP_CF_CONNECTING_IP"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);

if (!$resp->is_valid && $_SESSION['id'] == 0) {
    die("<div class='alert alert-error'>Das Captcha war falsch, bitte geh zurück und versuch es nochmal.<br><a href='javascript:history.back()'>Zurück zur letzten Seite</a></div>");
} elseif (!empty($_POST["email"])) {
    echo "Fuck ya";
} else {
    $userip = $_SERVER['HTTP_CF_CONNECTING_IP'];
    $minecraftname = $db->real_escape_string(htmlspecialchars($_POST['minecraftname']));
    $id = $db->real_escape_string(htmlspecialchars($_POST['id']));

    if (!is_mod($_SESSION['username'])) {
        $checkip = $db->query("SELECT id FROM sl_votes WHERE ip = '$userip'");
        $checkmc = $db->query("SELECT id FROM sl_votes WHERE minecraftname = '$minecraftname'");
        $checkuser = $db->query("SELECT id FROM sl_votes WHERE username = '" . $_SESSION['username'] . "'");
    }
    if ($checkip->num_rows > 0) {
        errormsg("Du hast heute schon gevotet!");
    } elseif ($checkmc->num_rows > 0 and !empty($minecraftname)) {
        errormsg("Du hast heute schon gevotet!");
    } elseif ($checkuser->num_rows > 0 and $_SESSION['id'] != 0) {
        errormsg("Du hast heute schon gevotet!");
    } else {
        $serverdataq = $db->query("SELECT votes, votifier, votifierip, votifierport, votifierkey FROM sl_servers WHERE id = '$id'");
        $serverdata = $serverdataq->fetch_object();

        if (preg_match("/[A-Za-z0-9_]+/", $minecraftname) && !empty($minecraftname)) {

            $votifier = Votifier($serverdata->votifierkey, $serverdata->votifierip, $serverdata->votifierport, $minecraftname, $userip);

            if (!$votifier) {
                errormsg("Die Votifier Anbindung des Servers konnte nicht erreicht werden! Dein Vote wird dennoch gespeichert.");
            }
        }

        $db->query("
                INSERT INTO `sl_votes` (
                            `username` ,
                            `serverid` ,
                            `minecraftname` ,
                            `ip`
                )
                VALUES (
                            '" . $_SESSION['username'] . "',
                            '$id',
                            '$minecraftname',
                            '$userip'
                );");

        $votes = $serverdata->votes;
        $votes++;

        $db->query("UPDATE `sl_servers` SET  `votes` = '$votes' WHERE `id` = '$id'");

        successmsg('Deine Bewertung wurde gespeichert!', "serverview", $id);
    }
}
?>