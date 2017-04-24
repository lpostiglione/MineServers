<div class="page-header">
    <h1>Gemeldete Kommentare</h1>
</div>
<?php
$query = $db->query("SELECT * FROM sl_comments WHERE reported = 'true' ORDER BY serverid ASC, id ASC");
$amount = $query->num_rows;

if ($amount > 0) {

    echo '<table class="table table-striped"><thead><tr><th>Info</th><th>Kommentar</th><th colspan="2">Aktionen</th></tr></thead><tbody>';

    while ($row = $query->fetch_array(MYSQLI_ASSOC)) {
        $date = date("d.m.y H:i", $row['date']);
        echo "<tr>";
        echo '<td class="nolinebreak"><b>Server:</b> <a href="index.php?site=serverview&id=' . $row['serverid'] . '#comments">' . $row['serverid'] . '</a><br>';
        echo "<b>Datum:</b> " . $date . "<br>";
        echo "<b>Autor:</b> " . $row['user'] . "</td>";
        echo "<td style='word-break: break-word'>" . $row['comment'] . "</td>";
        echo '<td class="nolinebreak"><a href="index.php?amethod=deletereport&id=' . $row['id'] . '" class="btn btn-success btn-mini"><i class="icon-white icon-ok"></i> Entwarnen</a> <a href="index.php?amethod=delcomment&id=' . $row['id'] . '" onclick="return confirm(\'Willst du das wirklich tun?\')" class="btn btn-danger btn-mini"><i class="icon-white icon-remove"></i> Löschen</a></td>';
        echo "</tr>";
    }

    echo "</tbody></table>";
} else {
    echo '<div class="alert alert-warning">Es gibt keine offenen Meldungen!</div>';
}
?>
<h1>Alle Kommentare</h1>
<?php
$result = $db->query("SELECT * FROM sl_comments");
$amount = $result->num_rows;

$page = empty($_GET["page"]) ? 1 : $_GET["page"];

$persite = 15;
$start = $page * $persite - $persite;

$query = $db->query("SELECT * FROM sl_comments ORDER BY date DESC LIMIT " . $start . ", " . $persite);

echo '<table class="table table-striped"><thead><tr><th>Info</th><th>Kommentar</th><th colspan="2">Aktionen</th></tr></thead><tbody>';

while ($row = $query->fetch_array(MYSQLI_ASSOC)) {
    $date = date("d.m.y H:i", $row['date']);
    echo "<tr>";
    echo '<td class="nolinebreak"><b>Server:</b> <a href="index.php?site=serverview&id=' . $row['serverid'] . '#comments">' . $row['serverid'] . '</a><br>';
    echo "<b>Datum:</b> " . $date . "<br>";
    echo "<b>Autor:</b> " . $row['user'] . "</td>";
    echo "<td style='word-break: break-word'>" . $row['comment'] . "</td>";
    echo '<td class="nolinebreak"><a href="index.php?amethod=delcomment&id=' . $row['id'] . '" onclick="return confirm(\'Willst du das wirklich tun?\')" class="btn btn-danger btn-mini"><i class="icon-white icon-remove"></i> Löschen</a></td>';
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
