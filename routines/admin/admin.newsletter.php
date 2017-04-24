<?php 
$query = $db->query("SELECT username, mail FROM sl_users WHERE banned = 0");

while ($result = $query->fetch_array(MYSQLI_ASSOC)) {
	
	$content = "<html><head><title>MineServers.eu Newsletter</title></head><body><h1>Howdy " . $result['username'] . "!</h1>" . $_POST['newsletter'] . "</body></html>";
	
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
	$headers .= 'From: MineServers <contact@mineservers.eu>' . "\r\n";
	
	$send = mail($result['mail'], "MineServers.eu Newsletter: " . $_POST['subject'], $content, $headers);	
	
	if($send){
		echo 'Der Newsletter wurde erfolgreich versendet an ' . $result['mail'] . '.<br>';
	} else {
		echo 'Der Newsletter konnte nicht versendet werden an ' . $result['mail'] . '.<br>';
	}
}
?>