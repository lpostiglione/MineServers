<?php

error_reporting(E_ALL);
date_default_timezone_set('Europe/Vienna');

require_once '/var/customers/webs/mineservers/includes/MinecraftQuery.class.php';

class minecraft_server {

    private $address;
    private $port;

    public function __construct($address, $port = 25565) {
        $this->address = $address;
        $this->port = $port;
    }

    public function get_ping_info(&$info) {
        $socket = @fsockopen($this->address, $this->port, $errno, $errstr, 1.0);

        if ($socket === false) {
            return false;
        }

        fwrite($socket, "\xfe\x01");

        $data = fread($socket, 256);

        if (substr($data, 0, 1) != "\xff") {
            return false;
        }

        if (substr($data, 3, 5) == "\x00\xa7\x00\x31\x00") {
            $data = explode("\x00", mb_convert_encoding(substr($data, 15), 'UTF-8', 'UCS-2'));
        } else {
            $data = explode('§', mb_convert_encoding(substr($data, 3), 'UTF-8', 'UCS-2'));
        }

        if (count($data) == 3) {
            $info = array(
                'version' => '1.3.2',
                'motd' => $data[0],
                'players' => intval($data[1]),
                'max_players' => intval($data[2]),
            );
        } else {
            $info = array(
                'version' => $data[0],
                'motd' => $data[1],
                'players' => intval($data[2]),
                'max_players' => intval($data[3]),
            );
        }

        return true;
    }

}

$cfgkey = "MineServers.EuisteinfachHamma";
require_once '/var/customers/webs/mineservers/config.php';

$result = $db->query("SELECT `id`, `servername`, `serveradress`, `serverport`, `online` FROM `sl_servers` ORDER BY `id` DESC");

while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
	
	if($row['online'] == "true"){
	
		$Query = new MinecraftQuery( );
	
	    try {
			$Query->Connect($row['serveradress'], $row['serverport']);
	
			$mcquery = "true";
			if (is_array($Query->GetInfo()['Plugins'])) {
				$Plugins = implode(';', $Query->GetInfo()['Plugins']);
			} else {
				$Plugins = "";
			}
			$Map = $Query->GetInfo()['Map'];
			$Software = $Query->GetInfo()['Software'];
			if ($Query->GetPlayers()) {
				$Players = implode(';', $Query->GetPlayers());
			} else {
				$Players = "";
			}
		} catch (MinecraftQueryException $e) {
			$mcquery = "false";
	    }
		
		if ($mcquery == "true") {
			$mcqexist = $db->query("SELECT `id` FROM `sl_mcquery` WHERE id = '" . $row['id'] . "'");
			if ($mcqexist->num_rows < 1) {
				$db->query("
	                    INSERT INTO  `sl_mcquery` (
	                        `id` ,
	                        `Plugins` ,
	                        `Map` ,
	                        `Software` ,
	                        `Players`
	                    ) VALUES (
	                        '" . $row['id'] . "',
	                        '" . $Plugins . "',
	                        '" . $Map . "',
	                        '" . $Software . "',
	                        '" . $Players . "'
	                    );
	                ");
			} else {
				$db->query("
	                    UPDATE 
	                        `sl_mcquery`
	                    SET 
	                        `Plugins` = '" . $Plugins . "',
	                        `Map` = '" . $Map . "',
	                        `Software` = '" . $Software . "',
	                        `Players` = '" . $Players . "'
	                    WHERE  
	                        `id` = " . $row['id'] . ";
	                ");
			}
		}
	
	}
	
	$country = strtolower(file_get_contents('https://api.wipmania.com/' . gethostbyname($row['serveradress'])));
	$db->query("
                    UPDATE  
                        `sl_servers` 
                    SET  
                        `ip` = '" . gethostbyname($row['serveradress']) . "',
                        `country` = '" . $country . "',
                        `mcquery`= '" . $mcquery . "'
                    WHERE  
                        `id` = " . $row['id'] . ";
                           ");
	print "[" . date("H:i:s") . "] Der Server \"" . $row['servername'] . "\" ist erreichbar.\n";
}
sleep(2);
set_time_limit(20);
?>
