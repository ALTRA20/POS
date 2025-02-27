<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'];
$history_id = $db->query("SELECT * FROM `stock_history_qr_detail` WHERE `id` = '$id'")->fetch_assoc()['stock_history_qr_id'];
$kurangDari2 = $db->query("SELECT * FROM `stock_history_qr_detail` WHERE `stock_history_qr_id` = '$history_id'")->num_rows < 2;
if ($kurangDari2) {
  $hapusHistory = $db->query("DELETE FROM `stock_history_qr` WHERE `id` = '$history_id'");
}
$hapus = $db->query("DELETE FROM `stock_history_qr_detail` WHERE `id` = '$id'");
if ($hapus) {
  echo json_encode([
    "status" => "success"
  ]);
}