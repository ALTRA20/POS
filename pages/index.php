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
    include $_SERVER['DOCUMENT_ROOT'].'/components/header/navbar2.php'; 
    $produks = [
        "Resleting Amco Gold No 3",
        "Plisir Spunbond Warna Putih 75gsm Lebar 2,5cm",
        "Resleting Amco Bronze No 3",
        "Prada Botega Bronze Metalik per Meter",
        "Plastik UV Q5 4m x 170mic x 100meter",
        "Prada Botega Mocasin Metalik per Meter",
        "Prada Botega Army Metalik per Meter",
        "Prada Botega Maroon Metalik per Meter",
    ];
    $harga = [
        [
            "jumlah" => "1",
            "harga" => "100000"
        ],
        [
            "jumlah" => "3",
            "harga" => "70000"
        ],
        [
            "jumlah" => "10",
            "harga" => "50000"
        ]
    ];
    ?>
<script>
    document.title = 'Jelajah GS';
</script>
<?php include $_SERVER['DOCUMENT_ROOT'].'/components/index/index.php'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'].'/components/footer/index.php'; ?>