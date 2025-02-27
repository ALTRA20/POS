<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';

$data = json_decode(file_get_contents("php://input"), true);
$userId = $data['idUser'];
$datas = [];

$id_history = $db->query("SELECT * FROM `stock_history_qr` WHERE `user_id` = '$userId' AND `is_done` = 0");
if ($id_history->num_rows > 0) {
  $id_history = $id_history->fetch_assoc()['id'];
  $stockQr = $db->query("SELECT *, produk.id AS product_id, stock_history_qr_detail.id AS id FROM `stock_history_qr_detail` JOIN `produk` ON  `produk`.id = `stock_history_qr_detail`.produk_id WHERE `stock_history_qr_id` = '$id_history'")->fetch_all(MYSQLI_ASSOC);
  foreach ($stockQr as $key => $stock) {
    $id = $stock['produk_id'];
    $foto = $db->query("SELECT * FROM `foto` WHERE `id_produk` = '$id' AND `is_cover` = 1");
    if ($foto->num_rows > 0) {
      $foto = $foto->fetch_assoc()['id'];
    }else{
      $foto = $db->query("SELECT * FROM `foto` WHERE `id_produk` = '$id' LIMIT 1");
      $foto = $foto->fetch_assoc()['id'];
    }
    $stock['foto'] = $foto;
    $datas[] = $stock;
  }
}

echo json_encode($datas);