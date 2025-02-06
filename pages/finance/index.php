<?php include $_SERVER['DOCUMENT_ROOT'].'/components/header/index.php'; 
if(!isset($_SESSION["username"])){
    echo "<script>window.location.href = '/pages/login.php'</script>";
}
$_SESSION['last_url'] = $_SERVER[REQUEST_URI];
?>
<style>
    .hr {
        border: none;
        height: 1px;
        background-color: black;
        margin: 20px 0;
    }
</style>
<script>
    document.title = 'History GS';
</script>
<section class="container text-dark py-5">
    <?php
    $status = 'IS NULL';
    $background = 'danger text-danger';
    if ($_GET['s'] == 'sudah') {
        $status = 'IS NOT NULL';
        $background = 'light text-dark';
    }
    $banks = [
        // "tr_bca"
    ];
    foreach ($banks as $key => $bank) {
        $link = 'http://192.168.0.30/api/transferan-sembako.php?tr='.$bank;
        $datasTransfer = json_decode(file_get_contents($link));
        foreach ($datasTransfer as $dataTransfer) {
            $idTransfer = $dataTransfer->idt;
            $kodeBayar = $dataTransfer->kodebayar;
            // var_dump("SELECT * FROM `$bank` WHERE `kode_bayar` = '$kodeBayar'");
            // echo '<br>';
            // echo $dataTransfer->keterangan;
            // echo '<br>';
            $is_available = $db->query("SELECT * FROM `$bank` WHERE `kode_bayar` = '$kodeBayar'")->num_rows > 0;
            if (!$is_available) {
                $tanggal = $dataTransfer->tgl;
                $nominalDanaMasuk = $dataTransfer->din;
                $created_at = $dataTransfer->swup;
                $keterangan = $dataTransfer->keterangan;
        
                // Gunakan prepared statement untuk menghindari SQL injection
                $stmt = $db->prepare("INSERT INTO `$bank`(`kode_bayar`, `nominal`, `keterangan`, `tanggal_transaksi`, `created_at`) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $kodeBayar, $nominalDanaMasuk, $keterangan, $tanggal, $created_at);
                
                if ($stmt->execute()) {
                    echo "Data berhasil disimpan di ".$bank.".\n";
                } else {
                    echo "Gagal menyimpan data: " . $stmt->error . "\n";
                }
                $stmt->close();
            }
        }   
    }
    ?>
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h2 class="">Finance Aproval</h2>
        <select class="w-fit form-select" id="status" aria-label="Default select example">
            <option selected>--- Status ---</option>
            <option value="sudah">Sudah Disetujui</option>
            <option value="belum">Belum Disetujui</option>
        </select>
    </div>
    <div class="row">
        <div class="col-2 col-md-5">Pesanan</div>
        <div class="col-2 col-md-2">Jalur</div>
        <div class="col-3 col-md-2">Nominal Bayar</div>
        <div class="col-5 col-md-2">Tanggal</div>
        <?php if ($_GET['s'] != 'sudah') : ?>
        <div class="col-5 col-md-1">Verifikasi</div>
        <?php endif ?>
    </div>

    <div class="" id="listBayar">
        <?php 
        $sql = "SELECT `bayar`.*, `bayar`.created_at AS `tanggal_bayar`, `customer`.nama, `pesanan`.id AS `id_pesanan`,`tr_duit_masuk`.nominal FROM `bayar` LEFT JOIN `tr_duit_masuk` ON `bayar`.`id` = `tr_duit_masuk`.`bayar_id` LEFT JOIN `pesanan` ON `bayar`.`pesanan_id` = `pesanan`.id LEFT JOIN `customer` ON `customer`.`id` = `pesanan`.`customer_id` WHERE `tr_duit_masuk`.`bayar_id` $status ORDER BY `bayar`.id DESC";
        $itemsPerPage = 5;
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $offset = ($page - 1) * $itemsPerPage;
        $jumlah_total = $db->query($sql)->num_rows;
        $bayars = $db->query("$sql LIMIT $offset, $itemsPerPage");
        $backgroundColor = 'light';
        foreach ($bayars as $key => $bayar) : 
            $nama = $bayar['nama'];
            $nominal_bayar = $bayar['nominal_bayar'];
            $id_pesanan = $bayar['id_pesanan'];
            $nominal = $bayar['nominal'];
            if (intval($nominal) > intval($nominal_bayar)) {
                $nominal = $nominal_bayar;
            }
        ?>
                <div class="row alert alert-<?=$background?> m-0 mb-3">
                <div class="col-2 col-md-5 mb-2">
                    <p class="m-0">GS <?=$bayar['id_pesanan']?></p>
                    <p class="m-0"><?=$bayar['nama']?></p>
                </div>
                <div class="col-2 col-md-2 mb-2"><?=$bayar['jalur']?></div>
                <div class="col-5 col-md-2 mb-2"><?=($_GET['s'] != 'sudah') ? rupiah($nominal_bayar) : rupiah($nominal)?></div>
                <div class="col-5 col-md-2 mb-2"><?=$bayar['created_at']?></div>
                <?php if ($_GET['s'] != 'sudah') : ?>
                <div class="col-5 col-md-1 mb-2">
                    <?php if ($bayar['jalur'] == "Cash") : ?>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#aproveCash" onclick="ubahModalCash(<?=$bayar['id']?>,'<?=$nama?>','<?=$nominal_bayar?>','<?=$id_pesanan?>')">Verifikasi</button>
                    <?php elseif ($bayar['jalur'] == "Lainnya") : ?>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalLainnya">Verifikasi</button>
                        <div class="modal fade" id="modalLainnya" tabindex="-1" aria-labelledby="modalLainnya" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header text-dark">
                                        <h1 class="modal-title fs-5" id="modalLainnya">Pilih Metode Verifikasi</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="d-flex justify-content-center gap-3">
                                            <button type="button" class="btn btn-success px-3" data-bs-toggle="modal" data-bs-target="#aproveCash" onclick="ubahModalLainnya(<?=$bayar['id']?>,'<?=$nama?>','<?=$nominal_bayar?>','<?=$id_pesanan?>','Cash')">Cash</button>
                                            <button type="button" class="btn btn-info px-3" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="ubahModalLainnya(<?=$bayar['id']?>,'<?=$nama?>','<?=$nominal_bayar?>','<?=$id_pesanan?>','BCA')">Bank</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else : ?>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="ubahModal(<?=$bayar['id']?>,'<?=$nama?>','<?=$nominal_bayar?>','<?=$id_pesanan?>')">Verifikasi</button>
                    <?php endif ?>
                </div>
                <?php endif ?>
            </div>
        <?php endforeach ?>
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
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form action="aproveBayar.php" method="POST" class="modal-content text-dark">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Aprove <span class="m-0" id="nama"></span></h1>
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
                            </div>
                            <div class="col-4">
                                <select class="form-select" aria-label="Default select example" name="bank" id="bank" onclick="getBanksTransfer()" onchange="getBanksTransfer()">
                                    <option value="bca">BCA</option>
                                    <option value="mandiri">MANDIRI</option>
                                    <option value="bni">BNI</option>
                                    <option value="bsi">BSI</option>
                                    <option value="bri">BRI</option>
                                    <option value="split">SPLIT</option>
                                </select>
                            </div>
                            <div class="col-4 text-center">
                                <p class="m-0">Nominal : <b><span class="m-0" id="nominal_bayarBank"></span></b></p>
                                <input type="text" class="d-none" id="idPesananBank" name="idPesananBank">
                                <input type="text" class="d-none" id="nominalInput" name="nominalInput">
                            </div>
                            <div class="col-4">
                                <!-- <input type="text" class="w-100 form-control border-dark" placeholder="Cari dengan nama buyer" oninput="getBanksTransfer(this.value)" disabled> -->
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
                        <input type="text" class="d-none" name="page" id="page" value="financeApprove">
                    </div>
                    <div class="modal-footer d-none" id="modal-footer-cash">
                        <button type="submit" class="btn btn-primary">Aprove</button>
                    </div>
                </form> 
            </div>
        </div>
    </div>
</section>
<script>
    function cashCheck(jumlah) {
        if (jumlah) {
            document.querySelector("#modal-footer-cash").classList.remove("d-none");
        } else {
            document.querySelector("#modal-footer-cash").classList.add("d-none");
        }
    }

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
        search = document.querySelector("#nominalInput").value;
        let bank = (document.querySelector("#bank").value != "--Banks--") ? document.querySelector("#bank").value : 'bca';
        const datas = {
            bank: bank,
            search: search
        };
        fetch('getBanksTransfer.php', {
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
                let nominal = data['duit_in'];
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
        document.querySelector("#nominalInput").value = nominal;
        document.querySelector("#bayarId").value = idBayar;
        document.querySelector("#idPesananBank").value = id_pesanan;
        getBanksTransfer();
    }

    function ubahJalurBayar(idBayar,jalurBayar) {
        const datas = {
            idBayar: idBayar,
            jalurBayar: jalurBayar
        };
        fetch('ubahJalurBayar.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(datas),
        })
        .then(response => response.json()) // Parse the JSON response
        .then(response => {
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    }
    
    function ubahModalLainnya(idBayar,name,nominal,id_pesanan,jalurBayar) {
        if (jalurBayar == 'Cash') {
            ubahModalCash(idBayar,name,nominal,id_pesanan);
        }else{
            ubahModal(idBayar,name,nominal,id_pesanan);
        }
        ubahJalurBayar(idBayar,jalurBayar);
    }
    
    document.querySelector("#status").addEventListener('change', function() {
        window.location.href = '/pages/finance/index.php?s='+this.value;
    });
</script>
<?php include $_SERVER['DOCUMENT_ROOT'].'/components/footer/index.php';?>