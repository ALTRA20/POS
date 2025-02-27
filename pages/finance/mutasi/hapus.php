<?php 
header('Content-Type: application/json');

include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id'];
$hapus = $db->query("UPDATE `tr_bca` SET `is_active`= 0 WHERE `id` = $id");

echo json_encode($mutasis);
?>