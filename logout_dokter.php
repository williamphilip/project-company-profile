<?php
session_start();
unset($_SESSION['admin_dokter_logged_in']);
unset($_SESSION['admin_username']);
session_destroy();
header("Location: login_dokter.php");
exit;