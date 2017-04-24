<?php 
header('Content-Type: text/javascript');
echo "document.write('<iframe src=\"http://www.mineservers.eu/widgets/votebutton.php?id=" . $_GET['id'] . "\" width=\"140\" height=\"30\" frameborder=\"0\" scrolling=\"no\"></iframe>');";
?>