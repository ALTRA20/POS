<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
include $_SERVER['DOCUMENT_ROOT'].'/function/exc.php';

$data = json_decode(file_get_contents("php://input"), true);
$id_customer = $data['id_customer'];
$userId = $data['userId'];
$product_id = $data['product_id'];
$jumlah = $data['jumlah'];
$request = $data['request'];
$note = $data['note'];
$markup = $data['markup'];
$keranjang = $db->query("SELECT * FROM `keranjang` WHERE `user_id` = '$userId'")->fetch_assoc();
$costumer_id_from_db = $keranjang['id_customer'];
$note_from_db = $keranjang['note'];

$special_price = $db->query("SELECT * FROM `special_price` WHERE `user_id` = '$id_customer' AND `id_produk` = '$product_id'");
if ($special_price->num_rows > 0) {
    $markup = $special_price->fetch_assoc()['markup'];
}

$keranjang = $db->query("SELECT * FROM `keranjang` WHERE `product_id` = '$product_id' AND `user_id` = '$userId'");
$is_available = $keranjang->num_rows > 0;
if (!$is_available) {
    $delete = $db->query("DELETE FROM `keranjang` WHERE `product_id` IS NULL AND `komentar` IS NULL AND `user_id` = '$userId'");
    if(count(json_decode($request)) == 0){
        $sql = "INSERT INTO `keranjang`(`user_id`, `id_customer`, `product_id`, `jumlah`, `markup`, `note`, `created_at`) VALUES ('$userId','$id_customer','$product_id','$jumlah','$markup','$note_from_db',CURRENT_TIMESTAMP())";
    }else{
        $sql = "INSERT INTO `keranjang`(`user_id`, `id_customer`, `product_id`, `jumlah`, `markup`, `request`, `note`, `created_at`) VALUES ('$userId','$id_customer','$product_id','$jumlah','$markup','$request','$note_from_db',CURRENT_TIMESTAMP())";
    }
}else{
    $sql = "UPDATE `keranjang` SET `user_id`='$userId',`product_id`='$product_id',`jumlah`='$jumlah',`note`='$note_from_db',`markup`='$markup'";
    if(count(json_decode($request)) != 0){
        $sql .= ",`request`='$request'";
    }
    $sql .= "WHERE `product_id` = '$product_id' AND `user_id` = '$userId'";
}

$query = $db->query($sql);
if ($query) {
    exc();
    echo "Berhasil mengubah data";
}else{
    echo $sql;
}
?>
