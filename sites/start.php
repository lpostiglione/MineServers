<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th width="24">Typ</th>
            <th>Server</th>
            <th>Spieler</th>
            <th>Votes</th>
            <th>Uptime</th>
            <th width="64">Premium</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $query = $db->query("SELECT * FROM sl_servers WHERE online = 'true' ORDER BY votes DESC LIMIT 15");
        while ($row = $query->fetch_array(MYSQLI_ASSOC)) {
            echo in_array($row["id"], $dons) ? "<tr class='success'>" : "<tr>";
            echo '<td><div style="text-align:center" class="gametypes">';
            echo '<a href="#" rel="tooltip" data-placement="top" data-original-title="' . $row["gametype"] . '"><img width="16" height="16" src="assets/img/gametypes/' . $row["gametype"] . '.png" alt="' . $row["gametype"] . '"></a>';
            echo '</div></td>';
            echo ($row['online'] == "true") ? "<td><a href='index.php?site=serverview&id=" . $row['id'] . "'>" . $row['servername'] . "</a></td>" : "<td><a class='text-error' href='index.php?site=serverview&id=" . $row['id'] . "'>" . $row['servername'] . "</a></td>";
            echo '<td><i class="icon-user"></i> ' . $row["player"] . ' <span class="muted">/ ' . $row["playerMax"] . '</span></td>';
            echo '<td><i class="icon-thumbs-up"></i> ' . $row["votes"] . '</td>';
            echo '<td class="uptime"><div rel="tooltip" data-placement="top" data-original-title="' . $row["uptime"] . '%" class="progress progress-' . getuptcolor($row["uptime"]) . ' nomargin"><div class="bar" style="width: ' . $row["uptime"] . '%">';
            echo ($row['uptime'] >= 80) ? '<div class="faded">' . $row["uptime"] . '%</div>' : '<div style="font-size: 8px" class="faded">' . $row["uptime"] . '%</div>';
            echo '</div></div></td>';
            echo '<td>';
            echo ($row["onlineMode"] == "true") ? '<i class="icon-ok-circle"></i> Ja' : '<i class="icon-remove-circle"></i> Nein';
            echo '</td></tr>';
        }
        ?>
    </tbody>
</table>
<script type="text/javascript">
    $('.gametypes').tooltip({selector: "a[rel=tooltip]"})
</script>
<p class="text-center"><a href="index.php?site=serverlist" class="btn btn-success btn-small">Zur kompletten Liste</a></p>