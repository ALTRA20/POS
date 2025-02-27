<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
$jsonData = file_get_contents("php://input");
$data = json_decode($jsonData, true);
$userId = $data['userId'];
$search = $data['search'];
$charPertama = $search[0];

$itemsPerPage = 4;
$page = isset($data['page']) ? intval($data['page']) : 1;
$offset = ($page - 1) * $itemsPerPage;

$sql = '';

if ($search) {
    if ($search[0] == "#") {
        $search = str_replace("#","",$search);
        $sql = "SELECT `produk`.*,`jumlah`,`request`,`keranjang`.markup FROM `produk` LEFT JOIN `keranjang` ON `keranjang`.`product_id` = `produk`.`id` AND `keranjang`.`user_id` = '$userId' WHERE `produk`.`id` = '$search' AND `produk`.`id_barcode` IS NOT NULL AND `produk`.is_active = 1 ORDER BY `produk`.`created_at` DESC";
    }else{
        $searchFix = '';
        $searchs = explode(' ', $search);
        foreach ($searchs as $key => $search) {
            if ($key != 0) {
                $searchFix .= " AND";
            }
            $searchFix .= "`produk`.nama LIKE '%$search%'";
        }

        $sql = "SELECT `produk`.*,`jumlah`,`request`,`keranjang`.markup FROM `produk` LEFT JOIN `keranjang` ON `keranjang`.`product_id` = `produk`.`id` AND `keranjang`.`user_id` = '$userId' WHERE ($searchFix OR `produk`.`id_barcode` = '$search') AND `produk`.`id_barcode` IS NOT NULL AND `produk`.is_active = 1 ORDER BY `produk`.`created_at` DESC";
    }
}else{
    $sql = "SELECT `produk`.*,`jumlah`,`request`,`keranjang`.markup FROM `produk` LEFT JOIN `keranjang` ON `keranjang`.`product_id` = `produk`.`id` AND `keranjang`.`user_id` = '$userId' WHERE `produk`.is_active = 1 AND `produk`.`id_barcode` IS NOT NULL OR `produk`.`id_barcode` = '$search' ORDER BY `produk`.`created_at` DESC";
}

$sqlJumlah = $db->query($sql)->num_rows;
$sql = $sql." LIMIT $offset, $itemsPerPage";

$produks = $db->query($sql);
$produkOri = [];
foreach ($produks as $key => $produk) {
    $produkOri[] = $produk;
};
foreach ($produks as $key => $produk) {
    $idProduk = $produk['id'];
    $id_parent = $produk['id_parent'];
    $gambars = $db->query("SELECT * FROM `foto` WHERE `id_produk` = '$idProduk' AND `is_active` = 1 ORDER BY `is_cover` DESC")->fetch_all();
    $parent = $db->query("SELECT * FROM `produk` LEFT JOIN `foto` ON `foto`.id_produk = `produk`.id WHERE `produk`.`id` = '$id_parent'")->fetch_all();
    $stock = $db->query("SELECT * FROM `log_stock` WHERE `id_produk` = '$idProduk' AND `is_verif` = 1 ORDER BY `id` DESC")->fetch_assoc()['tanggal_beli'];
    $produkOri[$key]["fotoProduk"] = $gambars;
    $produkOri[$key]["parent"] = $parent;
    $produkOri[$key]["tanggal_beli_stock"] = $stock;
}
// var_dump($produkOri);
$dataFixed = [
    "sql" => $sql,
    "jumlahPage" => $sqlJumlah/$itemsPerPage,
    "pegeNow" => $page,
    "datas" => $produkOri
];
// Send the result back to the JavaScript application
echo json_encode($dataFixed);
?>