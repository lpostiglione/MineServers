<?php

error_reporting(E_ALL);

$cfgkey = "MineServers.EuisteinfachHamma";
require_once '/var/customers/webs/mineservers/config.php';
require_once '/var/customers/webs/mineservers/includes/functions.php';
require_once '/var/customers/webs/mineservers/includes/MinecraftServerInfo.class.php';
require_once '/var/customers/webs/mineservers/includes/MinecraftQuery.class.php';

$result = $db->query("SELECT `id`, `servername`, `serveradress`, `serverport`, `pingsonline`, `pingsoffline` FROM `sl_servers` ORDER BY `id` DESC");

while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
    if (ip2long($row['serveradress']) !== false) {
        $serveradress = $row['serveradress'];
        $serverport = $row['serverport'];
    } elseif (getDNS("_minecraft._tcp." . $row['serveradress'], "srv") !== false) {
        $srvrecord = explode(":", getDNS("_minecraft._tcp." . $row['serveradress'], "srv"));
        $serveradress = $srvrecord[0];
        $serverport = isset($srvrecord[1]) ? $srvrecord[1] : $row['serverport'];
    } else {
        $serveradress = gethostbyname($row['serveradress']);
        $serverport = $row['serverport'];
    }

    $server = new MinecraftServerStatus();
    $data = $server->getStatus($serveradress, $serverport);

    if (!$data) {
        $pingsoffline = $row['pingsoffline'] + 1;
        $uptime = floatval(round(($row['pingsonline'] / ($pingsoffline + $row['pingsonline'])) * 100, 1));
        $db->query("
                UPDATE  
                    `sl_servers`
                SET  
                    `player` = '0',
                    `playerMax` = '0',
                    `online` = 'false',
                    `lastcheck` = '" . time() . "',
                    `uptime` = '" . $uptime . "',
                    `pingsoffline` = '" . $pingsoffline . "'
                WHERE  
                    `id` = " . $row['id'] . ";
         ");
        $mcqexist = $db->query("SELECT `id` FROM `sl_mcquery` WHERE id = '" . $row['id'] . "'");
        if ($mcqexist->num_rows < 1) {
            $db->query("
                    UPDATE 
                        `sl_mcquery`
                    SET 
                        `Players` = ''
                    WHERE  
                        `id` = " . $row['id'] . ";
                ");
        }
        print "[" . date("H:i:s") . "] Der Server \"" . addslashes($row['servername']) . "\" ist nicht erreichbar.\n";
    } else {
        $Query = new MinecraftQuery( );

        try {
            $Query->Connect($serveradress, $serverport);

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

        $pingsonline = $row['pingsonline'] + 1;
        $uptime = floatval(round(($pingsonline / ($pingsonline + $row['pingsoffline'])) * 100, 1));
        $country = strtolower(file_get_contents('https://api.wipmania.com/' . $serveradress));
        $motd = str_replace("§", "#", $data['motd']);
        //preg_replace('/§./', '', $data['motd']);
        $time = time();
        $db->query("
            UPDATE  
                `sl_servers` 
            SET  
                `ip` = '" . $serveradress . "',
                `country` = '" . $country . "',
                `motd` = '" . $motd . "',
                `version` = '" . $data['version'] . "',
                `player` = '" . $data['players'] . "',
                `playerMax` = '" . $data['slots'] . "',
                `online` = 'true',
                `lastonline`= '" . $time . "',
                `lastcheck` = '" . $time . "',
                `uptime` = '" . $uptime . "',
                `pingsonline` = '" . $pingsonline . "',
                `mcquery`= '" . $mcquery . "'
            WHERE  
                `id` = " . $row['id'] . ";
        ");
        print "[" . date("H:i:s") . "] Der Server \"" . addslashes($row['servername']) . "\" ist erreichbar.\n";
    }
    sleep(2);
    set_time_limit(20);
}
?>
