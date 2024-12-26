<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
$jsonData = file_get_contents("php://input");
$data = json_decode($jsonData, true);
$name = $data['name'];
$katalogs = [];
$katalog = $db->query("SELECT * FROM `katalog` WHERE `nama_katalog` LIKE '%$name%' ORDER BY id DESC");
foreach ($katalog as $key => $ktg) {
    $katalogs[] = $ktg;
}
echo json_encode($katalogs);
?>