<?php
require_once 'db.php';
session_start();

// Get some variables from posted data
// l = time to get backwarsd in hours
// s = sensorId

if (isset($_POST['l'])&& preg_match("([0-9]+)", $_POST['l'])) {
    $l = $_POST['l']." hour";
} else {
    $l = "24 hour";
}
if (isset($_POST['s'])&& preg_match("([0-9]+)", $_POST['s'])) {
    $s = $_POST['s'];
} else {
    $s = 1;
}


$sql = "SELECT * FROM sensor";
$sensors = array();
foreach ($dbh->query($sql, PDO::FETCH_ASSOC) as $r) {
    $sensors[$r['id']] = $r;
}

$table = array();
// Set table labels for Google Chart
$table['cols'] = array(
    array('label' => 'Time', 'type' => 'datetime')
);
$fields = array();

if($sensors[$s]['pressure'] == 1){
    $table['cols'][] = array('label' => 'Pressure', 'type' => 'number');
    $fields[] = "pressure";
    $table['units'][] = "# Pa";
}

if($sensors[$s]['temperature'] == 1){
    $table['cols'][] = array('label' => 'Temperature', 'type' => 'number');
    $fields[] = "temperature";
    $table['units'][] = htmlspecialchars("#,## °C");
}

if($sensors[$s]['humidity'] == 1){
    $table['cols'][] = array('label' => 'Humidity', 'type' => 'number');
    $fields[] = "humidity";
    $table['units'][] = htmlspecialchars("#,## %");
}

$fieldsStr = implode(", ", $fields);

$rows = array();

// Select the data from MySQL dtabase
$sql = "SELECT UNIX_TIMESTAMP(time) as time, $fieldsStr FROM measurement WHERE sensorId = $s AND time > NOW() - INTERVAL $l ORDER BY time DESC";

foreach ($dbh->query($sql) as $r) {
    $temp = array();
    // the following line will be used to slice the Pie chart
    $temp[] = array('v' => "\"" + ((string) $r['time']) + "\"");

    // Values of each slice
    foreach($fields as $f){
        $temp[] = array('v' => (double) $r[$f]);
    }
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