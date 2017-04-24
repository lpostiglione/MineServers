<?php
if ($_SESSION['id'] == 0) {
    ?>

    <h1>Passwort anfordern</h1>
    <p class="alert alert-info">Wenn du dein Kennwort vergessen hast, musst du entweder den Benutzernamen <b>oder</b> die E-Mail-Adresse angeben, die du bei deiner Anmeldung hinterlegt hast. Wenn du beide Daten nicht mehr weißt, wende dich bitte an contact@mineservers.eu!</p>
    <center>
        <form action = "index.php?method=requestpassword" method = "post">
            <div class="row row-fluid">
                <div class="control-group span6">
                    <label class = "control-label" for = "inputEmail">Benutzername</label>
                    <div class = "controls">
                        <input type = "text" id = "inputEmail" name = "user" placeholder = "Benutzername">
                    </div>
                </div>
                <div class = "control-group span6">
                    <label class = "control-label" for = "inputPassword">E-Mail</label>
                    <div class = "controls">
                        <input type = "text" id = "inputPassword" name = "email" placeholder = "email@domain.com">
                    </div>
                </div>
            </div>
            <?php 
            echo recaptcha_get_html(PUBLICKEY);
            ?>
            <br/>
            <input class="btn btn-success" type="submit" value="Passwort anfordern" />
        </form>
    </center>
    <?php 
} else {
    errormsg("Du bist schon eingeloggt!", "start");
}
?>