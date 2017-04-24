<?php

if ($_SESSION['id'] != 0) {
    errormsg("Du bist bereits registriert!", "start");
} else {
    $resp = recaptcha_check_answer(PRIVATEKEY, $_SERVER["HTTP_CF_CONNECTING_IP"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);

    if (!$resp->is_valid) {
        die("<div class='alert alert-error'>Das Captcha war falsch, bitte geh zurück und versuch es nochmal.<br><a href='javascript:history.back()'>Zurück zur letzten Seite</a></div>");
    } else {

        $user = $db->real_escape_string($_POST['user']);
        $minecraftname = $db->real_escape_string($_POST['minecraft']);
        $pass1 = $_POST['pw1'];
        $pass2 = $_POST['pw2'];
        $mail1 = $_POST['mail1'];
        $mail2 = $_POST['mail2'];

        if ($mail1 == $mail2) {
            $email = $db->real_escape_string(htmlspecialchars($mail1));
        } else {
            errormsg("Die E-Mail Adressen stimmen nicht überein!");
            exit();
        }

        $uequery = $db->query("SELECT username FROM sl_users WHERE username = '" . $user . "'");
        $eequery = $db->query("SELECT mail FROM sl_users WHERE mail = '" . $email . "'");
        $userexists = $uequery->fetch_array(MYSQLI_ASSOC);
        $emailexists = $eequery->fetch_array(MYSQLI_ASSOC);

        if (empty($userexists) && empty($emailexists)) {
            if (!$_POST['anb_rules_accepted']) {
                errormsg("Du musst die Nutzungsbestimmungen akzeptieren!");
                exit();
            }
            if (empty($user) or empty($email) or empty($pass1) or empty($pass2)) {
                errormsg("Bitte fülle alle Felder korrekt aus!");
                exit();
            }

            if ($pass1 != $pass2) {
                errormsg("Die Passwörter stimmen nicht überein!");
                exit();
            } else {
                $password = $db->real_escape_string(htmlspecialchars($pass1));
            }

            if (!preg_match("/^[a-zA-Z0-9]{3,16}$/i", $user)) {
                errormsg("Der Benutzername entspricht nicht den Vorgaben! Er darf nur aus Buchstaben und Zahlen bestehen und muss min. 3 und max. 16 Zeichen enthalten.");
                exit();
            }

            if (!preg_match('/^[^\x00-\x20()<>@,;:\\".[\]\x7f-\xff]+(?:\.[^\x00-\x20()<>@,;:\\".[\]\x7f-\xff]+)*\@[^\x00-\x20()<>@,;:\\".[\]\x7f-\xff]+(?:\.[^\x00-\x20()<>@,;:\\".[\]\x7f-\xff]+)+$/i', $email)) {
                errormsg("Die E-Mail Adresse ist ungültig.");
                exit();
            }

            if (checktrashmail($email)) {
                errormsg("Die E-Mail Domain ist gesperrt.");
                exit();
            }

            if (!preg_match("/^[a-zA-Z0-9]{3,32}$/i", $password)) {
                errormsg("Das Passwort entspricht nicht den Vorgaben! Es darf nur aus Buchstaben und Zahlen bestehen und muss min. 3 und max. 32 Zeichen enthalten.");
                exit();
            }

            $passwordhashed = md5(_SALT . md5($password));
            $activatecode = md5(random_str(32));

            $db->query("INSERT INTO sl_users (username, password, regdate, mail, activatecode, minecraftname) VALUES ('" . $user . "', '" . $passwordhashed . "', '" . time() . "', '" . $email . "', '" . $activatecode . "', '" . $minecraftname . "')");

            $mailer->AddAddress($email);
            $mailer->Subject = "MineServers.eu Aktivierung";
            $mailer->Body = "<html><head><title>MineServers.eu Aktivierung</title></head><body><h1>Howdy $user!</h1><p>Vielen Dank für deine Registrierung auf www.mineservers.eu.<br>Bevor wir deine Registrierung aktivieren können, musst du einmalig die Gültigkeit deiner E-Mail Adresse bestätigen.<br><br>Bitte bestätige die Gültigkeit deiner E-Mail-Adresse, indem du folgenden Link aufrufst:<br>$serverlisturl/index.php?method=activate&key=$activatecode&user=$user <br><br>Wenn du Probleme mit der Aktivierung hast, wende dich bitte an den Administrator: contact@mineservers.eu<br><br>Solltest du dich nicht auf www.mineservers.eu angemeldet haben, kannst du diese E-Mail ignorieren.<br><br>Vielen Dank,<br>Dein MineServers.eu Team</p></body></html>";
            $mailer->AltBody = "Howdy $user!\n\nVielen Dank für deine Registrierung auf www.mineservers.eu.\nBevor wir deine Registrierung aktivieren können, musst du einmalig die Gültigkeit deiner E-Mail Adresse bestätigen.\n\nBitte bestätige die Gültigkeit deiner E-Mail-Adresse, indem du folgenden Link aufrufst:\n$serverlisturl/index.php?method=activate&key=$activatecode&user=$user \n\nWenn du Probleme mit der Aktivierung hast, wende dich bitte an den Administrator: contact@mineservers.eu\n\nSolltest du dich nicht auf www.mineservers.eu angemeldet haben, kannst du diese E-Mail ignorieren.\n\nVielen Dank,\nDein MineServers.eu Team";

            $send = $mailer->Send();

            if ($send) {
                successmsg('Willkommen, ' . $user . '! Bitte bestätige deine E-Mail Adresse!', "start");
            } else {
                errormsg('Senden der Aktivierungs E-Mail fehlgeschlagen! Bitte kontaktiere contact@mineservers.eu', "start");
            }
        } else {
            errormsg("Benutzername " . $user . " und/oder E-Mail " . $email . " wird bereits verwendet!");
        }
    }
}
?>
