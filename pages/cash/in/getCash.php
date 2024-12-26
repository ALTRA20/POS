<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
$data = json_decode(file_get_contents("php://input"), true);
$tanggal = $data['tanggal'];
$duit_masuks = $db->query("SELECT `tr_duit_masuk`.*, DATE(`tr_duit_masuk`.created_at) AS tanggal, `customer`.nama FROM `tr_duit_masuk` LEFT JOIN bayar ON `bayar`.`id` = `tr_duit_masuk`.`bayar_id` LEFT JOIN `pesanan` ON `pesanan`.`id` = `bayar`.`pesanan_id` LEFT JOIN `customer` ON `customer`.`id` = `pesanan`.`customer_id` WHERE DATE(`tr_duit_masuk`.`created_at`) = '$tanggal' AND `tr_duit_masuk`.kode_bayar IS NULL");
$cashIn = [];
foreach ($duit_masuks as $key => $duit_masuk) {
    $cashIn[] = $duit_masuk;
}
echo json_encode($cashIn);