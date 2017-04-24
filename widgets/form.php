<?php
$cfgkey = "MineServers.EuisteinfachHamma";
require_once '../config.php';
require_once '../includes/functions.php';
require_once('../includes/recaptchalib.php');

if(!is_id($_GET['id'])){
	header("Location: http://nobrain.dk");
}

$id = !empty($_GET['id']) ? $_GET['id'] : 0;

$serverq = $db->query("SELECT votifier FROM sl_servers WHERE id = " . $id);
if($serverq->num_rows != 1){
	echo "Error 404: Server not found";
	exit();
}
$serverinfos = $serverq->fetch_object();
?>
<!doctype html>
<html>
	<head>
	    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="../assets/css/bootstrap-responsive.min.css" rel="stylesheet">
        <link href="../assets/css/font-awesome.min.css" rel="stylesheet">
        <link href="form.css" rel="stylesheet">
        <meta charset="utf-8">
		<script type="text/javascript">
          var RecaptchaOptions = {
              theme: 'clean',
              lang: 'de'
          };
		</script>
	</head>
	<body>
<form action="http://www.mineservers.eu/index.php?method=vote" method="post" target="_blank">
    <center>
        <?php
        if ($serverinfos->votifier == "true") {
            ?>
            <label for="minecraftname">Minecraft Username</label>
            <input type="text" id="minecraftname" name="minecraftname">
            <input type="text" name="email" style="display:none">
            <?php echo recaptcha_get_html(PUBLICKEY); ?>
            <br>
            <span class="help-block">
                <small>Mit dem Absenden der Bewertung erklärst du dich damit einverstanden, dass deine IP-Adresse und dein Name gespeichert werden und an den bewerteten Minecraft Server gesendet werden.</small>
            </span>
            <?php 
        } else {
			echo recaptcha_get_html(PUBLICKEY);
            ?>
            <br>
            <span class="help-block">
                <small>Mit dem Absenden der Bewertung erklärst du dich damit einverstanden, dass deine IP-Adresse gespeichert wird.</small>
            </span>
            <?php 
        }
		
        ?>
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <input class="btn btn-block btn-success btn-large" type="submit" value="Absenden" />
    </center>
</form>
	</body>
</html>