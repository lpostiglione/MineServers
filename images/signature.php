<?php
header("Content-type: image/png");
header("Cache-Control: no-cache"); 
header("Pragma: no-cache"); 

error_reporting(0);

$cfgkey = "MineServers.EuisteinfachHamma";
require_once '../config.php';
require_once('../includes/functions.php');

if(!is_id($_GET['id']) or !is_id($_GET['style'])){
	echo "Fehler";
	exit();
}

$style = $_GET['style'];
$id = $_GET['id'];

$serverq = $db->query("SELECT * FROM sl_servers WHERE id = '" . $id . "'");
$serverinfos = $serverq->fetch_object();

$servername = str_replace(array("ä", "ü", "ö", "Ä", "Ü", "Ö", "ß"), array("ae", "ue", "oe", "Ae", "Ue", "Oe", "ss"), $serverinfos->servername);
$serverip = "IP: " . $serverinfos->serveradress . ":" . $serverinfos->serverport;
$serverslots = "Spieler: " . $serverinfos->player . "/" . $serverinfos->playerMax;

$image = imagecreatefrompng("src/bg" . $style . ".png");
if (!$image) {
    die("File not found");
}

$overlay = imagecreatefrompng("src/overlay.png");
if (!$overlay) {
    die("File not found");
}

imagecopy($image, $overlay, 0, 0, 0, 0, 560, 120);

$font = "/var/customers/webs/mineservers/assets/fonts/minecraft.ttf";

$white = imagecolorallocate($image, 255, 255, 255);
$red = imagecolorallocate($image, 255, 0, 0);

$angle = 0;

$fontSize = 18;
$y = 74;
$dimensions = imagettfbbox($fontSize, $angle, $font, $servername);
$textWidth = abs($dimensions[4] - $dimensions[0]);
if($textWidth >= 400){
	$y = 66;
	$fontSize = 12;
	$dimensions = imagettfbbox($fontSize, $angle, $font, $servername);
	$textWidth = abs($dimensions[4] - $dimensions[0]);
}
$x = imagesx($image) - $textWidth - 2;
imagettftext($image, $fontSize, $angle, $x, $y, $white, $font, $servername);

$fontSize = 12;
$dimensions = imagettfbbox($fontSize, $angle, $font, $serverslots);
$textWidth = abs($dimensions[4] - $dimensions[0]);
$x = imagesx($image) - $textWidth - 2;
imagettftext($image, $fontSize, $angle, $x, 98, $white, $font, $serverslots);

$textWidthSlots = $textWidth;
$fontSize = 12;
$dimensions = imagettfbbox($fontSize, $angle, $font, $serverip);
$textWidth = abs($dimensions[4] - $dimensions[0]);
$x = imagesx($image) - $textWidth - $textWidthSlots - 16;
imagettftext($image, $fontSize, $angle, $x, 98, $white, $font, $serverip);

if ($serverinfos->online == "false")
    imagettftext($image, 20, 0, 410, 35, $red, $font, "Offline");

imagepng($image);
imagedestroy($image);