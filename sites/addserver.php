<?php
if ($_SESSION['id'] == 0) {
    errormsg(_NOPERMISSION, "back");
} else {
    ?>
    <div class="page-header">
        <h1>Server hinzuf&uuml;gen</h1>
    </div>
    <form action="index.php?method=addserver" method="POST">
    	<div class="row-fluid">
	    	<div class="span6">
	            <legend>Allgemein</legend>
	            <label for="servername">Servername<sup style="color:red">*</sup></label>
	            <input type="text" style="width:323px" name="servername" id="servername" placeholder="Servername" required>
	            <label for="serveradress">Serveradresse &amp; Port<sup style="color:red">*</sup></label>
	            <input type="text" style="width:237px" name="serveradress" id="serveradress" placeholder="zb. server.minecraft.net oder 54.243.168.153" required><span class="doubledot">&nbsp;:&nbsp;</span><input type="text" class="input-mini" name="serverport" placeholder="Port">
	            <label for="voiceadress">Voiceserver</label>
	            <input type="text" style="width:323px" name="voiceadress" id="voiceadress" placeholder="zb. voice.minecraft.net oder 54.243.168.153">
	            <label for="mapadress">Live-Map</label>
	            <div class="input-prepend">
	                <span class="add-on">http://</span>
	                <input type="text" class="input-xlarge" name="mapadress" id="mapadress" placeholder="zb. map.minecraft.net">
	            </div>
	            <label for="website">Webseite</label>
	            <div class="input-prepend">
	                <span class="add-on">http://</span>
	                <input type="text" class="input-xlarge" name="website" id="website" placeholder="zb. www.minecraft.net">
	            </div>
	    	</div>
	    	<div class="span6">
	            <legend>Original Minecraft erforderlich<sup style="color:red">*</sup></legend>
	            <label class="radio">
	                <input type="radio" name="onlinemode" id="onlinemode1" value="true" checked>
	                Ja
	            </label>
	            <label class="radio">
	                <input type="radio" name="onlinemode" id="onlinemode2" value="false">
	                Nein
	            </label>
	            <br><br>
	            <legend>Spieltyp<sup style="color:red">*</sup></legend>
	            <label class="radio">
	                <input type="radio" name="gametype" id="gametype1" value="1" checked>
	                Survival
	            </label>
	            <label class="radio">
	                <input type="radio" name="gametype" id="gametype2" value="2">
	                PVP
	            </label>
	            <label class="radio">
	                <input type="radio" name="gametype" id="gametype6" value="6">
	                RPG
	            </label>
	            <label class="radio">
	                <input type="radio" name="gametype" id="gametype3" value="3">
	                Citybuild
	            </label>
	            <label class="radio">
	                <input type="radio" name="gametype" id="gametype4" value="4">
	                Creative
	            </label>
	            <label class="radio">
	                <input type="radio" name="gametype" id="gametype5" value="5">
	                Anderes
	            </label>
	    	</div>
		</div>
		<legend>Eigenschaften<sup style="color:red">*</sup> <small>max. 21</small></legend>
		<center>
			<select multiple="multiple" id="eigenschaften" name="eigenschaften[]">
				<optgroup label="Gameplay">
					<option value="101"><?php echo $featurenames[101]; ?></option>
					<option value="102"><?php echo $featurenames[102]; ?></option>
					<option value="103"><?php echo $featurenames[103]; ?></option>
					<option value="104"><?php echo $featurenames[104]; ?></option>
					<option value="105"><?php echo $featurenames[105]; ?></option>
					<option value="106"><?php echo $featurenames[106]; ?></option>
				</optgroup>
				<optgroup label="Welten">
					<option value="201"><?php echo $featurenames[201]; ?></option>
					<option value="202"><?php echo $featurenames[202]; ?></option>
					<option value="203"><?php echo $featurenames[203]; ?></option>
					<option value="204"><?php echo $featurenames[204]; ?></option>
				</optgroup>
				<optgroup label="Spiele">
					<option value="301"><?php echo $featurenames[301]; ?></option>
					<option value="302"><?php echo $featurenames[302]; ?></option>
					<option value="303"><?php echo $featurenames[303]; ?></option>
					<option value="304"><?php echo $featurenames[304]; ?></option>
					<option value="305"><?php echo $featurenames[305]; ?></option>
					<option value="306"><?php echo $featurenames[306]; ?></option>
				</optgroup>
				<optgroup label="Mods">
					<option value="401"><?php echo $featurenames[401]; ?></option>
					<option value="402"><?php echo $featurenames[402]; ?></option>
					<option value="403"><?php echo $featurenames[403]; ?></option>
				</optgroup>
				<optgroup label="Server Features">
					<option value="501"><?php echo $featurenames[501]; ?></option>
					<option value="502"><?php echo $featurenames[502]; ?></option>
					<option value="503"><?php echo $featurenames[503]; ?></option>
					<option value="504"><?php echo $featurenames[504]; ?></option>
					<option value="505"><?php echo $featurenames[505]; ?></option>
					<option value="506"><?php echo $featurenames[506]; ?></option>
					<option value="507"><?php echo $featurenames[507]; ?></option>
					<option value="508"><?php echo $featurenames[508]; ?></option>
					<option value="509"><?php echo $featurenames[509]; ?></option>
					<option value="510"><?php echo $featurenames[510]; ?></option>
					<option value="511"><?php echo $featurenames[511]; ?></option>
					<option value="512"><?php echo $featurenames[512]; ?></option>
				</optgroup>
			</select>
			<script src="assets/js/jquery.multi-select.js" type="text/javascript"></script>
			<script type="text/javascript">
				$('#eigenschaften').multiSelect()
			</script>
		</center>
        <legend>Beschreibung<sup style="color:red">*</sup></legend>
		<textarea name="beschreibung"></textarea>
        <script type="text/javascript">
        	CKEDITOR.replace( 'beschreibung' );
        </script>
        <br>
        <p><sup style="color:red">*</sup> Muss ausgef&uuml;llt werden</p>
        <div class="form-actions">
            <input type="submit" value="Server hinzuf&uuml;gen" class="btn btn-success">
            <a href="index.php?site=addserver" class="btn">Abbrechen</a>
        </div>
    </form>

<?php } ?>