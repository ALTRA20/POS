<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
$data = json_decode(file_get_contents("php://input"), true);
$id = $data['idGambar'];
$idP = $data['idProduk'];

$update = $db->query("UPDATE `foto` SET `is_cover` = 0 WHERE `id_produk` = '$idP' AND `is_cover` = 1");
$update = $db->query("UPDATE `foto` SET `is_cover` = 1 WHERE `id` = '$id'");
if ($update) {
    echo json_encode($response = ["message" => "Berhasil menghapus foto"]);
}else{
    echo json_encode($response = ["message" => "Gagal menghapus foto"]);
}