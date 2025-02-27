<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/exc.php';
$jsonString = '{
    "id_costumer": "2",
    "nominal_pemesanan": "108200",
    "dataBarang": [
        {
            "harga-Jual": "17900",
            "jumlah": "3",
            "produk_id": "28"
        },
        {
            "harga-Jual": "17500",
            "jumlah": "1",
            "produk_id": "26"
        },
        {
            "harga-Jual": "16900",
            "jumlah": "1",
            "produk_id": "24"
        },
        {
            "harga-Jual": "14900",
            "jumlah": "1",
            "produk_id": "25"
        },
        {
            "harga-Jual": "4200",
            "jumlah": "1",
            "produk_id": "31"
        }
    ]
}';
$json = json_decode($jsonString, true);
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
$data = json_decode(file_get_contents("php://input"), true);
$id_costumer = $data['id_costumer'];
$dataBarang = $data['dataBarang'];
$nominal_pemesanan = $data['nominal_pemesanan'];
$note = $data['note'];
$dataBarangs = $data['dataBarang'];
$userId = $data['userId'];
$id_produks = '';

$id_pesanan = '';
$pesanan = $db->query("SELECT * FROM `pesanan` WHERE `user_id` = '$userId' AND `nominal_pesanan` IS NULL");
$availabel_pesanan = $pesanan->num_rows > 0;
if ($availabel_pesanan) {
    $id_pesanan = $pesanan->fetch_assoc()['id'];
    $update = $db->query("UPDATE `pesanan` SET `nominal_pesanan` = '$nominal_pemesanan' WHERE `user_id` = '$userId' AND `nominal_pesanan` IS NULL");
    $hapus_pesanan_detail = $db->query("DELETE FROM `pesanan_detail` WHERE `pesanan_id` = '$id_pesanan'");
}else{
    $insertPesan = $db->query("INSERT INTO `pesanan`(`customer_id`, `user_id`, `created_at`, `nominal_pesanan`,`note`) VALUES ('$id_costumer','$userId',CURRENT_TIMESTAMP(),'$nominal_pemesanan','$note')");
    $id_pesanan = $db->query("SELECT `id` FROM `pesanan` WHERE `customer_id` = '$id_costumer' AND `user_id` = '$userId' AND `nominal_pesanan` = '$nominal_pemesanan' ORDER BY `id` DESC")->fetch_assoc()['id'];
}

//produk custom
$keranjangs = $db->query("SELECT * FROM `keranjang` WHERE `user_id` = '$userId' AND `komentar` IS NOT NULL");
foreach ($keranjangs as $key => $keranjang) {
    $markup = $keranjang['markup'];
    $komentar = $keranjang['komentar'];
    $jumlah = intval($keranjang['jumlah']);
    $foto = $keranjang['foto'];
    $hargaJualCustom = intval($keranjang['harga']);
    $insert = $db->query("INSERT INTO `pesanan_detail`(`produk_id`, `pesanan_id`, `harga_jual`, `jumlah`, `subtotal`,`request`,`markup`,`komentar`,`foto`) VALUES (null,'$id_pesanan','$hargaJualCustom','$jumlah',NULL,null,'$markup','$komentar','$foto')");
    // echo "INSERT INTO `pesanan_detail`(`produk_id`, `pesanan_id`, `harga_jual`, `jumlah`, `subtotal`,`request`,`komentar`) VALUES (null,'$id_pesanan','$hargaJualCustom','$jumlah',NULL,null,'$komentar')";
}
foreach ($dataBarangs as $key => $dataBarang) {
    $produk_id = $dataBarang['produk_id'];
    $jumlah = $dataBarang['jumlah'];
    $hargaJual = $dataBarang['harga-Jual'];
    $markUp = $db->query("SELECT `markup` FROM `keranjang` WHERE `product_id` = '$produk_id'")->fetch_assoc()['markup'];
    // echo "SELECT `request` FROM `keranjang` WHERE `product_id` = '$produk_id'";
    // die();
    $request = $db->query("SELECT `request` FROM `keranjang` WHERE `product_id` = '$produk_id'")->fetch_assoc()['request'];
    if ($request != '') {
        $insert = $db->query("INSERT INTO `pesanan_detail`(`produk_id`, `pesanan_id`, `harga_jual`, `jumlah`, `subtotal`,`request`,`markup`) VALUES ('$produk_id','$id_pesanan','$hargaJual','$jumlah',NULL,'$request','$markUp')");
    }else{
        $insert = $db->query("INSERT INTO `pesanan_detail`(`produk_id`, `pesanan_id`, `harga_jual`, `jumlah`, `subtotal`,`request`,`markup`) VALUES ('$produk_id','$id_pesanan','$hargaJual','$jumlah',NULL,NULL,'$markUp')");
    }
    if ($key != 0) {
        $id_produks .=  ','.$produk_id;
    }else{
        $id_produks .=  $produk_id;
    }
}

    $cleaning_keranjang = $db->query("DELETE FROM `keranjang` WHERE `user_id` = '$userId'");
    if ($cleaning_keranjang) {
        
    }
exc();
// echo "INSERT INTO `pesanan_detail`(`produk_id`, `pesanan_id`, `harga_jual`, `jumlah`, `subtotal`,`request`,`markup`) VALUES ('$produk_id','$id_pesanan','$hargaJual','$jumlah',NULL,'$request','$markUp')";
echo $id_pesanan;   