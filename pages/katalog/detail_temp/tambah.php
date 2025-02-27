<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
$data = json_decode(file_get_contents("php://input"), true);
$katalog_id = $data['katalog_id'];
$product_id = $data['product_id'];
$markup = $data['markup'];
$userId = $data['userId'];

$katalog_detail = $db->query("SELECT * FROM `katalog_detail_temp` WHERE `katalog_id` = '$katalog_id' AND `produk_id` = '$product_id' AND `user_id` = '$userId' ORDER BY `katalog_id` DESC");
$is_available = $katalog_detail->num_rows > 0;
if(!$is_available){
    $urutan = $db->query("SELECT * FROM `katalog_detail_temp` WHERE `katalog_id` = '$katalog_id' AND `user_id` = '$userId' ORDER BY `urutan` DESC")->fetch_assoc()['urutan'];
    $urutan = $urutan + 1;
    $insertKatalog = $db->query("INSERT INTO `katalog_detail_temp`(`user_id`, `katalog_id`, `produk_id`, `markup`, `urutan`) VALUES ('$userId', '$katalog_id','$product_id','$markup','$urutan')");
}else{
    $katalog_detail = $katalog_detail->fetch_assoc();
    $urutan = $katalog_detail['urutan'];
    $updateKatalog = $db->query("UPDATE `katalog_detail_temp` SET `markup` = '$markup' WHERE `katalog_id` = '$katalog_id' AND `produk_id` = '$product_id' AND `user_id` = '$userId'");
}
echo json_encode([
    'status' => $is_available ? 'success' : 'success',
    'message' => $is_available ? 'Produk sudah ada di katalog' : 'Produk berhasil ditambahkan ke katalog',
    'data' => "INSERT INTO `katalog_detail_temp`(`user_id`, `katalog_id`, `produk_id`, `markup`, `urutan`) VALUES ('$userId', '$katalog_id','$product_id','$markup',1)"
]);
?>