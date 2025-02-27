<?php
session_start();
$_SESSION['last_url'] = $_SERVER[REQUEST_URI];
?>
<?php
if(!isset($_SESSION["username"])){
    echo "<script>window.location.href = '/pages/login.php'</script>";
}else{
    include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
    $username = $_SESSION["username"];
    $user = $db->query("SELECT * FROM `user` WHERE `username` = '$username'")->fetch_assoc();
    $jabatan = $user['jabatan'];
    $userId = $user['id'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
    <link href="/public/bootstrap/css/bootstrap.css" rel="stylesheet">
    <script src="/public/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/function/dateNow.js"></script>
    <link href="/pages/index.css" rel="stylesheet">
    <link rel="stylesheet" href="/public/jquery-ui.css">
    <script src="/public/jquery-3.6.0.min.js"></script>
    <script src="/public/jquery-ui.js"></script>
</head>
<body>
    <?php
    function format_rupiah($number) {
        return 'Rp ' . number_format($number, 0, ',', '.');
    }
    include $_SERVER['DOCUMENT_ROOT'].'/components/header/navbar.php'; 
    ?>
<script>
    document.title = 'Jelajah GS';
</script>
<?php include $_SERVER['DOCUMENT_ROOT'].'/components/index/index.php'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'].'/components/footer/index.php'; ?>