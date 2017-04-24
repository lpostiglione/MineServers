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

            <div class="page-header">
                <h1>Server bearbeiten</h1>
            </div>
            <form action="index.php?method=editserver" method="POST">
		    	<div class="row-fluid">
			    	<div class="span6">
	                    <legend>Allgemein</legend>
	                    <label for="servername">Servername<sup style="color:red">*</sup></label>
	                    <input type="text" style="width:323px" name="servername" id="servername" value="<?php echo $data->servername; ?>">
	                    <label for="serveradress">Serveradresse &amp; Port<sup style="color:red">*</sup></label>
	                    <input type="text" style="width:237px" name="serveradress" id="serveradress" value="<?php echo $data->serveradress; ?>"><span class="doubledot">&nbsp;:&nbsp;</span><input type="text" class="input-mini" name="serverport" value="<?php echo $data->serverport; ?>">
	                    <label for="voiceadress">Voiceserver</label>
	                    <input type="text" style="width:323px" name="voiceadress" id="voiceadress" value="<?php echo ($data->voiceadress != 0) ? $data->voiceadress : ""; ?>" placeholder="zb. voice.minecraft.net oder 54.243.168.153">
	                    <label for="mapadress">Live-Map</label>
	                    <div class="input-prepend">
	                        <span class="add-on">http://</span>
	                        <input type="text" class="input-xlarge" name="mapadress" id="mapadress" value="<?php echo ($data->mapadress != 0) ? $data->mapadress : ""; ?>" placeholder="zb. map.minecraft.net">
	                    </div>
	                    <label for="website">Webseite</label>
	                    <div class="input-prepend">
	                        <span class="add-on">http://</span>
	                        <input type="text" class="input-xlarge" name="website" id="website" value="<?php echo ($data->website != 0) ? $data->website : ""; ?>" placeholder="zb. www.minecraft.net">
	                    </div>
			    	</div>
			    	<div class="span6">
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
	                    <?php 
		                if($data->mcquery == "true"){
		                	$mcqueryq = $db->query("SELECT showplugins FROM sl_mcquery WHERE id = " . $id);
					        $qdata = $mcqueryq->fetch_object();
			                ?>
			                
		                    <legend>Zeige Plugins<sup style="color:red">*</sup></legend>
		                    <label class="radio inline">
		                        <input type="radio" name="showplugins" id="showpluginstrue" value="true" <?php if ($qdata->showplugins == "true") echo "checked"; ?>>
		                        Ja
		                    </label>
		                    <label class="radio inline">
		                        <input type="radio" name="showplugins" id="showpluginsfalse" value="false" <?php if ($qdata->showplugins == "false") echo "checked"; ?>>
		                        Nein
		                    </label>
			                <?php 
		                }
	                    ?>
			    	</div>
		    	</div>
				<legend>Eigenschaften<sup style="color:red">*</sup> <small>max. 21</small></legend>
				<center>
					<?php $features = explode(" ", $data->features); ?>
					<select multiple="multiple" id="eigenschaften" name="eigenschaften[]">
						<optgroup label="Gameplay">
							<option value="101" <?php echo in_array(101, $features) ? "selected" : ""; ?>><?php echo $featurenames[101]; ?></option>
							<option value="102" <?php echo in_array(102, $features) ? "selected" : ""; ?>><?php echo $featurenames[102]; ?></option>
							<option value="103" <?php echo in_array(103, $features) ? "selected" : ""; ?>><?php echo $featurenames[103]; ?></option>
							<option value="104" <?php echo in_array(104, $features) ? "selected" : ""; ?>><?php echo $featurenames[104]; ?></option>
							<option value="105" <?php echo in_array(105, $features) ? "selected" : ""; ?>><?php echo $featurenames[105]; ?></option>
							<option value="106" <?php echo in_array(106, $features) ? "selected" : ""; ?>><?php echo $featurenames[106]; ?></option>
						</optgroup>
						<optgroup label="Welten">
							<option value="201" <?php echo in_array(201, $features) ? "selected" : ""; ?>><?php echo $featurenames[201]; ?></option>
							<option value="202" <?php echo in_array(202, $features) ? "selected" : ""; ?>><?php echo $featurenames[202]; ?></option>
							<option value="203" <?php echo in_array(203, $features) ? "selected" : ""; ?>><?php echo $featurenames[203]; ?></option>
							<option value="204" <?php echo in_array(204, $features) ? "selected" : ""; ?>><?php echo $featurenames[204]; ?></option>
						</optgroup>
						<optgroup label="Spiele">
							<option value="301" <?php echo in_array(301, $features) ? "selected" : ""; ?>><?php echo $featurenames[301]; ?></option>
							<option value="302" <?php echo in_array(302, $features) ? "selected" : ""; ?>><?php echo $featurenames[302]; ?></option>
							<option value="303" <?php echo in_array(303, $features) ? "selected" : ""; ?>><?php echo $featurenames[303]; ?></option>
							<option value="304" <?php echo in_array(304, $features) ? "selected" : ""; ?>><?php echo $featurenames[304]; ?></option>
							<option value="305" <?php echo in_array(305, $features) ? "selected" : ""; ?>><?php echo $featurenames[305]; ?></option>
							<option value="306" <?php echo in_array(306, $features) ? "selected" : ""; ?>><?php echo $featurenames[306]; ?></option>
						</optgroup>
						<optgroup label="Mods">
							<option value="401" <?php echo in_array(401, $features) ? "selected" : ""; ?>><?php echo $featurenames[401]; ?></option>
							<option value="402" <?php echo in_array(402, $features) ? "selected" : ""; ?>><?php echo $featurenames[402]; ?></option>
							<option value="403" <?php echo in_array(403, $features) ? "selected" : ""; ?>><?php echo $featurenames[403]; ?></option>
						</optgroup>
						<optgroup label="Server Features">
							<option value="501" <?php echo in_array(501, $features) ? "selected" : ""; ?>><?php echo $featurenames[501]; ?></option>
							<option value="502" <?php echo in_array(502, $features) ? "selected" : ""; ?>><?php echo $featurenames[502]; ?></option>
							<option value="503" <?php echo in_array(503, $features) ? "selected" : ""; ?>><?php echo $featurenames[503]; ?></option>
							<option value="504" <?php echo in_array(504, $features) ? "selected" : ""; ?>><?php echo $featurenames[504]; ?></option>
							<option value="505" <?php echo in_array(505, $features) ? "selected" : ""; ?>><?php echo $featurenames[505]; ?></option>
							<option value="506" <?php echo in_array(506, $features) ? "selected" : ""; ?>><?php echo $featurenames[506]; ?></option>
							<option value="507" <?php echo in_array(507, $features) ? "selected" : ""; ?>><?php echo $featurenames[507]; ?></option>
							<option value="508" <?php echo in_array(508, $features) ? "selected" : ""; ?>><?php echo $featurenames[508]; ?></option>
							<option value="509" <?php echo in_array(509, $features) ? "selected" : ""; ?>><?php echo $featurenames[509]; ?></option>
							<option value="510" <?php echo in_array(510, $features) ? "selected" : ""; ?>><?php echo $featurenames[510]; ?></option>
							<option value="511" <?php echo in_array(511, $features) ? "selected" : ""; ?>><?php echo $featurenames[511]; ?></option>
							<option value="512" <?php echo in_array(512, $features) ? "selected" : ""; ?>><?php echo $featurenames[512]; ?></option>
						</optgroup>
					</select>
					<script src="assets/js/jquery.multi-select.js" type="text/javascript"></script>
					<script type="text/javascript">
						$('#eigenschaften').multiSelect()
					</script>
				</center>
                <legend>Beschreibung<sup style="color:red">*</sup></legend>
				<textarea name="beschreibung"><?php echo $data->description; ?></textarea>
	            <script type="text/javascript">
	            	CKEDITOR.replace( 'beschreibung' );
	            </script>
                <br>
                <p><sup style="color:red">*</sup> Muss ausgef&uuml;llt werden</p>
                <input type="hidden" name="id" value="<?php echo $data->id; ?>">
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
