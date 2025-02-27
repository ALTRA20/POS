<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
$id = $_GET['ip'];
$delete = $db->query("UPDATE `produk` SET `is_active` = 0 WHERE `id` = '$id'");
$nama = $db->query("SELECT `nama` FROM `produk` WHERE `id` = '$id'")->fetch_assoc()['nama'];
if ($delete) {
    echo "<script>alert('Success menghapus produk'".$nama.")</script>";
    echo "<script>window.location.href= '/'</script>";
}else{
    echo "<script>alert('Success menghapus produk'".$nama.")</script>";
    echo "<script>window.location.href= '/'</script>";
}