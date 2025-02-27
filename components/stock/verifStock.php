<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
$data = json_decode(file_get_contents("php://input"), true);
$idLogStock = $data['idLogStock'];
$idProduk = $data['idProduk'];
$qty = $data['qty'];
$unit = $data['unit'];
$harbel = $data['harbel'];
$stock_awal = $db->query("SELECT `stok_awal` FROM `log_stock` WHERE `id` = '$idLogStock'")->fetch_assoc()['stok_awal'];
$stock_baru = $qty + $stock_awal;
$updateProduk = $db->query("UPDATE `produk` SET `stock` = '$stock_baru' WHERE `id` = '$idProduk'");
$update = $db->query("UPDATE `log_stock` SET `stok_baru` = '$stock_baru', `unit` = '$unit', `harga` = '$harbel', `is_verif` = '1' WHERE `id` = '$idLogStock'");
echo json_encode(["message" => "UPDATE `produk` SET `stock` = '$stock_baru' WHERE `id` = '$idProduk'"]);