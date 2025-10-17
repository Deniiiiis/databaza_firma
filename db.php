<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "sprava_zariadeni";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Chyba pripojenia: " . $conn->connect_error);
}
?>
