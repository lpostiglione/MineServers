<?php
if ($_SESSION['id'] != 0) {
    errormsg("Du bist bereits eingeloggt!", "back");
} else {
    ?>
    <h1>Login</h1>
    <center>
        <form action="index.php?method=login" method="POST">
            <div class="input-prepend">
                <span class="add-on"><i class="icon-user"></i></span><input name="username" type="text" placeholder="Benutzername">
            </div>
            <br>
            <div class="input-prepend">
                <span class="add-on"><i class="icon-lock"></i></span><input name="password" type="password" placeholder="Passwort">
            </div>
            <br>
            <input type="submit" class="btn btn-primary" value="Login"> <a href="?site=register" class="btn btn-info">Registrieren</a>
        </form>
    </center>
<?php } ?>