<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
$id = $_POST['id'];
$deskripsi = $_POST['deskripsi'];
$sql = "UPDATE `produk` SET `talking_point` = '$deskripsi' WHERE `id` = '$id'";
$update = $db->query($sql); 
echo "<script>window.location.href='/pages/assets/produk.php?id=".$id."'</script>";
?>