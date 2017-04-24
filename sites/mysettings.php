<?php 
if ($_SESSION['id'] == 0) {
    errormsg(_NOPERMISSION, "back");
} else {
	$id = $_SESSION['id'];
	$query = $db->query("SELECT * FROM `sl_users` WHERE `id` = $id");
	$data = $query->fetch_object();
?>

<div class="page-header">
    <h1>Benutzer Einstellungen</h1>
</div>
<h3>Informationen</h3>
<table class="table table-bordered vcenter">
	<tr>
		<td rowspan="2" style="width: 64px;"><img src="images/avatar.php?name=<?php echo $_SESSION["username"] ?>&size=64" alt="Avatar"></td>
		<td>Benutzername:</td>
		<td><?php echo $data->username; ?></td>
	</tr>
	<tr>
	    <td>E-Mail:</td>
	    <td><?php echo $data->mail; ?></td>
	</tr>
</table>
<div class="row-fluid">
	<div class="span4">
		<h3>Passwort ändern</h3>
		<form action="index.php?method=changepassword" method="post">
			<div class="control-group">
				<label class="control-label" for="inputPasswordOld">Altes Passwort</label>
				<div class="controls">
					<input type="password" id="inputPasswordOld" name="oldpw" placeholder="Altes Passwort">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="inputPassword1">Neues Passwort</label>
				<div class="controls">
					<input type="password" id="inputPassword1" name="pw1" placeholder="Passwort"><br>
					<input type="password" id="inputPassword2" name="pw2" placeholder="Passwort wiederholen">
				</div>
			</div>
			<input class="btn btn-success" type="submit" value="Passwort ändern">
		</form>
	</div>
	<div class="span8">
		<h3>Minecraft Verifizierung</h3>
		<p class="muted">Diese Verifizierung ist optional.</p>
		<table class="table table-bordered">
			<?php 
				if($data->minecraftverified == "true"){
					echo "<tr><td>Status:</td><td class='text-success'>Verifiziert</td></tr>";
				} elseif($data->minecraftverified == "false" && $data->minecraftverifycode != "0") {
					echo "<tr><td>Status:</td><td class='text-warning'>Code ausgestellt - Warte auf Bestätigung</td></tr>";
				} else {
					echo "<tr><td>Status:</td><td class='text-error'>Nicht Verifiziert</td></tr>";
				}
				
				if($data->minecraftname != "Steve"){
					echo "<tr><td>Minecraft Name:</td><td>". $data->minecraftname . "</td></tr>";
				}
				
				if($data->minecraftverifycode != "0" && $data->minecraftverified == "false"){
					echo "<tr><td>Verifizierungscode:</td><td><code>". $data->minecraftverifycode ."</td></code></tr>";
				}
			?>
		</table>
		<?php 
		if($data->minecraftverified == "false" && $data->minecraftverifycode == "0"){
		?>
		<p>Um dieses Feature zu nutzen musst du Minecraft auf <a href="http://minecraft.net" title="Minecraft.net">Minecraft.net</a> gekauft haben.</p>
		<form action="index.php?method=minecraftverify" method="post">
			<div class="control-group">
				<label class="control-label" for="inputName">Minecraft Username</label>
				<div class="controls">
					<input type="text" style="margin-bottom:0" id="inputName" <?php echo ($data->minecraftname != "Steve") ? 'value="' . $data->minecraftname . '"' : ""; ?> name="name" placeholder="Steve">
					<input class="btn btn-success" type="submit" value="Verifizierung starten">
				</div>
			</div>
		</form>
		<?php 
		} elseif($data->minecraftverified == "false" && $data->minecraftverifycode != "0"){
			echo "<p style='text-align:justify'>Es wurde ein Verifizierungscode für dich generiert. Gib diesen bitte folgenden Befehl auf unserem Verifizierungsserver unter <b>verify.mineservers.eu</b> ein:  <code>/verify " . $data->minecraftverifycode . "</code></p>";
		}
		?>
	</div>
</div>


<?php } ?>