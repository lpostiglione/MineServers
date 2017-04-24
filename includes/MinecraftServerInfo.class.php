<?php

class MinecraftServerStatus {

    private $timeout;

    public function __construct($timeout = 3) {
        $this->timeout = $timeout;
    }

    public function getStatus($host = '127.0.0.1', $port = 25565) {

        //Get timestamp for the ping
        $start = microtime(true);

        //Connect to the server
        if (!$socket = @stream_socket_client('tcp://' . $host . ':' . $port, $errno, $errstr, $this->timeout)) {

            //Server is offline
            return false;
        } else {

            stream_set_timeout($socket, $this->timeout);

            //Write and read data
            fwrite($socket, "\xFE\x01");
            $data = fread($socket, 2048);
            fclose($socket);
            if ($data == null) {
                return false;
            }

            //Calculate the ping
            $ping = round((microtime(true) - $start) * 1000);

            //Evaluate the received data
            if (substr((String) $data, 3, 5) == "\x00\xa7\x00\x31\x00") {
                $result = explode("\x00", mb_convert_encoding(substr((String) $data, 15), 'UTF-8', 'UCS-2'));
                
                if($result[0] == ""){
                    $version = $result[1];
                    $motd = $result[2];
                } else {
                    $version = $result[0];
                    $motd = $result[1];
                }
            } else {
                $result = explode('§', mb_convert_encoding(substr((String) $data, 3), 'UTF-8', 'UCS-2'));

                $version = $result[0];
                $motd = "";
                foreach ($result as $key => $string) {
                    if ($key != sizeof($result) - 1 && $key != sizeof($result) - 2 && $key != 0) {
                        $motd .= '§' . $string;
                    }
                }
            }
            //Remove all special characters from a string
            //$motd = preg_replace("/[^[:alnum:][:punct:] ]/", "", $motd);
            //Set variables
            $res = array();
            $res['hostname'] = $host;
            $res['version'] = $version;
            $res['motd'] = $motd;
            $res['players'] = $result[sizeof($result) - 2];
            $res['slots'] = $result[sizeof($result) - 1];
            $res['ping'] = $ping;

            //return obj
            return $res;
        }
    }

}

/*class MinecraftServerInfo {
	/*
	 * Class written by Cruzer for mineservers.eu
	 *
	 * Website: http://postiglione.at
	 * 
	 *//*


    private $hostname;
    private $port;

    public function __construct($hostname, $port = 25565) {
        $this->hostname = $hostname;
        $this->port = $port;
    }
    
    public function getPingInfo(&$info) {
        $socket = @fsockopen($this->hostname, $this->port, $errno, $errstr, 1.0);

        if ($socket === false) {
            return false;
        }

        fwrite($socket, "\xfe\x01");

        $data = fread($socket, 256);
        $string = mb_convert_encoding($data, 'UTF-8');

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
                'maxplayers' => intval($data[2]),
                "string" => $string
            );
        } else {
            $info = array(
                'version' => $data[0],
                'motd' => $data[1],
                'players' => intval($data[2]),
                'maxplayers' => intval($data[3]),
                "string" => $string
            );
        }

        return true;
    }

}
*/