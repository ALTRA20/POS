<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
$idPoto = $_GET['idPoto'];
$idProduk = $_GET['idProduk'];
$is_video = $db->query("SELECT * FROM `foto` WHERE `id` = '$idPoto'")->fetch_assoc()['is_video'];
$ekstensi = '';
if ($is_video == 1) {
    $ekstensi = '.mp4';
}else{
    $ekstensi = '.jpg';
}
$filePath = $_SERVER['DOCUMENT_ROOT'] . '/public/foto/lg/' . $idPoto . $ekstensi; // Adjust the path to your file
if (file_exists($filePath)) {
    if (unlink($filePath)) {
        $hapus = $db->query("UPDATE `foto` SET `is_active`='0' WHERE `id` = '$idPoto'");
        if ($hapus) {
            echo "File deleted successfully.";
            echo "<script>window.location.href='/pages/assets/produk.php?id=".$idProduk."'</script>";
        }
    } else {
        echo "Error deleting file.";
    }
} else {
    echo "File does not exist.";
}