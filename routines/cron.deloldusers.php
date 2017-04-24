<?php 
$cfgkey = "MineServers.EuisteinfachHamma";
require_once '/var/customers/webs/mineservers/config.php';

$result = $db->query("SELECT `id`, `username`, `regdate` FROM `sl_users` WHERE `activated` = '0' ORDER BY `id` DESC");

while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
	
	if((time() - $row['regdate']) > 604800 ){
	$db->query("DELETE FROM `sl_users` WHERE `id` = " . $row['id']);
	print "Deleted: " . $row['username'] . "\n";
	}
}