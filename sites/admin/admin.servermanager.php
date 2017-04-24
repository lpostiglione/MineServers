<div class="page-header">
    <h1>Server Manager</h1>
</div>
<?php
$result = $db->query("SELECT * FROM sl_servers");
$amount = $result->num_rows;

if ($amount > 0) {
    $page = empty($_GET["page"]) ? 1 : $_GET["page"];

    $persite = 25;
    $start = $page * $persite - $persite;

    $query = $db->query("SELECT * FROM sl_servers ORDER BY id ASC LIMIT " . $start . ", " . $persite);

    echo '<table class="table table-striped"><thead><tr><th>ID</th><th>Besitzer</th><th>Servername</th><th>Status</th><th>Aktionen</th></tr></thead><tbody>';

    while ($row = $query->fetch_array(MYSQLI_ASSOC)) {
        $online = ($row['online'] == "true") ? "Ja" : getago($row['lastonline']);
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['owner'] . "</td>";
        echo "<td><a href=\"index.php?site=serverview&id=" . $row['id'] . "\">" . $row['servername'] . "</a></td>";
        echo "<td>" . $online . "</td>";
        echo '<td><a style="margin-bottom:4px" href="index.php?admin=editserver&id=' . $row['id'] . '" class="btn btn-success btn-mini"><i class="icon-white icon-pencil"></i> Bearbeiten</a><br><a href="index.php?amethod=delserver&id=' . $row['id'] . '" onclick="return confirm(\'Willst du das wirklich tun?\')" class="btn btn-danger btn-mini"><i class="icon-white icon-remove"></i> Löschen</a></td>';
        echo "</tr>";
    }

    echo "</tbody></table>";

    $pageamount = $amount / $persite;

    echo '<center><div class="pagination"><ul>';
    echo ($page == 1) ? '<li class="disabled"><a href="#">&laquo;</a></li>' : '<li><a href="index.php?admin=' . $_GET['admin'] . '&page=' . ($page - 1) . '">&laquo;</a></li>';
    for ($a = 0; $a < $pageamount; $a++) {
        $b = $a + 1;
        echo ($page == $b) ? '<li class="active"><a href="#">' . $b . '</a></li>' : '<li><a href="index.php?admin=' . $_GET['admin'] . '&page=' . $b . '">' . $b . '</a></li>';
    }
    echo ($page == $pageamount) ? '<li class="disabled"><a href="#">&raquo;</a></li>' : '<li><a href="index.php?admin=' . $_GET['admin'] . '&page=' . ($page + 1) . '">&raquo;</a></li>';
    echo "</ul></div></center>";
} else {
    echo '<div class="alert alert-warning">Es sind keine User registriert!</div>';
}
