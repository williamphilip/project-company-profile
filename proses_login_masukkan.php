<?php
session_start();
$conn = new mysqli("localhost", "root", "root", "rsia_paramount");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk mencocokkan admin
    $sql = "SELECT * FROM admin_masukkan WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['username'] = $username;
        header("Location: data_masukkan.php");
    } else {
        echo "<script>alert('Username atau Password salah!'); window.location.href='loginDataMasukkan.php';</script>";
    }
}
?>