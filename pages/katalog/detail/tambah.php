<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
$jsonData = file_get_contents("php://input");
$data = json_decode($jsonData, true);
$idKatalog = $data['idKatalog'];

$katalogDetail = $db->query("SELECT * FROM `katalog_detail` WHERE `katalog_id` = '$idKatalog'");
foreach ($katalogDetail as $key => $kd) {
    $produk_id = $kd['produk_id'];
    $markup = $kd['markup'];
    $urutan = $kd['urutan'];
    $insertTemp = $db->query("INSERT INTO `katalog_detail_temp`(`katalog_id`, `produk_id`, `markup`, `urutan`) VALUES ('$idKatalog', '$produk_id', '$markup', '$urutan')");
}
json_encode(array('status' => 'success', 'message' => 'Data berhasil di simpan'));
?>