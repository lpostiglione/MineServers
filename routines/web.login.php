<?php 

$username = $db->real_escape_string(htmlspecialchars($_POST['username']));
$sql = $db->query("SELECT username, mail, id, password, activated FROM sl_users WHERE username = '" . $username . "'");
$result = $sql->fetch_array(MYSQLI_ASSOC);

if ($_SESSION['id'] != 0) {
    errormsg('Du bist bereits eingeloggt!', "start");
} elseif (empty($result['username'])) {
    errormsg("Der Benutzername " . $username . " war entweder falsch geschrieben oder existiert nicht!", "login");
} elseif ($result["activated"] == 0) {
    errormsg("Bitte bestätige zuerst deine E-Mail Adresse", "start");
} elseif ($result["banned"] == 1) {
    errormsg("Du bist auf dieser Seite gesperrt!", "start");
} else {

    if (md5(_SALT . md5($_POST['password'])) == $result['password']) {

        if ($_POST['stayloggedin'] == "true") {
            $hash = randStr(64);
            setcookie("StayLoggedIn", $hash, time() + (3600 * 24 * 7));
            $db->query("INSERT INTO `sl_sessions` (`id`, `hash`, `username`, `mail`) VALUES ('" . $result["id"] . "', '$hash', '" . $result['username'] . "', '" . $result['email'] . "')");
        }

        $_SESSION["id"] = $result["id"];
        $_SESSION["username"] = $result["username"];
        $_SESSION["mail"] = $result["mail"];

        successmsg("Willkommen zurück " . $_SESSION['username'] . "!", "start");
    } else {
        errormsg("Dein Benutzername und/oder Passwort war ungültig!", "login");
    }
}
?>