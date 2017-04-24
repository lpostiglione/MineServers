<?php

$dbhost = 've234.myweb.li';
$dbuser = 'mineservers';
$dbpass = 'JZKy2bW?4VN9-QZ5C8T7-YsEyqo';
$dbname = 'mineservers';
define(_SALT, "28efdcf8631e733fe6b79a0f4212ba47e4403117");

/* DO NOT TOUCH!!! */

@$db = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

if (mysqli_connect_errno()) {
    printf("Verbindung fehlgeschlagen: %s\n", mysqli_connect_error());
    exit();
}

$db->set_charset('utf8');

$result = $db->query("SELECT `password`, `id`, `username` FROM `sl_users` ORDER BY `id` ASC");

while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
	
	$pass = md5( _SALT . $row['password'] );
	
	$db->query("UPDATE sl_users SET password = '".$pass."' WHERE id = '" . $row['id'] . "'");
	
	print($row['username'] . "  updated\n");
}
?>
