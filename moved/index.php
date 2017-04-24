<?php
    header("Status: 301 Moved Permanently");
    header("Location:http://www.mineservers.eu/index.php?". $_SERVER['QUERY_STRING']);
    exit;
?>
