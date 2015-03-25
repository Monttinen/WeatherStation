<?php
require_once 'db.php';
session_start();
$table = array();
// Set table labels for Google Chart
$table['cols'] = array(
    array('label' => 'Time', 'type' => 'datetime'),
    array('label' => 'Pressure', 'type' => 'number'),
    array('label' => 'Temperature', 'type' => 'number')
);

$rows = array();

// Get some variables from url
// l = time to get backwarsd in hours

if (isset($_POST['l'])) {
    $l = $_POST['l']." hour";
} else {
    $l = "24 hour";
}
if (isset($_POST['s'])) {
    $s = $_POST['s'];
} else {
    $s = 1;
}
// Select the data from MySQL dtabase
$sql = "SELECT UNIX_TIMESTAMP(time) as time, pressure, temperature FROM measurement WHERE sensorId = $s AND time > NOW() - INTERVAL $l ORDER BY time DESC";

foreach ($dbh->query($sql) as $r) {
    $temp = array();
    // the following line will be used to slice the Pie chart
    $temp[] = array('v' => "\"" + ((string) $r['time']) + "\"");

    // Values of each slice
    $temp[] = array('v' => (double) $r['pressure']);
    $temp[] = array('v' => (double) $r['temperature']);
    $rows[] = array('c' => $temp);
}

if(sizeof($rows)>500){
    $skip = round(sizeof($rows)/500.0);
    $i=0;
    $rows2 = array();
    
    foreach($rows as $r){
        if($i==$skip){
            $i=0;
            $rows2[]=$r;
        }
        $i++;
    }
    $rows = $rows2;
}

$table['rows'] = $rows;
$jsonTable = json_encode($table);

print json_encode($table);
?>