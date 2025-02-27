<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';

$data = json_decode(file_get_contents("php://input"), true);
$userId = $data['userId'];
$stock_history_qr = $db->query("SELECT * FROM `stock_history_qr` WHERE `user_id` = '$userId' AND `is_done` = 0");
$stock_history_qr_id = $stock_history_qr->fetch_assoc()['id'];

$commit = $db->query("UPDATE `stock_history_qr` SET `is_done`= '1' WHERE `user_id` = '$userId' AND `is_done` = 0");
$stock_history_qr_detail = $db->query("SELECT * FROM `stock_history_qr_detail` WHERE `stock_history_qr_id` = '$stock_history_qr_id'");
foreach ($stock_history_qr_detail as $key => $detail) {
  $idProduk = $detail['produk_id'];
  $quantity = $detail['quantity'];
  $quantityAwal = intVal($db->query("SELECT * FROM `produk` WHERE `id` = '$idProduk'")->fetch_assoc()['qr_stock']);
  $fixQuantity = $quantityAwal + $quantity;
  $update = $db->query("UPDATE `produk` SET `qr_stock` = '$fixQuantity' WHERE `id` = '$idProduk'");
}