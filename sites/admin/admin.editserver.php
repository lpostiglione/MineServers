<?php 
$id = $_GET['id'];
$query = $db->query("SELECT * FROM `sl_servers` WHERE `id` = $id");
$data = $query->fetch_object();
?>

<div class="page-header">
	<h1>Server bearbeiten</h1>
	</div>
            <form action="index.php?amethod=editserver" method="POST">
                <fieldset>
                    <legend>Allgemein</legend>
                    <label>Servername<sup style="color:red">*</sup></label>
                    <input type="text" class="input-xlarge" name="servername" value="<?php echo $data->servername; ?>">
                    <label>Serveradresse &amp; Port<sup style="color:red">*</sup></label>
                    <input type="text" class="input-xlarge" name="serveradress" value="<?php echo $data->serveradress; ?>"><span class="doubledot">&nbsp;:&nbsp;</span><input type="text" class="span2" name="serverport" value="<?php echo $data->serverport; ?>">
                    <label>Voiceserver</label>
                    <input type="text" class="input-xlarge" name="voiceadress" value="<?php echo ($data->voiceadress != 0) ? $data->voiceadress : ""; ?>" placeholder="zb. voice.minecraft.net oder 54.243.168.153">
                    <label>Live-Map</label>
                    <div class="input-prepend">
                        <span class="add-on">http://</span>
                        <input type="text" class="input-xlarge" name="mapadress" value="<?php echo ($data->mapadress != 0) ? $data->mapadress : ""; ?>" placeholder="zb. map.minecraft.net">
                    </div>
                    <label>Webseite</label>
                    <div class="input-prepend">
                        <span class="add-on">http://</span>
                        <input type="text" class="input-xlarge" name="website" value="<?php echo ($data->website != 0) ? $data->website : ""; ?>" placeholder="zb. www.minecraft.net">
                    </div>
                    <legend>Premium Only<sup style="color:red">*</sup></legend>
                    <label class="radio">
                        <input type="radio" name="onlinemode" id="onlinemode1" value="true" <?php if ($data->onlineMode == "true") echo "checked"; ?>>
                        Ja
                    </label>
                    <label class="radio">
                        <input type="radio" name="onlinemode" id="onlinemode2" value="false" <?php if ($data->onlineMode == "false") echo "checked"; ?>>
                        Nein
                    </label>
                    <legend>Spieltyp<sup style="color:red">*</sup></legend>
                    <label class="radio">
                        <input type="radio" name="gametype" id="gametype1" value="1" <?php if ($data->gametype == "Survival") echo "checked"; ?>>
                        Survival
                    </label>
                    <label class="radio">
                        <input type="radio" name="gametype" id="gametype2" value="2" <?php if ($data->gametype == "PVP") echo "checked"; ?>>
                        PVP
                    </label>
                    <label class="radio">
                        <input type="radio" name="gametype" id="gametype6" value="6" <?php if ($data->gametype == "RPG") echo "checked"; ?>>
                        RPG
                    </label>
                    <label class="radio">
                        <input type="radio" name="gametype" id="gametype3" value="3" <?php if ($data->gametype == "Citybuild") echo "checked"; ?>>
                        Citybuild
                    </label>
                    <label class="radio">
                        <input type="radio" name="gametype" id="gametype4" value="4" <?php if ($data->gametype == "Creative") echo "checked"; ?>>
                        Creative
                    </label>
                    <label class="radio">
                        <input type="radio" name="gametype" id="gametype5" value="5" <?php if ($data->gametype == "Anderes") echo "checked"; ?>>
                        Anderes
                    </label>
                    <legend>Beschreibung<sup style="color:red">*</sup></legend>
                    <textarea name="beschreibung"><?php echo $data->description; ?></textarea>
		            <script type="text/javascript">
		            	CKEDITOR.replace( 'beschreibung' );
		            </script>

                </fieldset>
                <br>
                <p><sup style="color:red">*</sup> Muss ausgef&uuml;llt werden</p>
                <input type="hidden" name="id" value="<?php echo $data->id; ?>">
                <div class="form-actions">
                    <input type="submit" value="Änderungen Speichern" class="btn btn-primary">
                    <a href="index.php?site=myservers" class="btn">Abbrechen</a>
                </div>
            </form>
