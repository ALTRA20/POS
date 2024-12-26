<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
$uploadDirectory = $_SERVER['DOCUMENT_ROOT'] . '/public/foto/lg/';
$messages = [];
$maxFileSize = 90 * 1024 * 1024; // 2 MB

if (!file_exists($uploadDirectory)) {
    mkdir($uploadDirectory, 0755, true);
}

$idProduk = isset($_POST['idProduk']) ? $_POST['idProduk'] : null;
$idUser = isset($_POST['idUser']) ? $_POST['idUser'] : null;

foreach ($_FILES['files']['tmp_name'] as $key => $tmpName) {
    $fileName = basename($_FILES['files']['name'][$key]);
    $fileType = mime_content_type($tmpName);
    $fileSize = $_FILES['files']['size'][$key];

    if ($_FILES['files']['error'][$key] !== UPLOAD_ERR_OK) {
        $messages[] = "Error uploading file: {$fileName}";
        continue;
    }

    if ($fileSize > $maxFileSize) {
        $messages[] = "File too large: {$fileName}";
        continue;
    }

    // Fetch the next ID
    $idResult = $db->query("SELECT COALESCE(MAX(`id`), 0) AS max_id FROM `foto`");
    $id = $idResult->fetch_assoc()['max_id'];
    $idNext = $id + 1;
    $is_video = 0;
    // Determine file extension and target file path
    $extension = '';
    if (strpos($fileType, 'image/') === 0) {
        $extension = 'jpg'; // Save images as JPG
    } elseif (strpos($fileType, 'video/') === 0) {
        $is_video = 1;
        $extension = 'mp4'; // Save videos as MP4
    } else {
        $messages[] = "Unsupported file type: {$fileName}";
        continue;
    }

    $targetFilePath = $uploadDirectory . $idNext . '.' . $extension;

    if (move_uploaded_file($tmpName, $targetFilePath)) {
        copy($targetFilePath, $_SERVER['DOCUMENT_ROOT'] . '/public/foto/md/' . $idNext . '.' . $extension);
        copy($targetFilePath, $_SERVER['DOCUMENT_ROOT'] . '/public/foto/sm/' . $idNext . '.' . $extension);
        // Insert using the retrieved values
        $insert = $db->query("INSERT INTO `foto` (`is_cover`, `id_user`, `id_produk`, `is_active`, `is_video`, `created_at`) VALUES (0, '$idUser', '$idProduk', 1, '$is_video', NOW())");

        if ($insert) {
            $messages[] = "Uploaded: $fileName";
        } else {
            $messages[] = "Database error: " . $db->error;
        }
    } else {
        $messages[] = "Failed to upload: $targetFilePath";
    }
}

header('Content-Type: application/json');
echo json_encode(['message' => implode(', ', $messages)]);
?>