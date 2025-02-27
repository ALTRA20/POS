<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';

// Retrieve JSON data sent from JavaScript
$data = json_decode(file_get_contents("php://input"), true);

// Extract necessary fields from the received data
$id = $data["id"];
$jalurBayar = $data["jalurBayar"];
$tanggalBayar = $data["tanggalBayar"];
$nominalBayar = $data["nominalBayar"];

// Fetch the 'caraBawa' value from the database based on 'pesanan_id'
$caraBawaResult = $db->query("SELECT * FROM `bayar` WHERE `pesanan_id` = '$id' LIMIT 1");
$caraBawaRow = $caraBawaResult->fetch_assoc();
$caraBawa = isset($caraBawaRow['caraBawa']) ? $caraBawaRow['caraBawa'] : '';

// Insert the received data along with 'caraBawa' into the 'bayar' table
$insertBayar = $db->query("INSERT INTO `bayar`(`pesanan_id`, `jalur`, `nominal_bayar`, `caraBawa`, `created_at`, `is_verifikasi`) VALUES ('$id','$jalurBayar','$nominalBayar','$caraBawa','$tanggalBayar',1)");

// Retrieve the newly inserted data
$datasFix = [];
$bayarResult = $db->query("SELECT * FROM `bayar` WHERE `pesanan_id` = '$id'");
foreach ($bayarResult as $key => $bayar) {
    $idBayar = $bayar['id'];
    $nominal = $db->query("SELECT * FROM `tr_duit_masuk` WHERE `bayar_id` = '$idBayar'")->fetch_assoc()['nominal'];
    $bayar['duit_masuk'] = $nominal;
    $datasFix[] = $bayar;
}

// Construct response based on insertion success or failure
if ($insertBayar && $datasFix) {
    $response = [
        "message" => "success",
        "data" => $datasFix
    ];
} else {
    $response = [
        "message" => "failure"
    ];
}

// Send response back to JavaScript
echo json_encode($response);
?>
