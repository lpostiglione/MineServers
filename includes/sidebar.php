<?php 
if (!empty($sidebar)) {
    echo $sidebar;
} else {
    ?>
    <ul class="nav nav-list">
        <li class="nav-header">Server of the day</li>
        <?php
        $sotd = file("includes/sotd.txt");
        echo '<li style="text-align: center;font-weight: bold;"><a href="index.php?site=serverview&id=' . $sotd[0] . '">' . $sotd[1] . '</a></li>';
        /*?>
        <li class="nav-header">News</li>
        <?php 
        $newsq = $db->query("SELECT threadID, topic FROM `mcsrv_wbb`.`wbb1_1_thread` WHERE boardID = '2' ORDER BY threadID DESC LIMIT 5");
        while ($row = $newsq->fetch_object()) {
            echo '<li><a href="http://board.mineservers.eu/index.php?page=Thread&threadID=' . $row->threadID . '">' . $row->topic . '</a></li>';
        }
		*/?>
        <li class="nav-header">Neueste Server</li>
        <?php
        $newestserverq = $db->query("SELECT id, servername FROM sl_servers WHERE online = 'true' ORDER BY id DESC LIMIT 5");
        while ($row = $newestserverq->fetch_object()) {
            echo '<li><a href="index.php?site=serverview&id=' . $row->id . '">' . $row->servername . '</a></li>';
        }
        ?>
        <li class="nav-header">Momentan aktive Server</li>
        <?php
        $activeserverq = $db->query("SELECT id, servername FROM sl_servers WHERE online = 'true' AND votes >= 5 ORDER BY player DESC LIMIT 5");
        while ($row = $activeserverq->fetch_object()) {
            echo '<li><a href="index.php?site=serverview&id=' . $row->id . '">' . $row->servername . '</a></li>';
        }
        ?>
        
        <!-- li class="nav-header">Empfohlen</li>
        <li class="nav-header">Sponsored</li> -->
    </ul>
<?php } ?>