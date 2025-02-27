<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
$jsonData = file_get_contents("php://input");
$data = json_decode($jsonData, true);
$katalog_id = $data['katalog_id'];
$userId = $data['userId'];
$katalogs = [];
$katalogDetails = $db->query("SELECT `katalog_detail_temp`.*, `produk`.nama, `produk`.harga_jual, `produk`.id AS produkId FROM `katalog_detail_temp` LEFT JOIN katalog ON `katalog`.id = `katalog_detail_temp`.katalog_id JOIN produk ON `produk`.id = `katalog_detail_temp`.produk_id WHERE `katalog`.id = '$katalog_id' AND `katalog_detail_temp`.`user_id` = '$userId' ORDER BY `katalog_detail_temp`.urutan ASC");
foreach ($katalogDetails as $key => $katalogDetail) {
    $produkId = $katalogDetail['produkId'];
    $katalogDetail['foto'] = $db->query("SELECT * FROM `foto` WHERE `id_produk` = '$produkId' AND `is_active` = 1")->fetch_Assoc()['id'];
    $katalogs [] = $katalogDetail;
}
echo json_encode($katalogs);