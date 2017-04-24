<?php 
if ($_SESSION['id'] == 0) {
    errormsg(_NOPERMISSION, "back");
} else {
    ?>
    <div class="page-header">
        <h1>Meine Server</h1>
    </div>
    <a href="index.php?site=addserver" class="btn btn-success btn-small"><i class="icon-plus icon-white"></i> Server hinzuf&uuml;gen</a> <button class="btn btn-info btn-small disabled">Nächster Vote Reset: <span id="countdowner"></span></button> <a ><a href="#votelinkInfo" role="button" data-toggle="modal" class="btn btn-info btn-small"><i class="icon-white icon-share-alt"></i> Kurzer Votelink</a>
        <br><br>

        <?php
        $result = $db->query("SELECT id FROM sl_servers WHERE owner = '" . $_SESSION['username'] . "'");
        $amount = $result->num_rows;

        if ($amount > 0) {
            $page = empty($_GET["page"]) ? 1 : $_GET["page"];

            $persite = 5;
            $start = $page * $persite - $persite;

            $query = $db->query("SELECT servername, id FROM sl_servers WHERE owner = '" . $_SESSION['username'] . "' ORDER BY id ASC LIMIT " . $start . ", " . $persite);

            echo '<table class="table table-striped">';
            echo '<thead><tr><th>ID</th><th>Servername</th><th colspan="5">Aktionen</th></thead><tbody>';
            while ($row = $query->fetch_array(MYSQLI_ASSOC)) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo '<td><a href="index.php?site=serverview&id=' . $row['id'] . '">' . $row['servername'] . "</a></td>";
                echo '<td><a href="#vbtnModal' . $row['id'] . '" role="button" data-toggle="modal" class="btn btn-info btn-mini"><i class="icon-white icon-info-sign"></i> Widgets</a></td>';
                echo '<td><a href="index.php?site=apisettings&id=' . $row['id'] . '" class="btn btn-success btn-mini"><i class="icon-white icon-exchange"></i> API</a></td>';
                echo '<td><a href="index.php?site=editvotifier&id=' . $row['id'] . '" class="btn btn-success btn-mini"><i class="icon-white icon-thumbs-up"></i> Votifier</a></td>';
                echo '<td><a href="index.php?site=editserver&id=' . $row['id'] . '" class="btn btn-success btn-mini"><i class="icon-white icon-pencil"></i> Bearbeiten</a></td>';
                echo '<td><a href="index.php?method=delserver&id=' . $row['id'] . '" onclick="return confirm(\'Willst du das wirklich tun?\')" class="btn btn-danger btn-mini"><i class="icon-white icon-remove"></i> Löschen</a></td>';
                echo "</tr>";
            }

            echo "</tbody></table>";

            $pageamount = $amount / $persite;

            echo '<div style="text-align:center" class="pagination"><ul>';
            for ($a = 0; $a < $pageamount; $a++) {
                $b = $a + 1;
                if ($page == $b) {
                    echo '<li class="active"><a href="index.php?site=' . $_GET['site'] . '&page=' . $b . '">' . $b . '</a></li>';
                } else {
                    echo '<li><a href="index.php?site=' . $_GET['site'] . '&page=' . $b . '">' . $b . '</a></li>';
                }
            }
            echo "</ul></div>";
            $queryb = $db->query("SELECT servername, id FROM sl_servers WHERE owner = '" . $_SESSION['username'] . "' ORDER BY id ASC LIMIT " . $start . ", " . $persite);
            while ($row = $queryb->fetch_array(MYSQLI_ASSOC)) {
                $id = $row['id'];
                echo '<div id="vbtnModal' . $id . '" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="vbtnModalLabel' . $id . '" aria-hidden="true">
		  <div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		    <h3 id="vbtnModalLabel' . $id . '">Votebutton</h3>
		  </div>
		  <div class="modal-body">
			<center>
				<h3>Votebutton</h3>
			    <label>Vorschau</label>
			    <script type="text/javascript" src="http://www.mineservers.eu/widgets/votebutton.js?id=' . $id . '"></script>
			    <br><br>
			    <label>Code</label>
			    <textarea onclick="this.select()" style="cursor: pointer;width: 90%;height: 80px;resize: none;" readonly><script type="text/javascript" src="http://www.mineservers.eu/widgets/votebutton.js?id=' . $id . '"></script><noscript><a href="http://www.mineservers.eu/index.php?site=vote&id=' . $id . '">Voten</a></noscript></textarea>
			    <h3>Vote Widget</h3>
			    <label>Vorschau</label>
			    <script type="text/javascript" src="http://www.mineservers.eu/widgets/form.js?id=' . $id . '"></script>
			    <br><br>
			    <label>Code</label>
			    <textarea onclick="this.select()" style="cursor: pointer;width: 90%;height: 80px;resize: none;" readonly><script type="text/javascript" src="http://www.mineservers.eu/widgets/form.js?id=' . $id . '"></script><noscript><a href="http://www.mineservers.eu/index.php?site=vote&id=' . $id . '">Voten</a></noscript></textarea>
			</center>
		  </div>
		  <div class="modal-footer">
		    <button class="btn" data-dismiss="modal" aria-hidden="true">Zurück</button>
		  </div>
		</div>';
            }
        } else {
            echo '<div class="alert alert-warning">Du hast noch keine Server angelegt</div>';
        }
    }
    ?>
    <div id="votelinkInfo" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="votelinkInfoLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="votelinkInfoLabel">Votelinks</h3>
        </div>
        <div class="modal-body">
            <p>Wir betreiben eine eigene Domain nur zu diesem Zweck. Um deine User auf die Voteseite zu leiten, gibst du einfach den Link <code>http://mcvote.eu/(Deine Server ID)/</code> an. Optional kannst du nach dem abschließenden "/" noch einen Benutzernamen einfügen, der dann automatisch in das entsprechende Feld auf der Voteseite eingetragen wird.</p>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Zurück</button>
        </div>
    </div>
    <script type="text/javascript">
        var jahr =<?php echo get_votereset()[0]; ?>, monat =<?php echo get_votereset()[1]; ?>, tag =<?php echo get_votereset()[2]; ?>, stunde =<?php echo get_votereset()[3]; ?>, minute =<?php echo get_votereset()[4]; ?>, sekunde =<?php echo get_votereset()[5]; ?>;
        var zielDatum = new Date(jahr, monat - 1, tag, stunde, minute, sekunde);

        function countdown() {
            startDatum = new Date();

            if (startDatum < zielDatum) {

                var jahre = 0, monate = 0, tage = 0, stunden = 0, minuten = 0, sekunden = 0;

                while (startDatum < zielDatum) {
                    jahre++;
                    startDatum.setFullYear(startDatum.getFullYear() + 1);
                }
                startDatum.setFullYear(startDatum.getFullYear() - 1);
                jahre--;

                while (startDatum < zielDatum) {
                    monate++;
                    startDatum.setMonth(startDatum.getMonth() + 1);
                }
                startDatum.setMonth(startDatum.getMonth() - 1);
                monate--;

                while (startDatum.getTime() + (24 * 60 * 60 * 1000) < zielDatum) {
                    tage++;
                    startDatum.setTime(startDatum.getTime() + (24 * 60 * 60 * 1000));
                }

                stunden = Math.floor((zielDatum - startDatum) / (60 * 60 * 1000));
                startDatum.setTime(startDatum.getTime() + stunden * 60 * 60 * 1000);

                minuten = Math.floor((zielDatum - startDatum) / (60 * 1000));
                startDatum.setTime(startDatum.getTime() + minuten * 60 * 1000);

                sekunden = Math.floor((zielDatum - startDatum) / 1000);

                (jahre != 1) ? jahre = jahre + " Jahre, " : jahre = jahre + " Jahr, ";
                (monate != 1) ? monate = monate + " Monate, " : monate = monate + " Monat, ";
                (tage != 1) ? tage = tage + " Tage, " : tage = tage + " Tag, ";
                (stunden != 1) ? stunden = stunden + " Stunden, " : stunden = stunden + " Stunde, ";
                (minuten != 1) ? minuten = minuten + " Minuten, " : minuten = minuten + " Minute, ";
                if (sekunden < 10)
                    sekunden = "0" + sekunden;
                (sekunden != 1) ? sekunden = sekunden + " Sekunden" : sekunden = sekunden + " Sekunde";

                document.getElementById("countdowner").innerHTML = tage + stunden + minuten + sekunden;

                setTimeout('countdown()', 200);
            }

            else
                document.getElementById("countdowner").innerHTML =
                        "0 Jahre,  0 Monate,  0 Tage,  0 Stunden,  0 Minuten  und  00 Sekunden";
        }

        countdown();
    </script>
