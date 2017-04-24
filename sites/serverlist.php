<div class="page-header">
    <div class="pull-right">
        <ul class="nav nav-pills">
            <li><a href="index.php?site=serverlist&sorting=rand" title="Zufällige Sortierung">Zufall</a></li>
            <li class="dropdown"><a data-toggle="dropdown" href="#sort" class="dropdown-toggle">Sortierung<b class="caret"></b></a>
                <ul class="dropdown-menu pull-right">
                    <li><a href="index.php?site=serverlist&sorting=votes">Nach Votes</a></li>
                    <li><a href="index.php?site=serverlist&sorting=new">Neue zuerst</a></li>
                    <li><a href="index.php?site=serverlist&sorting=age">Alte zuerst</a></li>
                    <li><a href="index.php?site=serverlist&sorting=players">Nach Spielern</a></li>
                    <li><a href="index.php?site=serverlist&sorting=uptime">Nach Uptime</a></li>
                    <li><a href="index.php?site=serverlist&sorting=slots">Nach Slots</a></li>
                    <li><a href="index.php?site=serverlist&sorting=premium">Nur Premium</a></li>
                    <li><a href="index.php?site=serverlist&sorting=cracked">Nur Cracked</a></li>
                    <li><a href="index.php?site=serverlist&sorting=all">Alle anzeigen</a></li>
                    <li class="dropdown-submenu">
                        <a tabindex="-1" href="#">Spieltyp</a>
                        <ul class="dropdown-menu">
                            <li><a href="index.php?site=serverlist&sorting=survival">Survival</a></li>
                            <li><a href="index.php?site=serverlist&sorting=pvp">PVP</a></li>
                            <li><a href="index.php?site=serverlist&sorting=citybuild">Citybuild</a></li>
                            <li><a href="index.php?site=serverlist&sorting=creative">Creative</a></li>
                            <li><a href="index.php?site=serverlist&sorting=other">Andere</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
    <h1>Minecraft Server Liste</h1>
</div>
<?php
$page = empty($_GET["page"]) ? 1 : $_GET["page"];
$sorting = empty($_GET["sorting"]) ? "votes" : $_GET["sorting"];

$persite = 20;
$start = $page * $persite - $persite;

switch ($sorting) {
    case "votes":
        $querycommand = "SELECT * FROM `sl_servers` WHERE `online` = 'true' ORDER BY `votes` DESC, `player` DESC LIMIT " . $start . ", " . $persite;
        $numrowcommand = "SELECT * FROM sl_servers WHERE online = 'true'";
        break;
    case "new":
        $querycommand = "SELECT * FROM sl_servers WHERE online = 'true' ORDER BY id DESC LIMIT " . $start . ", " . $persite;
        $numrowcommand = "SELECT * FROM sl_servers WHERE online = 'true'";
        break;
    case "players":
        $querycommand = "SELECT * FROM sl_servers WHERE online = 'true' ORDER BY player DESC, votes DESC LIMIT " . $start . ", " . $persite;
        $numrowcommand = "SELECT * FROM sl_servers WHERE online = 'true'";
        break;
    case "uptime":
        $querycommand = "SELECT * FROM sl_servers WHERE online = 'true' ORDER BY uptime DESC, player DESC, votes DESC LIMIT " . $start . ", " . $persite;
        $numrowcommand = "SELECT * FROM sl_servers WHERE online = 'true'";
        break;
    case "slots":
        $querycommand = "SELECT * FROM sl_servers WHERE online = 'true' ORDER BY playerMax DESC, player DESC, votes DESC LIMIT " . $start . ", " . $persite;
        $numrowcommand = "SELECT * FROM sl_servers WHERE online = 'true'";
        break;
    case "cracked":
        $querycommand = "SELECT * FROM sl_servers WHERE online = 'true' AND onlineMode = 'false' ORDER BY votes DESC, player DESC LIMIT " . $start . ", " . $persite;
        $numrowcommand = "SELECT * FROM sl_servers WHERE online = 'true' AND onlineMode = 'false'";
        break;
    case "premium":
        $querycommand = "SELECT * FROM sl_servers WHERE online = 'true' AND onlineMode = 'true' ORDER BY votes DESC, player DESC LIMIT " . $start . ", " . $persite;
        $numrowcommand = "SELECT * FROM sl_servers WHERE online = 'true' AND onlineMode = 'true'";
        break;
    case "age":
        $querycommand = "SELECT * FROM sl_servers WHERE online = 'true' ORDER BY id ASC LIMIT " . $start . ", " . $persite;
        $numrowcommand = "SELECT * FROM sl_servers WHERE online = 'true'";
        break;
    case "rand":
        $querycommand = "SELECT * FROM sl_servers WHERE online = 'true' ORDER BY RAND() ASC LIMIT " . $start . ", " . $persite;
        $numrowcommand = "SELECT * FROM sl_servers WHERE online = 'true'";
        break;
    case "all":
        $querycommand = "SELECT * FROM sl_servers ORDER BY votes DESC, player DESC, online DESC, lastonline DESC LIMIT " . $start . ", " . $persite;
        $numrowcommand = "SELECT * FROM sl_servers";
        break;
    case "survival":
        $querycommand = "SELECT * FROM sl_servers WHERE gametype = 'Survival' AND online = 'true' ORDER BY votes DESC, player DESC LIMIT " . $start . ", " . $persite;
        $numrowcommand = "SELECT * FROM sl_servers WHERE gametype = 'Survival' AND online = 'true'";
        break;
    case "pvp":
        $querycommand = "SELECT * FROM sl_servers WHERE gametype = 'PVP' AND online = 'true' ORDER BY votes DESC, player DESC LIMIT " . $start . ", " . $persite;
        $numrowcommand = "SELECT * FROM sl_servers WHERE gametype = 'PVP' AND online = 'true'";
        break;
    case "citybuild":
        $querycommand = "SELECT * FROM sl_servers WHERE gametype = 'Citybuild' AND online = 'true' ORDER BY votes DESC, player DESC LIMIT " . $start . ", " . $persite;
        $numrowcommand = "SELECT * FROM sl_servers WHERE gametype = 'Citybuild' AND online = 'true'";
        break;
    case "creative":
        $querycommand = "SELECT * FROM sl_servers WHERE gametype = 'Creative' AND online = 'true' ORDER BY votes DESC, player DESC LIMIT " . $start . ", " . $persite;
        $numrowcommand = "SELECT * FROM sl_servers WHERE gametype = 'Creative' AND online = 'true'";
        break;
    case "other":
        $querycommand = "SELECT * FROM sl_servers WHERE gametype = 'Anderes' AND online = 'true' ORDER BY votes DESC, player DESC LIMIT " . $start . ", " . $persite;
        $numrowcommand = "SELECT * FROM sl_servers WHERE gametype = 'Anderes' AND online = 'true'";
        break;
    
}

