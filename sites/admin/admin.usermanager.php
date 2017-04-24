<div class="page-header">
    <h1>User Manager</h1>
</div>
<?php
$result = $db->query("SELECT * FROM sl_users");
$amount = $result->num_rows;

if ($amount > 0) {
    $page = empty($_GET["page"]) ? 1 : $_GET["page"];

    $persite = 25;
    $start = $page * $persite - $persite;

    $query = $db->query("SELECT * FROM sl_users ORDER BY id ASC LIMIT " . $start . ", " . $persite);

    echo '<table class="table table-striped"><thead><tr><th>ID</th><th>Username</th><th>E-Mail</th><th>Minecraft</th><th colspan="2">Aktionen</th></tr></thead><tbody>';

    while ($row = $query->fetch_array(MYSQLI_ASSOC)) {
        $activated = ($row['activated'] == 1) ? "Ja" : "Nein";
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        if ($row['banned'] == 1) {
            echo "<td><strike>" . $row['username'] . "</strike></td>";
            echo "<td><strike>" . $row['mail'] . "</strike></td>";
        } else {
            echo "<td>" . $row['username'] . "</td>";
            echo "<td>" . $row['mail'] . "</td>";
        }
        echo "<td>" . $row['minecraftname'] . "</td>";
        if ($activated == "Ja") {
            echo '<td><a href="#" class="btn btn-success btn-mini disabled"><i class="icon-white icon-ok"></i> Aktivieren</a></td>';
        } else {
            echo '<td><a href="index.php?amethod=activateuser&id=' . $row['id'] . '" class="btn btn-success btn-mini"><i class="icon-white icon-ok"></i> Aktivieren</a></td>';
        }
        echo '<td><a href="index.php?amethod=banuser&id=' . $row['id'] . '" onclick="return confirm(\'Willst du das wirklich tun?\')" class="btn btn-inverse btn-mini"><i class="icon-white icon-remove"></i> Bannen</a></td>';
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
