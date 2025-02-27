<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/exc.php';
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
$data = json_decode(file_get_contents("php://input"), true);
$id_pesanan = $data['id_pesanan'];
$pesanan = $db->query("SELECT `customer_id`,`user_id`,`note` FROM `pesanan` WHERE `id` = '$id_pesanan'")->fetch_assoc();
$customer_id = $pesanan['customer_id'];
$user_id = $pesanan['user_id'];
$hapus_all = $db->query("DELETE FROM `keranjang` WHERE `user_id` = '$user_id'");
$note = $pesanan['note'];
$pesanan_details = $db->query("SELECT * FROM `pesanan_detail` WHERE `pesanan_id` = '$id_pesanan'");

$ids_pesanan_detail = '';
foreach ($pesanan_details as $key => $pesanan_detail) {
    $idpd = $pesanan_detail['id'];
    $produk_id = $pesanan_detail['produk_id'];
    $jumlah = $pesanan_detail['jumlah'];
    $request = $pesanan_detail['request'];
    $harga_jual = $pesanan_detail['harga_jual'];
    $markup = $pesanan_detail['markup'];
    $komentar = $pesanan_detail['komentar'];
    if (!$pesanan_detail['produk_id'] == '') {
        if ($request == '') {
            $insertKeranjang = $db->query("INSERT INTO `keranjang`(`user_id`, `id_customer`, `product_id`, `jumlah`, `request`, `markup`, `note`, `created_at`) VALUES ('$user_id','$customer_id','$produk_id','$jumlah',null,'$markup','$note',CURRENT_TIMESTAMP())");
        }else{
            $insertKeranjang = $db->query("INSERT INTO `keranjang`(`user_id`, `id_customer`, `product_id`, `jumlah`, `request`, `markup`, `note`, `created_at`) VALUES ('$user_id','$customer_id','$produk_id','$jumlah','$request','$markup','$note',CURRENT_TIMESTAMP())");
        }
        if ($key != 0) {
            $ids_pesanan_detail .= ','.$idpd;
        }else{
            $ids_pesanan_detail .= $idpd;
        }
    }else{
        $insertKeranjang = $db->query("INSERT INTO `keranjang`(`user_id`, `id_customer`, `product_id`, `jumlah`, `request`, `markup`, `note`, `created_at`,`komentar`,`harga`) VALUES ('$user_id','$customer_id',null,'$jumlah',null,'$markup','$note',CURRENT_TIMESTAMP(),'$komentar','$harga_jual')");
    }
}
exc();
if ($ids_pesanan_detail != '') {
    $hapus_pesanan = $db->query("UPDATE `pesanan` SET `nominal_pesanan` = NULL WHERE `id` = '$id_pesanan'");
    if ($hapus_pesanan) {
        // $hapus_pesanan_detail = $db->query("DELETE FROM `pesanan_detail` WHERE `id` IN ($ids_pesanan_detail)");
        echo "success";
    }
}