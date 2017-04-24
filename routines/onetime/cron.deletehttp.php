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

$result = $db->query("SELECT `id`, `mapadress`, `website` FROM `sl_servers` ORDER BY `id` ASC");

while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
    $website = str_ireplace(array("http://", "https://"), "", $row['website']);
    $website = $db->real_escape_string(htmlspecialchars($website));

    $mapadress = str_ireplace(array("http://", "https://"), "", $row['mapadress']);
    $mapadress = $db->real_escape_string(htmlspecialchars($mapadress));

    $db->query("
                    UPDATE  
                        `sl_servers` 
                    SET  
                        `website` = '" . $website . "',
                        `mapadress` = '" . $mapadress . "'
                    WHERE  
                        `id` = '" . $row['id'] . "'
                        ");
}
?>
