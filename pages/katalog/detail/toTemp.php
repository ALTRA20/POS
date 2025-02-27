<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
$jsonData = file_get_contents("php://input");
$data = json_decode($jsonData, true);
$idKatalog = $data['katalog_id'];
$idUser = $data['idUser'];

$hapusDetailTemp = $db->query("DELETE FROM `katalog_detail_temp` WHERE `user_id` = '$idUser'");
$katalogDetail = $db->query("SELECT * FROM `katalog_detail` WHERE `katalog_id` = '$idKatalog' AND `user_id` = '$idUser'");
foreach ($katalogDetail as $key => $kd) {
    $produk_id = $kd['produk_id'];
    $markup = $kd['markup'];
    $urutan = $kd['urutan'];
    $user_id = $kd['user_id'];
    $insertTemp = $db->query("INSERT INTO `katalog_detail_temp`(`user_id`, `katalog_id`, `produk_id`, `markup`, `urutan`) VALUES ('$user_id', '$idKatalog', '$produk_id', '$markup', '$urutan')");
}
echo json_encode(array('status' => 'success', 'message' => "Data berhasil di simpan"));
?>