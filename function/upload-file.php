<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/exc.php';
function uploadFile($file, $namaTagFile, $directory, $nameFileFixed){
    $targetDir = $_SERVER['DOCUMENT_ROOT'] . '/public/' . $directory . '/';

    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    $targetFile = $targetDir . $nameFileFixed;
    // echo $file[$namaTagFile]["name"];
    if (move_uploaded_file($file[$namaTagFile]["tmp_name"], $targetFile)) {
        exc();
        echo "success";
    } else {
        echo "Gagal mengunggah file.";
    }
}
?>