<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
$data = json_decode(file_get_contents("php://input"), true);
$bank = $data['bank'];
$mutasi_id = $data['mutasi_id'];
$kodeBayar = $data['kodeBayar'];
$bayar_id = $data['bayar_id'];
$nominal = $data['nominal_bayar_input'];
$userId = $data['userId'];
$status = '';

$insert = $db->query("INSERT INTO `tr_duit_masuk`(`kode_bayar`, `user_id`, `nominal`, `bayar_id`, `created_at`) VALUES ('$kodeBayar','$userId','$nominal','$bayar_id',CURRENT_TIMESTAMP())");

$bank = "tr_".$bank;
$updateBCA = $db->query("UPDATE `$bank` SET `id_bayar` = '$bayar_id' WHERE `id` = '$mutasi_id'");

$id_pesanan = $db->query("SELECT `pesanan_id` FROM `bayar` WHERE `id` = '$bayar_id'")->fetch_assoc()['pesanan_id'];
$updateLevelPesanan = $db->query("UPDATE `pesanan` SET `level`= '3' WHERE `id` = '$id_pesanan'");

if ($insert) {
  $status = 'Sukses menggunakan transferan';
}else{
  $status = 'Gagal menggunakan transferan';
}

echo json_encode([
  "status" => $status,
  "sql" => "UPDATE `$bank` SET `id_bayar` = '$bayar_id' WHERE `id` = '$mutasi_id'"
]);
?>