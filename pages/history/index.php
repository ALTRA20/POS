<?php include $_SERVER['DOCUMENT_ROOT'].'/components/header/index.php'; 
if(!isset($_SESSION["username"])){
    echo "<script>window.location.href = '/pages/login.php'</script>";
}
?>
<?php
$_SESSION['last_url'] = $_SERVER[REQUEST_URI];
?>
<script>
    document.title = 'History GS';
</script>
<div class="bg-success">
    <section class="container p-5">
        <div class="row">
            <div class="col-md-1">
                <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="serachCustomer()">🔍👥</button>
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content bg-light text-dark">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Cari Nota Customer</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="text" class="form-control text-dark border border-dark" autocomplete="off" id="serachCustomer" placeholder="input nama" oninput="serachCustomer()">
                                <div class="" id="listCustomer">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <form action="" method="" class="d-flex gap-2">
                    <input type="number" class="form-control bg-light text-dark" name="nomorGS" placeholder="🔍📜nomor nota GS">
                    <button class="btn btn-primary" name="btn-search">Cari</button>
                </form>
                <a href="/pages/history/" class="btn btn-danger">Reset</a>
            </div>
            <div class="col d-flex justify-content-end">
                <div class="w-fit d-flex p-0 gap-2">
                    <?php
                    $dateYesterday = date("Y-m-d", strtotime("-1 days"));
                    $dateToday = date("Y-m-d");
                    ?>
                    <button class="btn btn-warning" onclick="window.location.href = '/pages/history/?date=<?= $dateToday
                    ?>'">Now</button>
                    <button class="btn btn-warning" onclick="window.location.href = '/pages/history/?date=<?= $dateYesterday
                    ?>'">Yesterday</button>
                </div>
            </div>
        </div>
        <?php 
        if($jabatan == 'super-admin' || $jabatan == 'finance') {
            $sql = "SELECT `pesanan`.*, `customer`.nama, `customer`.alamat, `customer`.wa, `user`.username  
            FROM `pesanan`
            JOIN `customer` ON `customer`.id = `pesanan`.`customer_id`
            JOIN `user` ON `user`.id = `pesanan`.`user_id`";
            if (isset($_GET['btn-search'])) {
                $nomorGS = $_GET['nomorGS'];
                $sql .= " WHERE `pesanan`.id LIKE '%$nomorGS%'";
            }

            if (isset($_GET['searchNotabyId'])) {
                $id = $_GET['i'];
                $sql .= " WHERE `pesanan`.customer_id LIKE '%$id%'";
            }

            if (isset($_GET['date'])) {
                $date = $_GET['date'];
                $sql .= " WHERE DATE(`pesanan`.created_at) = '$date'";
            }
        }else{
            $sql = "SELECT `pesanan`.*, `customer`.nama, `customer`.alamat, `customer`.wa, `user`.username  
            FROM `pesanan`
            JOIN `customer` ON `customer`.id = `pesanan`.`customer_id`
            JOIN `user` ON `user`.id = `pesanan`.`user_id` WHERE `pesanan`.`user_id` = '$userId'";

            if (isset($_GET['btn-search'])) {
                $nomorGS = $_GET['nomorGS'];
                $sql .= " AND `pesanan`.id LIKE '%$nomorGS%'";
            }
            if (isset($_GET['searchNotabyId'])) {
                $id = $_GET['i'];
                $sql .= " AND `pesanan`.customer_id LIKE '%$id%'";
            }
            if (isset($_GET['date'])) {
                $date = $_GET['date'];
                $sql .= " AND DATE(`pesanan`.created_at) = '$date'";
            }
        }
        
        $sql .= " ORDER BY `pesanan`.`id` DESC";
        $itemsPerPage = 4;
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $offset = ($page - 1) * $itemsPerPage;
        $pesanans = $db->query("$sql LIMIT $offset, $itemsPerPage");
        $pesanansAll = $db->query($sql);
        $jumlah_total = $pesanansAll->num_rows;
        echo '<br>Jumlah Data: '.$jumlah_total;
        if ($pesanans->num_rows > 0) : ?>
            <?php foreach ($pesanans as $key => $pesanan) :
                $id_pesanan = $pesanan['id'];
                $is_markup = 0;
                ?>

                <style>
                    .hr {
                        border: none; /* Remove the default border */
                        height: 1px; /* Set the height of the line */
                        background-color: white; /* Set the color of the line */
                        margin: 10px 0; /* Adjust margin as needed */
                    }
                </style>
                <?php
                    $level = $pesanan['level'];
                    $backgroundColor = '';
                    
                    if ($level == 0) {
                        $backgroundColor = 'danger';
                    }else if ($level == 1) {
                        $backgroundColor = 'danger';
                    }else if ($level == 2) {
                        $backgroundColor = 'warning';
                    }else if ($level == 3) {
                        $backgroundColor = 'light';
                    }
                    // var_dump($backgroundColor);
                ?>
                <div class="my-4">
                    <div class="row alert alert-<?=$backgroundColor?> p-3 rounded">
                    <div class='col-md-2'>
                    <?php 
                    $x = $pesanan['created_at'];
                    echo $x;
                    ?>
                    </div>
                        <div class="col-md-2">
                            <div class="bg-primary text-light text-center py-2 px-4 pointer rounded" id="btnGS<?=$pesanan['id']?>" data-bs-toggle="modal" data-bs-target="#GS<?=$pesanan['id']?>">GS <?=$pesanan['id']?></div>
                            <div class="modal fade" id="GS<?=$pesanan['id']?>" tabindex="-1" aria-labelledby="GS<?=$pesanan['id']?>Label" aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content bg-light">
                                        <div class="modal-header">
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="my-5 p-2">
                                                <div class="alert alert-primary">
                                                    <div class="row alert alert-dark text-dark m-0 p-4">
                                                        <div class="col-md-4 justify-content-end">
                                                            <h2 class="" id="GS">GS <?=$pesanan['id']?></h2>
                                                            <h2 class=""><?=$pesanan['nama'].' | '.$pesanan['alamat']?></h2>
                                                        </div>
                                                        <div class="col-md-7">
                                                            <p class="m-0">Note : <?=$pesanan['note']?></p>
                                                        </div>
                                                        <div class="col-md-1 d-flex align-items-center">
                                                            <?php 
                                                            $bayars = $db->query("SELECT *, pesanan.id AS IDP, bayar.id AS IDB FROM `bayar`LEFT JOIN `pesanan` ON `bayar`.`pesanan_id` = `pesanan`.id LEFT JOIN `customer` ON `customer`.`id` = `pesanan`.`customer_id` WHERE `pesanan_id` = '$id_pesanan'");
                                                            $qtyBayar = $bayars->num_rows;
                                                            if ($qtyBayar == 0) : ?>
                                                                <form action="/pages/pemesanan/terimaBayar.php" class="">
                                                                    <input type="text" class="d-none" name="i" value="<?=$pesanan['id']?>">
                                                                    <button class="w-fit btn btn-dark text-light px-4">Edit</button>
                                                                </form>
                                                            <?php else : ?>
                                                                <form action="/pages/nota/" method="get" class="">
                                                                    <?php
                                                                    $date = date("Y-m-d");
                                                                    $date = md5($date);
                                                                    ?>
                                                                    <input type="text" class="form-control d-none" id="" name="f" value="<?=$date?>">
                                                                    <input type="text" class="form-control d-none" id="" name="i" value="<?=rand(10,999)?>">
                                                                    <input type="text" class="form-control d-none" id="" name="d" value="<?=$pesanan['id']?>">
                                                                    <button class="w-fit btn btn-dark text-light px-4">Nota</button>
                                                                </form>
                                                            <?php endif ?>
                                                        </div>
                                                    </div>
                                                    <div class="mt-2">
                                                        <?php
                                                        $barangs = [];
                                                        $pesanan_details = $db->query("SELECT `pesanan_detail`.*, `produk`.nama, `produk`.id AS produkId 
                                                        FROM `pesanan_detail`
                                                        LEFT JOIN `produk` ON `produk`.id = `pesanan_detail`.`produk_id`
                                                        WHERE `pesanan_id` = '$id_pesanan'
                                                        ORDER BY 
                                                          CASE 
                                                            WHEN `pesanan_detail`.`komentar` IS NOT NULL THEN 1 
                                                            ELSE 0 
                                                          END, 
                                                          `pesanan_detail`.`id` ASC");
                                                        $total = 0;
                                                        $jumlah_pesanan = $pesanan_details->num_rows;
                                                        foreach ($pesanan_details as $key => $pesanan_detail) :
                                                        if(intVal($pesanan_detail['markup']) > 0) {
                                                            $is_markup = $pesanan_detail['markup'];
                                                        }
                                                        $barangs [$key]["produk_id"] = $pesanan_detail['produk_id'];
                                                        $barangs [$key]["nama"] = $pesanan_detail['nama'];
                                                        $barangs [$key]["foto"] = $pesanan_detail['foto'];
                                                        $barangs [$key]["markup"] = $pesanan_detail['markup'];
                                                        echo "<div class='hr'></div>";
                                                        $total += ($pesanan_detail['harga_jual'] + $pesanan_detail['markup']) * $pesanan_detail['jumlah'];
                                                        // echo $total;
                                                        ?>
                                                        <div class="row p-4 mx-0">
                                                            <div class="col-1">
                                                                <h5 class=""><?=$pesanan_detail['jumlah']?></h5>
                                                            </div>
                                                            <div class="col-11 col-md-6 d-flex align-items-center gap-2">
                                                                <?php
                                                                $produkId = $pesanan_detail['produkId'];
                                                                
                                                                $foto = $db->query("SELECT `id` FROM `foto` WHERE id_produk = '$produkId' AND is_active = 1 AND is_cover = 1")->fetch_assoc()['id'];
                                                                
                                                                ?>
                                                                <img src="<?= ($pesanan_detail['komentar']) ? ($pesanan_detail['foto'] ? '/public/foto/temp/'.$pesanan_detail['foto'] : '/public/foto/md/custom.jpg') : ($foto ? '/public/foto/md/'.$foto.'.jpg' : '/public/404.png') ?>" alt="" class="rounded-circle" style="width:70px; height:70px;">
                                                                <p class="m-0"><?=($pesanan_detail['nama']) ? $pesanan_detail['nama'] : $pesanan_detail['komentar']?></p>
                                                            </div>
                                                            <div class="col-md-2 col-6">
                                                                <p class="m-0"><?=format_rupiah($pesanan_detail['harga_jual'] + $pesanan_detail['markup'])?></p>
                                                            </div>
                                                            <div class="col-md-2 col-6 text-end text-md-start">
                                                                <p class="<?=($pesanan_detail['markup']) ? 'fst-italic underline text-danger' : ''?> m-0"><?=format_rupiah(($pesanan_detail['harga_jual'] + $pesanan_detail['markup']) * $pesanan_detail['jumlah'])?></p>
                                                            </div>
                                                            <?php if($pesanan['level'] <= 1) : ?>
                                                            <div class="col-md-1 col-6 text-end text-md-start d-none">
                                                                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editProdukFinance" onclick="btnEditProdukFinance(<?=$pesanan_detail['id']?>,<?=$pesanan_detail['jumlah']?>)">Edit</button>
                                                            </div>
                                                            <?php endif ?>
                                                            <?php if($request) : ?>
                                                            <div class="row px-md-5 py-4 mt-3">
                                                                <div class="col-12">
                                                                    <p class="m-0">Request</p>
                                                                </div>
                                                                <?php
                                                                $requests = json_decode($pesanan_detail['request']);
                                                                foreach ($requests as $key => $request) :
                                                                $total += $request[2] * $request[0];
                                                                ?>
                                                                <div class="col-md-1 col-2">
                                                                    <h5 class=""><?=$key+1?></h5>
                                                                </div>
                                                                <div class="col-10 col-md-7 p-0">
                                                                    <p class="m-0"><?=$request[1]?></p>
                                                                </div>
                                                                <div class="col-md-2 col-6">
                                                                    <p class="m-0"><?=format_rupiah($request[2])?></p>
                                                                </div>
                                                                <div class="col-md-2 col-6 text-end text-md-start">
                                                                    <p class="m-0"><?=format_rupiah($request[2] * $request[0])?></p>
                                                                </div>
                                                                <?php endforeach ?>
                                                            </div>
                                                            <?php endif ?>
                                                        </div>
                                                        <?php endforeach ?>
                                                        <div class="text-end">
                                                            <input type="text" class="form-control" id="jumlahYangHarusDibayar<?=$pesanan['id']?>" value="<?=$total?>">
                                                            <h3 class=""><?=format_rupiah($total)?></h3>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="">
                                                    <?php
                                                    if ($qtyBayar > 0) :
                                                    ?>
                                                        <div class="row">
                                                            <div class="col-4 col-md-3 d-none">Status Bayar</div>
                                                            <div class="col-2 col-md-3 d-none">Jalur</div>
                                                            <div class="col-5 col-md-3 d-none">Nominal Bayar</div>
                                                            <div class="col-5 col-md-3 d-none">Tanggal</div>
                                                            <div class="" id="listBayar<?=$pesanan['id']?>">
                                                                <?php
                                                                $nominalBayar = 0;
                                                                // var_dump("SELECT `nominal` FROM `tr_duit_masuk` JOIN `bayar` ON `bayar`.`id` = `tr_duit_masuk`.`bayar_id` WHERE `bayar`.`pesanan_id` = '$id_pesanan'");

                                                                $nominals = $db->query("SELECT `nominal` FROM `tr_duit_masuk` JOIN `bayar` ON `bayar`.`id` = `tr_duit_masuk`.`bayar_id` WHERE `bayar`.`pesanan_id` = '$id_pesanan'");
                                                                foreach ($nominals as $key => $nominal) {
                                                                    $nominalBayar += intval($nominal['nominal']);
                                                                }
                                                                
                                                                foreach ($bayars as $key => $bayar) :
                                                                    // var_dump($bayar);
                                                                    $idBayar = $bayar['IDB'];
                                                                    $nama = $bayar['nama'];
                                                                    $nominal_bayar = $bayar['nominal_bayar'];
                                                                    $nominal = $bayar['nominal'];
                                                                    $nominal = $db->query("SELECT * FROM `tr_duit_masuk` WHERE `bayar_id` = '$idBayar'")->fetch_assoc()['nominal'];
                                                                    // Convert the string to a DateTime object
                                                                    $date = new DateTime($bayar['created_at']);

                                                                ?>
                                                                
                                                                    <div class="row alert <?=($nominal) ? 'bg-success text-light' : 'alert-danger text-danger'?> m-0 mb-3">
                                                                        <div class="col-4 col-md-2 mb-2">Bayar ke <?=$key + 1?></div>
                                                                        <div class="col-5 col-md-2 mb-2"><?=$date->format('Y-M-d')?></div>
                                                                        <div class="col-2 col-md-3 mb-2"><?=$bayar['jalur']?></div>
                                                                        <div class="col-3 col-md-3 mb-2"><?=($nominal) ? $nominal : $bayar['nominal_bayar']?></div>
                                                                        <div class="col-2 col-md-2">
                                                                            <?php if ($jabatan == "super-admin" || $jabatan == "finance") : ?>
                                                                                <?php if (!$nominal) : ?>
                                                                                    <?php if ($bayar['jalur'] != "Cash") : ?>
                                                                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#verifikasiTransfer" onclick="ubahModal(<?=$idBayar?>,'<?=$nama?>','<?=$nominal_bayar?>','<?=$id_pesanan?>')">Verifikasi</button>
                                                                                    <?php else : ?>
                                                                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#aproveCash" onclick="ubahModalCash(<?=$idBayar?>,'<?=$nama?>','<?=$nominal_bayar?>','<?=$id_pesanan?>')">Verifikasi</button>
                                                                                    <?php endif ?>
                                                                                <?php endif ?>
                                                                            <?php endif ?>
                                                                        </div>
                                                                    </div>
                                                                <?php endforeach ?>
                                                            </div>
                                                        </div>
                                                        <div class="w-100 text-center" onclick="document.querySelector('#inputsBayarOverlay<?=$pesanan['id']?>').classList.toggle('d-none')" id="tombolTambahBayar">
                                                            <div class="btn btn-primary">
                                                                +
                                                            </div>
                                                        </div>
                                                        <div class="row d-none" id="inputsBayarOverlay<?=$pesanan['id']?>">
                                                            <div class="col-md-3">
                                                                <input type="date" id="tanggalBayar<?=$pesanan['id']?>" onchange="cekInput(<?=$id_pesanan?>)" class="form-control border border-dark" name="tanggalBayar">
                                                            </div>
                                                            <div class="col-md-4">
                                                                <select class="form-select bg-transparent border border-dark" onchange="cekInput(<?=$id_pesanan?>)" id="jalurBayar<?=$pesanan['id']?>" aria-label="Default select example" name="bayar">
                                                                    <option value="" selected>--- Jalur Bayar ---</option>
                                                                    <option value="Cash">Cash</option>
                                                                    <option value="BCA">BCA</option>
                                                                    <option value="SPLIT">SPLIT</option>
                                                                    <option value="Lainnya">Lainnya</option>
                                                                </select>    
                                                            </div>
                                                            <div class="col-md-4">
                                                                <input type="number" id="nominalBayar<?=$pesanan['id']?>" oninput="cekInput(<?=$id_pesanan?>)" class="form-control border-light text-dark  border border-dark" placeholder="nominal">
                                                            </div>
                                                            <div class="col-md-1 d-flex justify-content-end d-none" id="btn-bayar-overlay<?=$pesanan['id']?>">
                                                                <button class="btn btn-dark text-light" id="btn-bayar" onclick="bayar(<?=$pesanan['id']?>)">Bayar</button>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 d-flex justify-content-start fw-bold gap-2">
                                                            <p class="m-0">Total Bayar:</p>
                                                            <p class="m-0"><?=$nominalBayar?></p>
                                                        </div>
                                                    <?php endif ?>
                                                    <div class="d-flex justify-content-end gap-2 mx-md-5 mx-3 mt-5">
                                                    </div>
                                                    <div class="d-flex justify-content-end gap-2 mx-md-5 mx-3 mt-4 text-danger">
                                                        <h5 class="" id="totalHarga">Kurang Bayar: </h5>
                                                        <h5 class="" id="totalHarga"><?=format_rupiah($total - $nominalBayar)?></h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal" id="verifikasiTransfer" tabindex="-1" aria-labelledby="verifikasiTransferLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <form action="/pages/finance/aproveBayar.php" method="POST" class="modal-content text-dark">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="verifikasiTransferLabel">Aprove <span class="m-0" id="nama"></span></h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row mt-3 px-3">
                                                <div class="col-md-12">
                                                    <input type="text" class="d-none" name="edit" value="<?=($status == 'IS NOT NULL') ? '1' : '0'?>">
                                                    <input type="text" class="d-none" name="userId" value="<?=$userId?>">
                                                    <input type="text" class="d-none" name="tr_id" id="tr_id">
                                                    <input type="text" class="d-none" name="kode_bayar" id="kode_bayar">
                                                    <input type="text" class="d-none" name="bayarId" id="bayarId">
                                                    <input type="text" class="d-none" name="page" id="page" value="history">
                                                </div>
                                                <div class="col-4">
                                                    <select class="form-select" aria-label="Default select example" name="bank" id="bank" onclick="getBanksTransfer()" onchange="getBanksTransfer()">
                                                        <option value="bca">BCA</option>
                                                        <option value="split">SPLIT</option>
                                                    </select>
                                                </div>
                                                <div class="col-4 text-center">
                                                    <p class="m-0">Nominal : <b><span class="m-0" id="nominal_bayarBank"></span></b></p>
                                                    <input type="text" class="d-none" id="idPesananBank" name="idPesananBank">
                                                </div>
                                                <div class="col-4">
                                                    <input type="text" class="w-100 form-control border-dark" placeholder="Cari dengan nama buyer" oninput="getBanksTransfer(this.value)">
                                                </div>
                                            </div>
                                            <div class="hr"></div>
                                            <div class="row mx-3 rounded">
                                                <div class="col-1 d-flex justify-content-center align-items-center">Kode Bayar</div>
                                                <div class="col-7 d-flex justify-content-center align-items-center">Keterangan</div>
                                                <div class="col-2 d-flex justify-content-center align-items-center">Nominal</div>
                                                <div class="col-2 d-flex justify-content-center align-items-center">Tanggal Transaksi</div>
                                            </div>
                                            <div class="w-100 d-flex justify-content-end px-3" id="refresh" onclick="getBanksTransfer()">
                                                <div class="d-flex gap-2 my-3 alert alert-danger p-2 pointer">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-arrow-repeat" viewBox="0 0 16 16">
                                                        <path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41m-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9"/>
                                                        <path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5 5 0 0 0 8 3M3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9z"/>
                                                    </svg>
                                                    <p class="m-0">Refresh</p>
                                                </div>
                                            </div>
                                            <div class="px-4" id="transferList">
                                                
                                            </div>
                                        </div>
                                        <div class="modal-footer d-none" id="modal-footer-bank">
                                            <button type="submit" class="btn btn-primary">Aprove</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="modal fade" id="aproveCash" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form action="aproveCash.php" method="POST" class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Aprove <span class="m-0" id="namaCash"></span></h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <b class="">Nominal yang harus dibayar : </b>
                                                <span class="m-0" id="nominal_bayar"></span>
                                            </div>
                                            <label for="nominal">Nominal</label>
                                            <input type="text" class="form-control border-dark" id="nominal" oninput="cashCheck(this.value)" onfocus="use_number(this); this.select()" onblur="use_text(this)" name="nominal" autocomplete="off">
                                            <input type="text" class="d-none" id="userId" name="userId" value="<?=$userId?>">
                                            <input type="text" class="d-none" id="idPesanan" name="idPesanan">
                                            <input type="text" class="d-none" id="bayarCashId" name="bayarCashId">
                                        </div>
                                        <div class="modal-footer d-none" id="modal-footer-cash">
                                            <button type="submit" class="btn btn-primary">Aprove</button>
                                        </div>
                                    </form> 
                                </div>
                            </div>
                            <div class="mx-2">
                                <h5 class=""><?=$pesanan['nama']?> - <?=$pesanan['alamat']?></h5>
                                <p class="m-0"><?=$pesanan['wa']?></p>
                                <p class="m-0"><?=$pesanan['username']?></p>
                            </div>
                        </div>
                        <div class="col-md-1 col-6 mt-2 mt-md-0 d-flex justify-md-content-center">
                            <button class="btn btn-danger h-fit" data-bs-toggle="modal" data-bs-target="#level<?=$pesanan['id']?>">Level <?=$level?></button>
                        </div>
                        <div class="modal fade" id="level<?=$pesanan['id']?>" tabindex="-1" aria-labelledby="levelLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content text-dark">
                                    <div class="modal-header">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <?php
                                        $datas = [
                                            [
                                                "level" => "Level 1",
                                                "alur" => "cs cetak nota",
                                                "informasi" => "nota dicetak oleh cs, lalu diserahkan ke finance untuk di verifikasi",
                                                "from" => "CS",
                                                "to" => "Finance"
                                            ],
                                            [
                                                "level" => "Level 2",
                                                "alur" => "finance acc",
                                                "informasi" => "duit DP sudah diterima oleh finance, nota diserahkan ke stokis / produksi untuk disiapkan barangnya",
                                                "from" => "Finance",
                                                "to" => "Stokis / Produksi"
                                            ],
                                            [
                                                "level" => "Level 3",
                                                "alur" => "penetapan biaya + QC",
                                                "informasi" => "barang sudah ready, nota dan barang diserahkan ke QC + Finance untuk dicek barang dan kelunasan pembayarannya",
                                                "from" => "Stokis/Produksi",
                                                "to" => "Finance + QC"
                                            ],
                                            [
                                                "level" => "Level 4",
                                                "alur" => "Pengiriman / penyerahan barang",
                                                "informasi" => "Bagian pengiriman melakukan pengiriman / penyerahan kepada konsumen",
                                                "from" => "QC + Finance",
                                                "to" => "Admin Cargo"
                                            ],
                                            [
                                                "level" => "Level 5",
                                                "informasi" => "admin cargo melakukan arsiping nota",
                                                "from" => "Admin Cargo"
                                            ]
                                        ];
                                        foreach ($datas as $key => $data) :
                                        ?>
                                        <div class="row m-0 <?=($key != 0) ? 'border-top pt-3 mt-3' : ''?> <?=($level >= $key+1) ? 'alert alert-success' : ''?>">
                                            <div class="col-md-2 mb-2">
                                                <h3 class=""><?=$data['level']?></h3>
                                            </div>
                                            <div class="col-md-3 mb-2 d-flex flex-wrap gap-2">
                                                <?php if($data['alur']) : ?>
                                                <p class="m-0">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-activity me-2" viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd" d="M6 2a.5.5 0 0 1 .47.33L10 12.036l1.53-4.208A.5.5 0 0 1 12 7.5h3.5a.5.5 0 0 1 0 1h-3.15l-1.88 5.17a.5.5 0 0 1-.94 0L6 3.964 4.47 8.171A.5.5 0 0 1 4 8.5H.5a.5.5 0 0 1 0-1h3.15l1.88-5.17A.5.5 0 0 1 6 2"/>
                                                    </svg><?=$data['alur']?>
                                                </p>
                                                <?php endif ?>
                                            </div>
                                            <div class="col-md-4 mb-2 d-flex flex-wrap gap-2">
                                                <p class="m-0">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-info-circle-fill me-2" viewBox="0 0 16 16">
                                                        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2"/>
                                                    </svg>
                                                <?=$data['informasi']?></p>
                                            </div>
                                            <div class="col-md-3 mb-2 d-flex flex-wrap gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-people-fill me-2" viewBox="0 0 16 16">
                                                    <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
                                                </svg>
                                                <p class="m-0"><?=$data['from']?></p>
                                                <?php if ($data['to']) : ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/>
                                                </svg>
                                                <p class="m-0"><?=$data['to']?></p>
                                                <?php endif ?>
                                            </div>
                                        </div>
                                        <?php endforeach ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-6 mt-2 mt-md-0 d-flex flex-column justify-content-center align-items-center">
                            <p class="m-0"><?=rupiah($total)?></p>
                            <?php 
                            if ($total - $nominalBayar > 0) : ?>
                                <p class="m-0"><span class="text-danger">Kurang </span><?=format_rupiah($total - $nominalBayar)?></p>
                            <?php elseif($total - $nominalBayar < 0): ?>
                                <p class="m-0"><span class="text-success">Lebihan </span><?=format_rupiah($nominalBayar - $total)?></p>
                            <?php endif ?>
                        </div>
                        <div class="col-md-5 d-flex flex-wrap">
                            <?php if ($is_markup > 0) : ?>
                                <div class="position-absolute bg-danger text-light p-2" style="right:-20px;top:45%;font-size:22px;">[^]</div>
                            <?php endif ?>
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
                                        <?php 
                                        $foto = $db->query("SELECT * FROM `foto` WHERE `id_produk` = '$id_produk' ORDER BY `id` DESC LIMIT 1")->fetch_assoc()['id']; 
                                        if ($foto) {
                                            $foto = "/public/foto/md/$foto.jpg";
                                        }else{
                                            $foto = "/public/404.png";
                                        }
                                        ?>
                                        <img src="<?=$foto?>" alt="<?=($barang['produk_id']) ? $barang['nama'] : 'custom-produk'?>" id="img-produk" class="rounded-circle <?=($barang['markup'] > 0) ? 'border border-success border-5' : ''?> mx-1" style="width:70px;height:70px;">
                                    <?php endif; ?>
                                <?php else: ?>
                                    <img src="<?=($barang['foto']) ? '/public/foto/temp/'.$barang['foto'] : '/public/foto/md/custom.jpg'?>" alt="<?=($barang['produk_id']) ? $barang['nama'] : 'custom-produk'?>" id="img-produk" class="rounded-circle <?=($barang['markup'] > 0) ? 'border border-success border-5' : ''?> mx-1" style="width:70px;height:70px;">
                                <?php endif; ?>
                            <?php endforeach ?>
                        </div>
                    </div>
                </div>
            <?php endforeach;?>
            <ul class="w-full d-flex justify-content-center pagination d-flex justify-content-center flex-wrap mt-5 gap-3">
                <?php
                $totalPages = ceil($jumlah_total / $itemsPerPage);
                
                // Mendapatkan query string yang sudah ada kecuali 'page'
                $queryString = $_GET;
                unset($queryString['page']); // Hapus parameter page dari query string
                $queryString = http_build_query($queryString); // Bangun ulang query string

                if ($page <= 4) {
                    $startPage = 1;
                    $endPage = min(8, $totalPages); // Jangan melebihi total halaman
                } else {
                    $startPage = $page - 3;
                    $endPage = min($page + 4, $totalPages); // Jangan melebihi total halaman
                }
                if ($startPage > 1) {
                    // Tampilkan tombol "Previous" jika ada halaman tersembunyi sebelumnya
                    ?>
                    <li class="page-item"><a class="page-link" href="?<?=$queryString?>&page=<?= $startPage - 1 ?>">Previous</a></li>
                    <?php
                }
                for ($pageNumber = $startPage; $pageNumber <= $endPage; $pageNumber++) :
                    $isActive = ($pageNumber === $page) ? 'active' : '';
                    ?>
                    <li class="page-item <?=$isActive?>"><a class="page-link" href="?<?=$queryString?>&page=<?= $pageNumber ?>"><?=$pageNumber ?></a></li>
                    <?php
                endfor;
                if ($endPage < $totalPages) {
                    // Tampilkan tombol "Next" jika ada halaman tersembunyi setelahnya
                    ?>
                    <li class="page-item"><a class="page-link" href="?<?=$queryString?>&page=<?= $endPage + 1 ?>">Next</a></li>
                    <?php
                }
                ?>
            </ul>

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
    function choose(elementchoose,idtf,kodeBayar) {
        document.querySelector("#tr_id").value = idtf;
        document.querySelector("#kode_bayar").value = kodeBayar;

        document.querySelectorAll("#transfer").forEach(element => {
            if(element != elementchoose){
                element.remove();
            }
            document.querySelector("#refresh").classList.remove("d-none");
            document.querySelector("#modal-footer-bank").classList.remove("d-none");
        });
    }

    function getBanksTransfer(search) {
        let bank = (document.querySelector("#bank").value != "--Banks--") ? document.querySelector("#bank").value : 'bca';
        const datas = {               
            bank: bank,
            search: search
        };
        fetch('/pages/finance/getBanksTransfer.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(datas),
        })
        .then(response => response.json()) // Parse the JSON response
        .then(response => {
            document.querySelector("#transferList").innerHTML = '';
            document.querySelector("#refresh").classList.add("d-none");
            let transferList = '';
            response.forEach(data => {
                let idtf = data['id'];
                let kode_bayar = data['kode_bayar'];
                let keterangan = data['keterangan'];
                let nominal = (bank == 'split') ? data['nominal'] : data['duit_in'];
                let tanggal_transaksi = data['tanggal_transaksi'];
                if (data['tr']) {
                    kode_bayar = data['tr']['kode_bayar'];
                    keterangan = data['tr']['keterangan'];
                    tanggal_transaksi = data['tr']['tanggal_transaksi'];
                }
                transferList += `<div class="row customerCard border border-dark p-3 rounded my-3" id="transfer" onclick="choose(this,'${idtf}','${kode_bayar}')" style="background:#accfa4;">
                                <div class="col-1 d-flex align-items-center">${kode_bayar}</div>
                                <div class="col-7 d-flex align-items-center">${keterangan}</div>
                                <div class="col-2 d-flex align-items-center">${nominal}</div>
                                <div class="col-2 d-flex align-items-center">${tanggal_transaksi}</div>
                            </div>`;
            });
            document.querySelector("#transferList").innerHTML = transferList;
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    }
    getBanksTransfer();

    function ubahModalCash(idBayar,name,nominal,id_pesanan) {
        document.querySelector("#namaCash").innerHTML = name;
        document.querySelector("#nominal_bayar").innerHTML = rupiah(nominal);
        document.querySelector("#bayarCashId").value = idBayar;
        document.querySelector("#idPesanan").value = id_pesanan;
    }

    function ubahModal(idBayar,name,nominal,id_pesanan) {
        document.querySelector("#nama").innerHTML = name;
        document.querySelector("#nominal_bayarBank").innerHTML = rupiah(nominal);
        document.querySelector("#bayarId").value = idBayar;
        document.querySelector("#idPesananBank").value = id_pesanan;
    }
    
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
            let jumlahYangHarusDibayar = document.querySelector("#jumlahYangHarusDibayar"+id).value;
            if (parseInt(jumlahYangHarusDibayar) > parseInt(document.querySelector("#nominalBayar"+id).value)) {
                document.querySelector("#jumlahYangHarusDibayar"+id).value = '';
            }else{
                jumlah += 1;
            }
        }

        if (jumlah == 3) {
            document.querySelector("#btn-bayar-overlay"+id).classList.remove("d-none");
        }else{
            document.querySelector("#btn-bayar-overlay"+id).classList.add("d-none");
        }
        
    }
    function serachCustomer() {
            let input = document.querySelector("#serachCustomer").value;
            const datas = {
                input: input
            };
            fetch('/components/customer/waOrName.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(datas),
            })
            .then(response => response.json()) // Parse the JSON response
            .then(response => {
                let element = '';
                response.forEach(data => {
                    element += `<form action="" method="" class="my-3" id="">
                                    <input type="text" class="d-none" name="i" value="${data[0]}">
                                    <button name="searchNotabyId" class="btn bg-pink">${data[1]} ;; ${data[2]} ${data[3]}</button>
                                </form>`;
                });
                document.querySelector("#listCustomer").innerHTML = element;
            })
            .catch((error) => {
                console.error('Error:', error);
            });
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
            if (response.message === "success") {
                const datas = response.data; // Access the 'data' object
                let listBayar = '';
                Object.entries(datas).forEach(([key, value]) => {
                    console.log(value);
                    let duit_masuk = value['duit_masuk'];
                    let bg = (duit_masuk) ? 'bg-success text-light' : 'alert-danger text-danger'
                    let bayarKe = parseInt(key, 10)+1;
                    listBayar += `<div class="row alert ${bg} m-0 mb-3">
                                    <div class="col-4 col-md-3 mb-2">Bayar ke ${bayarKe}</div>  
                                    <div class="col-2 col-md-3 mb-2">${value['created_at']}</div>
                                    <div class="col-5 col-md-3 mb-2">${value['jalur']}</div>
                                    <div class="col-5 col-md-3 mb-2">${value['nominal_bayar']}</div>
                                </div>`;
                    // console.log();
                });
                document.querySelector("#listBayar"+id).innerHTML = listBayar;
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