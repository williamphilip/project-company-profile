<?php
session_start();
// Jika sudah login, langsung lempar ke dashboard
if (isset($_SESSION['admin_dokter_logged_in'])) {
    header("Location: admin_dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin Jadwal Dokter - RSIA Paramount</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600;700&display=swap');
        body { font-family: 'Quicksand', sans-serif; }
    </style>
</head>
<body class="bg-pink-50/40 min-h-screen flex items-center justify-center px-4">

    <div class="bg-white p-8 md:p-10 rounded-[2.5rem] shadow-xl w-full max-w-md border border-pink-100">
        
        <div class="text-center mb-8">
            <div class="inline-block bg-pink-100 text-pink-600 p-3 rounded-2xl mb-3">
                <i class="fas fa-user-shield text-2xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Login <span class="text-pink-600">Admin Dokter</span></h2>
            <p class="text-xs text-gray-400 mt-1">RSIA Paramount - Manajemen Jadwal Praktik</p>
        </div>

        <?php if (isset($_GET['error'])): ?>
            <div class="mb-6 bg-red-50 border border-red-200 text-red-600 text-xs font-semibold px-4 py-3 rounded-xl text-center">
                Username atau password salah! Silakan coba lagi.
            </div>
        <?php endif; ?>

        <form action="proses_login_dokter.php" method="POST" class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Username</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                        <i class="fas fa-user"></i>
                    </span>
                    <input type="text" name="username" required placeholder="Masukkan username" class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-pink-400 text-sm">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" name="password" required placeholder="Masukkan password" class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-pink-400 text-sm">
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full bg-pink-600 hover:bg-pink-700 text-white py-4 rounded-xl font-bold text-sm shadow-lg shadow-pink-200 transition">
                    Masuk ke Dashboard
                </button>
            </div>
        </form>

        <div class="mt-8 text-center border-t border-gray-100 pt-6">
            <a href="index.php" class="text-xs text-gray-500 hover:text-pink-600 font-semibold">
                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Beranda Utama
            </a>
        </div>

    </div>

</body>
</html>