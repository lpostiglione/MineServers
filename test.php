<?php
error_reporting(E_ALL);

function read_packet_length($socket) {
	$a = 0;
	$b = 0;
	while(true) {
		$c = socket_read($socket, 1);
		if(!$c) {
			return 0;
		}
		$c = Ord($c);
		$a |= ($c & 0x7F) << $b++ * 7;
		if( $b > 5 ) {
			return false;
		}
		if(($c & 0x80) != 128) {
			break;
		}
	}
	return $a;
}

$host = "78.143.30.4";
$port = 25565;

$start = microtime(true);
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_connect($socket, $host, $port); 

            socket_send($socket, "\xFE\x01", 2, 0);
            $length = socket_recv($socket, $data, 512, 0);

            $ping = round((microtime(true)-$start)*1000);//calculate the high five duration
            
            if($length < 4 || $data[0] != "\xFF") {
                return false;
            }

            $motd = "";
            $motdraw = "";

            //Evaluate the received data
            if (substr((String)$data, 3, 5) == "\x00\xa7\x00\x31\x00"){

                $result = explode("\x00", mb_convert_encoding(substr((String)$data, 15), 'UTF-8', 'UCS-2'));
                $motd = $result[1];
                $motdraw = $motd;

            } else {

                $result = explode('§', mb_convert_encoding(substr((String)$data, 3), 'UTF-8', 'UCS-2'));
                    foreach ($result as $key => $string) {
                        if($key != sizeof($result)-1 && $key != sizeof($result)-2 && $key != 0) {
                            $motd .= '§'.$string;
                        }
                    }
                    $motdraw = $motd;
                }
socket_close($socket);
echo "<pre>";
print_r($data);
echo "</pre>";
echo $result[0];
?>