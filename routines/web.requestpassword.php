<?php

$resp = recaptcha_check_answer(PRIVATEKEY, $_SERVER["HTTP_CF_CONNECTING_IP"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);

if (!$resp->is_valid) {
    errormsg("Das Captcha war falsch bitte versuch es nochmal!");
} elseif ($_SESSION['id'] != 0) {
    errormsg("Du bist bereits eingeloggt!");
} elseif (empty($_POST["email"]) && empty($_POST["user"])) {
    errormsg("Bitte fülle mindestens ein Feld aus!");
} else {

    $user = $db->real_escape_string(htmlspecialchars($_POST["user"]));
    $email = $db->real_escape_string(htmlspecialchars($_POST["email"]));

    $query = (!empty($_POST["user"])) ? "SELECT username, mail, id, password FROM sl_users WHERE username = '" . $user . "'" : "SELECT username, mail, id, password FROM sl_users WHERE mail = '" . $email . "'";
    $sql = $db->query($query);

    if ($sql->num_rows <= 0) {
        errormsg("Die angegebenen Daten sind nicht in unserem System!");
    } else {

        $result = $sql->fetch_array(MYSQLI_ASSOC);

        $requestkey = md5(random_str(32));

        $newpassq = (!empty($_POST["user"])) ? "UPDATE `sl_users` SET `openrequest` = '1', `requestkey` = '" . $requestkey . "' WHERE username = '" . $user . "'" : "UPDATE `sl_users` SET `openrequest` = '1', `requestkey` = '" . $requestkey . "' WHERE mail = '" . $email . "'";

        $sql = $db->query($newpassq);

        $mailtext = "<html><body><h1>Howdy " . $result['username'] . "!</h1> <p>Du kannst dein Passwort zurücksetzen indem du auf folgenden Link klickst: <a href='" . $serverlisturl . "/index.php?site=getpassword&key=" . $requestkey . "'>Zurücksetzen</a><br>Sollte der Link nicht funktionieren kopiere bitte folgende Zeile in die Adressleiste deines Browsers:<br><code>" . $serverlisturl . "/index.php?site=getpassword&key=" . $requestkey . "</code></p></body></html>";

        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
        $headers .= 'From: MineServers.eu <contact@mineservers.eu>' . "\r\n";

        $send = mail($email, 'MineServers.eu Password reset', $mailtext, $headers);

        if ($send) {
            successmsg('Dein neues Passwort wurde dir per E-Mail zugeschickt!', "login");
        } else {
            errormsg("Senden der E-Mail fehlgeschlagen! Bitte kontaktiere contact@mineservers.eu!");
        }
    }
}
?>
