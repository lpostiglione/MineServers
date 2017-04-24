<?php 
	header('Content-Type: text/plain; charset=UTF-8');
	$version = scandir("McServersVote");
	echo $version[count($version) - 1];
?>