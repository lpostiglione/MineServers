<?php
if ($_SESSION['id'] == 0) {
    errormsg(_NOPERMISSION, "back");
} else {
    $id = $_GET['id'];
    if (ctype_digit($id)) {
        $query = $db->query("SELECT * FROM `sl_servers` WHERE `id` = $id");
        $data = $query->fetch_object();

        if ($_SESSION['username'] == $data->owner) {
            ?>
			<style type="text/css">
			.radio.inline, .checkbox.inline {
				display: inline-block;
				padding-top: 0;
				margin-bottom: 5px;
				vertical-align: text-bottom;
			}
			</style>
            <div class="page-header">
                <h1>Votifier Einstellungen</h1>
            </div>
            <form action="index.php?method=editvotifier" method="POST">
                <fieldset>
                    <label>Anbindung Aktiv</label>
                    <label class="radio inline">
                        <input type="radio" name="votifier" id="votifieron" value="true" <?php if($data->votifier == "true") echo "checked"; ?>>
                        Ja
                    </label>
                    <label class="radio inline">
                        <input type="radio" name="votifier" id="votifieroff" value="false" <?php if($data->votifier == "false") echo "checked"; ?>>
                        Nein
                    </label>
                    <?php if($data->votifier == "true") echo '<a style="margin-top: -12px;margin-left: 10px;" href="index.php?method=testvotifier&id=' . $id . '" class="btn btn-info btn-small"><i class="icon-white icon-refresh"></i> Anbindung Testen</a>'; ?>
                    <label>Votifier IP &amp; Port</label>
                    <input type="text" class="span6" name="votifierip" value="<?php if($data->votifierip == "0.0.0.0"){ echo gethostbyname($data->serveradress); } else { echo $data->votifierip; }?>"> : <input type="text" class="span2" name="votifierport" value="<?php echo $data->votifierport; ?>">                   
                    <label>Public Key</label>
                    <textarea name="votifierkey" style=" width: 440px; height: 200px;"><?php echo $data->votifierkey; ?></textarea>
                </fieldset>
                <input type="hidden" name="id" value="<?php echo $data->id; ?>">
                <br>
                <div class="form-actions">
                    <input type="submit" value="Änderungen Speichern" class="btn btn-success">
                    <a href="index.php?site=myservers" class="btn">Abbrechen</a>
                </div>
            </form>
            <?php 
        } else {
            errormsg("Du bist nicht der Besitzer diese Servers", back);
        }
    } else {
        errormsg("ID ist ungültig", back);
    }
}
?>