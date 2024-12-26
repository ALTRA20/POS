<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
$data = json_decode(file_get_contents("php://input"), true);
$user_id = $data['user_id'];
$nama_katalog = $data['nama_katalog'];
$insertKatalog = $db->query("INSERT INTO `katalog`(`nama_katalog`, `created_at`, `updated_at`, `user_id`) VALUES ('$nama_katalog',CURRENT_TIMESTAMP(),CURRENT_TIMESTAMP(),'$user_id')");
$katalogs = [];
$katalog = $db->query("SELECT * FROM `katalog` WHERE `nama_katalog` LIKE '%$name%'");
foreach ($katalog as $key => $ktg) {
    $katalogs[] = $ktg;
}
echo json_encode($katalogs);