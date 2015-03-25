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
?>