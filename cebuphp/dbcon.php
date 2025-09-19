<?php
$host= 'localhost';
$dbname= 'enrollment';
$username= 'root';
$password= '';
$charset= 'utf8mb4';

$dsn= "mysql:host=$host;dbname=$dbname;charset=$charset";

try {
    $connection = new PDO($dsn, $username, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo 'Luh? Cumonnect ka??';
} catch (Exception $e) {
    echo 'Hindi ka ngani nakaconnect: ' . $e->getMessage();
}
?>
