<?php 
$id = $_GET['id'];
$serverdataq = $db->query("SELECT servername, votifier FROM sl_servers WHERE id = '$id'");
if($serverdataq->num_rows != 1){
	include("sites/404.php");
	exit();
}
$serverdata = $serverdataq->fetch_object();
?>
<div class="page-header">
    <h1>Voten <small>für <?php echo $serverdata->servername ?></small></h1>
</div>
<div class="hiersolltewerbungstehen">
	<script type="text/javascript"><!--
	google_ad_client = "ca-pub-8528772941279859";
	/* MineServers 2 */
	google_ad_slot = "6357039863";
	google_ad_width = 468;
	google_ad_height = 60;
	//-->
	</script>
	<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
</div>
<br>
<form action="index.php?method=vote" method="post">
    <center>
        <?php
        if ($_SESSION['id'] == 0) {
            echo recaptcha_get_html(PUBLICKEY);
            echo "<br>";
        }
		
        if ($serverdata->votifier == "true") {
            ?>
            <label for="minecraftname">Minecraft Username</label>
            <input type="text" id="minecraftname" name="minecraftname" value="<?php echo $_GET['user']; ?>">
            <input type="text" name="email" style="display:none">
            <span class="help-block">
                <small>Mit dem Absenden der Bewertung erklärst du dich damit einverstanden, dass deine IP-Adresse und dein Name gespeichert werden und an den bewerteten Minecraft Server gesendet werden.</small>
            </span>
            <?php 
        } else {
            ?>
            <span class="help-block">
                <small>Mit dem Absenden der Bewertung erklärst du dich damit einverstanden, dass deine IP-Adresse und dein Name gespeichert werden.</small>
            </span>
            <?php 
        }
        ?>
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <input class="btn btn-success btn-large" style=" width: 150px; " type="submit" value="Absenden" />
    </center>
</form>
<?php 
if ($serverdata->votifier == "true") {
    $voters = $db->query("SELECT minecraftname FROM sl_votes WHERE serverid = " . $id);
    ?>
    <hr>
    <h4>Heute schon für <?php echo $serverdata->servername ?> gevotet haben:</h4>

    <?php 
    echo '<div class="players well">';
    if ($voters->num_rows == 0) {
        echo "<p style='text-align:center'>Leider hat heute noch niemand für diesen Server gevotet. Sei der erste!</p>";
    } else {
        while ($voter = $voters->fetch_array(MYSQLI_ASSOC)) {
            echo '<a href="#" rel="tooltip" data-placement="top" data-original-title="' . $voter['minecraftname'] . '" style=" display: inline-block; "><img width="32" height="32" style="margin:1px;" src="images/avatar.php?name=' . $voter['minecraftname'] . '&size=32" alt="' . $voter['minecraftname'] . '"></a>';
        }
    }
    echo "</div>";
}
?>
<div class="hiersolltewerbungstehen-lg">
	<script type="text/javascript"><!--
	google_ad_client = "ca-pub-8528772941279859";
	/* MineServers */
	google_ad_slot = "5986464269";
	google_ad_width = 728;
	google_ad_height = 90;
	//-->
	</script>
	<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
</div>