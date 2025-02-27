<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';

$data = json_decode(file_get_contents("php://input"), true);
$userId = $data['userId'];
$id_customer = $data['id_customer'];
$product_id = $data['product_id'];
$quantity = $data['jumlah'];

$stock_history_qr = $db->query("SELECT * FROM `stock_history_qr` WHERE `user_id` = '$userId' AND `is_done` = 0");
$is_available = $stock_history_qr->num_rows > 0;
if ($is_available) {
  $lastId = $stock_history_qr->fetch_assoc()['id'];
}else{
  $hqr = $db->query("INSERT INTO `stock_history_qr`(`user_id`, `created_at`) VALUES ('$userId',CURRENT_TIMESTAMP())");
  $lastId = $db->insert_id;
}
$sql = "";

$stock_history_qr_detail = $db->query("SELECT * FROM `stock_history_qr_detail` WHERE `stock_history_qr_id` = '$lastId' AND `produk_id` = '$product_id'");
$is_available = $stock_history_qr_detail->num_rows > 0;
if ($is_available) {
  $stock_history_qr_detail = $stock_history_qr_detail->fetch_assoc();
  $id = $stock_history_qr_detail['id'];
  if ($data['qr']) {
    $quantity = $stock_history_qr_detail['quantity'];
    $quantity = intVal($quantity) + 1;
  }
  $sql = "UPDATE `stock_history_qr_detail` SET `quantity` = '$quantity' WHERE `stock_history_qr_id` = '$lastId' AND `produk_id` = '$product_id'";
  $update = $db->query("UPDATE `stock_history_qr_detail` SET `quantity` = '$quantity' WHERE `stock_history_qr_id` = '$lastId' AND `produk_id` = '$product_id'");
}else{
  $hqr = $db->query("INSERT INTO `stock_history_qr_detail`(`stock_history_qr_id`, `produk_id`, `quantity`) VALUES ('$lastId','$product_id','$quantity')");
}

echo json_encode([
  "status" => "SELECT * FROM `stock_history_qr_detail` WHERE `stock_history_qr_id` = '$lastId' AND `produk_id` = '$product_id'"
]);