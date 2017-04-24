<?php
$cfgkey = "MineServers.EuisteinfachHamma";
require_once '../config.php';
require_once '../includes/functions.php';

if(!is_id($_GET['id'])){
	header("Location: http://nobrain.dk");
}

$id = (is_id($_GET['id'])) ? $_GET['id'] : 0;

$serverq = $db->query("SELECT votes FROM sl_servers WHERE id = '" . $id . "'");
$serverinfos = $serverq->fetch_object();
?>
<!doctype html>
<html>
	<head>
	    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="../assets/css/bootstrap-responsive.min.css" rel="stylesheet">
        <link href="../assets/css/font-awesome.min.css" rel="stylesheet">
		<link href="votebutton.css" rel="stylesheet">
	</head>
	<body>
		<a href="http://www.mineservers.eu/index.php?site=vote&id=<?php echo $id; ?>" target="_blank" class="btn btn-success"><i class="icon-white icon-thumbs-up"></i> Vote</a><a href="http://www.mineservers.eu/index.php?site=serverview&id=<?php echo $id; ?>" target="_blank" class="mineservers-vote-count"><?php echo $serverinfos->votes; ?></span>
	</body>
</html>