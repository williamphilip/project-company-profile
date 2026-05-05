<?php
// 1. Koneksi ke database
$host = "localhost";
$user = "root"; // sesuaikan username database Anda
$pass = "root";     // sesuaikan password database Anda
$db   = "rsia_paramount";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// 2. Ambil data dari FORM (pastikan name di HTML sesuai)
$nama_dokter = $_POST['nama_dokter'] ?? '';
$nama_pasien = $_POST['nama_pasien'] ?? '';
$nomor_wa    = $_POST['nomor_wa']    ?? '';

// 3. Penanganan Error Tanggal (Kunci agar tidak error lagi)
// Jika user tidak isi tanggal, gunakan tanggal hari ini
$tanggal_kunjungan = !empty($_POST['tanggal_kunjungan']) ? $_POST['tanggal_kunjungan'] : date('Y-m-d');

// 4. Query Insert (ID tidak perlu dimasukkan karena otomatis)
$sql = "INSERT INTO pendaftaran_online (nama_dokter, nama_pasien, tanggal_kunjungan, nomor_wa) VALUES (?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $nama_dokter, $nama_pasien, $tanggal_kunjungan, $nomor_wa);

if ($stmt->execute()) {
    echo "Data berhasil disimpan!";
} else {
    echo "Gagal menyimpan data: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>