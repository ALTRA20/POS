<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
header('Content-Type: application/json');

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Periksa apakah file diunggah
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['file']['tmp_name'];
        $fileType = $_FILES['file']['type'];

        // Validasi tipe file
        if ($fileType === 'text/csv') {
            $csvData = [];
            if (($handle = fopen($fileTmpPath, "r")) !== FALSE) {
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $csvData[] = $data;
                }
                fclose($handle);
            }
            if (trim(str_replace("'", "", $csvData[0][2])) == "0374059625") {
                // Hapus header atau bagian tidak relevan
                $csvDatas = array_slice($csvData, 5);

                function choseAlphabet($number) {
                    $huruf = ['Q','A', 'B', 'C', 'D', 'E', 'F', 'G', 'H','K'];
                    
                    // Pastikan $number adalah angka valid
                    return $huruf[$number];
                }
                
                // Fungsi untuk menghasilkan kode bayar unik
                function generateUniqueKodeBayar($db, $prefix = "W") {
                    // Ambil ID terakhir dari tabel menggunakan mysql_insert_id
                    $id_terakhir = mysqli_insert_id($db);
                
                    // Ubah $id_terakhir menjadi array digit
                    $ids_terakhir = str_split((string)$id_terakhir);
                    $kodeBayar = $prefix;
                
                    // Konversi setiap digit ke huruf
                    foreach ($ids_terakhir as $angka) {
                        $kodeBayar .= choseAlphabet((int)$angka);
                    }
                    $updateKode = $db->query("UPDATE `tr_bca` SET `kode_bayar` = '$kodeBayar' WHERE `id` = '$id_terakhir'");
                    return $kodeBayar;
                }

                // Fungsi untuk menentukan tanggal
                function getValidDate($rawDate, &$bufferDate) {
                    $rawDate = trim(str_replace("'", "", $rawDate));
                    if ($rawDate === "PEND") {
                        $date = (new DateTime($bufferDate))->modify('+1 day');
                        $dateFix = $date->format('Y-m-d');
                        return $dateFix;
                    }

                    $date = DateTime::createFromFormat('d/m/Y', $rawDate);
                    if ($date && $date->format('d/m/Y') === $rawDate) {
                        $bufferDate = $date->format('Y-m-d');
                        return $bufferDate;
                    }

                    return null;
                }

                $datasStatus = [];
                $bufferDate = '';

                // Fungsi untuk memproses baris CSV
                function processRow($db, $csvRow, $column, &$bufferDate) {
                    global $datasStatus;

                    $tanggal = getValidDate(trim($csvRow[0]), $bufferDate);
                    $keterangan = $csvRow[1] ?? '';
                    $status = $csvRow[4] ?? '';
                    $jumlah = explode('.', $csvRow[3])[0];

                    // Cek apakah data sudah ada di database
                    $stmt = $db->prepare("SELECT * 
                    FROM tr_bca 
                    WHERE tanggal_transaksi BETWEEN DATE_SUB(?, INTERVAL 2 DAY) AND DATE_ADD(?, INTERVAL 2 DAY) 
                    AND $column = ? LIMIT 1");

                    $stmt->bind_param("sss", $bufferDate, $bufferDate, $jumlah);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows === 0) {
                        $insert_stmt = $db->prepare("INSERT INTO `tr_bca` (`$column`, `keterangan`, `tanggal_transaksi`, `created_at`, `status`) VALUES (?, ?, ?, CURRENT_TIMESTAMP(), ?)");
                        $insert_stmt->bind_param("ssss", $jumlah, $keterangan, $tanggal, $status);
                        if ($insert_stmt->execute()) {
                            $updateKode = generateUniqueKodeBayar($db);
                            $csvRow['upload'] = false;
                            $csvRow['status'] = "berhasil";
                        } else {
                            $csvRow['upload'] = true;
                            $csvRow['status'] = "gagal";
                        }
                        $csvRow['sql'] = "INSERT INTO `tr_bca` (`$column`, `keterangan`, `tanggal_transaksi`, `created_at`, `status`) VALUES ('$jumlah', '$keterangan', '$tanggal', CURRENT_TIMESTAMP(), '$status')";

                        $csvRow['tanggal'] = $tanggal;
                        $datasStatus[] = $csvRow;
                    } else {
                        $stmt = $db->prepare("SELECT * 
                        FROM tr_bca 
                        WHERE tanggal_transaksi BETWEEN DATE_SUB(?, INTERVAL 2 DAY) AND DATE_ADD(?, INTERVAL 2 DAY) AND `keterangan` = ?
                        AND $column = ? LIMIT 1");

                        $stmt->bind_param("ssss", $bufferDate, $bufferDate, $keterangan, $jumlah);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($result->num_rows === 0) {
                            $csvRow['upload'] = true;
                        }else{
                            $csvRow['upload'] = false;
                        }
                        $csvRow['status'] = "duplicate";
                        $csvRow['tanggal'] = $tanggal;
                        $datasStatus[] = $csvRow;
                    }
                }

                // Proses semua baris CSV
                foreach ($csvDatas as $csvRow) {
                    if (isset($csvRow[0]) && $csvRow[0] !== "Saldo Awal" && $csvRow[1] !== "=") {
                        $column = ($csvRow[4] === "CR") ? "duit_in" : "duit_out";
                        processRow($db, $csvRow, $column, $bufferDate);
                    }
                }

                // Kirim hasil proses sebagai respons
                echo json_encode([
                    'status' => 'success',
                    'message' => 'File processed successfully!',
                    'datas' => $datasStatus,
                ]);
            }else{
                echo json_encode([
                    'status' => 'failed',
                    'message' => 'Nomor rekening berbeda!',
                    'datas' => $datasStatus,
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid file type. Only CSV files are allowed.',
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'File upload error.',
        ]);
    }
// } else {
//     echo json_encode([
//         'status' => 'error',
//         'message' => 'Invalid request method.',
//     ]);
// }
?>