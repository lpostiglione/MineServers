<?php 
//CHECK OB ALLE FELDER AUSGEFÜLLT
if (!empty($_POST['servername']) && !empty($_POST['serveradress']) && !empty($_POST['gametype']) && !empty($_POST['beschreibung']) && !empty($_POST['onlinemode'])) {
	
	//SETZT UND ESCAPED VARIABLEN
	$id = $db->real_escape_string(htmlspecialchars($_POST['id']));
	$servername = $db->real_escape_string(htmlspecialchars($_POST['servername']));
	$serveradress = $db->real_escape_string(htmlspecialchars($_POST['serveradress']));
	$serverport = (empty($_POST['serverport'])) ? "25565" : $db->real_escape_string($_POST['serverport']);
	$onlinemode = $db->real_escape_string($_POST['onlinemode']);
	$gmraw = $db->real_escape_string($_POST['gametype']);
	$description = $db->real_escape_string($_POST['beschreibung']);
	$voiceadress = !empty($_POST['voiceadress']) ? $db->real_escape_string(htmlspecialchars($_POST['voiceadress'])) : 0;
    $mapadress = str_replace(array("http://", "https://"), "", $_POST['mapadress']);
    $mapadress = (!empty($_POST['mapadress'])) ? $db->real_escape_string(htmlspecialchars($mapadress)) : 0;
    $website = str_replace(array("http://", "https://"), "", $_POST['website']);
    $website = (!empty($_POST['website'])) ? $db->real_escape_string(htmlspecialchars($website)) : 0;
	
	//CHECK OB SERVERNAME DEN VORGABEN ENTSPRICHT
	if (!(strlen($servername) > 4 and strlen($servername) < 48)) {
		errormsg("Der Servername \"" . $servername . "\" entspricht nicht den Vorgaben. Er enthält " . strlen($servername) . " Zeichen.", "back");
		exit();
	}
	
	//PRÜFT OB DIE SERVERADRESSE VALIDE IST	
	if (!checkifvalidip($serveradress)) {
		errormsg("Die Serveradresse entspricht nicht den Vorgaben", "back");
		exit();
	}
	
	//CHECK OB SERVERPORT VALIDE
	if (!preg_match("/^[0-9]{2,8}$/i", $serverport)) {
		errormsg("Der Serverport entspricht nicht den Vorgaben", "back");
		exit();
	}
	
	if ($onlinemode != "true" && $onlinemode != "false") {
		errormsg("Der Online Mode entspricht nicht den Vorgaben", "back");
		exit();
	}

	//BILDET EINE IP AUS DER SERVERADRESSE
	if(ip2long($serveradress) !== false){
		$ip = $serveradress;
	} elseif(getDNS("_minecraft._tcp." . $serveradress, "srv") !== false){
		$srvrecord = explode(":", getDNS("_minecraft._tcp." . $serveradress, "srv"));
		$ip = $srvrecord[0];
	} else {
		$ip = gethostbyname($serveradress);
	}
	
	//PRÜFT OB BEREITS EIN SERVER MIT DER IP EINGETRAGEN IST UND OB DAS DER SELBE IST ODER NICHT
	$exists = $db->query("SELECT * FROM `sl_servers` WHERE `ip` = '" . $ip . "' AND `serverport` = '".$serverport."'");
	$existsf = $exists->fetch_object;
	if ($exists->num_rows != 0 && $existsf->id != $_GET['id']) {
		errormsg("Es ist bereits ein Server mit dieser IP eingetragen.", "back");
		exit();
	}
	
	//WEIST DEM GAMETYPE DEN ENTSPTRECHENDEN NAMEN ZU UND PRÜFT OB VALIDE
	if (preg_match("/^[1-6]{1}$/i", $gmraw)) {
		switch ($gmraw) {
			case $gmraw == "1": $gametype = "Survival";
				break;
			case $gmraw == "2": $gametype = "PVP";
				break;
			case $gmraw == "3": $gametype = "Citybuild";
				break;
			case $gmraw == "4": $gametype = "Creative";
				break;
			case $gmraw == "5": $gametype = "Anderes";
				break;
			case $gmraw == "6": $gametype = "RPG";
				break;
		}
	} else {
		errormsg("Der Gametype entspricht nicht den Vorgaben", "back");
		exit();
	}
	
	//PRÜFT OB BESCHREIBUNG VALIDE
	if (!(strlen($description) > 10 and strlen($description) < 8192)) {
		errormsg("Die Beschreibung entspricht nicht den Vorgaben", "back");
		exit();
	}
	
	$db->query("UPDATE `sl_servers` SET `servername` = '" . $servername . "', `serveradress` = '" . $serveradress . "', `serverport` = '" . $serverport . "', `ip` = '" . $ip . "', `voiceadress` = '" . $voiceadress . "', `mapadress` = '" . $mapadress . "', `website` = '" . $website . "', `gametype` = '" . $gametype . "', `description` = '" . $description . "', `onlineMode` = '" . $onlinemode . "' WHERE `id` = " . $id);
     
     /*$db->query("
            UPDATE  
                `sl_servers` 
            SET  
                `servername` = '" . $servername . "',
                `serveradress` = '" . $serveradress . "',
                `serverport` = '" . $serverport . "',
                `voiceadress` = '" . $voiceadress . "',
                `mapadress` = '" . $mapadress . "',
                `website` = '" . $website . "',
                `gametype` = '" . $gametype . "',
                `description` = '" . $description . "',
                `onlineMode` = '" . $onlinemode . "'
            WHERE  
                `id` = " . $id . ";
                ");*/

	if (!empty($db->error)) {
		errormsg("Fehler! Bitte überprüfe deine Eingaben oder kontaktiere einen Administrator.", "back");
	} else {
		successmsg("Deine &Auml;nderungen an  \"" . $servername . "\" wurde erfolgreich gespeichert!", "myservers");
	}
} else {
	errormsg("Bitte f&uuml;lle alle Felder aus", "back");
}
?>