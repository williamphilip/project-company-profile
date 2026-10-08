<?php
session_start();
if (!isset($_SESSION['admin_dokter_logged_in'])) {
    header("Location: login_dokter.php");
    exit;
}
include 'koneksi.php';

$pesan_error = "";
$pesan_sukses = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_dokter  = trim($_POST['nama_dokter']);
    $poliklinik   = trim($_POST['poliklinik']);
    $hari_praktik = trim($_POST['hari_praktik']);
    $jam_praktik  = trim($_POST['jam_praktik']);
    $status       = trim($_POST['status']);

    // Proses Upload Foto
    $foto_path = "";
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $file_tmp   = $_FILES['foto']['tmp_name'];
        $file_name  = $_FILES['foto']['name'];
        $file_ext   = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        // Ekstensi yang diizinkan
        $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];
        
        if (in_array($file_ext, $allowed_ext)) {
            // Buat nama file unik agar tidak bentrok
            $new_file_name = uniqid('dokter_', true) . '.' . $file_ext;
            // Tentukan folder penyimpanan (pastikan folder 'assets/img/' atau folder tujuan Anda sudah ada)
            $upload_dir = 'assets/img/';
            
            // Buat folder otomatis jika belum ada
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $dest_path = $upload_dir . $new_file_name;
            
            if (move_uploaded_file($file_tmp, $dest_path)) {
                $foto_path = $dest_path;
            } else {
                $pesan_error = "Gagal mengunggah foto.";
            }
        } else {
            $pesan_error = "Format foto harus JPG, JPEG, PNG, atau WEBP.";
        }
    } else {
        // Jika tidak upload foto, gunakan path default atau kosong
        $foto_path = 'assets/img/default-doctor.png';
    }

    if (empty($pesan_error)) {
        if (empty($nama_dokter) || empty($poliklinik) || empty($hari_praktik) || empty($jam_praktik)) {
            $pesan_error = "Semua kolom wajib diisi!";
        } else {
            // Sesuaikan nama kolom dengan database Anda (nama_dokter, poliklinik, hari_praktik, jam_praktik, status, foto)
            $stmt = $conn->prepare("INSERT INTO jadwal_dokter (nama_dokter, poliklinik, hari_praktik, jam_praktik, status, foto) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $nama_dokter, $poliklinik, $hari_praktik, $jam_praktik, $status, $foto_path);

            if ($stmt->execute()) {
                $pesan_sukses = "Data dokter berhasil ditambahkan!";
                header("refresh:1.5;url=admin_dashboard.php");
            } else {
                $pesan_error = "Gagal menyimpan ke database: " . $conn->error;
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Dokter - RSIA Paramount</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600;700&display=swap');
        body { font-family: 'Quicksand', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center px-4 py-8">

    <div class="bg-white p-6 sm:p-10 rounded-2xl shadow-sm border border-gray-200 w-full max-w-lg">
        
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-gray-800">Tambah <span class="text-pink-600">Dokter Baru</span></h2>
                <p class="text-xs text-gray-400 mt-0.5">RSIA Paramount</p>
            </div>
            <a href="admin_dashboard.php" class="bg-gray-100 hover:bg-gray-200 text-gray-600 p-2.5 rounded-xl transition text-xs flex items-center gap-1 font-semibold">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <?php if (!empty($pesan_error)): ?>
            <div class="mb-5 bg-red-50 border border-red-200 text-red-600 text-xs font-semibold px-4 py-3 rounded-xl text-center">
                <?= $pesan_error; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($pesan_sukses)): ?>
            <div class="mb-5 bg-green-50 border border-green-200 text-green-600 text-xs font-semibold px-4 py-3 rounded-xl text-center">
                <?= $pesan_sukses; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Nama Lengkap Dokter & Gelar</label>
                <input type="text" name="nama_dokter" required placeholder="Contoh: dr. Annisa Sp.A" class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-pink-400 text-sm">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Poliklinik</label>
                <input type="text" name="poliklinik" required placeholder="Contoh: Poli Anak" class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-pink-400 text-sm">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Hari Praktik</label>
                <input type="text" name="hari_praktik" required placeholder="Contoh: Senin, Rabu, Jumat" class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-pink-400 text-sm">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Jam Praktik</label>
                <input type="text" name="jam_praktik" required placeholder="Contoh: 08:00 - 12:00 WIB" class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-pink-400 text-sm">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Status Ketersediaan</label>
                <select name="status" class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-pink-400 text-sm">
                    <option value="Tersedia">Tersedia</option>
                    <option value="Cuti">Cuti</option>
                    <option value="Berhalangan">Berhalangan</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Foto Dokter</label>
                <input type="file" name="foto" accept="image/*" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-pink-400 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-pink-50 file:text-pink-600 hover:file:bg-pink-100">
                <p class="text-[11px] text-gray-400 mt-1">Format yang diizinkan: JPG, JPEG, PNG, WEBP.</p>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full bg-pink-600 hover:bg-pink-700 text-white py-4 rounded-xl font-bold text-sm shadow-lg transition">
                    Simpan Data Dokter
                </button>
            </div>
        </form>

    </div>

</body>
</html>