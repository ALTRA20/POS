<?php $_SESSION['last_url'] = $_SERVER[REQUEST_URI]; ?>
<?php 
session_start(); 
if(!isset($_SESSION["username"])){
    echo "<script>window.location.href = '/pages/login.php'</script>";
}
include $_SERVER['DOCUMENT_ROOT']."/function/db.php";
if (isset($_GET['d'])) {
    $date = md5(date("Y-m-d"));
    if ($date = $_GET['f']) {
        $id = $_GET['d'];
    }
}
$pesanans = $db->query("SELECT `pesanan`.*, 
`customer`.nama AS customer, 
`customer`.alamat AS alamatCustomer, 
`customer`.wa AS waCustomer, 
`user`.username AS usernameUser,
`user`.jabatan
FROM `pesanan`
JOIN `customer` ON `customer`.id = `pesanan`.`customer_id`
JOIN `user` ON `user`.id = `pesanan`.`user_id`
WHERE `pesanan`.id = '$id'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="/function/dateNow.js"></script>
    <link href="/pages/index.css" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>
<style>
    @font-face {
        font-family: 'Helvetica';
        src: url('/public/Helvetica.TTF') format('truetype');
        font-family: 'CONSOLA';
        src: url('/public/CONSOLA.TTF') format('truetype');
        font-family: 'CONSOLAB';
        src: url('/public/CONSOLAB.TTF') format('truetype');
        font-family: 'Fake-Receipt';
        src: url('/public/Fake-Receipt.otf') format('truetype');
        font-family: 'Merchant-Copy';
        src: url('/public/Merchant-Copy.ttf') format('truetype');
        font-family: 'Merchant-Copy-Wide';
        src: url('/public/Merchant-Copy-Wide.ttf') format('truetype');
        font-family: 'Kanit-Black';
        src: url('/public/Kanit-Black.ttf') format('truetype');
        font-family: 'Kanit-Bold';
        src: url('/public/Kanit-Bold.ttf') format('truetype');
        font-family: 'Kanit-Medium';
        src: url('/public/Kanit-Medium.ttf') format('truetype');
        font-family: 'Courier-New';
        src: url('/public/Courier-New.ttf') format('truetype');
        font-family: 'lucon';
        src: url('/public/lucon.ttf') format('truetype');
        font-family: 'DejaVuSansMono';
        src: url('/public/DejaVuSansMono.ttf') format('truetype');
        font-family: 'IBMPlexSans-Medium';
        src: url('/public/IBMPlexSans-Medium.ttf') format('truetype');
        font-family: 'Signika-Regular';
        src: url('/public/Signika-Regular.ttf') format('truetype'); 
        font-family: 'micross';
        src: url('/public/micross.ttf') format('truetype');
        
        font-weight: normal;
        font-style: normal;
    }
    * {
        /* font-family: 'Helvetica';
        font-family: 'Times New Roman';
        font-family: 'CONSOLA';
        font-family: 'CONSOLAB';
        font-family: 'Fake-Receipt';
        font-family: 'Merchant-Copy'; */
        font-family: arial;
        /* font-family: 'serif'; */
        /*font-family: 'roboto';*/
        /* font-family: 'micross'; */
        /*font-family: 'Calibri';*/
        /* font-family: 'Georgia';
        font-family: 'Cambria';
        font-family: 'Bodoni';
        font-family: 'Comic Sans MS';
        font-family: 'Kanit-Black';
        font-family: 'Kanit-Bold';
        font-family: 'Kanit-Medium';
        font-family: 'Courier-New';
        font-family: 'lucon';
        font-family: 'DejaVuSansMono';
        font-family: 'Roboto Mono';
        font-family: 'IBMPlexSans-Medium';*/
        /* font-family: 'Signika-Regular';  */
        /* font-weight: bold; */
    }
    body{
        color: black !important;
    }
    .hr {
        border: none;
        height: 1px;
        background-color: black;
        margin: 5px 0;
    }
    .hrIjo {
        border: none;
        height: 5px;
        background-color: green;
        margin: 20px 0;
    }
    .hr-v{
        border: none;
        width: 2px;
        height: auto;
        background-color: green;
        margin: 0 10px;
    }
    @media print {   
        * {
            padding: 0 !important;
            margin: 0 !important;
            color: #000;
        }
        .fz-me {
            /*font-size: 18px;*/
        }
        @page {
            height: auto;
            size: auto;
            margin: 0;
            padding: 0;
        }
        .no-print, .no-print * {
            display: none !important;
        }
        .hidden-padding {
            padding: 0 !important;
        }
        body {
            width: 100%;
            overflow: visible !important;
        }
        section {
            overflow: visible !important;
            height: auto !important;
        }
        
        element {
            -ms-overflow-style: none; 
            scrollbar-width: none;
            overflow-y: scroll;
        }
        element::-webkit-scrollbar {
            display: none; 
        }
    }
</style>
<body>
<?php include $_SERVER['DOCUMENT_ROOT'].'/function/db.php'; ?>
<section class="overflow-auto d-flex justify-content-center p-5 hidden-padding">
    <?php 
        function format_rupiah($number) {
            return 'Rp ' . number_format($number, 0, ',', '.');
        }

        foreach ($pesanans as $key => $pesanan) : 
        $id_pesanan = $pesanan['id'];
        ?>
        <div class="" style="width:75mm !important; print-color-adjust: exact;">
            <div class="d-flex gap-2 mb-4 no-print">
                <a href="/" class="nav-link d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svlg" width="18" height="18" fill="currentColor" class="bi bi-house-door-fill" viewBox="0 0 16 16">
                        <path d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5"/>
                    </svg>
                </a>
                <div class="hr-v bg-dark"></div>
                <a href="<?=(isset($_GET['bc'])) ? '/pages/history/' : '/pages/history/' ?>" class="nav-link">History</a>
                <div class="hr-v bg-dark"></div>
                <div class="pointer" id="printer" onclick="window.print()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-printer-fill" viewBox="0 0 16 16">
                    <path d="M5 1a2 2 0 0 0-2 2v1h10V3a2 2 0 0 0-2-2zm6 8H5a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1"/>
                    <path d="M0 7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2h-1v-2a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v2H2a2 2 0 0 1-2-2zm2.5 1a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1"/>
                    </svg>
                </div>
            </div>
            <div class="bg-light p-3" style='font-size:1em'>
                <!--<img src="/public/nota/<?=$_GET['d']?>.png" alt="" class="w-100">
                <p class=" m-0" style="font-size:1.6em;">Dani Grosir Sembako <br>WA: 0851-9855-1742</p>
                <p class=" m-0 " style='font-size:1em'>Nota: GS<?=$_GET['d']?></p>

                <div class="hr"></div>-->
                <?php if (!isset($_GET['bc'])) : ?>
                    <p class="fw-bold m-0" style="font-size:1em;"><?=$pesanan['customer']?> - <?=$pesanan['alamatCustomer']?> <?=$pesanan['waCustomer']?></p>
                <?php endif ?>
                <div class="text-end">
                    <!--<p class=" m-0 "><?=$pesanan['caraBawa']?></p>-->
                    <?php $infoNotaAtas = "GS$_GET[d] $pesanan[usernameUser]";?>
                    <p class=" m-0 "><small><?=$pesanan['created_at']?></small></p>
                    <?=$infoNotaAtas;?>
                </div>
                =======================<br>
                <?php 
                $totalHargaBarang = 0;
                $totalHargaRequest = 0;
                $pesanan_details = $db->query("SELECT `pesanan_detail`.*, `produk`.nama, `produk`.id AS produkId 
                FROM `pesanan_detail`
                LEFT JOIN `produk` ON `produk`.id = `pesanan_detail`.`produk_id`
                WHERE `pesanan_id` = '$id'
                ORDER BY 
                  CASE 
                    WHEN `pesanan_detail`.`komentar` IS NOT NULL THEN 1 
                    ELSE 0 
                  END, 
                  `pesanan_detail`.`id` ASC");
                foreach ($pesanan_details as $key => $pesanan_detail) : 
                    $markup = intval($pesanan_detail['markup']);
                    $harga_jual = intval($pesanan_detail['harga_jual'] + $markup, 10);
                    $jumlah = intval($pesanan_detail['jumlah'], 10);
                    $totalHargaBarangPerBarang = 0;
                    $totalHargaBarangPerBarang = $harga_jual * $jumlah;
                    $totalHargaBarang += $totalHargaBarangPerBarang;
                ?>
                <?=($key != 0) ? '' : ''?>    
                <div class="row mb-0 fz-me">
                    <div class="col-12">
                        <span class="fw-bold m-0" style="font-size:1.5em;padding:0 30px 0 30px"><?=$pesanan_detail['jumlah']?></span>
                        <?php $pecah = explode(";;",$pesanan_detail['nama']); $namasingkat=$pecah[0];?> <span class="m-0" style="font-size:1em"><?=($pesanan_detail['komentar']) ? $pesanan_detail['komentar'] : trim($namasingkat);?></span>
                        <span style="font-size:0.8em;font-style:italic">@<?=format_rupiah($pesanan_detail['harga_jual']+$markup)?> = <?=format_rupiah($totalHargaBarangPerBarang)?></span>
                    </div>
                    <?php
                    $requests = json_decode($pesanan_detail['request']);
                    foreach ($requests as $key => $request) :
                        $qty = $request[0];
                        $harga = $request[2];
                        $totalHargaRequest += $harga * $qty;
                    ?>
                    <div class="row px-4">
                        <div class="col-1 "><?=$request[0]?></div>
                        <div class="col-11"><?=$request[1]?></div>
                        <div class="col-12">@<?=format_rupiah($request[2])?> = <?=format_rupiah($request[2] * $request[0])?></div>
                    </div>
                    <?php endforeach ?>
                </div>
                <?php endforeach ?>
                <div class="">~~~~~~~~~~~~~~~~~~~~~~</div>
                <div class="d-flex gap-3">
                    <p class="m-0">Total:</p>
                    <?php $total = $totalHargaBarang + $totalHargaRequest ?>
                    <p class="" style="font-size:1.6em"><?=format_rupiah($total)?></p>
                </div>
                <div class="hr"></div>
                
                <div class="m-0 d-none">Pembayaran:</div>
                <?php 
                $totalPembayaran = 0;
                $pembayarans = $db->query("SELECT `nominal`, `jalur`, `tr_duit_masuk`.`created_at` FROM `tr_duit_masuk` JOIN `bayar` ON `bayar`.`id` = `tr_duit_masuk`.`bayar_id` WHERE `bayar`.`pesanan_id` = '$id_pesanan'");
                foreach ($pembayarans as $key => $pembayaran) : 
                    $totalPembayaran += intval($pembayaran['nominal'], 10);
                ?>
                    <p class="m-0"><b><?=format_rupiah($pembayaran['nominal'])?></b> via <b style="text-decoration: underline;"><?=$pembayaran['jalur']?></b> <?=$pembayaran['created_at']?></p>
                <?php endforeach ?>
                <p class="m-0 text-center mt-3 d-none">KB: <?=format_rupiah($total - $totalPembayaran)?></p>
                <div style='text-align:right'>*<br></div>
                <!--<img src='./hadiah2.png' style='width:80%'>-->
                <!--<img src='./hadiahe.png' style='width:80%'>-->

<br><i>cek hadiah: 0851-9855-1742</i>
<div style='text-align:right'>
*<br>
*<br>
*<br>
*<br>
*<br>
</div>
            </div>
        </div>
    <?php endforeach ?>
</section>
<script>
    document.querySelector("#printer").click();
</script>
<?php include $_SERVER['DOCUMENT_ROOT'].'/components/footer/index.php'; ?>