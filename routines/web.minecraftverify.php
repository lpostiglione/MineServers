<?php 

if ($_SESSION['id'] == 0) {
    errormsg(_NOPERMISSION, "back");
} else {

    if (!empty($_POST['name'])) {

        $minecraftname = $_POST['name'];

        if (mcpremium($minecraftname)) {

            $minecraftname = $db->real_escape_string($minecraftname);
            $id = $db->real_escape_string($_SESSION['id']);
            $verifycode = $db->real_escape_string(random_str(8));

            $db->query("UPDATE `sl_users` SET `minecraftname` = '$minecraftname', `minecraftverifycode` = '$verifycode' WHERE `id` = $id");

            successmsg('Dein Minecraft Benutzername wurde gesetzt. Bitte verifiziere ihn nun!', "mysettings");
        } else {
            errormsg("Der Minecraft Account ist nicht Premium!");
        }
    } else {
        errormsg("Du musst einen gültigen Benutzernamen angeben!");
    }
}
?>