<?php 
$getid = $_GET['id'];

$serverq = $db->query("SELECT * FROM sl_servers WHERE id = " . $getid);
$serverfound = $serverq->num_rows;
if ($serverfound == 1) {
    $serverinfos = $serverq->fetch_object();
    if ($serverinfos->online == "false") {
        ?>
        <div class="alert alert-error">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>Achtung!</strong> Dieser Server ist als Offine markiert. Dies kann auch an temporären Wartungsarbeiten liegen, schau einfach in ein paar Stunden wieder vorbei.<br>
            Zuletzt Online: <?php 
            if ($serverinfos->lastonline == 0)
                echo "Nie";
            else
                echo getago($serverinfos->lastonline);
            ?>
        </div>
    <?php } ?>
    <div class="well">
        <p class="lastcheck muted"><small>Letzte Aktualisierung: <?php echo getago($serverinfos->lastcheck); ?></small></p>
        <h1 class="servername"><?php echo $serverinfos->servername; ?></h1>
        <p class="motd"><?php echo addcolors($serverinfos->motd); ?></p>
    </div>
    <legend>Allgemein</legend>
    <div class="row-fluid">
        <div class="span3">
            <center><i class="icon-user"></i> <strong><?php echo $serverinfos->player . " / " . $serverinfos->playerMax; ?></strong></center>
        </div>
        <div class="span9">
            <div class="progress progress-striped active" style="margin-bottom: 0px;">
                <div class="bar bar-success" style="width: <?php echo $serverinfos->player / $serverinfos->playerMax * 100 . "%"; ?>"></div>
            </div>
        </div>
    </div>
    <hr>
    <center>
        <div class="features" style="margin-top: 20px">
            <?php 
            $features = explode(' ', $serverinfos->features);
            foreach ($features as $val) {
                echo '<div class="feature" rel="tooltip" data-placement="top" data-original-title="' . $featurenames[$val] . '"><i class="feature-' . $featureicons[$val] . '"></i></div>';
            }
            ?>
        </div>
    </center>
    <br>
    <?php 
    if ($serverinfos->mcquery == "true") {
        $mcqueryq = $db->query("SELECT * FROM sl_mcquery WHERE id = " . $getid);
        $mcquery = $mcqueryq->fetch_object();
    }
    ?>
    <table class="table table-hover">
        <tbody>
            <tr><th>Server IP</th><td><?php echo $serverinfos->serveradress . ":" . $serverinfos->serverport; ?> <a style="cursor:pointer;" class="icnlink" onclick="prompt('Kopiere diese Adresse im Spiel unter Multiplayer in das Feld Server Adresse:', '<?php echo $serverinfos->serveradress . ":" . $serverinfos->serverport; ?>');
                    return false;"><i class="icon-share"></i></a></td></tr>

            <tr><th>Votes</th><td><?php echo $serverinfos->votes . ' <a href="index.php?site=vote&id=' . $serverinfos->id . '" class="btn btn-mini btn-success"><i class="icon-white icon-thumbs-up"></i> Vote</a>'; ?></td></tr>

            <tr><th>Uptime</th><td><div class="progress progress-<?php echo getuptcolor($serverinfos->uptime); ?> progress-striped active nomargin"><div class="bar" style="width: <?php echo $serverinfos->uptime; ?>%;"><?php echo $serverinfos->uptime; ?>%</div></div></td></tr>

            <tr><th>Version</th><td><?php echo $serverinfos->version; ?></td></tr>

            <tr><th>Eingetragen seit</th><td><?php echo getreltime($serverinfos->created); ?></td></tr>

            <tr><th>Land</th><td><img src="assets/img/flags/<?php echo $serverinfos->country; ?>.png"></td></tr>

            <tr><th>Besitzer</th><td><?php echo $serverinfos->owner; ?></td></tr>
            <?php 
            if (!empty($serverinfos->website)) {
                $website = explode("/", $serverinfos->website);
                echo '<tr><th>Webseite</th><td><a target="_blank" href="http://' . $serverinfos->website . '">' . $website[0] . '</a></td></tr>';
            }

            if (!empty($serverinfos->voiceadress)) {
                echo '<tr><th>Voiceserver</th><td>' . $serverinfos->voiceadress . '</td></tr>';
            }

            if (!empty($serverinfos->mapadress)) {
                $livemap = explode("/", $serverinfos->mapadress);
                echo '<tr><th>Live Map</th><td><a target="_blank" href="http://' . $serverinfos->mapadress . '">' . $livemap[0] . '</a></td></tr>';
            }

            if (!empty($mcquery->Map))
                echo '<tr><th>Map</th><td>' . $mcquery->Map . '</td></tr>';
            if (!empty($mcquery->Software))
                echo '<tr><th>Software</th><td>' . $mcquery->Software . '</td></tr>';
            ?>
        </tbody>
    </table>
    <br>    
    <ul id="ServerViewTab" class="nav nav-tabs">
        <li class="active"><a href="#description" data-toggle="tab">Beschreibung</a></li>
        <li class=""><a href="#banner" data-toggle="tab">Banner</a></li>
        <?php 
        if ($serverinfos->mcquery == "true" && !empty($mcquery->Plugins) && $mcquery->showplugins == "true") {
            echo '<li class=""><a href="#plugins" data-toggle="tab">Plugins</a></li>';
        }
        ?>
        <li class=""><a href="#comments" data-toggle="tab">Kommentare</a></li>
    </ul>
    <div id="ServerViewTabContent" class="tab-content">
        <div class="tab-pane fade active in" id="description">
            <div class="serverdescription"><?php echo $serverinfos->description ?></div>
        </div>
        <div class="tab-pane fade" id="banner">
            <h3>Server Banner</h3>
            <p>Banner sind eine großartige Methode um für deinen Server zu werben und gleichzeitig die Spielerzahlen anzuzeigen. Füge den Code auf deiner Webseite oder in deiner Signatur ein und dein Banner wird sich automatisch aktualisieren.</p>
            <ul class="nav nav-tabs">
                <li class="dropdown active">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#560x120">Stil auswählen <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li class="active"><a data-toggle="tab" href="#560x120_1">Regenwald</a></li>
                        <li><a data-toggle="tab" href="#560x120_2">Horizont</a></li>
                        <li><a data-toggle="tab" href="#560x120_3">Düsterer Wald</a></li>
                        <li><a data-toggle="tab" href="#560x120_4">Himmel</a></li>
                        <li><a data-toggle="tab" href="#560x120_5">Wasserstadt</a></li>
                        <li><a data-toggle="tab" href="#560x120_6">Schlucht</a></li>
                        <li><a data-toggle="tab" href="#560x120_7">Sonnenuntergang</a></li>
                        <li><a data-toggle="tab" href="#560x120_8">Fluss</a></li>
                    </ul>
                </li>
            </ul>
            <div class="tab-content">
                <?php 
                for ($i = 1; $i <= 8; $i++) {
                    echo ($i == 1) ? '<div class="tab-pane fade active in" id="560x120_' . $i . '">' : '<div class="tab-pane fade" id="560x120_' . $i . '">';
                    echo '
                    <p class="text-center">
                        <a href="#" class="thumbnail" style=" display: inline-block; ">
                            <img src="http://mineservers.eu/banners/' . $serverinfos->id . '/banner-' . $i . '.png">
                        </a>
                    </p>
                    <br>
                    <p class="text-center">
                        <label>BBCode (Forum):</label>
                        <input type="text" style="cursor:pointer" onclick="this.select()" class="span8" value="[url=http://www.mineservers.eu/index.php?site=serverview&id=' . $serverinfos->id . '][img]http://www.mineservers.eu/banners/' . $serverinfos->id . '/banner-' . $i . '.png[/img][/url]" readonly>
                        <label>HTML Code (Webseite):</label>
                        <input type="text" style="cursor:pointer" onclick="this.select()" class="span8" value=\'<a href="http://www.mineservers.eu/index.php?site=serverview&id=' . $serverinfos->id . '"><img src="http://www.mineservers.eu/banners/' . $serverinfos->id . '/banner-' . $i . '.png"></a>\' readonly>
                        <label>Direktlink</label>
                        <input type="text" style="cursor:pointer" onclick="this.select()" class="span8" value="http://www.mineservers.eu/banners/' . $serverinfos->id . '/banner-' . $i . '.png" readonly>
                    </p></div>';
                }
                ?>
            </div>
        </div>
        <?php 
        if ($mcquery->showplugins == "true" && $serverinfos->mcquery == "true" && !empty($mcquery->Plugins)) {
            echo '<div class="tab-pane fade" id="plugins">';
            $plugins = explode(";", $mcquery->Plugins);
            asort($plugins);
            echo "<table class='table table-bordered'><tr>";
            $x = 0;
            foreach ($plugins as $plugin) {
                $x++;
                echo '<td>' . $plugin . '</td>';
                if ($x == 3) {
                    echo "</tr><tr>";
                    $x = 0;
                }
            }
            echo "</tr></table></div>";
        }
        ?>
        <div class="tab-pane fade" id="comments">
            <?php 
            $commentsq = $db->query("SELECT * FROM sl_comments WHERE serverid = '" . $serverinfos->id . "' ORDER BY id ASC");
            if ($commentsq->num_rows > 0) {
                echo '<ol class="commentlist">';
                while ($comment = $commentsq->fetch_array(MYSQLI_ASSOC)) {
                    echo '<li>';
                    echo '<div class="the-comment">';
                    echo '<img class="avatar" src="images/avatar.php?name=' . $comment["user"] . '&size=64">';
                    echo '<div class="comment-arrow"></div>';
                    echo '<div class="comment-box">';
                    echo '<div class="comment-author"><strong>' . $comment["user"] . '</strong> <small>' . date("j. F Y \u\m G:i", $comment["date"]) . '</small>';
                    if ($_SESSION['id'] != 0 && !is_mod($comment["user"])) {
                        echo '<a class="icnlink" href="?method=reportcomment&id=' . $comment['id'] . '" style="float: right;"><i class="icon-warning-sign"></i></a>';
                    } elseif (is_mod($comment["user"])) {
                        echo '<a class="icnlink" href="#" style="float: right;"><i class="icon-user"></i> Team</a>';
                    }
                    echo '</div>';
                    echo '<div class="comment-text"><p>' . $comment["comment"] . '</p></div>';
                    echo '</div></div></li>';
                }
                echo '</ol>';
            } else {
                echo '<p>Es wurde leider noch kein Kommentar verfasst. Sei der erste!</p>';
            }

            echo '<hr>';

            if ($_SESSION['id'] != 0) {
                echo '<form class="comment-form" action="index.php?method=addcomment" method="POST">';
                echo '<input type="hidden" name="serverid" value="' . $serverinfos->id . '">';
                echo '<textarea class="comment-box" name="comment" placeholder="Schreibe einen Kommentar..."></textarea>';
                echo '<br><input type="submit" class="btn btn-info" value="Kommentar absenden"> <small>Min. 16 Zeichen und max. 1024 Zeichen</small>';
                echo '</form>';
            } else {
                echo '<p>Du musst registriert sein um ein Kommentar verfassen zu können.</p>';
            }
            ?>
        </div>
    </div>

    <?php 
} else {
    include "sites/404.php";
}
?>