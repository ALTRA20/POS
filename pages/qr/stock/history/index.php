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
        <!-- <div class="row">
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
                    ?>'">Today</button>
                    <button class="btn btn-warning" onclick="window.location.href = '/pages/history/?date=<?= $dateYesterday
                    ?>'">Yesterday</button>
                </div>
            </div>
        </div> -->
        <?php 
        function timeAgo($date) {
            if (is_string($date)) {
                $date = new DateTime(str_replace(" ", "T", $date));
            }
            $now = new DateTime();
            $diff = $now->getTimestamp() - $date->getTimestamp();
        
            $intervals = [
                "tahun" => 31536000,
                "bulan" => 2592000,
                "minggu" => 604800,
                "hari" => 86400,
                "jam" => 3600,
                "menit" => 60,
                "detik" => 1
            ];
        
            foreach ($intervals as $key => $value) {
                $interval = floor($diff / $value);
                if ($interval >= 1) {
                    return "$interval $key yang lalu";
                }
            }
            return "just now";
        }
        
        $sql = "SELECT *, stock_history_qr.id AS id, stock_history_qr.created_at AS created_at FROM `stock_history_qr` JOIN user ON user.id = stock_history_qr.user_id";
        $itemsPerPage = 4;
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $offset = ($page - 1) * $itemsPerPage;
        $pesanans = $db->query("$sql ORDER BY `stock_history_qr`.`id` DESC LIMIT $offset, $itemsPerPage");
        $created_at = $pesanans->fetch_assoc()['created_at'];
        $pesanansAll = $db->query($sql);
        $jumlah_total = $pesanansAll->num_rows;
        // echo '<br>Jumlah Data: '.$jumlah_total;
        if ($pesanans->num_rows > 0) : 
            foreach ($pesanans as $key => $pesanan) :
                $history_id = $pesanan['id'];
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
                    $bcBtn = "bg-danger text-light";
                    if ($level == 3) {
                        $bcBtn = "bg-primary text-light";
                    }
                    // var_dump($backgroundColor);
                ?>
                <div class="my-4">
                    <div class="row alert alert-light p-3 rounded">
                      <div class='col-md-2 d-flex justify-content-center align-items-center'>
                        <?php 
                        $x = $pesanan['created_at'];
                        echo timeAgo($x);
                        ?>
                      </div>
                      <div class="col-md-3 d-flex flex-column justify-content-center align-items-center">
                          <div class="w-100 bg-primary text-light text-center py-2 px-4 pointer rounded" id="btnGS<?=$pesanan['id']?>" data-bs-toggle="modal" data-bs-target="#GS<?=$pesanan['id']?>">History <?=$pesanan['id']?></div>
                          <div class="modal fade" id="GS<?=$pesanan['id']?>" tabindex="-1" aria-labelledby="GS<?=$pesanan['id']?>Label" aria-hidden="true">
                              <div class="modal-dialog modal-md">
                                  <div class="modal-content bg-light">
                                      <div class="modal-header">
                                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                      </div>
                                      <div class="modal-body">
                                          <div class="p-2">
                                              <div class="alert alert-primary">
                                                  <div class="row alert alert-dark text-dark m-0 p-4">
                                                      <div class="col-6 justify-content-end">
                                                          <h4 class="" id="GS">History <?=$pesanan['id']?></h4>
                                                      </div>
                                                      <!-- <div class="col-8 d-flex justify-content-end align-items-center">
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
                                                      </div> -->
                                                  </div>
                                                  <div class="mt-2">
                                                      <?php
                                                      $barangs = [];
                                                      $pesanan_details = $db->query("SELECT *,produk.id AS produkId FROM `stock_history_qr_detail` JOIN produk ON produk.id = stock_history_qr_detail.produk_id WHERE `stock_history_qr_id` = '$history_id'");
                                                      foreach ($pesanan_details as $key => $pesanan_detail) :
                                                      $barangs [$key]["produk_id"] = $pesanan_detail['produk_id'];
                                                      $barangs [$key]["nama"] = $pesanan_detail['nama'];
                                                      $barangs [$key]["foto"] = $pesanan_detail['foto'];
                                                      $barangs [$key]["markup"] = $pesanan_detail['markup'];
                                                      echo "<div class='hr'></div>";
                                                      ?>  
                                                      <div class="row p-2 mx-0">
                                                          <div class="col-1 d-flex align-items-center">
                                                              <h5 class=""><?=$pesanan_detail['quantity']?></h5>
                                                          </div>
                                                          <div class="col-8 d-flex align-items-center gap-2">
                                                              <?php
                                                              $produkId = $pesanan_detail['produkId'];
                                                              
                                                              $foto = $db->query("SELECT `id` FROM `foto` WHERE id_produk = '$produkId' AND is_active = 1 AND is_cover = 1")->fetch_assoc()['id'];
                                                              
                                                              ?>
                                                              <img src="<?= ($pesanan_detail['komentar']) ? ($pesanan_detail['foto'] ? '/public/foto/temp/'.$pesanan_detail['foto'] : '/public/foto/md/custom.jpg') : ($foto ? '/public/foto/md/'.$foto.'.jpg' : '/public/404.png') ?>" alt="" class="rounded-circle" style="width:70px; height:70px;">
                                                              <p class="m-0"><?=($pesanan_detail['nama']) ? $pesanan_detail['nama'] : $pesanan_detail['komentar']?></p>
                                                          </div>
                                                          <div class="col-3 text-end text-md-start">
                                                            <span class=""><?= date("Y-m-d", strtotime($created_at)) ?></span>
                                                          </div>
                                                      </div>
                                                      <?php endforeach ?>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                          <div class="modal fade" id="aproveCash" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                              <div class="modal-dialog">
                                  <form action="/pages/finance/aproveCash.php" method="POST" class="modal-content">
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
                              <h5 class=""><?=$pesanan['username']?></h5>
                          </div>
                      </div>
                      <div class="col-md-7 d-flex flex-wrap justify-content-center align-items-center">
                          <?php if ($is_markup > 0) : ?>
                              <div class="position-absolute bg-danger text-light p-2" style="right:-20px;top:45%;font-size:22px;">[^]</div>
                          <?php endif ?>
                        <?php
                        foreach ($barangs as $key => $barang) : ?>
                          <div class="text-center">
                                  <?php if ($barang['produk_id']): ?>
                                      <?php 
                                      $id_produk = $barang['produk_id'];
                                      $foto = $db->query("SELECT * FROM `foto` WHERE `id_produk` = '$id_produk' AND is_cover = 1");
                                      if ($foto && $foto->num_rows > 0): 
                                          $foto_data = $foto->fetch_assoc();
                                      ?>
                                          <a href="/pages/history/produk/?i=<?=$id_produk?>" class=""><img src="/public/foto/md/<?= htmlspecialchars($foto_data['id']) ?>.jpg" alt="<?=($barang['produk_id']) ? $barang['nama'] : 'custom-produk'?>" id="img-produk" class="rounded-circle <?=($barang['markup'] > 0) ? 'border border-success border-5' : ''?> mx-1" style="width:70px;height:70px;"></a>
                                      <?php else: ?>
                                          <?php 
                                          $foto = $db->query("SELECT * FROM `foto` WHERE `id_produk` = '$id_produk' ORDER BY `id` DESC LIMIT 1")->fetch_assoc()['id']; 
                                          if ($foto) {
                                              $foto = "/public/foto/md/$foto.jpg";
                                          }else{
                                              $foto = "/public/404.png";
                                          }
                                          ?>
                                          <a href="/pages/history/produk/?i=<?=$id_produk?>" class=""><img src="<?=$foto?>" alt="<?=($barang['produk_id']) ? $barang['nama'] : 'custom-produk'?>" id="img-produk" class="rounded-circle <?=($barang['markup'] > 0) ? 'border border-success border-5' : ''?> mx-1" style="width:70px;height:70px;"></a>
                                      <?php endif; ?>
                                  <?php else: ?>
                                      <img src="<?=($barang['foto']) ? '/public/foto/temp/'.$barang['foto'] : '/public/foto/md/custom.jpg'?>" alt="<?=($barang['produk_id']) ? $barang['nama'] : 'custom-produk'?>" id="img-produk" class="rounded-circle <?=($barang['markup'] > 0) ? 'border border-success border-5' : ''?> mx-1" style="width:70px;height:70px;">
                                  <?php endif; ?>
                                  <h5 class=""><?=$pesanan_detail['quantity']?></h5>
                            </div>
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
                    // console.log(value);
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

    const urlParams = new URLSearchParams(window.location.search);
    const gs = urlParams.get('gs');
    if (gs) {
        document.querySelector("#btnGS"+gs).click();
    }
</script>
<?php include $_SERVER['DOCUMENT_ROOT'].'/components/footer/index.php';?>