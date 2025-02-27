<?php
if ($_FILES) {
    $targetDir = $_SERVER['DOCUMENT_ROOT']."/public/foto/temp/"; // Direktori tujuan penyimpanan file
    $targetFile = $targetDir . basename($_FILES["file"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Pengecekan jenis file
    $check = getimagesize($_FILES["file"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        echo json_encode(["status" => "Error", "message" => "File is not an image."]);
        $uploadOk = 0;
    }

    // Pengecekan ukuran file
    if ($_FILES["file"]["size"] > 1000000) {
        echo json_encode(["status" => "Error", "message" => "Sorry, your file is too large."]);
        $uploadOk = 0;
    }

    // Pengecekan format file
    $allowedTypes = ["jpg", "jpeg", "png", "gif", "mpeg", "mov", "avi", "mp4"];
    if (!in_array($imageFileType, $allowedTypes)) {
        echo json_encode(["status" => "Error", "message" => "Sorry, only JPG, JPEG, PNG, GIF, MPEG, MOV, AVI & MP4 files are allowed."]);
        $uploadOk = 0;
    }

    // Jika file valid, upload
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
            echo $_FILES["file"]["name"];
        } else {
            echo json_encode(["status" => "Error", "message" => "Sorry, there was an error uploading your file."]);
        }
    }
}else{
    include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
    session_start();
    $username = $_SESSION["username"];
    $user = $db->query("SELECT * FROM `user` WHERE `username` = '$username'")->fetch_assoc();
    $jabatan = $user['jabatan'];
    $userId = $user['id'];
    
    $data = json_decode(file_get_contents("php://input"), true);
    $namaProduk = mysqli_real_escape_string($db, $data['namaProduk']);
    $idCustomer = $data['idCustomer'];
    $jumlah = $data['jumlah'];
    $hargaAsli = $data['harga'];
    $harga = $data['harga']/$jumlah;
    $fotoProduk = $data['fotoProduk'];
    $idProduk = '';

    $array_data = array(
        array("harga" => $harga, "jumlah" => "1")
    );
    $hargaJual = json_encode($array_data);
    $insertKeranjang = $db->query("INSERT INTO `keranjang`(`user_id`, `id_customer`, `product_id`, `jumlah`, `request`, `note`, `created_at`,`komentar`,`harga`,`foto`) VALUES ('$userId','$idCustomer',null,'$jumlah',null,'',CURRENT_TIMESTAMP(),'$namaProduk','$hargaAsli','$fotoProduk')");
    echo json_encode(array("message" => "Success"));
}