<?php 
$selall = "onfocus='this.select();' onmouseup='return false;'";
function cetax($x){
    echo "<pre>$x</pre>";
};
function rupiah($number) {
    // echo $number;
    return 'Rp' . number_format($number, 0, ',', '.');
}

session_start();
if(!isset($_SESSION["username"])){
    echo "<script>window.location.href = '/pages/login.php'</script>";
}else{
    include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
    include $_SERVER['DOCUMENT_ROOT'].'/function/exc.php';
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
    <link rel="stylesheet" href="/lib/jquery-ui.css">
    <script src="/lib/jquery-3.6.0.min.js"></script>
    <script src="/lib/jquery-ui.js"></script>
    <script type="text/javascript" src="/function/convertIdr.js"></script>
    <script src="/function/opentip-master/opentip-jquery.min.js"></script><!-- Change to the adapter you actually use -->
    <link href="/function/opentip-master/opentip.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="/components/header/header.css" type="text/css" >
</head>
<body>
    <?php
    function format_rupiah($number) {
        return 'Rp ' . number_format($number, 0, ',', '.');
    }
    ?>
    <?php include $_SERVER['DOCUMENT_ROOT'].'/components/header/navbar.php'; ?>

