<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM admin_dokter WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Verifikasi password (menggunakan password_verify)
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin_dokter_logged_in'] = true;
            $_SESSION['admin_username'] = $row['username'];
            
            header("Location: admin_dashboard.php");
            exit;
        }
    }

    // Jika gagal, kembalikan ke halaman login dengan parameter error
    header("Location: login_dokter.php?error=1");
    exit;
}