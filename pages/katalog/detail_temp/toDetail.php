<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
$jsonData = file_get_contents("php://input");
$data = json_decode($jsonData, true);
$katalog_id = $data['katalog_id'];
$user_id = $data['idUser'];

$hapusDetail = $db->query("DELETE FROM `katalog_detail` WHERE `katalog_id` = '$katalog_id' AND `user_id` = '$user_id'");
$katalogDetail = $db->query("SELECT * FROM `katalog_detail_temp` WHERE `katalog_id` = '$katalog_id' AND `user_id` = '$user_id' ");
foreach ($katalogDetail as $key => $kd) {
    $produk_id = $kd['produk_id'];
    $markup = $kd['markup'];
    $urutan = $kd['urutan'];
    $user_id = $kd['user_id'];
    $insertTemp = $db->query("INSERT INTO `katalog_detail`(`user_id`, `katalog_id`, `produk_id`, `markup`, `urutan`) VALUES ('$user_id', '$katalog_id', '$produk_id', '$markup', '$urutan')");
}
$deleteTemp = $db->query("DELETE FROM `katalog_detail_temp` WHERE `user_id` = '$user_id'");
echo json_encode(array('status' => 'success', 'message' => "INSERT INTO `katalog_detail`(`user_id`, `katalog_id`, `produk_id`, `markup`, `urutan`) VALUES ('$user_id', '$katalog_id', '$produk_id', '$markup', '$urutan')"));
?>