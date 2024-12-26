<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
$data = json_decode(file_get_contents("php://input"), true);
$userId = $data['userId'];
$namaVendor = $data['namaVendor'];
$asliNota = $data['asliNota'];
$stocksTemp = $db->query("SELECT * FROM `log_stock_temp` WHERE `user_id` = '$userId' AND `vendor` = '$namaVendor'");
$id_c = '';
foreach ($stocksTemp as $key => $stockTemp) {
    $id_produk = $stockTemp['id_produk'];
    $quantity = $stockTemp['quantity'];
    $idStockTemp = $stockTemp['id'];
    $unit = $stockTemp['unit'];
    $id_cover = $stockTemp['id_cover'];
    $tanggal_beli = $stockTemp['tanggal_beli'];
    $totalAsliNota = $stockTemp['totalAsliNota'];
    $id_c = $id_cover;
    $stock_awal = $db->query("SELECT * FROM `produk` WHERE `id` = '$id_produk'")->fetch_assoc()['stock'];
    $stock_baru = $stock_awal + $quantity;
    $harga = $stockTemp['harga'];
    $insertLogStock = $db->query("INSERT INTO `log_stock`(`created_at`, `id_produk`, `stok_awal`, `stok_baru`, `id_user`, `vendor`, `harga`,`unit`,`id_cover`,`tanggal_beli`,`totalAsliNota`) VALUES (CURRENT_TIMESTAMP(),'$id_produk','$stock_awal','$stock_baru','$userId','$namaVendor','$harga','$unit','$id_cover','$tanggal_beli','$totalAsliNota')");
    if ($insertLogStock) {
        $delete = $db->query("DELETE FROM `log_stock_temp` WHERE `id` = '$idStockTemp'");
    }
}
$update = $db->query("UPDATE `foto_nota_stock` SET `used` = 1 WHERE `id` = '$id_c'");
echo json_encode(array("message" => "INSERT INTO `log_stock`(`created_at`, `id_produk`, `stok_awal`, `stok_baru`, `id_user`, `vendor`, `harga`,`unit`) VALUES (CURRENT_TIMESTAMP(),'$id_produk','$stock_awal','$stock_baru','$userId','$namaVendor','$harga','$unit')"));