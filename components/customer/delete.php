<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
$id = $_GET['id'];
// var_dump($id);
$delete = $db->query("DELETE FROM `customer` WHERE `id` = '$id'");
if ($delete) {
    echo "<script>window.location.href = '/pages/customer/'</script>";
}