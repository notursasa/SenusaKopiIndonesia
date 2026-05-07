<?php

$server   = "localhost";
$username = "root";
$password = "";
$database = "db_senusa_kopi";

$conn = mysqli_connect($server, $username, $password, $database);

if (!$conn) {
    die("Koneksi Database Gagal: " . mysqli_connect_error());
}

date_default_timezone_set('Asia/Jakarta');
?>