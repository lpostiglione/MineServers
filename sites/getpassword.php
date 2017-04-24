<?php
if ($_SESSION['id'] != 0) {
    errormsg("Du bist schon eingeloggt!", "start");
} elseif(empty($_GET['key'])){
	errormsg("Bitte den Link in der E-Mail nutzen", "start");
} else {
?>
    <h1>Passwort zurücksetzen</h1>
    <hr>
    <center>
        <form action="index.php?method=getpassword" method="post">
                <div class="control-group">
                    <label class="control-label" for="inputPassword1">Neues Passwort</label>
                    <div class="controls">
                        <input type="password" id="inputPassword1" name="pw1" placeholder="Passwort">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="inputPassword2">Wiederholen</label>
                    <div class="controls">
                        <input type="password" id="inputPassword2" name="pw2" placeholder="Passwort">
                    </div>
                </div>
            <br/>
            <input type="hidden" value="<?php echo $_GET["key"]; ?>" name="requestkey">
            <input class="btn btn-success" type="submit" value="Passwort ändern">
        </form>
    </center>
	<?php 
	}
	?>