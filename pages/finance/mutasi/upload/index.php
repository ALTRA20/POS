<?php
session_start();
$_SESSION['last_url'] = $_SERVER[REQUEST_URI];
?>
<?php include $_SERVER['DOCUMENT_ROOT'].'/components/header/index.php';?>
<?php
// die();
// function rupiah($number) {
//     if ($number) {
//         return 'Rp' . number_format($number, 0, ',', '.');
//     }
//     return null;
// }

?>
<section class="w-100 p-5 bg-primary-me h-fulll">
    <?php
    function choseAlphabet($number) {
        $huruf = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
        if ($number == 0) {
            return "X";
        }else{
            return $huruf[$number - 1];
        }
    }
    if (isset($_POST['btn-upload'])) {
        $fileTmpPath = $_FILES['file']['tmp_name'];

        // Validasi apakah file sudah diunggah
        if (is_uploaded_file($fileTmpPath)) {
            // Buka file dan baca isinya
            $csvData = [];
            if (($handle = fopen($fileTmpPath, "r")) !== FALSE) {
                // Loop setiap baris di dalam file CSV
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $csvData[] = $data; // Simpan sebagai array
                }
                fclose($handle);
            }
            
            // Hapus elemen indeks 0 hingga 4
            $csvDatas = array_slice($csvData, 5);

            // Tampilkan data untuk debugging
            foreach ($csvDatas as $key => $csvRow) {
                // Periksa apakah elemen pertama ($csvRow[0]) ada
                if (isset($csvRow[0])) {
                    if (isset($csvRow[0]) && $csvRow[0] === "Saldo Awal") {
                        break;
                    }
                    // Bersihkan elemen dari spasi dan tanda `'`
                    $cleanedValue = trim(str_replace("'", "", $csvRow[0]));
            
                    // Cek apakah nilai adalah tanggal
                    $date = DateTime::createFromFormat('d/m/Y', $cleanedValue);
                    $isDate = $date && $date->format('d/m/Y') === $cleanedValue;
                    // var_dump($csvRow);
                    if ($isDate) {
                        $tanggal = $date->format('Y-m-d'); // Konversi ke format database
                    } else {
                        $tanggal = DATE('Y-m-d');
                    }
                    $keterangan = $csvRow[1] ?? ''; // Pastikan kolom ada, default kosong
                    $cabang = trim(str_replace("'", "", $csvRow[2])) ?? '';
                    $jumlah = explode('.', $csvRow[3])[0];
                    $status_debit = $csvRow[4] ?? '';
                    $is_mutasi = $db->query("SELECT * FROM `tr_bca` WHERE `tanggal_transaksi` = '$tanggal' AND `nominal` = '$jumlah'")->num_rows > 0;
                    // echo "SELECT * FROM `tr_bca` WHERE `tanggal_transaksi` = '$tanggal' AND `nominal` = '$jumlah'".'<br>';
                    
                    // die();
                    if (!$is_mutasi) {
                        $kodeBayar = "W";
                        $kodes_bayar = [];
                    
                        // Ambil semua kode bayar dari database
                        $result = $db->query("SELECT `kode_bayar` FROM `tr_bca`");
                        if ($result) {
                            while ($row = $result->fetch_assoc()) {
                                $kodes_bayar[] = $row['kode_bayar'];
                            }
                        }
                    
                        // Generate kode unik
                        do {
                            $kodeBayar = "W" . choseAlphabet(rand(0, 25)) . choseAlphabet(rand(0, 25)) . choseAlphabet(rand(0, 25));
                        } while (in_array($kodeBayar, $kodes_bayar));
                    
                        // Insert ke database
                        $db->query("INSERT INTO `tr_bca`(`kode_bayar`, `nominal`, `keterangan`, `tanggal_transaksi`, `created_at`) 
                                    VALUES ('$kodeBayar', '$jumlah', '$keterangan', '$tanggal', CURRENT_TIMESTAMP())");
                    }                    
                    echo "<script>window.location.href = window.location.href</script>";
                } else {
                    echo "Kolom pertama kosong atau tidak tersedia.\n";
                }
            }                      
        } else {
            echo "<p class='text-danger'>Error: File tidak dapat diunggah.</p>";
        }
    }
    ?>
    <div class="w-100 d-flex justify-content-between align-items-center my-4">
        <?php 
        $dateNow = date("Y-m-d");
        $yesterday = date("Y-m-d", strtotime("-1 days"));
        $date = $dateNow;
        if (isset($_GET['date'])) {
            $date = $_GET['date'];
        }
        $mutasis = $db->query("SELECT * FROM `tr_bca` WHERE `tanggal_transaksi` = '$date'");
        ?>
        <h2 class="">Mutasi Rekening <?=($date == $dateNow) ? "<span class='text-warning'>Hari Ini</span>" : "Tanggal <span class='text-warning'>$date</span>" ?></h2>
        <div class="d-flex gap-3">
            <?php if ($date == $dateNow) : ?>
                <a href="?date=<?=$yesterday?>" class="btn btn-success">Kemarin</a>
            <?php else : ?>
                <a href="?date=<?=$dateNow?>" class="btn btn-success">Hari Ini</a>
            <?php endif; ?>
            <input type="date" id="date" class="w-fit bg-light form-control">
        </div>
    </div>
    <div class="row border-bottom">
        <div class="col-1 p-4">Kode Bayar</div>
        <div class="col-1 p-4">tanggal transaksi</div>
        <div class="col-2 p-4">Created At</div>
        <div class="col-7 p-4">Keterangan</div>
        <div class="col-1 p-4">Nominal</div>
    </div>
    <div class="row mb-4">
        <?php foreach ($mutasis as $key => $mutasi) : ?>
            <div class="col-1 p-4"><?=$mutasi['kode_bayar']?></div>
            <div class="col-1 p-4"><?=$mutasi['tanggal_transaksi']?></div>
            <div class="col-2 p-4"><?=$mutasi['created_at']?></div>
            <div class="col-7 p-4"><?=$mutasi['keterangan']?></div>
            <div class="col-1 p-4"><?=$mutasi['nominal']?></div>
        <?php endforeach ?>
    </div>
    <div class="w-100 d-flex justify-content-center">
        <!-- Button trigger modal -->
        
        <div class="pointer" data-bs-toggle="modal" data-bs-target="#exampleModal">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z"/>
            </svg>
        </div>

        <!-- Modal -->
        <div class="modal fade text-primary-me" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Upload Mutasi</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="modal-body" action="" method="POST" enctype="multipart/form-data">
                    <input type="file" class="form-control text-primary-me" name="file" accept="csv" oninput="validasi(this)">
                    <div class="w-100 d-flex justify-content-end mt-3 d-none" id="mutasiBtn">
                        <button class="btn btn-success bg-primary-me px-4" name="btn-upload">Upload</button>
                    </div>
                </form>
            </div>
        </div>
        </div>
    </div>
</section>
<script>
    function validasi(input) {  
        console.log(input);
        if (input.value != "") {
            document.getElementById('mutasiBtn').classList.remove('d-none');
        }
    }
    document.getElementById('date').addEventListener('change', function() {
        window.location.href = `http://localhost/pages/finance/mutasi/upload/?date=${this.value}`
    })
</script>