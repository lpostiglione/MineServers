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

if($_GET['secret'] == "a81dcaed7fe61d09cb24ad32bec3810f" && !empty($_GET['user']) && isset($_GET['key'])){
	
	//CONFIG UND CLASSES WERDEN EINGEBUNDEN/DEFINIERT
	$cfgkey = "MineServers.EuisteinfachHamma";
	require_once '../config.php';
	$prefix = "&6[MineServers Verify] ";
	
	//CONTENT TYPE DEFINIEREN
	header('Content-Type: text/plain; charset=UTF-8');
	
	if(!empty($_GET['key'])){
		
		//SUCHE NACH SERVERN MIT ENTSPRECHENDEM API KEY
		$minecraftname = $db->real_escape_string($_REQUEST['user']);
		$minecraftcode = $_REQUEST['key'];
		$userinfoq = $db->query("SELECT * FROM sl_users WHERE minecraftname = '" . $minecraftname . "'");
		
		//CHECK OB USERNAME VORHANDEN IST
		if($userinfoq->num_rows > 0){
			
			//INFOS AUS DER API TABELLE AUSLESEN
			$userinfo = $userinfoq->fetch_object();
			
			if($userinfo->minecraftverifycode == $minecraftcode){
			
				if($userinfo->minecraftverified == "false"){
				
					//AUSGABE WENN KEY ÜBEREINSTIMMT
					echo $prefix . "&aDu hast dich erfolgreich verifiziert";
					
					$db->query("UPDATE `sl_users` SET  `minecraftverified` = 'true' WHERE `id` = " . $userinfo->id);
					
				} else {
					//AUSGABE WENN BEREITS AKTIVIERT
					echo $prefix . "&cDu bist bereits verifiziert";
				}
				
				
			} else {
				
				//AUSGABE WENN KEY NICHT ÜBEREINSTIMMT
				echo $prefix . "&cDein Verifizierungscode ist nicht korrekt";
				
			}
			
		} else {
			
			//AUSGABE WENN KEY NICHT ÜBEREINSTIMMT
			echo $prefix . "&cBitte registriere dich vorher auf www.mineservers.eu";
			
		}
		
	} else {
		
		//AUSGABE WENN KEY LEER
		echo $prefix . "&cBitte gib einen Verifizierungscode ein";
		
	}
		
} else {
	//AUSGABE WENN SECRET FALSCH ODER USERNAME NICHT ANGEGEBEN
	header("HTTP/1.0 404 Not Found");
	echo $notfound;
	exit();
}
?>