<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
include $_SERVER['DOCUMENT_ROOT'].'/function/upload-file.php';
$namaFile = 'foto'.rand(100,999);
$video = uploadFile($_FILES, 'file', 'foto-temp/', $namaFile.'.jpg');
// $video = uploadFile($_FILES, 'file', 'nyobaaaa/thumbnail/');
$namaFile = $db->query("INSERT INTO `fotoTemp`(`file`) VALUES ('$namaFile')");
if ($video) {
    echo "File berhasil diunggah: " . basename($_FILES["file"]["name"]);
} else {
    echo "Gagal mengunggah file.";
}
?>