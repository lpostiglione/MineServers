<script type="text/javascript" src="https://www.google.com/jsapi"></script>

<?php 
$query = $db->query("SELECT id FROM sl_users");
$totalusers = $query->num_rows;

$query2 = $db->query("SELECT id FROM sl_users WHERE activated = '1'");
$activatedusers = $query2->num_rows;

$nonactivatedusers = $totalusers - $activatedusers;

$query3 = $db->query("SELECT id FROM sl_servers WHERE online = 'true'");
$onlineservers = $query3->num_rows;

$query4 = $db->query("SELECT id FROM sl_servers WHERE online = 'false'");
$offlineservers = $query4->num_rows;

$totalservers = $onlineservers + $offlineservers;

$query5 = $db->query("SELECT id FROM sl_votes");
$dailyvotes = $query5->num_rows;

$query6 = $db->query("SELECT id FROM sl_comments");
$totalcomments = $query6->num_rows; 

?>

<script type="text/javascript">
  google.load("visualization", "1", {packages:["corechart"]});
  google.setOnLoadCallback(drawChart);
  function drawChart() {
	var data = google.visualization.arrayToDataTable([
	  ['Data', 'Count'],
	  ['Aktivierte User',      <?php echo $activatedusers; ?>],
	  ['Nicht Aktivierte User',    <?php echo $nonactivatedusers; ?>]
	]);

	var options = {
	  title: 'User Statistik',
	  backgroundColor: '#fff'
	};

	var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
	chart.draw(data, options);
  }
</script>
<div id="chart_div"></div>

<script type="text/javascript">
  google.load("visualization", "1", {packages:["corechart"]});
  google.setOnLoadCallback(drawChart);
  function drawChart() {
	var data = google.visualization.arrayToDataTable([
	  ['Data', 'Count'],
	  ['Server Online',     <?php echo $onlineservers; ?>],
	  ['Server Offline',   <?php echo $offlineservers; ?>]
	]);

	var options = {
	  title: 'Server Statistik',
	  backgroundColor: '#fff'
	};

	var chart = new google.visualization.PieChart(document.getElementById('chart_div2'));
	chart.draw(data, options);
  }
</script>
<div id="chart_div2"></div>

<script type="text/javascript">
  google.load("visualization", "1", {packages:["corechart"]});
  google.setOnLoadCallback(drawChart);
  function drawChart() {
	var data = google.visualization.arrayToDataTable([
	  ['Type', 'Anzahl' ],
	  ['User',  	 <?php echo $totalusers; ?>],
	  ['Server',  <?php echo $totalservers; ?>],
	  ['Kommentare', <?php echo $totalcomments; ?>],
	  ['Votes (Heute)',    <?php echo $dailyvotes; ?>]
	]);

	var options = {
	  title: 'Allgemeine Statistik',
	  backgroundColor : '#fff'
	};

	var chart = new google.visualization.ColumnChart(document.getElementById('chart_div3'));
	chart.draw(data, options);
  }
</script>
<div id="chart_div3"></div>
