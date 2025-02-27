<?php 
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
$data = json_decode(file_get_contents("php://input"), true);

$search = $data['search'];

$result = $db->query("SELECT `bayar`.*, `customer`.`nama` FROM `bayar` LEFT JOIN `tr_duit_masuk` ON tr_duit_masuk.bayar_id = bayar.id LEFT JOIN `pesanan` ON `pesanan`.`id` = `bayar`.`pesanan_id` LEFT JOIN `customer` ON `customer`.id = `pesanan`.`customer_id` WHERE `bayar`.`nominal_bayar` = '$search' AND `bayar`.`jalur` != 'Cash' AND `tr_duit_masuk`.`nominal` IS NULL ORDER BY `bayar`.`id` DESC")->fetch_all(MYSQLI_ASSOC);


// Kembalikan hasil sebagai JSON
echo json_encode($result);
?>