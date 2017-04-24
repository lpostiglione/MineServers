<?php
header("Content-type: image/png");

$cfgkey = "MineServers.EuisteinfachHamma";
require_once '../config.php';
require_once('../includes/functions.php');
$cacheFolder = 'cache';

$name = isset($_GET['name']) ? $_GET['name'] : 'char';
$size = isset($_GET['size']) && is_numeric($_GET['size']) ? intval($_GET['size']) : 16;

if($name != "char"){
	$nameq = $db->real_escape_string($name);
	$query = $db->query("SELECT minecraftname FROM `sl_users` WHERE `username` = '$nameq'");
	$data = $query->fetch_array(MYSQLI_ASSOC);
	
	if($data['minecraftname'] != "Steve" && $query->num_rows == 1){
		$name = $data['minecraftname'];
	}

}

$cachePath = $cacheFolder . DIRECTORY_SEPARATOR . $name . '-' . $size . '.png';

if (is_file($cachePath)) {
    include($cachePath);
    exit();
}

$src = imagecreatefrompng("http://s3.amazonaws.com/MinecraftSkins/" . $name . ".png");

if (!$src) {
    $src = imagecreatefrompng("http://s3.amazonaws.com/MinecraftSkins/char.png");
}

$dest = imagecreatetruecolor(8, 8);
imagecopy($dest, $src, 0, 0, 8, 8, 8, 8);

$final = imagecreatetruecolor($size, $size);
imagecopyresized($final, $dest, 0, 0, 0, 0, $size, $size, 8, 8);

imagepng($final, $cachePath);
readfile($cachePath);

imagedestroy($dest);
imagedestroy($final);
?>