<?php 
$notfound = <<<HTTPERROR
<HTML>
<HEAD>
<TITLE>404 Not Found</TITLE>
<BASE href="/error_docs/"><!--[if lte IE 6]></BASE><![endif]-->
</HEAD>
<BODY>
<H1>Not Found</H1>
The requested document was not found on this server.
<P>
<HR>
<ADDRESS>
Web Server at mineservers.eu
</ADDRESS>
</BODY>
</HTML>

<!--
   - Unfortunately, Microsoft has added a clever new
   - "feature" to Internet Explorer. If the text of
   - an error's message is "too small", specifically
   - less than 512 bytes, Internet Explorer returns
   - its own error message. You can turn that off,
   - but it's pretty tricky to find switch called
   - "smart error messages". That means, of course,
   - that short error messages are censored by default.
   - IIS always returns error messages that are long
   - enough to make Internet Explorer happy. The
   - workaround is pretty simple: pad the error
   - message with a big comment like this to push it
   - over the five hundred and twelve bytes minimum.
   - Of course, that's exactly what you're reading
   - right now.
   -->
HTTPERROR;

//CONFIG UND CLASSES WERDEN EINGEBUNDEN/DEFINIERT
$cfgkey = "MineServers.EuisteinfachHamma";
require_once '../config.php';
require_once '../includes/functions.php';
require_once '../includes/MinecraftQuery.class.php';
$prefix = "&6[MineServers Vote] ";
$pruefsumme = file_get_contents("http://api.mineservers.eu/McServersVote/". file_get_contents("http://api.mineservers.eu/version.php") . "/checksum.txt");

//SUCHE NACH SERVERN MIT ENTSPRECHENDEM API KEY
$apikeyrequest = $db->real_escape_string($_REQUEST['apikey']);
$apiinfoq = $db->query("SELECT * FROM sl_voteapi WHERE apikey = '" . $apikeyrequest . "'");

