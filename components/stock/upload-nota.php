<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
include $_SERVER['DOCUMENT_ROOT'].'/function/upload-file.php';
session_start();
$username = $_SESSION['username'];
$userId = $db->query("SELECT `id` FROM user WHERE `username` = '$username'")->fetch_assoc()['id'];
$id = hash('sha256', "satoshi".rand(1, 21000000));
$namaFile = $id . '.jpg';
if (file_exists($_SERVER['DOCUMENT_ROOT'].'/public/nota-stock/'.$namaFile)) {
    unlink($_SERVER['DOCUMENT_ROOT'].'/public/nota-stock/'.$namaFile);
}
$video = uploadFile($_FILES, 'file', 'nota-stock/', $namaFile);
// $video = uploadFile($_FILES, 'file', 'nyobaaaa/thumbnail/');
$is_available = $db->query("SELECT * FROM `foto_nota_stock` WHERE `user_id` = '$userId' AND `used` = '0'")->num_rows > 0;
if ($is_available) {
    $update = $db->query("UPDATE `foto_nota_stock` SET `file`='$namaFile' WHERE `user_id` = '$userId' AND `used` = '0'");
}else{
    $insert = $db->query("INSERT INTO `foto_nota_stock`(`file`, `user_id`, `used`) VALUES ('$namaFile','$userId','0')");
}
if ($video) {
    echo "File berhasil diunggah: " . basename($_FILES["file"]["name"]);
} else {
    echo "Gagal mengunggah file.";
}
// die();
?>