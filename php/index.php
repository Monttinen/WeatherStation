<?php
/* Connect to an ODBC database using driver invocation */
$dsn = 'mysql:dbname=weather;host=127.0.0.1;port=1194';
$user = 'weather';
$password = 'weather123';

try {
	$dbh = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
	echo 'Connection failed: ' . $e->getMessage();
}

$rows = array();
//flag is not needed
$flag = true;
$table = array();
$table['cols'] = array(
	array('label' => 'Time', 'type' => 'string'),
	array('label' => 'Pressure', 'type' => 'number'),
	array('label' => 'Temperature', 'type' => 'number')
);

$rows = array();
$sql = "SELECT time, pressure, temperature FROM measurement WHERE sensorId = 1 ORDER BY time ASC LIMIT 2880 ";
foreach ($dbh->query($sql) as $r) {
	$temp = array();
	// the following line will be used to slice the Pie chart
	$temp[] = array('v' => (string) $r['time']);

	// Values of each slice
	$temp[] = array('v' => (double) $r['pressure']);
	$temp[] = array('v' => (double) $r['temperature']);
	$rows[] = array('c' => $temp);
}

$table['rows'] = $rows;
$jsonTable = json_encode($table);

//print json_encode($table);
?>
<html>
	<head>
		<script type="text/javascript"
				src="https://www.google.com/jsapi?autoload={
				'modules':[{
				'name':'visualization',
				'version':'1',
				'packages':['corechart']
				}]
		}"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script type="text/javascript">
					google.setOnLoadCallback(drawChart);
					function drawChart() {
					var data = new google.visualization.DataTable(<?php echo $jsonTable; ?>);
							var options = {
							title: 'Sensor 1',
									curveType: 'function',
									legend: { position: 'bottom' },
									vAxes: {0: {logScale: false},
											1: {logScale: false}
									},
									series:{
										0:{targetAxisIndex:0},
										1:{targetAxisIndex:1},
										2:{targetAxisIndex:1}
									}
							};
							var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
							chart.draw(data, options);
					}
		</script>
	</head>
	<body>
		<div id="curve_chart" style="width: 90%; min-width: 800px; min-height: 500px; height: 70%"></div>
		<div id="text"></div>
	</body>
</html>
