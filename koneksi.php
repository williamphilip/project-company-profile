<?php
$host = "localhost";
$user = "root";
$pass = "root"; // Sesuaikan password database Anda
$db   = "rsia_paramount";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
