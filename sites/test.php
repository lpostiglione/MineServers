<?php 

error_reporting(E_ALL);

if ($_SESSION['username'] != "Cruzer") {
    $n = 1;
    while ($n <= 100) {
        echo "<script>alert('Gratuliere! Du hast meine geheime Testseite gefunden ;)')</script>";
        $n++;
    }
} else {
//Get timestamp for the ping
    $start = microtime(true);
    $timeout = "5";
//Connect to the server
    if (!$socket = @stream_socket_client('tcp://46.4.65.73:27015', $errno, $errstr, $timeout)) {

        echo "OFflinE";
    } else {

        stream_set_timeout($socket, $timeout);
//Write and read data
        fwrite($socket, "\xFE\x01");
        $data = fread($socket, 2048);
        fclose($socket);
        if ($data == null) {
            exit();
        }
        echo "<pre>" . $data . "</pre>";
//Calculate the ping
        $ping = round((microtime(true) - $start) * 1000);

//Evaluate the received data
        if (substr((String) $data, 3, 5) == "\x00\xa7\x00\x31\x00") {

            $result = explode("\x00", mb_convert_encoding(substr((String) $data, 15), 'UTF-8', 'UCS-2'));
            echo "<pre>" . mb_convert_encoding(substr((String) $data, 15), 'UTF-8', 'UCS-2') . "</pre>";
            $motd = $result[1];
            echo "<pre>";
            print_r($result);
            echo "</pre>";
        } else {
            $result = explode('§', mb_convert_encoding(substr((String) $data, 3), 'UTF-8', 'UCS-2'));
            echo "<pre>Number 2:\n" . mb_convert_encoding(substr((String) $data, 15), 'UTF-8', 'UCS-2') . "</pre>";

            $motd = "";
            foreach ($result as $key => $string) {
                if ($key != sizeof($result) - 1 && $key != sizeof($result) - 2 && $key != 0) {
                    $motd .= '§' . $string;
                }
            }
        }
    }
}
?>