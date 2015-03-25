<?php
require_once 'db.php';

$sql = "SELECT * FROM sensor";
$sensors = array();
foreach ($dbh->query($sql, PDO::FETCH_ASSOC) as $r) {
    $sensors[] = $r;
}

print json_encode($sensors);
?>