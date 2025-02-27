<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
$data = json_decode(file_get_contents("php://input"), true);
$id = $data['idGambar'];
$fileLg = $_SERVER['DOCUMENT_ROOT'] . "/public/foto/lg/" . $id . ".jpg";
$fileMd = $_SERVER['DOCUMENT_ROOT'] . "/public/foto/md/" . $id . ".jpg";
if (file_exists($fileLg)) { unlink($fileLg); }
if (file_exists($fileMd)) { unlink($fileMd); }

$delete = $db->query("UPDATE `foto` SET `is_active` = 0 WHERE `id` = '$id'");
if ($delete) {
    echo json_encode($response = ["message" => "Berhasil menghapus foto"]);
}else{
    echo json_encode($response = ["message" => "Gagal menghapus foto"]);
}