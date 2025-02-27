<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(array("message" => "Invalid data format"));
    die;
}
$nama = $data['nama'];
$kategoriProduk = $data['kategoriProduk'];
$hargaJual = $data['hargaJual'];
$hargaBeli = $data['hargaBeli'];
$dimensi = $data['dimensi'];
$berat = $data['berat'];
$stock = $data['stock'];
$idBarcode = $data['idBarcode'];
$idParent = $data['idParent'];
$talkingPoint = mysqli_real_escape_string($db, $data['talkingPoint']);

// Melakukan pembersihan dan validasi data (contoh menggunakan mysqli_real_escape_string)
$nama = mysqli_real_escape_string($db, $nama);
$hargaBeli = mysqli_real_escape_string($db, $hargaBeli);
$dimensi = mysqli_real_escape_string($db, $dimensi);
$berat = mysqli_real_escape_string($db, $berat);
$stock = mysqli_real_escape_string($db, $stock);

$insert = $db->query("INSERT INTO `produk`(`nama`, `kategori`, `harga_jual`, `harga_jual_created_at`, `harga_beli`, `harga_beli_created_at`, `created_at`, `is_active`, `dimensi`, `berat`, `stock`,`id_barcode`,`id_parent`,`talking_point`) VALUES ('$nama','$kategoriProduk','$hargaJual',CURRENT_TIMESTAMP(),'$hargaBeli',CURRENT_TIMESTAMP(),CURRENT_TIMESTAMP(),1,'$dimensi','$berat','$stock','$idBarcode','$idParent','$talkingPoint')");

if ($insert) {
    $idProdukQuery = $db->query("SELECT `id` FROM `produk` WHERE `nama` = '$nama'");
    $idProdukResult = $idProdukQuery->fetch_assoc();
    $idProduk = $idProdukResult['id'];

    $hargaJuals = json_decode($hargaJual, true);
    $min_jumlah = PHP_INT_MAX; // Inisialisasi dengan nilai maksimum yang mungkin
    $jumlah_terkecil = null;

    foreach ($hargaJuals as $item) {
        $jumlah = intval($item['jumlah']);
        if ($jumlah < $min_jumlah) {
            $min_jumlah = $jumlah;
            $jumlah_terkecil = $item;
        }
    }
    $harga_jumlah_terkecil = intval($jumlah_terkecil['harga']);

    $isHargaJual = $db->query("SELECT * FROM `log_harga_jual` WHERE `id_produk` = '$idProduk' AND `harga_jual` = '$harga_jumlah_terkecil'")->num_rows > 0;
    if (!$isHargaJual) {
        $update = $db->query("INSERT INTO `log_harga_jual`(`id_produk`, `harga_jual`, `created_at`) VALUES ('$idProduk','$harga_jumlah_terkecil',CURRENT_DATE())");
    }
    echo json_encode(array("message" => "Success menambahkan barang"));
} else {
    echo json_encode(array("message" => "Gagal menambahkan barang"));
}

$db->close();
?>
