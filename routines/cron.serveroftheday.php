<?php

$cfgkey = "MineServers.EuisteinfachHamma";
require_once '/var/customers/webs/mineservers/config.php';

$result = $db->query("SELECT `id`, `servername` FROM `sl_servers` WHERE `online` = 'true' ORDER BY RAND() LIMIT 1");
$server = $result->fetch_object();

$sotd = fopen("/var/customers/webs/mineservers/includes/sotd.txt", "w+");
fwrite($sotd, $server->id . "\n" . $server->servername);
fclose($sotd);
?>