$query = $db->query($querycommand);
?>

<table class="servertable table table-striped table-hover">
    <thead>
        <tr>
            <th width="24">Typ</th>
            <th>Server</th>
            <th>Spieler</th>
            <th>Votes</th>
            <th>Uptime</th>
            <th>Premium</th>
        </tr>
    </thead>
    <tbody>
        <?php
        while ($row = $query->fetch_array(MYSQLI_ASSOC)) {
            echo in_array($row["id"], $dons) ? "<tr class='success'>" : "<tr>";
            echo '<td><div style="text-align:center" class="gametypes">';
            echo '<a href="#" rel="tooltip" data-placement="top" data-original-title="' . $row["gametype"] . '"><img width="16" height="16" src="assets/img/gametypes/' . $row["gametype"] . '.png" alt="' . $row["gametype"] . '"></a>';
            echo '</div></td>';
            echo ($row['online'] == "true") ? "<td><a href='index.php?site=serverview&id=" . $row['id'] . "'>" . $row['servername'] . "</a>" : "<td><a class='text-error' href='index.php?site=serverview&id=" . $row['id'] . "'>" . $row['servername'] . "</a>";
            echo '<br><span class="subservername">' . delcolors($row["motd"]) . '</span></td>';
            echo '<td class="nolinebreak"><i class="icon-user"></i> ' . $row["player"] . ' <span class="muted">/ ' . $row["playerMax"] . '</span></td>';
            echo '<td class="nolinebreak"><i class="icon-thumbs-up"></i> ' . $row["votes"] . '</td>';
            echo '<td class="uptime"><div rel="tooltip" data-placement="top" data-original-title="' . $row["uptime"] . '%" class="progress progress-' . getuptcolor($row["uptime"]) . ' nomargin"><div class="bar" style="width: ' . $row["uptime"] . '%">';
            echo ($row['uptime'] >= 80) ? '<div class="faded">' . $row["uptime"] . '%</div>' : '<div style="font-size: 8px" class="faded">' . $row["uptime"] . '%</div>';
            echo '</div></div></td>';
            echo '<td class="nolinebreak">';
            echo ($row["onlineMode"] == "true") ? '<i class="icon-ok-circle"></i> Ja' : '<i class="icon-remove-circle"></i> Nein';
            echo '</td></tr>';
        }
        ?>
    </tbody>
</table>
<?php
$numrows = $db->query($numrowcommand);
$pamount = $numrows->num_rows;
$pageamount = ceil($pamount / $persite);

echo '<div class="pagination pagination-centered"><ul>';
echo ($page == 1) ? '<li class="disabled"><a href="#">&laquo;</a></li>' : '<li><a href="index.php?site=' . $_GET['site'] . '&sorting=' . $sorting . '&page=' . ($page - 1) . '">&laquo;</a></li>';
for ($a = 0; $a < $pageamount; $a++) {
    $b = $a + 1;
    echo ($page == $b) ? '<li class="active"><a href="#">' . $b . '</a></li>' : '<li><a href="index.php?site=' . $_GET['site'] . '&sorting=' . $sorting . '&page=' . $b . '">' . $b . '</a></li>';
}
echo ($page == $pageamount) ? '<li class="disabled"><a href="#">&raquo;</a></li>' : '<li><a href="index.php?site=' . $_GET['site'] . '&sorting=' . $sorting . '&page=' . ($page + 1) . '">&raquo;</a></li>';
echo "</ul></div>";
?>