//CHECK OB API KEY VORHANDEN IST
if($apiinfoq->num_rows > 0){
	
	//CONTENT TYPE DEFINIEREN
	header('Content-Type: text/plain; charset=UTF-8');
	
	//INFOS AUS DER API TABELLE AUSLESEN
	$apiinfo = $apiinfoq->fetch_object();
	
	//INFOS AUS DER SERVERTABELLE AUSLESEN
	$serverinfoq = $db->query("SELECT * FROM sl_servers WHERE id = " . $apiinfo->id);
	$serverinfo = $serverinfoq->fetch_object();
	
	$Query = new MinecraftQuery( );
	$Query->Connect( $serverinfo->ip, $serverinfo->serverport );
	$QueryInfos = $Query->GetInfo();
	
	if($QueryInfos == false){
		echo base64_encode("-1;Bitte aktiviere Minecraft Query");
		exit();
	}
	
	if($serverinfo->ip == $QueryInfos['HostIp']){
	
		//CHECK OB ES EIN REQUEST IST ODER EIN VERSCHLÜSSELTER VOTE
		if($_REQUEST['request'] == "true"){
		
			//CHECK OB DIE API BEIM ENTSPRECHENDEN SERVER AKTIVIERT IST
			if ($serverinfo->api != "true"){
				echo base64_encode("-1;" . $prefix . "&cDie API wurde deaktiviert");
				exit();
			}
			
			//CHECK OB EIN BENUTZERNAME ANGEGEBEN WURDE (ALS GET VARIABLE)
			if(isset($_REQUEST['username']) && !empty($_REQUEST['username'])){
			
				//CHECK OB BENUTZER ONLINE
				if(is_array($Query->GetPlayers()) && in_array($_REQUEST['username'], $Query->GetPlayers())){
					
					//FRAGEN FÜR DIE SICHERHEITSABFRAGE
					$questions = array(
						array("q" => "&3Was ist Gruen und explodiert? (Ein Wort)", "a" => "Creeper"),
						array("q" => "&3Wie heisst der Erfinder von Minecraft?", "a" => "Notch"),
						array("q" => "&3Wie heisst der (aktuelle) Hauptentwickler von Minecraft?", "a" => "Jeb"),
						array("q" => "&3Welches Erz ist Rot und leuchtet?", "a" => "Redstone"),
						array("q" => "&3Was ist Rosa und droppt Fleisch wenn man es umbringt?", "a" => "Schwein"),
						array("q" => "&3Welche Farbe hat Stein?", "a" => "Grau"),
						array("q" => "&3Was ergibt Eins mal Sechs? (In Worten)", "a" => "Sechs"),
						array("q" => "&3Wie heisst die Hoelle in Minecraft?", "a" => "Nether"),
						array("q" => "&3Worauf kann man in Minecraft reiten?", "a" => "Pferd"),
						array("q" => "&3Gib folgendes Wort richtig ein: Meinkraft", "a" => "Minecraft"),
						array("q" => "&3Wie lautet der Name von diesem Spiel?", "a" => "Minecraft"),
						array("q" => "&3Was ergibt Zwei plus Sieben? (In Worten)", "a" => "Neun")
					);
					
					//VARIABLEN WERDEN DEFINIERT
					$requestkey = random_str(24);
					$username = $db->real_escape_string($_REQUEST['username']);
					$question = $questions[rand(0, 10)];
					$time = time();
					
					//VARIABLEN WERDEN iN DIE REQUESTTABELLE GESCHRIEBEN
					$db->query("
						INSERT INTO `sl_apirequests` (
							`requestkey` ,
							`username` ,
							`question` ,
							`answer` ,
							`time`
						) VALUES (
							'$requestkey',
							'$username',
							'" . $question["q"] . "',
							'" . $question["a"] . "',
							'$time'
						);");
		
					//ANTWORT MIT FRAGE UND REQUESTKEY WIRD AUSGEGEBEN
					echo base64_encode($requestkey . ";" . $prefix  . $question["q"]);
					
				} else {
					
					//FEHER WENN DER BENUTZERNAME NICHT ANGEGEBEN WAR
					echo base64_encode("-1;" . $prefix . "&cAnfrage fehlerhaft - User nicht online!");
				}
				
			} else {
				
				//FEHER WENN DER BENUTZERNAME NICHT ANGEGEBEN WAR
				echo base64_encode("-1;" . $prefix . "&cAnfrage fehlerhaft");
			}
		
		//FÜHRT DAS AUS WENN ES KEINE ANFRAGE SONDERN EIN VERSCHLÜSSELTER VOTE WAR
		} else {
			
			//CHECK OB API AKTIV
			if ($serverinfo->api == "false"){
				echo "1;" . $prefix . "&cDie API wurde deaktiviert";
				exit();
			}
			
			//DECODET DEN BASE64 STRING
			$crypt = base64_decode($_REQUEST['data']);
			
			//DECRYPTET DEN RSA BLOCK MIT DEM PRIVATE KEY
			$decryption = openssl_private_decrypt($crypt, $datastring, $apiinfo->privatekey);
			
			//CHECK OB RSA STRING GÜLTIG UND DECRYPTEN ERFOLGREICH
			if($decryption == true){
										            
				//TEILT DEN DECODIERTEN STRING IN EIN ARRAY NACH DEM SCHEMA:
				//      0      |  1   |   2    |     3     |    4	 |	   5 	|   6   |
				//unixtimestamp;apikey;username;ip-vom-user;prüfsumme;requestkey;antwort
				$data = explode(";", $datastring);
				
				//CHECK OB DER APIKEY DES STRINGS MIT DEM APIKEY DES REQUESTS ÜBEREINSTIMMT
				if($data[1] == $apikeyrequest){
					
					//CHECK OB DIE PRÜFSUMME STIMMT ODER DAS PLUGIN VERALTET/VERÄNDERT IST
					if($data[4] == $pruefsumme){
						
						//DEFINIERT VARIABLEN FÜR DIE DATENBANK
						$requestkey = $db->real_escape_string($data[5]);
						$useranswer = $db->real_escape_string($data[6]);
						$minecraftname = $db->real_escape_string($data[2]);
						
						//LÖSCHT ALLE REQUESTS DIE ÄLTER SIND ALS 30 SEKUNDEN
						$db->query("DELETE FROM sl_apirequests WHERE time < " . (time() - 30));
						
						//SUUCHT DEN ENTSPRECHENDEN REQUEST IN DER DATENBANK
						$requestqaq = $db->query("SELECT * FROM sl_apirequests WHERE requestkey = '".$requestkey."'");
						
						//CHECK OB EIN ENTSPRECHENDER REQUEST VORHANDEN IST
						if($requestqaq->num_rows == 1){
							
							//SPEICHERT DIE INFORMATIONEN ÜBER DEN REQUEST IN EINEM OBJEKT
							$requestqa = $requestqaq->fetch_object();
							
							//CHECKT OB ANTWORT & USER AUS DEM REQUEST TABLE MIT USER & ANTWORT AUS DEM STRING ÜBEREINSTIMMEN
							if((strtolower($requestqa->answer) == strtolower($useranswer)) && (strtolower($requestqa->username) == strtolower($minecraftname))){
								
								//ESCAPED DIE IP DES USERS AUS DEM STRING UND DIE SERVER ID AUS DER API TABELLE
								$userip = $db->real_escape_string($data[3]);
								$srvid = $db->real_escape_string($apiinfo->id);
								
								//PRÜFT OB DER USERNAME UND/ODER DIE IP HEUTE SCHON GEVOTET HABEN
								$checkip = $db->query("SELECT id FROM sl_votes WHERE ip = '$userip'");
								$checkmc = $db->query("SELECT id FROM sl_votes WHERE minecraftname = '$minecraftname'");
								
								//FEHLER WENN DIE IP HEUTE SCHON GEVOTET HAT
								if ($checkip->num_rows > 0) {
									
								    echo "1;" . $prefix . '&cDu hast heute schon gevotet.';
								
								//FEHLER WENN DER USERNAME HEUTE SCHON GEVOTET HAT
								} elseif ($checkmc->num_rows > 0 and !empty($minecraftname)) {
								    
								    echo "1;" . $prefix . '&cDu hast heute schon gevotet.';
								
								//DO THIS WENN ALLES OK IST UND DER USER NICHT GEVOTET HAT
								} else {
									
									//FÜHRT VOTIFIER AUS WENN DER MINECRAFTNAME OK IST UND VOTIFIER AKTIVIERT IST
								    if (preg_match("/[A-Za-z0-9_]+/", $minecraftname) && $serverinfo->votifier == "true") {
										Votifier($serverinfo->votifierkey, $serverinfo->votifierip, $serverinfo->votifierport, $minecraftname, $data[3]);
									}
									
									//SCHREIBT DIE DATEN IN DIE VOTE TABELLE
								    $db->query("
								    	INSERT INTO `sl_votes` (
											`serverid` ,
											`minecraftname` ,
											`ip`
										) VALUES (
											'$srvid',
											'$minecraftname',
											'$userip'
										)");
									
									//STEIGERT DIE VOTES DES SERVERS UM 1
								    $votes = $serverinfo->votes;
								    $votes++;
								    $db->query("UPDATE `sl_servers` SET  `votes` = '$votes' WHERE `id` = '$srvid'");
								    
								    //GIBT DIE ERFOLGSMELDUNG ZURÜCK
									echo "1;" . $prefix . '&aDanke dass du den Server bewertet hast';
								}
								
							} else {
								//AUSGABE BEI FALSCHER ANTWORT, LÖSCHT REQUEST AUS DER DATENBANK - PASST ZU "USER & ANTWORT CHECK"
								echo "0;" . $prefix . "&cFalsche Antwort! Bitte versuch es noch einmal";
								$db->query("DELETE FROM sl_apirequests WHERE requestkey = '".$requestkey."'");
							}
						} else {
							//AUSGABE WENN KEIN REQUEST IN DER MYSQL VORHANDEN IST - PASST ZU "REQUEST VORHANDEN"
							echo "1;" . $prefix . "&cAnfrage abgelaufen";
						}
					} else {
						//AUSGABE WENN PRÜFSUMME NICHT ÜBEREINSTIMMT - PASST ZU "PLUGIN VERALTET/VERÄNDERT"
						echo "1;" . $prefix . "&cPlugin fehlerhaft oder nicht die aktuellste Version! Bitte benachrichtige einen Administrator" . $pruefsumme . "-" . $data[4];
					}
				} else {
					//AUSGABE WENN API KEY NICHT ÜBEREINSTIMMT - PASST ZU "ÜBEREINSTIMMUNG STRING & REQUEST"
					echo "1;" . $prefix . "&cFalscher API Key! Bitte benachrichtige einen Administrator";
				}
			
			} else {
				//AUSGABE WENN RSA NICHT ERFOLGREICH ENTSCHLÜSSELT WERDEN KONNTE - PASST ZU "DECRYPTION"
				echo "1;" . $prefix . "&cAnfrage fehlerhaft";
			}
		}
	
	} else {
		
		//AUSGABE WENN IP NICHT ÜBEREINSTIMMT
		header("HTTP/1.0 404 Not Found");
		echo $notfound;
		exit();
			
	}
		
} else {
	//AUSGABE WENN KEIN API KEY VORHANDEN
	header("HTTP/1.0 404 Not Found");
	echo $notfound;
	exit();
}
?>