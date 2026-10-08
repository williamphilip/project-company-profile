<?php
session_start();
if (!isset($_SESSION['admin_dokter_logged_in'])) {
    header("Location: login_dokter.php");
    exit;
}
include 'koneksi.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$pesan_error = "";
$pesan_sukses = "";

// Ambil data dokter berdasarkan ID
$stmt = $conn->prepare("SELECT * FROM jadwal_dokter WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    header("Location: admin_dashboard.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_dokter  = trim($_POST['nama_dokter']);
    $poliklinik   = trim($_POST['poliklinik']);
    $hari_praktik = trim($_POST['hari_praktik']);
    $jam_praktik  = trim($_POST['jam_praktik']);
    $status       = trim($_POST['status']);
    
    $foto_path = $data['foto']; // Gunakan foto lama secara default

    // Proses Ganti Foto (jika ada file baru yang diunggah)
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $file_tmp   = $_FILES['foto']['tmp_name'];
        $file_name  = $_FILES['foto']['name'];
        $file_ext   = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];
        
        if (in_array($file_ext, $allowed_ext)) {
            $new_file_name = uniqid('dokter_', true) . '.' . $file_ext;
            $upload_dir = 'assets/img/';
            
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $dest_path = $upload_dir . $new_file_name;
            
            if (move_uploaded_file($file_tmp, $dest_path)) {
                // Hapus foto lama jika ada dan bukan default
                if (!empty($data['foto']) && file_exists($data['foto']) && $data['foto'] != 'assets/img/default-doctor.png') {
                    @unlink($data['foto']);
                }
                $foto_path = $dest_path;
            } else {
                $pesan_error = "Gagal mengunggah foto baru.";
            }
        } else {
            $pesan_error = "Format foto harus JPG, JPEG, PNG, atau WEBP.";
        }
    }

    if (empty($pesan_error)) {
        if (empty($nama_dokter) || empty($poliklinik) || empty($hari_praktik) || empty($jam_praktik)) {
            $pesan_error = "Semua kolom wajib diisi!";
        } else {
            $update = $conn->prepare("UPDATE jadwal_dokter SET nama_dokter = ?, poliklinik = ?, hari_praktik = ?, jam_praktik = ?, status = ?, foto = ? WHERE id = ?");
            $update->bind_param("ssssssi", $nama_dokter, $poliklinik, $hari_praktik, $jam_praktik, $status, $foto_path, $id);

            if ($update->execute()) {
                $pesan_sukses = "Data dokter berhasil diperbarui!";
                header("refresh:1.5;url=admin_dashboard.php");
            } else {
                $pesan_error = "Gagal memperbarui database: " . $conn->error;
            }
            $update->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Dokter - RSIA Paramount</title>
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
                <h2 class="text-lg sm:text-xl font-bold text-gray-800">Edit <span class="text-pink-600">Data Dokter</span></h2>
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
                <input type="text" name="nama_dokter" value="<?= htmlspecialchars($data['nama_dokter']); ?>" required class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-pink-400 text-sm">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Poliklinik</label>
                <input type="text" name="poliklinik" value="<?= htmlspecialchars($data['poliklinik']); ?>" required class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-pink-400 text-sm">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Hari Praktik</label>
                <input type="text" name="hari_praktik" value="<?= htmlspecialchars($data['hari_praktik']); ?>" required class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-pink-400 text-sm">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Jam Praktik</label>
                <input type="text" name="jam_praktik" value="<?= htmlspecialchars($data['jam_praktik']); ?>" required class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-pink-400 text-sm">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Status Ketersediaan</label>
                <select name="status" class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-pink-400 text-sm">
                    <option value="Tersedia" <?= ($data['status'] == 'Tersedia') ? 'selected' : ''; ?>>Tersedia</option>
                    <option value="Cuti" <?= ($data['status'] == 'Cuti') ? 'selected' : ''; ?>>Cuti</option>
                    <option value="Berhalangan" <?= ($data['status'] == 'Berhalangan') ? 'selected' : ''; ?>>Berhalangan</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Foto Dokter Saat Ini</label>
                <div class="flex items-center space-x-4 mb-3">
                    <img src="<?= htmlspecialchars($data['foto']); ?>" alt="Foto Dokter" class="w-14 h-14 object-cover rounded-xl border bg-gray-100 shrink-0" onerror="this.src='assets/img/default-doctor.png'">
                    <p class="text-[11px] text-gray-400">Biarkan kosong jika tidak ingin mengubah foto dokter.</p>
                </div>
                <input type="file" name="foto" accept="image/*" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-pink-400 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-pink-50 file:text-pink-600 hover:file:bg-pink-100">
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full bg-pink-600 hover:bg-pink-700 text-white py-4 rounded-xl font-bold text-sm shadow-lg transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>

    </div>

</body>
</html>