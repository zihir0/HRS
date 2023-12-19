<?php
$server = "localhost";
$username = 'root';
$password = '';
$dbname = 'hotel_reservation';
$port = '3307';

$connection = mysqli_connect($server, $username, $password, $dbname, $port);

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    echo "Connected successfully";
}
?>

