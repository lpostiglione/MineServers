<?php
$cfgkey = "MineServers.EuisteinfachHamma";
require_once '/var/customers/webs/mineservers/config.php';

$db->query("UPDATE sl_servers SET votes = '0'");
?>