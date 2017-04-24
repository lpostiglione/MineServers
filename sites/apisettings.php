<?php
if ($_SESSION['id'] == 0) {
    errormsg(_NOPERMISSION, "back");
} else {
    $id = $_GET['id'];
	$serverdataquery = $db->query("SELECT * FROM `sl_servers` WHERE `id` = $id");
	$serverdata = $serverdataquery->fetch_object();
	
	if ($_SESSION['username'] == $serverdata->owner) {
	
		$apidataq = $db->query("SELECT * FROM sl_voteapi WHERE id = $id");
		if($apidataq->num_rows == 1){
			$apidata = $apidataq->fetch_object();
		} else {
			$apidata = false;
		}
			?>
            <div class="page-header">
                <h1>API Einstellungen</h1>
            </div>
            <form action="index.php?method=apisettings" method="POST">
                <table class="table table-bordered">
                	<tr>
	                    <td>API Aktiviert</td>
	                    <td colspan="2">
		                    <label class="radio inline">
		                        <input type="radio" name="apienabled" id="apion" value="true" <?php if($serverdata->api == "true") echo "checked"; ?>> Ja
		                    </label>
		                    <label class="radio inline">
		                        <input type="radio" name="apienabled" id="apioff" value="false" <?php if($serverdata->api == "false") echo "checked"; ?>>  Nein
		                    </label>
	                    </td>
                	</tr>
                    <tr>
	                    <td>API Key</td>
						<?php echo ($apidata != false) ? '<td>' . $apidata->apikey . '</td><td><label class="checkbox inline"><input type="checkbox" name="gennewapikey" value="true"> Neuen Key generieren</label></td>': '<td colspan="2"><span class="muted">Der API Key wird automatisch beim aktivieren der API generiert</span></td>'; ?>
                    </tr>
                    <tr>
                    	<td>Public Key</td>
                    	<td colspan="2"><?php echo ($apidata != false) ? '<a href="apis/getapipublickey.php?id=' .$serverdata->id .'" class="btn btn-success btn-mini"><i class="icon-white icon-download-alt"></i> Download</a>' : '<span class="muted">Der Private Key wird automatisch beim aktivieren der API generiert</span>'; ?></td>
                    </tr>
                </table>
                <input type="hidden" name="id" value="<?php echo $serverdata->id; ?>">
                <div class="form-actions">
                    <input type="submit" value="Speichern" class="btn btn-primary">
                    <a href="index.php?site=myservers" class="btn">Abbrechen</a>
                </div>
            </form>
			<?php 
		
	} else {
   		errormsg("nopermission", "back");
	}
}