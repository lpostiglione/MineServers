<?php

function errormsg($text, $site = "back", $id = "") {
    $delay = 3;
    if ($site == "back") {
        $link = $_SERVER["HTTP_REFERER"];
    } else {
        $link = "http://www.mineservers.eu/index.php?site=" . $site;
    }

    if (!empty($id)) {
        $link .= "&id=" . $id;
    }

    echo "<script type='text/javascript'>
            var SecVar = $delay;
            var UrlVar = '" . $link . "';
            var SetInt = window.setInterval('redirCont()', 1000);
            
            function redirCont(){
                SecVar--;
                document.getElementById('redirCont').innerHTML = SecVar;
                if(SecVar==0){
                    window.clearInterval(SetInt);
                    window.location = UrlVar;
                }
            }
          </script>
          <div class = 'alert alert-error'>" . $text . "<br>
            <br>
            <small>
              Du wirst in <span id='redirCont'>$delay</span> Sekunden weitergeleitet.<br>
              <a href = '" . $link . "'>Klicke hier um direkt weitergeleitet zu werden</a>
            </small>
          </div>";
}

function successmsg($text, $site = "back", $id = "") {
    $delay = 3;
    if ($site == "back") {
        $link = $_SERVER["HTTP_REFERER"];
    } else {
        $link = "http://www.mineservers.eu/index.php?site=" . $site;
    }

    if (!empty($id)) {
        $link .= "&id=" . $id;
    }

    echo "<script type='text/javascript'>
            var SecVar = $delay;
            var UrlVar = '" . $link . "';
            var SetInt = window.setInterval('redirCont()', 1000);
            
            function redirCont(){
                SecVar--;
                document.getElementById('redirCont').innerHTML = SecVar;
                if(SecVar==0){
                    window.clearInterval(SetInt);
                    window.location = UrlVar;
                }
            }
          </script>
          <div class = 'alert alert-success'>" . $text . "<br>
            <br>
            <small>
              Du wirst in <span id='redirCont'>$delay</span> Sekunden weitergeleitet.<br>
              <a href = '" . $link . "'>Klicke hier um direkt weitergeleitet zu werden</a>
            </small>
          </div>";
}

function randStr($length = 10) {
    $randomstring = '';

    if ($length > 32) {
        $multiplier = round($length / 32, 0, PHP_ROUND_HALF_DOWN);
        $remainder = $length % 32;

        for ($i = 0; $i < $multiplier; $i++) {
            $randomstring .= substr(str_shuffle(md5(rand())), 0, 32);
        }

        $randomstring .= substr(str_shuffle(md5(rand())), 0, $remainder);
    } else
        $randomstring = substr(str_shuffle(md5(rand())), 0, $length);

    return $randomstring;
}

function getreltime($timestamp) {

    $monthsec = date("t") * 24 * 60 * 60;

    if ((time() - $timestamp) < 60) {
        $time = time() - $timestamp;

        if ($time == 1)
            return "1 Sekunde";
        else
            return $time . " Sekunden";
    } elseif ((time() - $timestamp) < 3600) {
        $time = round((time() - $timestamp) / 60);

        if ($time == 1)
            return "1 Minute";
        else
            return $time . " Minuten";
    } elseif ((time() - $timestamp) < 86400) {
        $time = round(((time() - $timestamp) / 60) / 60);

        if ($time == 1)
            return "1 Stunde";
        else
            return $time . " Stunden";
    } elseif ((time() - $timestamp) < 604800) {
        $time = round((((time() - $timestamp) / 60) / 60) / 24);

        if ($time == 1)
            return "1 Tag";
        else
            return $time . " Tagen";
    } elseif ((time() - $timestamp) < $monthsec) {
        $time = round(((((time() - $timestamp) / 60) / 60) / 24) / 7);

        if ($time == 1)
            return "1 Woche";
        else
            return $time . " Wochen";
    } else {
        $time = round((((((time() - $timestamp) / 60) / 60) / 24) / 7) / (date("t") / 7));

        if ($time == 1)
            return "1 Monat";
        else
            return $time . " Monaten";
    }
}

