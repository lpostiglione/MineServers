<?php

if ($_SESSION['id'] == 0) {
    errormsg(_NOPERMISSION, "back");
} else {
    //PRÜFT OB SERVER ID VALIDE
    if (!is_id($_POST['serverid'])) {
        errormsg("Ungültige Server ID!");
        //PRÜFT OB KOMMENTARTEXT VALIDE
    } elseif (strlen($_POST['comment']) <= 16 xor strlen($_POST['comment']) >= 1024) {
        errormsg("Der Kommentar muss zwischen 16 und 1024 Zeichen enthalten!");
    } else {
        //DEFINIERT UND ESCAPED VARIABLEN
        $user = $db->real_escape_string(htmlspecialchars($_SESSION['username']));
        $comment = $db->real_escape_string(str_replace("\r\n", "<br>", htmlspecialchars($_POST['comment'])));
        $serverid = $db->real_escape_string(htmlspecialchars($_POST['serverid']));
        $date = $db->real_escape_string(htmlspecialchars(time()));

        //SCHREIBT VARIABLEN IN MYSQL
        $db->query("INSERT INTO sl_comments (serverid, date, user, comment) VALUES ('" . $serverid . "', '" . $date . "', '" . $user . "', '" . $comment . "')");

        //LIEST EMAIL DES SERVEROWNERS AUS UND SCHICKT EINE BENACHRICHTIGUNG
        $serverinfos = $db->query("SELECT * FROM sl_servers WHERE id = '$serverid'");
        $serverinfo = $serverinfos->fetch_object();

        $ownerinfos = $db->query("SELECT mail FROM sl_users WHERE username = '" . $serverinfo->owner . "'");
        $ownerinfo = $ownerinfos->fetch_object();
        $email = $ownerinfo->mail;

        $mailer->AddAddress($email);
        $mailer->Subject = 'MineServers.eu: Kommentar zu deinem Server';
        $mailer->Body = '<html><head><title>MineServers.eu: Kommentar zu deinem Server</title></head><body><h1>Howdy ' . $serverinfo->owner . '!</h1><p>Jemand hat bei deinem Server "' . $serverinfo->servername . '" einen Kommentar hinterlassen:<br><br><blockquote>' . $comment . '</blockquote><br><a href="' . $serverlisturl . '/index.php?site=serverview&id=' . $serverid . '">Klicke hier um zu deinem Servereintrag zu gelangen</a><br><br>Vielen Dank,<br>Dein MineServers.eu Team</p></body></html>';
        $mailer->AltBody = 'Howdy ' . $serverinfo->owner . '!\n\nJemand hat bei deinem Server "' . $serverinfo->servername . '" einen Kommentar hinterlassen:\n\n' . $comment . '\nKlicke hier um zu deinem Servereintrag zu gelangen:\n' . $serverlisturl . '/index.php?site=serverview&id=' . $serverid . '\n\nVielen Dank,\nDein MineServers.eu Team';

        $mailer->Send();

        if (!empty($db->error)) {
            errormsg("Ein Fehler ist aufgetreten! Bitte kontaktiere den Support.");
        } else {
            successmsg('Kommentar erfolgreich hinzugefügt!', "serverview", $serverid);
        }
    }
}
?>
