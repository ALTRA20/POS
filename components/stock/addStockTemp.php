<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
$data = json_decode(file_get_contents("php://input"), true);
$userId = $data['userId'];
$idProduk = $data['idProduk'];
if (!is_numeric($idProduk)) {
    $hargaJual = [["harga"=> "0", "jumlah"=> "1"]];
    $hargaJual = json_encode($hargaJual);
    $insert = $db->query("INSERT INTO `produk`(`nama`, `kategori`, `harga_jual`, `harga_jual_created_at`, `harga_beli`, `harga_beli_created_at`, `created_at`, `is_active`, `dimensi`, `berat`, `stock`) VALUES ('$idProduk','','$hargaJual',CURRENT_TIMESTAMP(),'0',CURRENT_TIMESTAMP(),CURRENT_TIMESTAMP(),1,'','','0')");
    if ($insert) {
        $id = $db->query("SELECT `id` FROM `produk` WHERE `nama` = '$idProduk'")->fetch_assoc()['id'];
        $idProduk = $id;
    }
}
$namaVendor = $data['namaVendor'];
$asliNota = $data['asliNota'];
$qty = $data['qty'];
$harbel = $data['harbel'];
$unit = $data['unit'];
$tanggalBeli = $data['tanggalBeli'];
$idFotoNota = $data['idFotoNota'];
$stockAwal = $db->query("SELECT `stock` FROM `produk` WHERE `id` = '$idProduk'")->fetch_assoc()['stock'];
$stockBaru = $stockAwal + $qty;
$insert = $db->query("INSERT INTO `log_stock_temp`(`user_id`, `id_produk`, `vendor`, `quantity`, `harga`, `unit`, `id_cover`, `tanggal_beli`,`totalAsliNota`) VALUES ('$userId','$idProduk','$namaVendor','$qty','$harbel','$unit','$idFotoNota','$tanggalBeli','$asliNota')");
if ($insert) {
    echo json_encode(array("message" => "Success menambahkan barang"));
} else {
    echo json_encode(array("message" => "INSERT INTO `log_stock_temp`(`user_id`, `id_produk`, `vendor`, `quantity`, `harga`, `unit`, `id_cover`, `tanggal_beli`) VALUES ('$userId','$idProduk','$namaVendor','$qty','$harbel','$unit','$idFotoNota','$tanggalBeli')"));
}