function getago($timestamp) {
    if ($timestamp == 0) {
        return "Noch nie";
    } elseif ((time() - $timestamp) < 60) {
        $time = time() - $timestamp;
        if ($time == 1)
            return "Vor 1 Sekunde";
        else
            return "Vor " . $time . " Sekunden";
    } elseif ((time() - $timestamp) < 3600) {
        $time = round((time() - $timestamp) / 60);

        if ($time == 1)
            return "Vor 1 Minute";
        else
            return "Vor " . $time . " Minuten";
    } elseif ((time() - $timestamp) < 86400) {
        $time = round(((time() - $timestamp) / 60) / 60);

        if ($time == 1)
            return "Vor 1 Stunde";
        else
            return "Vor " . $time . " Stunden";
    } elseif ((time() - $timestamp) < 604800) {
        $time = round((((time() - $timestamp) / 60) / 60) / 24);

        if ($time == 1)
            return "Vor 1 Tag";
        else
            return "Vor " . $time . " Tagen";
    } else {
        $time = round(((((time() - $timestamp) / 60) / 60) / 24) / 7);

        if ($time == 1)
            return "Vor 1 Woche";
        else
            return "Vor " . $time . " Wochen";
    }
}

function getServerStatus($site, $port) {
    $fp = @fsockopen($site, $port, $errno, $errstr, 2);
    if (!$fp) {
        return false;
    } else {
        return true;
    }
}

function is_id($string) {
    if (preg_match("/^\d*$/", $string)) {
        return true;
    } else {
        return false;
    }
}

