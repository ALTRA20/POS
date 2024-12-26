<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
include $_SERVER['DOCUMENT_ROOT'].'/function/exc.php';

$datas = json_decode(file_get_contents("php://input"), true);
$userId = $datas['userId'];
$note = $datas['note'];
$tambah = $db->query("UPDATE `keranjang` SET `note` = '$note' WHERE `user_id` = '$userId'");
if ($tambah) {
    exc();
    echo "Berhasil menambahkan catatan";
}else{
    echo "Gagal menambahkan catatan";
}