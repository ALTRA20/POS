<?php include $_SERVER['DOCUMENT_ROOT'].'/components/header/index.php'; 
if(!isset($_SESSION["username"])){
    echo "<script>window.location.href = '/pages/login.php'</script>";
}
function menentukanHarga($jumlahDibeli, $listHarga) {
    // Decode harga_jual dari format JSON
    $hargaList = json_decode($listHarga, true);
    
    // Mengurutkan array hargaList berdasarkan jumlah secara descending
    usort($hargaList, function($a, $b) {
        return $b['jumlah'] - $a['jumlah'];
    });
    
    $hargaSatuan = 0;
    foreach ($hargaList as $harga) {
        if ((int)$jumlahDibeli >= (int)$harga['jumlah']) {
            $hargaSatuan = $harga['harga'];
            break; // Exit the loop once the appropriate price is found
        }
    }
    
    return $hargaSatuan;
}
$_SESSION['last_url'] = $_SERVER[REQUEST_URI];
$katalog_id = $_GET['i'];
$katalog = $db->query("SELECT * FROM `katalog` WHERE `id` = '$katalog_id'")->fetch_assoc();
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
<script>
    document.title = 'History GS';
</script>
<style>
    .fz-me{
        font-size: 1.2rem;
        font-weight: bold;
    }
    .fz-me-2{
        font-weight: bold;
        font-size: 0.8rem;
    }
    @media only screen and (max-width: 600px) {
        .fz-me{
            font-size: 1rem;
        }
    }
    @media print {
        .no-print {
            display: none;
        }
    }
</style>
<div class="w-100 d-flex justify-content-center p-0">
    <div class="alert alert-dark p-3 px-4 rounded-0" id="content" style="width:900px; height: 69em;">
        <div class="w-100 d-flex justify-content-end gap-3 no-print">
            <div class="w-fit pointer" onclick="generateJPG()">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-card-image" viewBox="0 0 16 16">
                    <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
                    <path d="M1.5 2A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2zm13 1a.5.5 0 0 1 .5.5v6l-3.775-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12v.54L1 12.5v-9a.5.5 0 0 1 .5-.5z"/>
                </svg>
            </div>
            <div class="w-fit pointer" onclick="generatePDF()">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-filetype-pdf text-dark" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5zM1.6 11.85H0v3.999h.791v-1.342h.803q.43 0 .732-.173.305-.175.463-.474a1.4 1.4 0 0 0 .161-.677q0-.375-.158-.677a1.2 1.2 0 0 0-.46-.477q-.3-.18-.732-.179m.545 1.333a.8.8 0 0 1-.085.38.57.57 0 0 1-.238.241.8.8 0 0 1-.375.082H.788V12.48h.66q.327 0 .512.181.185.183.185.522m1.217-1.333v3.999h1.46q.602 0 .998-.237a1.45 1.45 0 0 0 .595-.689q.196-.45.196-1.084 0-.63-.196-1.075a1.43 1.43 0 0 0-.589-.68q-.396-.234-1.005-.234zm.791.645h.563q.371 0 .609.152a.9.9 0 0 1 .354.454q.118.302.118.753a2.3 2.3 0 0 1-.068.592 1.1 1.1 0 0 1-.196.422.8.8 0 0 1-.334.252 1.3 1.3 0 0 1-.483.082h-.563zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638z"/>
                </svg>
            </div>
        </div>
        <div class="row border-0 rounded-0 text-dark m-0 pb-0 alert alert-dark border-0 rounded-0 p-0 m-0">
            <div class="col-md-10 justify-content-end p-0">
                <h2 class="text-uppercase m-0"><?=$katalog['nama_katalog']?></h2>
            </div>
            <hr class="mt-3">
        </div>
        <div class="alert alert-dark border-0 rounded-0 p-0">
            <?php
            $katalog_details = $db->query("SELECT *, `produk`.id AS produkId FROM `katalog_detail` JOIN produk ON `produk`.id = `katalog_detail`.produk_id WHERE `katalog_id` = '$katalog_id' ORDER BY `katalog_detail`.urutan ASC");
            $total = 0;
            $jumlah_pesanan = $katalog_details->num_rows;
            foreach ($katalog_details as $key => $katalog_detail) :
            if(intVal($katalog_detail['markup']) > 0) {
                $is_markup = $katalog_detail['markup'];
            }
            echo "<div class='hr'></div>";
            $harga_jual = menentukanHarga(1,$katalog_detail['harga_jual']);
            ?>
            <div class="row my-3 mx-0 p-0">
                <div class="d-flex align-items-center gap-3">
                    <?php
                    $produkId = $katalog_detail['produkId'];
                        $foto = $db->query("SELECT `id` FROM `foto` WHERE id_produk = '$produkId' AND is_active = 1 AND is_cover = 1")->fetch_assoc()['id'];
                    
                    ?>
                    <img src="<?= ($katalog_detail['komentar']) ? ($katalog_detail['foto'] ? '/public/foto/temp/'.$katalog_detail['foto'] : '/public/foto/md/custom.jpg') : ($foto ? '/public/foto/md/'.$foto.'.jpg' : '/public/404.png') ?>" alt="" class="rounded-circle" style="width:70px; height:70px;">
                    <div class="">
                        <p class="fz-me text-success m-0"><?=($katalog_detail['nama']) ? $katalog_detail['nama'] : $katalog_detail['komentar']?></p>
                        <p class="fz-me-2 text-primary m-0"><?=format_rupiah($harga_jual + $katalog_detail['markup'])?></p>
                    </div>
                </div>
            </div>
            <?php endforeach ?>
        </div>
    </div>
</div>
<script>
    function generateJPG() {
        const element = document.getElementById('content');
        element.style.height = 'auto';
        document.querySelectorAll(".no-print").forEach(element => {
            element.classList.add("d-none");
        });
        html2canvas(element).then(canvas => {
            // Mengubah canvas menjadi data URL
            const dataURL = canvas.toDataURL('image/jpeg');
            
            // Membuat link download
            const link = document.createElement('a');
            link.href = dataURL;
            link.download = 'screenshot.jpg';
            link.click();
        });
        document.querySelectorAll(".no-print").forEach(element => {
            element.classList.remove("d-none");
        });
        element.style.height = '69em';
    }

    function generatePDF() {
        const element = document.getElementById('content');
        document.querySelectorAll(".no-print").forEach(element => {
            element.classList.add("d-none");
        });
        html2pdf().from(element).save('dokumen.pdf');
        document.querySelectorAll(".no-print").forEach(element => {
            element.classList.remove("d-none");
        });
    }
</script>