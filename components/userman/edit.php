<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';

$data = json_decode(file_get_contents("php://input"), true);
$idUser = $data['idUser'];
$username = $data['username'];
$jabatan = $data['jabatan'];
$password_real = $data['password_real'];
if ($data['password_real']) {
    $password = md5($data['password']);
    $update = $db->query("UPDATE `user` SET `username`='$username',`password_real`='$password_real',`password`='$password',`jabatan`='$jabatan',`updated_at`= CURRENT_TIMESTAMP() WHERE `id` = '$idUser'");
    echo "success";
}else{
    $update = $db->query("UPDATE `user` SET `username`='$username',`jabatan`='$jabatan',`updated_at`= CURRENT_TIMESTAMP() WHERE `id` = '$idUser'");
}
if ($update) {
    echo "success";
}else{
    echo "gagal";
}