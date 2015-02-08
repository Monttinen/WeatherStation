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

$table = array();
// Set table labels for Google Chart
$table['cols'] = array(
	array('label' => 'Time', 'type' => 'datetime'),
	array('label' => 'Pressure', 'type' => 'number'),
	array('label' => 'Temperature', 'type' => 'number')
);

$rows = array();

// Get some variables from url
// l = time to get backwarsd in days
if(isset($_GET['l'])){
	$l = intval($_GET['l']);
	if($l<1){
		$l = 1;
	}
} else {
  $l = 1;
}


// Select the data from MySQL dtabase
$sql = "SELECT UNIX_TIMESTAMP(time) as time, pressure, temperature FROM measurement WHERE sensorId = 1 AND time > NOW() - INTERVAL $l DAY ORDER BY time DESC";
foreach ($dbh->query($sql) as $r) {
	$temp = array();
	// the following line will be used to slice the Pie chart
	$temp[] = array('v' => "\"" + ((string) $r['time']) + "\"");

	// Values of each slice
	$temp[] = array('v' => (double) $r['pressure']);
	$temp[] = array('v' => (double) $r['temperature']);
	$rows[] = array('c' => $temp);
}

$table['rows'] = $rows;
$jsonTable = json_encode($table);

//print json_encode($table);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8"> 
		<script type="text/javascript"
				src="https://www.google.com/jsapi?autoload={
				'modules':[{
				'name':'visualization',
				'version':'1',
				'packages':['corechart']
				}]
		}"></script>
		<!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>  not needed ATM -->
		<script type="text/javascript">
					google.setOnLoadCallback(drawChart);
					
					function drawChart() {
						var json = <?php echo $jsonTable; ?>;
						
						// Parse unix timestamps into Date objects
						for (var i = 0; i < json.rows.length; i++) {
							json.rows[i].c[0].v = new Date(json.rows[i].c[0].v * 1000);
						}
						
						// Set the data and options for Google Chart
						var data = new google.visualization.DataTable(json);
						var options = {
						title: 'Sensor 1',
								curveType: 'function',
								legend: { position: 'bottom' },
								vAxes: {0: {logScale: false, format: '# hPa'},
										1: {logScale: false, format: '# °C'}
								},
								hAxis: {
								format: 'HH:mm'
								},
								series:{
								0:{targetAxisIndex:0},
										1:{targetAxisIndex:1},
										2:{targetAxisIndex:1}
								}
						};
						
						// Create and draw the chart to HTML
						var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
						chart.draw(data, options);
					}
		</script>
		<title>Weather Station</title>
	</head>
	<body>
		<div id="curve_chart" style="width: 90%; min-width: 800px; min-height: 500px; height: 70%"></div>
		<div id="text"></div>
	</body>
</html>
