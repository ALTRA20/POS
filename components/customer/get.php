<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
$data = json_decode(file_get_contents("php://input"), true);
$name = rtrim($data['name']);
$names = explode(" ", $name);
$limit = 10;

$sql = "SELECT * FROM `customer` WHERE";
foreach ($names as $key => $name) {
    if ($key != 0) {
        $sql .= ' AND';
    }
    $sql .= " `nama` LIKE '%$name%'";
}
if ($limit) {
    $sql .= " LIMIT ".$limit;
}
$customers = $db->query($sql)->fetch_all();
echo json_encode($customers);