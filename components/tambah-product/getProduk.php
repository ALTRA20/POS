<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';

$data = json_decode(file_get_contents("php://input"), true);
$search = $data['search'];
$limit = $data['limit'];

$sql = "SELECT * FROM `produk` WHERE `is_active` = 1";
if ($search) {
    $sql .= " AND `nama` LIKE '%$search%' OR `id` LIKE '%$search%'";
}
if ($limit) {
    $sql .= " LIMIT ".$limit;
}
$products = $db->query($sql);
$produks = [];
foreach ($products as $key => $product) {
    $produkId = $product['id'];
    $foto = $db->query("SELECT * FROM `foto` WHERE `id_produk` = '$produkId' AND `is_active` = 1")->fetch_assoc()['id'];
    $product['foto'] = $foto;
    $produks[] = $product;
}
echo json_encode($produks);