<script>
    document.querySelector("#history").innerHTML = "Tambah Stock";
    document.querySelector("#history").href = "/pages/stock/tambah.php";
</script>
<div id="chart_div"></div>
      
<section class="container py-5 text-dark">
    <?php
    $db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");

    // Jalankan kueri utama
    $query = "
        SELECT 
            GROUP_CONCAT(log_stock.id_produk) AS idsProduk, 
            GROUP_CONCAT(log_stock.harga) AS hargas, 
            GROUP_CONCAT(log_stock.is_verif) AS is_verifs, 
            vendor, 
            log_stock.id_user, 
            log_stock.created_at,
            log_stock.tanggal_beli,
            log_stock.id_cover
        FROM 
            log_stock 
        GROUP BY 
            vendor, log_stock.id_user, log_stock.created_at 
        ORDER BY 
            MAX(log_stock.id) DESC 
        LIMIT 0, 25";
        // Eksekusi kueri
    $logs_stock = $db->query($query);
    foreach ($logs_stock as $keyStocks => $log_stock) :
        $vendor = $log_stock['vendor'];
        $created_at = $log_stock['created_at'];
        $idsProduk = $log_stock['idsProduk'];
        // var_dump($idsProduk);
        $id_nota = $log_stock['id_cover'];
        $fotoNota = $db->query("SELECT * FROM `foto_nota_stock` WHERE `id` = '$id_nota'")->fetch_assoc()['file'];
        $produks = $db->query("SELECT `log_stock`.*, `produk`.`nama`, `produk`.harga_beli,`produk`.id AS produkId FROM `log_stock` JOIN `produk` ON `produk`.id = `log_stock`.`id_produk` WHERE `log_stock`.id_produk in ($idsProduk) AND `log_stock`.vendor = '$vendor' AND `log_stock`.created_at = '$created_at'");
        $hargaFix = 0;
        $hargas = $produks;
        foreach ($hargas as $key => $harga) {
            $stock = $harga['stok_baru'] - $harga['stok_awal'];
            $hargaFix += $stock * $harga['harga'];
        }
    ?>
    <div class="row alert alert-primary">
        <div class="col-3">
            <p class="m-0"><?=$log_stock['vendor']?></p>
            <p class="m-0"><?=$log_stock['tanggal_beli']?></p>
        </div>
        <div class="col-7 d-flex flex-wrap gap-2">
            <?php
            $idsProduk = explode(',',$log_stock["idsProduk"]);
            $is_verifs = explode(',',$log_stock["is_verifs"]);
            foreach ($idsProduk as $key => $idProduk) : 
            $foto = $db->query("SELECT `id` FROM `foto` WHERE `id_produk` = '$idProduk' AND `is_cover` = 1 ORDER BY is_active DESC")->fetch_assoc()['id'];
            $nama_file = '/public/foto/md/'.$foto.'.jpg';
            if (file_exists($_SERVER['DOCUMENT_ROOT'].$nama_file)) {
                $foto = $nama_file;
            } else {
                $foto = '/public/404.png';
            }
            ?>
            <div class="rounded-circle position-relative" style="width:80px;height:80px;">
                <img src="<?=$foto?>" alt="" class="w-100 rounded-circle">
                <?php if($is_verifs[$key] == 1) : ?>
                <div class="position-absolute top-0 end-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-patch-check-fill bg-light rounded-circle" viewBox="0 0 16 16">
                        <path d="M10.067.87a2.89 2.89 0 0 0-4.134 0l-.622.638-.89-.011a2.89 2.89 0 0 0-2.924 2.924l.01.89-.636.622a2.89 2.89 0 0 0 0 4.134l.637.622-.011.89a2.89 2.89 0 0 0 2.924 2.924l.89-.01.622.636a2.89 2.89 0 0 0 4.134 0l.622-.637.89.011a2.89 2.89 0 0 0 2.924-2.924l-.01-.89.636-.622a2.89 2.89 0 0 0 0-4.134l-.637-.622.011-.89a2.89 2.89 0 0 0-2.924-2.924l-.89.01zm.287 5.984-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7 8.793l2.646-2.647a.5.5 0 0 1 .708.708"/>
                    </svg>
                </div>
                <?php endif ?>
            </div>
            <?php endforeach ?>
        </div>
        <div class="col-2 d-flex flex-column align-items-end">
            <p class="m-0"><?=rupiah($hargaFix)?></p>
            <button class="btn btn-success"  data-toggle="modal" data-target="#modalOuter<?=$keyStocks?>">Lihat Nota</button>
            <div class="modal fade" id="modalOuter<?=$keyStocks?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Modal Luar</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body text-dark">
                            <div class="row">
                                <div class="col-10">
                                    <p class="m-0"><?=$log_stock['vendor']?></p>
                                    <p class="m-0"><?=$log_stock['tanggal_beli']?></p>
                                    <p class="m-0" id="totalHarga"><?=rupiah($hargaFix)?></p>
                                </div>
                                <div class="col-2">
                                    <a href="/public/nota-stock/<?=$fotoNota?>" target="_blank" class="btn btn-success">Nota</a>
                                </div>
                            </div>
                            <hr class="">
                            <?php
                            foreach ($produks as $key => $produk) : 
                                $produkId = $produk['produkId'];
                            ?>
                            <div class="row my-2">
                                <div class="col-2 d-flex justify-content-center p-0">
                                    <?php $fotoProduk = $db->query("SELECT `id` FROM `foto` WHERE `id_produk` = '$produkId' AND `is_cover` = 1 ORDER BY is_active DESC")->fetch_assoc()['id'];?>
                                    <img src="<?=($fotoProduk) ? '/public/foto/md/'.$fotoProduk.'.jpg' : '/public/404.png' ?>" alt="" class="rounded-circle" style="width:70px;height:70px;">
                                </div>
                                <div class="col-6 p-0 ps-2">
                                    <p class="text-success m-0"><?=explode(';;',$produk['nama'])[0]?></p>
                                </div>
                                <div class="col-1 p-0 text-primary"><?= $produk['stok_baru'] - $produk['stok_awal'] ?> <?=$produk['unit']?></div>
                                <div class="col-3 p-0 text-end">
                                    <?php if($produk['is_verif'] == 0) : ?>
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalInner<?=$keyStocks?><?=$key?>">verifikasi</button>
                                        <div class="modal fade" id="modalInner<?=$keyStocks?><?=$key?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content shadow-lg">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLongTitle">Verifikasi</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-2 d-flex justify-content-center p-0">
                                                                <img src="<?=($fotoProduk) ? '/public/foto/md/'.$fotoProduk.'.jpg' : '/public/404.png' ?>" alt="" class="rounded-circle" style="width:70px;height:70px;">
                                                            </div>
                                                            <div class="col-10 p-0 ps-2 text-start text-success">
                                                                <p class="m-0"><?=$produk['nama']?></p>
                                                            </div>
                                                            <div class="col-3 p-0 text-end">
                                                                <!-- <button type="button" class="btn btn-success">Ganti</button> -->
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-2"></div>
                                                            <div class="col-3">QTY</div>
                                                            <div class="col-3">Unit</div>
                                                            <div class="col-4">Harbel</div>
                                                        </div>
                                                        <div class="row my-3">
                                                            <div class="col-2">diNota</div>
                                                            <div class="col-3 text-primary"><?=$produk['stok_baru'] - $produk['stok_awal'] ?></div>
                                                            <div class="col-3 text-primary"><?=$produk['unit']?></div>
                                                            <div class="col-4 text-danger">@<?=rupiah($produk['harga'])?></div>
                                                        </div>
                                                        <div class="row alert alert-dark my-3">
                                                            <div class="col-2">DiDB</div>
                                                            <div class="col-3"></div>
                                                            <div class="col-3"></div>
                                                            <div class="col-4">@<?=rupiah($produk['harga_beli'])?></div>
                                                        </div>
                                                        <div class="row my-3">
                                                            <div class="col-2 text-small">Verifikasi: </div>
                                                            <div class="col-3">
                                                                <input type="number" class="form-control border-dark" autocomplete="off" id="qty<?=$keyStocks?><?=$key?>" oninput="validasiVerif(<?=$keyStocks?>,<?=$key?>)" placeholder="qty">
                                                            </div>
                                                            <div class="col-3">
                                                                <input type="text" class="form-control border-dark" autocomplete="off" id="unit<?=$keyStocks?><?=$key?>" oninput="validasiVerif(<?=$keyStocks?>,<?=$key?>)" placeholder="unit">
                                                            </div>
                                                            <div class="col-4">
                                                                <input type="number" class="form-control border-dark" autocomplete="off" id="harbel<?=$keyStocks?><?=$key?>" oninput="validasiVerif(<?=$keyStocks?>,<?=$key?>)" placeholder="harbel">
                                                            </div>
                                                        </div>
                                                        <div class="d-none justify-content-end" id="verif<?=$keyStocks?><?=$key?>">
                                                            <button class="btn btn-primary" onclick="verif(<?=$produk['id']?>,<?=$keyStocks?>,<?=$key?>,<?=$produkId?>)">Kirim</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else : ?>
                                        <p class="m-0"><?=$produk['created_at']?></p>
                                    <?php endif ?>
                                </div>
                            </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach ?>
