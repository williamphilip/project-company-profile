<?php
include 'koneksi.php';

// Hapus data admin lama jika ada (agar bersih dari error)
$conn->query("DELETE FROM admin_dokter");

// Buat akun baru
$username_baru = "admin_dokter";
$password_polos = "password123";

// Hash password secara otomatis dan aman menggunakan PHP
$password_hashed = password_hash($password_polos, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO admin_dokter (username, password) VALUES (?, ?)");
$stmt->bind_param("ss", $username_baru, $password_hashed);

if ($stmt->execute()) {
    echo "<div style='font-family: sans-serif; text-align: center; margin-top: 50px;'>";
    echo "<h2 style='color: green;'>Berhasil Membuat Akun Admin Baru!</h2>";
    echo "<p>Silakan gunakan kredensial berikut untuk login:</p>";
    echo "<p><b>Username:</b> admin_dokter</p>";
    echo "<p><b>Password:</b> password123</p>";
    echo "<br><a href='login_dokter.php' style='background: #db2777; color: white; padding: 10px 20px; text-decoration: none; border-radius: 8px; font-weight: bold;'>Menuju Halaman Login</a>";
    echo "</div>";
} else {
    echo "Gagal membuat akun: " . $conn->error;
}
$stmt->close();
$conn->close();
?>