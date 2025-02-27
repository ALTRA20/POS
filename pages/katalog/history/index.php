<?php
session_start();
$_SESSION['last_url'] = $_SERVER[REQUEST_URI];

if(!isset($_SESSION["username"])){
    echo "<script>window.location.href = '/pages/login.php'</script>";
}else{
    include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
    $username = $_SESSION["username"];
    $user = $db->query("SELECT * FROM `user` WHERE `username` = '$username'")->fetch_assoc();
    $jabatan = $user['jabatan'];
    $userId = $user['id'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
    <link href="/public/bootstrap/css/bootstrap.css" rel="stylesheet">
    <script src="/public/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/function/dateNow.js"></script>
    <link href="/pages/index.css" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>
<body>
<script>
    document.title = 'History GS';
</script>
<?php include $_SERVER['DOCUMENT_ROOT'].'/components/header/navbarKatalog.php';  ?>
<div class="bg-success">
    <section class="container p-5">
        <div class="row justify-content-start">
            <div class="col-md-4 d-flex gap-2">
                <form action="" method="" class="d-flex gap-2">
                    <input type="text" class="form-control bg-light text-dark" name="s" autocomplete="off" placeholder="Cari katalog berdasarkan nama">
                    <button class="btn btn-primary">Cari</button>
                </form>
                <a href="/pages/katalog/history/" class="btn btn-danger">Reset</a>
            </div>
        </div>
        <?php 
        
        $sql = "SELECT * FROM `katalog`";
        if (isset($_GET['s'])) {
            $sql .= " WHERE `nama_katalog` LIKE '%".$_GET['s']."%'";
        }
        $katalogs = $db->query($sql." ORDER BY `id` DESC");
        $jumlah_total = $katalogs->num_rows;
        echo '<br>Jumlah Data: '.$jumlah_total;
        if ($katalogs->num_rows > 0) : ?>
            <?php foreach ($katalogs as $key => $katalog) :
                $katalog_id = $katalog['id'];
                ?>
                <style>
                    .hr {
                        border: none; /* Remove the default border */
                        height: 1px; /* Set the height of the line */
                        background-color: white; /* Set the color of the line */
                        margin: 10px 0; /* Adjust margin as needed */
                    }
                </style>
                <div class="my-4">
                    <div class="row alert alert-warning p-3 rounded">
                    <div class='col-md-2'>
                    <?php 
                    $x = $katalog['created_at'];
                    echo $x;
                    ?>
                    </div>
                        <div class="col-md-2">
                            <a href="/pages/katalog/full/?i=<?=$katalog['id']?>" class="w-100 btn btn-primary text-light text-center py-2 px-4 pointer rounded" id="btnGS<?=$katalog['id']?>">Katalog <?=$katalog['id']?></a>
                            <div class="mx-2">
                                <h5 class=""><?=$katalog['nama_katalog']?></h5>
                            </div>
                        </div>
                        <div class="col-md-5 d-flex flex-wrap">
                            <?php   
                            foreach ($barangs as $key => $barang) : ?>
                                <?php if ($barang['produk_id']): ?>
                                    <?php 
                                    $id_produk = $barang['produk_id'];
                                    $foto = $db->query("SELECT * FROM `foto` WHERE `id_produk` = '$id_produk' AND is_cover = 1");
                                    if ($foto && $foto->num_rows > 0): 
                                        $foto_data = $foto->fetch_assoc();
                                    ?>
                                        <img src="/public/foto/md/<?= htmlspecialchars($foto_data['id']) ?>.jpg" alt="<?=($barang['produk_id']) ? $barang['nama'] : 'custom-produk'?>" id="img-produk" class="rounded-circle <?=($barang['markup'] > 0) ? 'border border-success border-5' : ''?> mx-1" style="width:70px;height:70px;">
                                    <?php else: ?>
                                        <img src="/public/404.png" alt="<?=($barang['produk_id']) ? $barang['nama'] : 'custom-produk'?>" id="img-produk" class="rounded-circle <?=($barang['markup'] > 0) ? 'border border-success border-5' : ''?> mx-1" style="width:70px;height:70px;">
                                    <?php endif; ?>
                                <?php else: ?>
                                    <img src="<?=($barang['foto']) ? '/public/foto/temp/'.$barang['foto'] : '/public/foto/md/custom.jpg'?>" alt="<?=($barang['produk_id']) ? $barang['nama'] : 'custom-produk'?>" id="img-produk" class="rounded-circle <?=($barang['markup'] > 0) ? 'border border-success border-5' : ''?> mx-1" style="width:70px;height:70px;">
                                <?php endif; ?>
                            <?php endforeach ?>
                        </div>
                    </div>
                </div>
            <?php endforeach;?>
            <div class="modal fade" id="editProdukFinance" tabindex="-1" aria-labelledby="editProdukFinanceLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content text-dark">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="editProdukFinanceLabel">Pilih Produk</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="text" class="form-control border-dark" autocomplete="off" onclick="searchProdukEditFinance(this)" oninput="searchProdukEditFinance(this)">
                            <input type="number" class="form-control border-dark d-none" autocomplete="off" id="idPesananDetail">
                            <input type="number" class="form-control border-dark d-none" autocomplete="off" id="jumlahPesananDetail">
                            <div class="my-3" id="listProdukEditFinance">
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php else : ?>
            <div class="w-100 h-vh-80 d-flex justify-content-center align-items-center">
                <h5 class="">TIDAK ADA DATA YANG TERSEDIA</h5>
            </div>
        <?php endif ?>
    </section>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll("#img-produk").forEach(element => {
            let toolTipText = element.getAttribute("alt");
            new Opentip(element, toolTipText);
        });
    });

    function menentukanHarga(jumlahDibeli, listHarga) {
        // Decode harga_jual dari format JSON
        const hargaList = JSON.parse(listHarga);
        // Mengurutkan array hargaList berdasarkan jumlah secara descending
        hargaList.sort((a, b) => b.jumlah - a.jumlah);
        
        let hargaSatuan = 0;
        for (const harga of hargaList) {
            if (parseInt(jumlahDibeli) >= parseInt(harga.jumlah)) {
                hargaSatuan = harga.harga;
                break; // Exit the loop once the appropriate price is found
            }
        }
        return hargaSatuan;
    }
    function btnEditProdukFinance(id,jumlah) {
        document.querySelector("#idPesananDetail").value = id;
        document.querySelector("#jumlahPesananDetail").value = jumlah;
    }
    function produkEditFinance(idProduk, harga, jumlahPesananDetail) {
        let idPesananDetail = document.querySelector("#idPesananDetail").value;
        const datas = {
            idPesananDetail: idPesananDetail,
            idProdukBaru: idProduk,
            jumlahPesananDetail: jumlahPesananDetail,
            harga: harga
        };
        fetch('/components/pemesanan/editPesananDetail.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(datas),
        })
        .then(response => response.text()) // Parse the JSON response
        .then(response => {
            window.location.href = '/pages/history/?i='+1+'&searchNotabyId=';
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    }
    function searchProdukEditFinance(input) {
        let search = input.value;
        const datas = {
            search: search,
            limit: 6
        };
        fetch('/components/tambah-product/getProduk.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(datas),
        })
        .then(response => response.json()) // Parse the JSON response
        .then(response => {
            let jumlahPesananDetail = document.querySelector("#jumlahPesananDetail").value;
            let element = '';
            response.forEach(data => {
                let harga = menentukanHarga(jumlahPesananDetail, data['harga_jual']);
                element += `<div class="d-flex align-items-center gap-2 my-3 p-2 border border-dark pointer customerCard" onclick="produkEditFinance(${data['id']},${harga},${jumlahPesananDetail})">
                <img src="${(data['foto']) ? '/public/foto/md/'+data['foto']+'.jpg' : '/public/404.png'}" alt="" class="rounded-circle mx-1" style="width:70px;height:70px;">
                <p class="m-0 fw-bold">${data['nama']}</p>
                </div>`;
            });
            document.querySelector("#listProdukEditFinance").innerHTML = element;
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    }
    function cekInput(id) {
        let jumlah = 0;
        
        if (document.querySelector("#tanggalBayar"+id).value != '') {
            jumlah += 1;
        }

        if (document.querySelector("#jalurBayar"+id).value != '') {
            jumlah += 1;
        }

        if (document.querySelector("#nominalBayar"+id).value != '') {
            jumlah += 1;
        }

        if (jumlah == 3) {
            document.querySelector("#btn-bayar-overlay"+id).classList.remove("d-none");
        }else{
            document.querySelector("#btn-bayar-overlay"+id).classList.add("d-none");
        }
        
    }
    function bayar(id) {
        let jalurBayar = document.querySelector("#jalurBayar"+id).value;
        let tanggalBayar = document.querySelector("#tanggalBayar"+id).value;
        let nominalBayar = document.querySelector("#nominalBayar"+id).value;
        const datas = {
            id: id,
            jalurBayar: jalurBayar,
            tanggalBayar: tanggalBayar,
            nominalBayar: nominalBayar
        };
            fetch('/components/pemesanan/bayar.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(datas),
            })
            .then(response => response.json()) // Parse the JSON response
            .then(response => {
                console.log(response);
                if (response.message === "success") {
                    const datas = response.data; // Access the 'data' object
                    let listBayar = '';
                    Object.entries(datas).forEach(([key, value]) => {
                        let bayarKe = parseInt(key, 10)+1;
                        listBayar += `<div class="row alert alert-danger text-danger m-0 mb-3">
                                        <div class="col-4 col-md-3 mb-2">Bayar ke ${bayarKe}</div>
                                        <div class="col-2 col-md-3 mb-2">${value[2]}</div>
                                        <div class="col-5 col-md-3 mb-2">${value[3]}</div>
                                        <div class="col-5 col-md-3 mb-2">${value[5]}</div>
                                    </div>`;
                        // console.log();
                    });
                    document.querySelector("#listBayar").innerHTML = listBayar;
                    // Now you can use 'data' object as needed in your JavaScript code
                } else {
                    console.error("Insertion failed or no data returned.");
                }
            })
            .catch((error) => {
                console.error('Error:', error);
            });
    }
</script>
<?php include $_SERVER['DOCUMENT_ROOT'].'/components/footer/index.php';?>