</section>
<script>
    function validasiVerif(id1, id2) {
        let valid = 0;
        console.log("#qty"+id1+id2);
        let qty = document.querySelector("#qty"+id1+id2).value;
        if (qty != '') {
            valid += 1;
        }
        let unit = document.querySelector("#unit"+id1+id2).value;
        if (unit != '') {
            valid += 1;
        }
        let harbel = document.querySelector("#harbel"+id1+id2).value;
        if (harbel != '') {
            valid += 1;
        }
        if (valid == 3) {
            document.querySelector("#verif"+id1+id2).classList.remove("d-none");
            document.querySelector("#verif"+id1+id2).classList.add("d-flex");
        }else{
            document.querySelector("#verif"+id1+id2).classList.add("d-none");
            document.querySelector("#verif"+id1+id2).classList.remove("d-flex");
        }
    }
    function verif(idLogStock,id1, id2, idP) {
        let qty = document.querySelector("#qty"+id1+id2).value;
        let unit = document.querySelector("#unit"+id1+id2).value;
        let harbel = document.querySelector("#harbel"+id1+id2).value;
        
        var dataToSend = { 
            idLogStock: idLogStock,
            idProduk: idP,
            qty: qty,
            unit: unit,
            harbel: harbel
        };
        fetch('/components/stock/verifStock.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(dataToSend),
        })
        .then(response => response.json())
        .then(datas => {
            // console.log(datas);
            window.location.href = window.location.href;
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    }
</script>