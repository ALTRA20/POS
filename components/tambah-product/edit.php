<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(array("message" => "Invalid data format"));
    die;
}

$id = $data['id'];
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

$data = json_decode($hargaJual, true);
$min_jumlah = PHP_INT_MAX; // Inisialisasi dengan nilai maksimum yang mungkin
$jumlah_terkecil = null;

foreach ($data as $item) {
    $jumlah = intval($item['jumlah']);
    if ($jumlah < $min_jumlah) {
        $min_jumlah = $jumlah;
        $jumlah_terkecil = $item;
    }
}
$harga_jumlah_terkecil = intval($jumlah_terkecil['harga']);

$isHargaJual = $db->query("SELECT * FROM `log_harga_jual` WHERE `id_produk` = '$id' AND `harga_jual` = '$harga_jumlah_terkecil'")->num_rows > 0;
if (!$isHargaJual) {
    $update = $db->query("INSERT INTO `log_harga_jual`(`id_produk`, `harga_jual`, `created_at`) VALUES ('$id','$harga_jumlah_terkecil',CURRENT_DATE())");
}

$update = $db->query("UPDATE `produk` SET `nama`='$nama',`harga_jual`='$hargaJual',`harga_jual_created_at`=CURRENT_TIMESTAMP(),`harga_beli`='$hargaBeli',`harga_beli_created_at`=CURRENT_TIMESTAMP(),`dimensi`='$dimensi',`berat`='$berat',`stock`='$stock',`id_barcode`='$idBarcode',`id_parent`='$idParent',`talking_point`='$talkingPoint' WHERE `id` = '$id'");

if ($update) {
    echo json_encode(array("message" => "Success mengedit barang"));
} else {
    echo json_encode(array("message" => "Gagal mengedit barang"));
}

$db->close();
?>
