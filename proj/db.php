<?php
$host = "localhost";   // or server IP
$port = "5432";        // default PostgreSQL port
$dbname = "proj";      // your PostgreSQL database name
$user = "postgres";    // your PostgreSQL username
$password = "123456789"; // replace with your password

$con = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$con) {
    die("Connection failed: " . pg_last_error());
}
?>
