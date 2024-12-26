<?php
header('Content-Type: application/json');

include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['idUser'])) {
    echo json_encode(['error' => 'ID pengguna tidak diberikan']);
    exit;
}

$userId = $data['idUser'];

try {
    $keranjangs = $db->query("SELECT 
        `keranjang`.*,
        `produk`.`nama` AS produk_nama,
        `produk`.`harga_jual`,
        `foto`.`id` AS foto,
        `customer`.`nama` AS customer_nama,
        `customer`.`alamat`,
        `customer`.`wa`
    FROM `keranjang` 
    LEFT JOIN `produk` ON `produk`.id = `keranjang`.`product_id` 
    LEFT JOIN `foto` ON `foto`.id_produk = `keranjang`.`product_id` AND `foto`.`is_cover` = 1
    LEFT JOIN `customer` ON `customer`.id = `keranjang`.`id_customer`
    WHERE `keranjang`.`user_id` = '$userId' AND `keranjang`.komentar IS NULL 
    ORDER BY `keranjang`.id ASC");

    $keranjang_list = [];
    foreach ($keranjangs as $keranjang) {
        $keranjang_list[] = $keranjang;
    }

    echo json_encode($keranjang_list);

} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
