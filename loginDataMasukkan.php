<?php 
session_start();
if (isset($_SESSION['admin_logged_in'])) {
  header("Location: data_masukkan.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Data Masukkan - RSIA Paramount</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600;700&display=swap');
        body { font-family: 'Quicksand', sans-serif; }
    </style>
</head>
<body class="bg-pink-50 min-h-screen flex items-center justify-center p-6">

    <div class="max-w-md w-full bg-white rounded-[2.5rem] shadow-2xl overflow-hidden border border-pink-100">
        <div class="bg-teal-500 p-8 text-center text-white">
            <div class="bg-white w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                <i class="fas fa-user-lock text-teal-500 text-2xl"></i>
            </div>
            <h2 class="text-2xl font-bold">Login Data Masukkan</h2>
            <p class="text-teal-50 text-sm">RSIA Paramount Makassar</p>
        </div>

        <form action="proses_login_masukkan.php" method="POST" class="p-10 space-y-6">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Username</label>
                <div class="relative">
                    <i class="fas fa-user absolute left-4 top-4 text-pink-300"></i>
                    <input type="text" name="username" required 
                        class="w-full pl-12 pr-4 py-4 bg-pink-50/30 border border-pink-100 rounded-2xl focus:ring-4 focus:ring-pink-100 focus:border-pink-400 outline-none transition">
                </div>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Password</label>
                <div class="relative">
                    <i class="fas fa-key absolute left-4 top-4 text-pink-300"></i>
                    <input type="password" name="password" required 
                        class="w-full pl-12 pr-4 py-4 bg-pink-50/30 border border-pink-100 rounded-2xl focus:ring-4 focus:ring-pink-100 focus:border-pink-400 outline-none transition">
                </div>
            </div>
            <button type="submit" class="w-full bg-pink-500 text-white py-4 rounded-2xl font-bold text-lg hover:bg-pink-600 shadow-lg shadow-pink-200 transition-all active:scale-95">
                Masuk ke Dashboard
            </button>
        </form>
    </div>

</body>
</html>