<?php
header('Content-Type: text/plain');
$id = $_GET['id'];

$cfgkey = "MineServers.EuisteinfachHamma";
require_once '../config.php';

$serverq = $db->query("SELECT version, motd, player, playerMax, online FROM sl_servers WHERE id = '" . $id . "'");
$serverinfos = $serverq->fetch_object();

echo $serverinfos->version . "\x00" . $serverinfos->motd . "\x00" . $serverinfos->player . "\x00" . $serverinfos->playerMax . "\x00" . $serverinfos->online;

?>