<div class="page-header">
    <h1>Registrieren</h1>
</div>
<form action="index.php?method=register" method="POST">
    <div class="row-fluid">
        <div class="span6">
            <center>
                <div class="input-prepend">
                    <label for="user">Benutzername</label>
                    <span class="add-on"><i class="icon-user"></i></span><input id="user" type="text" name="user">
                </div>
                <div class="input-prepend">
                    <label for="mail1">E-Mail</label>
                    <span class="add-on"><i class="icon-envelope"></i></span><input id="mail1" type="text" name="mail1">
                </div>
                <div class="input-prepend">
                    <label for="pw1">Passwort</label>
                    <span class="add-on"><i class="icon-lock"></i></span><input id="pw1" type="password" name="pw1">
                </div>
            </center>
        </div>
        <div class="span6">
            <center>
                <div class="input-prepend">
                    <label for="minecraft">Minecraft Benutzername</label>
                    <span class="add-on"><i class="icon-gamepad"></i></span><input id="minecraft" type="text" name="minecraft">
                </div>
                <div class="input-prepend">
                    <label for="mail2">E-Mail bestätigen</label>
                    <span class="add-on"><i class="icon-envelope"></i></span><input id="mail2" type="text" name="mail2">
                </div>
                <div class="input-prepend">
                    <label for="pw2">Passwort best&auml;tigen</label>
                    <span class="add-on"><i class="icon-lock"></i></span><input id="pw2" type="password" name="pw2">
                </div>
            </center>
        </div>
    </div>
    <br>
    <center>
        <?php
        echo recaptcha_get_html(PUBLICKEY);
        ?>
    </center>  
    <br>
    <label class="checkbox">
        <input type="checkbox" name="anb_rules_accepted"> Ich habe die <a target="_blank" href="?site=nutzungsbestimmungen">Nutzungsbestimmungen</a> und die <a target="_blank" href="?site=regeln">Regeln</a> gelesen und akzeptiere sie.
    </label>
    <div class="form-actions">
        <input type="submit" class="btn btn-success" value="Registrieren">
    </div>
</form>