function checkifvalidip($host) {
    $ip = gethostbyname($host);

    if ($ip != '84.200.203.153') {
        if (false !== filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function Votifier($public_key, $server_ip, $server_port, $username, $userip, $answer = false) {

    $public_key = wordwrap($public_key, 65, "\n", true);
    $public_key = "-----BEGIN PUBLIC KEY-----\n" . $public_key . "\n-----END PUBLIC KEY-----";

    $timeStamp = time();

    $string = "VOTE\nmineservers.eu\n$username\n$userip\n$timeStamp\n";

    $leftover = (256 - strlen($string)) / 2;
    while ($leftover > 0) {
        $string.= "\x0";
        $leftover--;
    }

    openssl_public_encrypt($string, $crypted, $public_key);

    $socket = fsockopen($server_ip, $server_port, $errno, $errstr, 3);
    if ($socket) {
        fwrite($socket, $crypted);
        if ($answer) {
            $read = fread($socket, 256);
            return $read;
        } else {
            return true;
        }
    } else {
        return false;
    }
}

function checktrashmail($email) {
    $email = strtolower(trim(strip_tags($email)));

    $heurisspamlist = 'wh4f.org nervmich.net nervtmich.net wegwerfadresse.de bugmenot.com sofort-mail.de trash-mail.de spambog.com spambog.de discardmail.com discardmail.de dodgeit.com mailinator.com spam.la mytrashmail.com emaildienst.de senseless-entertainment.com temporaryinbox.com put2.net afrobacon.com golfilla.info zoemail.net zoemail.com willhackforfood.biz wegwerfadresse.de mns.ru ukr.net trashmail.org trashmail.de trashmail.com temporaryinbox.com tempinbox.com sriaus.com spamtrail.com spammotel.com spaminator.de spamhole.com spamgourmet.com spamex.com spamday.com spambob.org spambob.net spambob.com kasmail.com sneakemail.com rootprompt.org punkass.com nurfuerspam.de nospammail.net netzidiot.de mailnull.com mailmoat.com mailexpire.com kasmail.com fastsubaru.com fastsuzuki.com fasttoyota.com fastyamaha.com fastnissan.com fastmitsubishi.com fastmazda.com fastkawasaki.com fastchrysler.com fastchevy.com fastacura.com emailias.com e4ward.com dumpmail.de centermail.net centermail.com spamgourmet.com trashmail.net antichef.net mailexpire.com TempEMail.net drdrb.com';

    $pasarray = explode(' ', strtolower($heurisspamlist));
    $mailcheck = explode('@', $email);
    if (in_array($mailcheck[1], $pasarray, true)) {
        return true;
    } else {
        return false;
    }
}

function delcolors($string) {
    $return = preg_replace("/#./i", "", $string);
    return $return;
}

function addcolors($string) {
    $colors = Array(
        "g" => "<span class=\"c0-font\">",
        "1" => "<span class=\"c1-font\">",
        "2" => "<span class=\"c2-font\">",
        "3" => "<span class=\"c3-font\">",
        "4" => "<span class=\"c4-font\">",
        "5" => "<span class=\"c5-font\">",
        "6" => "<span class=\"c6-font\">",
        "7" => "<span class=\"c7-font\">",
        "8" => "<span class=\"c8-font\">",
        "9" => "<span class=\"c9-font\">",
        "a" => "<span class=\"ca-font\">",
        "b" => "<span class=\"cb-font\">",
        "c" => "<span class=\"cc-font\">",
        "d" => "<span class=\"cd-font\">",
        "e" => "<span class=\"ce-font\">",
        "f" => "<span class=\"cf-font\">",
        "l" => "<span class=\"cl-font\">",
        "m" => "<span class=\"cm-font\">",
        "n" => "<span class=\"cn-font\">",
        "o" => "<span class=\"co-font\">",
        "k" => "<span class=\"ck-font\">"
    );
    $string = str_replace("#0", "#g", $string);
    $motdarr = explode("#", $string);
    $spans = 0;
    $colored = "";

    foreach ($motdarr as $row) {
        if (!isset($isset)) {
            $isset = "isset";
            $colored .= $row;
        } elseif (empty($row)) {
            
        } elseif (strtolower(substr($row, 0, 1)) == "r") {
            while ($spans > 0) {
                $colored .= "</span>";
                $spans--;
            }
        } else {
            $colorcode = strtolower(substr($row, 0, 1));
            if (preg_match("/^[0-9a-g]$/i", $colorcode)) {
                while ($spans > 0) {
                    $colored .= "</span>";
                    $spans--;
                }
            }
            $colored .= $colors[$colorcode];
            $colored .= substr($row, 1);
            $spans++;
        }
    }
    while ($spans > 0) {
        $colored .= "</span>";
        $spans--;
    }
    return $colored;
}

function getuptcolor($upt) {
    if ($upt >= 90) {
        return "success";
    } elseif ($upt >= 50) {
        return "warning";
    } else {
        return "danger";
    }
}

function secure_seed_rng($count = 8) {
    $output = '';

    if (@is_readable('/dev/urandom') && ($handle = @fopen('/dev/urandom', 'rb'))) {
        $output = @fread($handle, $count);
        @fclose($handle);
    }

    if (strlen($output) < $count) {
        $output = '';

        $unique_state = microtime() . @getmypid();

        for ($i = 0; $i < $count; $i += 16) {
            $unique_state = md5(microtime() . $unique_state);
            $output .= pack('H*', md5($unique_state));
        }
    }

    $output = hexdec(substr(dechex(crc32(base64_encode($output))), 0, $count));

    return $output;
}

function my_rand($min = null, $max = null, $force_seed = false) {
    static $seeded = false;
    static $obfuscator = 0;

    if ($seeded == false || $force_seed == true) {
        mt_srand(secure_seed_rng());
        $seeded = true;

        $obfuscator = abs((int) secure_seed_rng());

        if ($obfuscator > mt_getrandmax()) {
            $obfuscator -= mt_getrandmax();
        }
    }

    if ($min !== null && $max !== null) {
        $distance = $max - $min;
        if ($distance > 0) {
            return $min + (int) ((float) ($distance + 1) * (float) (mt_rand() ^ $obfuscator) / (mt_getrandmax() + 1));
        } else {
            return mt_rand($min, $max);
        }
    } else {
        $val = mt_rand() ^ $obfuscator;
        return $val;
    }
}

function random_str($length = "8") {
    $set = array("a", "A", "b", "B", "c", "C", "d", "D", "e", "E", "f", "F", "g", "G", "h", "H", "i", "I", "j", "J", "k", "K", "l", "L", "m", "M", "n", "N", "o", "O", "p", "P", "q", "Q", "r", "R", "s", "S", "t", "T", "u", "U", "v", "V", "w", "W", "x", "X", "y", "Y", "z", "Z", "1", "2", "3", "4", "5", "6", "7", "8", "9");
    $str = '';

    for ($i = 1; $i <= $length; ++$i) {
        $ch = my_rand(0, count($set) - 1);
        $str .= $set[$ch];
    }

    return $str;
}

function is_image($path) {
    $a = getimagesize($path);
    $image_type = $a[2];

    if (in_array($image_type, array(IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_BMP))) {
        return true;
    }
    return false;
}

function month($n) {
    $months = Array(
        "1" => "Jänner",
        "2" => "Februar",
        "3" => "März",
        "4" => "April",
        "5" => "Mai",
        "6" => "Juni",
        "7" => "Juli",
        "8" => "August",
        "9" => "September",
        "10" => "Oktober",
        "11" => "November",
        "12" => "Dezember"
    );

    if (is_numeric($n) && $n <= 12 && $n >= 1) {
        return $months[$n];
    } else {
        return false;
    }
}

function get_gravatar($email, $s = 80, $d = 'mm', $r = 'g') {
    $url = 'http://www.gravatar.com/avatar/';
    $url .= md5(strtolower(trim($email)));
    $url .= "?s=$s&d=$d&r=$r";
    return $url;
}

function is_mod($user) {
    $moderators = array("Cruzer");

    if (in_array($user, $moderators)) {
        return true;
    } else {
        return false;
    }
}

function get_votereset() {
    $return = array();
    $votereset = strtotime("next Monday") + 60;

    array_push($return, date("Y", $votereset));
    array_push($return, date("m", $votereset));
    array_push($return, date("d", $votereset));
    array_push($return, date("H", $votereset));
    array_push($return, date("i", $votereset));
    array_push($return, date("s", $votereset));

    return $return;
}

function doLog($text, $log) {
    if ($log == "activation") {
        $filename = "logfiles/activation.log";
    } elseif ($log == "voteapi") {
        $filename = "logfiles/voteapi.log";
    }


    $fh = fopen($filename, "a") or die();
    fwrite($fh, date("d-m-Y, H:i") . " - $text\n") or die();
    fclose($fh);
}

function getDNS($hostname, $type = '') {
    $records = `dig +noall +answer $hostname $type`;
    $records = explode("\n", $records);
    $res_hostname = '';
    $port = 0;

    foreach ($records as $record) {
        preg_match_all('/([^\s]+)\s*/', $record, $matches);
        if (is_array($matches) && is_array($matches[1]) && count($matches[1]) > 3) {
            switch (strtoupper($matches[1][3])) {
                case 'SRV':
                    if (count($matches[1]) > 6) {
                        $port = $matches[1][6];
                        $res_hostname = $matches[1][7];
                    }
                    break;
                case 'A':
                case 'CNAME':
                    if (count($matches[1]) > 3) {
                        $res_hostname = $matches[1][4];
                    }
                    break;
            }
            if (!empty($res_hostname) && substr($res_hostname, -1) == '.')
                break;
        }
    }
    if (substr($res_hostname, -1) == '.') {
        $res_hostname = getDNS(trim($res_hostname, '. '));
    }

    if (empty($res_hostname))
        return false;
    //if (empty($res_hostname)) die('Failed to lookup IP for ' . (!empty($type) ? '(' . $type .' record) ' : '' ) . $hostname . PHP_EOL);
    if (empty($port))
        return $res_hostname;
    return $res_hostname . ':' . $port;
}

function mcpremium($username) {
    $premium = file_get_contents("https://minecraft.net/haspaid.jsp?user=" . $username);
    if ($premium == "true") {
        return true;
    } else {
        return false;
    }
}

?>