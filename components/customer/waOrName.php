<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
$data = json_decode(file_get_contents("php://input"), true);
$input = $data['input'];
$customers = $db->query("SELECT * FROM `customer` WHERE `nama` LIKE '%$input%' OR `wa` LIKE '%$input%'")->fetch_all();
echo json_encode($customers);