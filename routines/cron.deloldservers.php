<?php 
$cfgkey = "MineServers.EuisteinfachHamma";
require_once '/var/customers/webs/mineservers/config.php';

$result = $db->query("SELECT `id`, `lastonline` FROM `sl_servers` ORDER BY `id` DESC");

while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
	
	if((time() - $row['lastonline']) > 3628800 ){
	$db->query("DELETE FROM `sl_servers` WHERE `id` = " . $row['id']);
	$db->query("DELETE FROM `sl_comments` WHERE `serverid` = " . $row['id']);
	print "Deleted: " . $row['id'] . "\n";
	}
}