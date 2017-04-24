<?php

$notfound = '<HTML><HEAD><TITLE>404 Not Found</TITLE><BASE href="/error_docs/"><!--[if lte IE 6]></BASE><![endif]--></HEAD><BODY><H1>Not Found</H1>The requested document was not found on this server.<P><HR><ADDRESS>Web Server at mineservers.eu</ADDRESS></BODY></HTML><!--   - Unfortunately, Microsoft has added a clever new   - "feature" to Internet Explorer. If the text of   - an error\'s message is "too small", specifically   - less than 512 bytes, Internet Explorer returns   - its own error message. You can turn that off,   - but it\'s pretty tricky to find switch called   - "smart error messages". That means, of course,   - that short error messages are censored by default.   - IIS always returns error messages that are long   - enough to make Internet Explorer happy. The   - workaround is pretty simple: pad the error   - message with a big comment like this to push it   - over the five hundred and twelve bytes minimum.   - Of course, that\'s exactly what you\'re reading   - right now.   -->';

if ($cfgkey != "MineServers.EuisteinfachHamma") {
    header("HTTP/1.0 404 Not Found");
    echo $notfound;
    exit();
}

/* MySQL Config */
$dbhost = 'localhost';
$dbuser = 'mineserverssql3';
$dbpass = 'JZKy2bW?4VN9-QZ5C8T7-YsEyqo';
$dbname = 'mineserverssql3';
$serverlisturl = 'http://www.mineservers.eu';

/* Definiere Konstanten */
define("PUBLICKEY", "6LcGmO4SAAAAAIqoU7uWS2gRwed_fDc2JcJEkGk7");
define("PRIVATEKEY", "6LcGmO4SAAAAALcDTfhf9mNS5fDKR4EKsdQGomLG");
define("_SALT", "28efdcf8631e733fe6b79a0f4212ba47e4403117");
define("_NOPERMISSION", "Dazu hast du keine Berechtigung!");

/* Hauptmenü */
$mainmenu = array(
    array("site" => "start", "link" => "index.php?site=start", "name" => "Startseite"),
    array("site" => "serverlist", "link" => "index.php?site=serverlist", "name" => "Serverliste"),
    array("site" => "forum", "link" => "http://board.mineservers.eu/", "name" => "Forum"),
    array("site" => "faq", "link" => "index.php?site=faq", "name" => "Hilfe")
);

/* Mail Einstellungen */
if (isset($mailer)) {
    $mailer->IsSMTP();
    $mailer->CharSet = 'utf-8';
    $mailer->Host = 'xrth.eu';
    $mailer->SMTPAuth = true;
    $mailer->Username = 'contact@mineservers.eu';
    $mailer->Password = 'Oceans5235';
    $mailer->From = 'contact@mineservers.eu';
    $mailer->FromName = 'MineServers.eu';
    $mailer->IsHTML(true);
}

/* CKEditor Seiten */
$ckesites = array(
    "editserver",
    "addserver",
    "newsletter"
);

/* DO NOT TOUCH!!! */
@$db = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
echo mysqli_connect_error();
if (mysqli_connect_errno()) {
    exit();
}

$db->set_charset('utf8');

date_default_timezone_set('Europe/Vienna');
?>