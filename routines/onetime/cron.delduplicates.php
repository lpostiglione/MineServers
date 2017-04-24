<?php

$dbhost = 've234.myweb.li';
$dbuser = 'mineservers';
$dbpass = 'JZKy2bW?4VN9-QZ5C8T7-YsEyqo';
$dbname = 'mineservers';

/* DO NOT TOUCH!!! */

@$db = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

if (mysqli_connect_errno()) {
    printf("Verbindung fehlgeschlagen: %s\n", mysqli_connect_error());
    exit();
}

$db->set_charset('utf8');

$result = $db->query("SELECT `id`, `ip`, `serverport` FROM `sl_servers` ORDER BY `id` ASC");

while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
    $server = $db->query("SELECT `id`, `ip`, `serverport` FROM `sl_servers` WHERE `ip` = '" . $row['ip'] . "' AND `serverport` = '" . $row['serverport'] . "'");

    if ($server->num_rows > 1 && !empty($row['ip']) && $row["ip"] != "127.0.0.1" && $row["ip"] != "0.0.0.0") {
        $db->query("DELETE FROM `sl_servers` WHERE `ip` = '" . $row['ip'] . "' AND `serverport` = '" . $row['serverport'] . "'");
        $db->query("INSERT INTO `sl_blacklist` (`ip`, `port`) VALUES ('" . $row['ip'] . "', '" . $row['serverport'] . "')");
    }
}
?>
