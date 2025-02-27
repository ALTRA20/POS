<?php 
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
$data = json_decode(file_get_contents("php://input"), true);

$bank = $data['bank'];

if ($bank == 'bca') {
    $sql = "SELECT * FROM tr_".$bank;
    // Periksa apakah ada parameter `n` di URL
    if (isset($data['n'])) {
        $nominal = $data['n'];
        
        // Tentukan tambahan query berdasarkan apakah `WHERE` sudah ada atau belum
        if (stripos($sql, 'WHERE') !== false) {
            $sqlAdd = ' AND (`duit_in` LIKE ? OR `keterangan` LIKE ?)';
        } else {
            $sqlAdd = ' WHERE (`duit_in` LIKE ? OR `keterangan` LIKE ?)';
        }
        
        $sql .= $sqlAdd;
        $sql .= " AND `status` != 'DB' ";
        
        // Tambahkan ORDER BY di akhir
        $sql .= ' ORDER BY id DESC';
        
        // Siapkan query
        $stmt = $db->prepare($sql);
    
        // Parameter untuk `LIKE`
        $likeNominal = '%' . $nominal . '%'; // Menambahkan '%' di awal dan akhir
    
        // Bind parameter
        $stmt->bind_param('ss', $likeNominal, $likeNominal);
    
        // Debugging Query (Optional)
        $sqlDebug = $sql;
        $sqlDebug = str_replace('?', "'$likeNominal'", $sqlDebug);
    
        // Eksekusi query
        $stmt->execute();
    
        // Ambil hasil query
        $result = $stmt->get_result();
        $mutasis = [
            "data" => $result->fetch_all(MYSQLI_ASSOC),
            "sql" => $sqlDebug,
        ];
    } else {
        // Jika tidak ada parameter `n`, jalankan query tanpa tambahan
        $mutasis = [
            "data" => $db->query($sql . " WHERE `status` != 'DB' ORDER BY `id` DESC")->fetch_all(MYSQLI_ASSOC),
            "sql" => $sql . " WHERE `status` != 'DB' ORDER BY `id` DESC",
        ];
    }
}else{
    $nominal = $data['n'];
    $sql = "SELECT *, tr_$bank.id AS splitId, tr_$bank.nominal AS nominalSplit, tr_$bank.id_bayar AS idBayarSplit, tr_$bank.kode_bayar AS kb FROM tr_".$bank;
    $mutasis = [
        "data" => $db->query($sql . " JOIN tr_bca ON tr_bca.id = tr_split.tr_id WHERE `tr_split`.nominal LIKE '%$nominal%' ORDER BY `tr_split`.`id` DESC")->fetch_all(MYSQLI_ASSOC),
        "sql" => $sql . " JOIN tr_bca ON tr_bca.id = tr_split.tr_id WHERE `tr_split`.nominal LIKE '%$nominal%' ORDER BY `tr_split`.`id` ",
    ];
}


// Kembalikan hasil sebagai JSON
echo json_encode($mutasis);
?>