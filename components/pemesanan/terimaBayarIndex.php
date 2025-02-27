<?php
if (isset($_GET['i'])) {
    $id_pesanan = $_GET['i'];
}
?>
<script>
    $(function() {
        // $("#tanggalBayar").datepicker({
        //     dateFormat: 'yy-mm-dd' // Format tanggal sesuai dengan 'YYYY-MM-DD'
        // });
        // $("#tanggalBawa").datepicker({
        //     dateFormat: 'yy-mm-dd' // Format tanggal sesuai dengan 'YYYY-MM-DD'
        // });
    });
</script>

<sectio0n class="d-flex flex-column justify-content-center p-md-5">
    <?php
    $pesanans = $db->query("SELECT `pesanan`.*,`customer`.`nama`,`customer`.`alamat`,`customer`.`wa` FROM `pesanan` JOIN `customer` ON `customer`.`id` = `pesanan`.`customer_id` WHERE `pesanan`.`id` = '$id_pesanan'");
    foreach ($pesanans as $key => $pesanan) :
        $id_pesanan = $pesanan['id'];
    ?>
    <div class="row">
        <div class="col-md-8 h-100">
            <div class="my-5 alert alert-primary p-2">
                <div class="row alert alert-dark m-0 p-4">
                    <div class="col-md-4 justify-content-end">
                        <h2 class="text-2xl font-bold" style='color:blue'>GS<?=$id_pesanan?></h2>
                        <h2 class="text-2xl font-bold text-green-700"><?=$pesanan['nama'].' | '.$pesanan['alamat']?></h2>
                        <h2 class="text-2xl font-bold" style='color:green'><?=$pesanan['wa']?></h2>
                    </div>
                    <div class="col-md-7">
                        <p class="m-0">Note : <?=$pesanan['note']?></p>
                    </div>
                    <div class="col-md-1 d-flex align-items-center">
                        <button class="w-fit h-fit btn btn-light px-4" onclick="goToKeranjang(<?=$id_pesanan?>)">Edit</button>
                    </div>
                </div>
                <?php
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
                foreach ($pesanan_details as $key => $pesanan_detail) :
                    $bayarPerProduk = 0;
                    $bayarPerProduk = ($pesanan_detail['harga_jual'] + $pesanan_detail['markup']) * $pesanan_detail['jumlah'];
                    $total += $bayarPerProduk;
                ?>
                    <div class="row mt-4 px-4 text-dark m-0">
                        <div class="col-1">
                            <h5 class=""><?=$pesanan_detail['jumlah']?></h5>
                        </div>
                        <div class="col-11 col-md-7 d-flex align-items-center gap-2">
                            <?php
                            $produkId = $pesanan_detail['produkId'];
                            $foto = $db->query("SELECT `id` FROM `foto` WHERE id_produk = '$produkId' AND is_active = 1")->fetch_assoc()['id'];
                            ?>  
                            <img src="<?= ($pesanan_detail['komentar']) ? ($pesanan_detail['foto'] ? '/public/foto/temp/'.$pesanan_detail['foto'] : '/public/foto/md/custom.jpg') : ($foto ? '/public/foto/md/'.$foto.'.jpg' : '/public/404.png') ?>" alt="" class="rounded-circle" style="width:70px; height:70px;">
                            <h5 class="m-0"><?=($pesanan_detail['nama']) ? $pesanan_detail['nama'] : $pesanan_detail['komentar']?></h5>
                        </div>
                        <div class="col-md-2 col-6">
                            <p class="m-0"><?=rupiah($pesanan_detail['harga_jual']);?> <?= (intVal($pesanan_detail['markup']) > 0) ? ' + '.$pesanan_detail['markup'] : ''?></p>
                        </div>  
                        <div class="col-md-2 col-6 text-end text-md-start">
                            <h5 class="<?=($pesanan_detail['markup']) ? 'fst-italic underline text-danger' : ''?>"><?=rupiah($bayarPerProduk)?></h5>
                        </div>
                        
                        <?php 
                        if (!empty($pesanan_detail['request'])){ ?>

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
                                <h5 class="m-0"><?=$request[1]?></h5>
                            </div>
                            <div class="col-md-2 col-6">
                                <p class="m-0"><?=$request[2]?></p>
                            </div>
                            <div class="col-md-2 col-6 text-end text-md-start">
                                <h5 class=""><?=$request[2] * $request[0]?></h5>
                            </div>
                            <?php endforeach ?>
                        </div>
                                <?php }?>
                    </div>
                <?php endforeach ?>
                <div class="">
                    <?php
                    $bayars = $db->query("SELECT * FROM `bayar` WHERE `pesanan_id` = '$id_pesanan'");
                    $qtyBayar = $bayars->num_rows;
                    $totalBayar = 0;
                    foreach ($bayars as $key => $bayar) :
                        $totalBayar += $bayar['nominal_bayar'];
                    ?>
                    <div class="row">
                        <div class="col-4 col-md-4">Status Bayar</div>
                        <div class="col-2 col-md-4">Jalur</div>
                        <div class="col-5 col-md-4">Nominal Bayar</div>
                        <div class="col-4 col-md-4">Bayar ke <?=$ey + 1?></div>
                        <div class="col-2 col-md-4"><?=$bayar['jalur']?></div>
                        <div class="col-5 col-md-4"><?=$bayar['nominal_bayar']?></div>
                        <div class="col-12 d-flex justify-content-start fw-bold gap-2">
                            <p class="m-0">Total Bayar:</p>
                            <p class="m-0"><?=total($totalBayar)?></p>
                        </div>
                    </div>
                    <?php endforeach ?>
                    <div class="d-flex justify-content-end gap-2 mx-md-5 mx-3 mt-5">
                        <p class="m-0">Total :</p>
                        <h5 class="" id="totalHarga"><?=rupiah($total)?></h5>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mx-md-5 mx-3 mt-5">
                        <h3 class="m-0">Kurang Bayar :</h3>
                        <h3 class="" id="totalHarga"><?=rupiah($total)?></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 h-100 py-5">
            <form action="/components/pemesanan/qr.php" method="post" class="w-100 h-100 border border-dark text-dark px-3" style="width:360px;">
                <div class="row my-3">
                    <div class="btn btn-primary" onclick="completeIsi()">Quick Pay</div>
                    <input type="number" id="isQuick" class="form-control border border-dark d-none" name="isQuick" value="0">
                </div>

                <div class="row my-3">
                    <div class="col-4 d-flex justify-content-end">Jalur Bayar:</div>
                    <div class="col-8">
                        <select class="form-select border border-dark" id="jalurBayar" aria-label="Default select example"  onChange="cekButtonKirim()" name="bayar">
                            <option value="" selected>--- Jalur Bayar ---</option>
                            <option value="Cash">Cash</option>
                            <option value="BCA">BCA</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                </div>


                <div class="row my-3">
                    <div class="col-4 d-flex justify-content-end">Nominal Bayar:</div>
                    <div class="col-8">
                        <input type="number" id="bayar" class="form-control bg-light border border-dark" min="0" oninput="cekButtonKirim()" name="nominalBayar">
                    </div>
                </div>
                <div class="row my-3">
                    <div class="col-4 d-flex justify-content-end">kurangbayar:</div>
                    <div class="col-8" id="kurangBayar"><?=$total?></div>
                </div>
                <div class="row my-3">
                    <div class="col-4 d-flex justify-content-end">Tgl Bayar:</div>
                    <div class="col-8">
                        <input type="date" id="tanggalBayar" class="form-control border border-dark bg-light" onChange="cekButtonKirim()" name="tanggalBayar">
                    </div>
                </div>
                
                <div class="row my-3 d-none">
                    <div class="col-4 d-flex justify-content-end">Cara Bawa:</div>
                    <div class="col-8">
                        <select class="form-select border border-dark" id="caraBawa" aria-label="Default select example" onChange="cekButtonKirim()" name="bawa">
                            <option value="">--- Cara Bawa ---</option>
                            <option value="dibawa" selected>Langsung Dibawa</option>
                            <option value="besok">Besok Tanggal:</option>
                        </select>
                        <input type="date" id="tanggalBawa" name="tanggalBawa" class="form-control border border-dark d-none">
                    </div>
                </div>
                <input type="text" class="d-none" name="i" value="<?=$id_pesanan?>">
                <div class="d-flex justify-content-end d-none" id="btn-kirim">
                    <button class="btn btn-success" name="bayarNow">Kirim</button>
                </div>
            </form>
        </div>
    </div>
    <?php endforeach ?>
</sectio0n>
<script>
    function completeIsi() {
        let hargaTotal = document.querySelector("#totalHarga").innerHTML;
        document.querySelector("#bayar").value = document.querySelector("#totalHarga").innerHTML.replace(/Rp/g, "").replace(/\./g, "");
        document.querySelector("#kurangBayar").innerHTML = 0;
        document.querySelector("#tanggalBayar").value = dateNow();
        document.querySelector("#jalurBayar").options[3].selected = true;
        document.querySelector("#caraBawa").options[1].selected = true;
        document.querySelector("#btn-kirim").classList.remove("d-none");
        document.querySelector("#isQuick").value = 1;
    }
    document.querySelector("#caraBawa").addEventListener("change", function () {
        let caraBawa = this.value;
        console.log(caraBawa);
        if(caraBawa == 'besok'){
            document.querySelector("#tanggalBawa").classList.remove("d-none");
        }else{
            document.querySelector("#tanggalBawa").classList.add("d-none");
        }
    })
    document.querySelector("#bayar").addEventListener("input", function () {
        let kurang = document.querySelector("#totalHarga").innerHTML.replace(/Rp/g, "").replace(/\./g, "") - this.value;
        document.querySelector("#kurangBayar").innerHTML = kurang;
    })
    function goToKeranjang(id) {
        let konfirmasi = confirm("Yakin ingin mengedit pesanan ini?");
        if (konfirmasi) {
            datas = {
                id_pesanan : id
            }
            fetch('/components/pemesanan/goToKeranjang.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(datas),
            })
            .then(response => response.text())
            .then(datas => {
                console.log(datas);
                if (datas == 'success') {
                    window.location.href = '/pages/';
                }
            })
            .catch((error) => {
                console.error('Error:', error);
            });
        }
    }
    function cekButtonKirim() {
        let count = 0;
        if (document.querySelector("#bayar").value != '') {
            count += 1;
        }
        if (document.querySelector("#tanggalBayar").value != '') {
            count += 1;
        }
        if (document.querySelector("#jalurBayar").value != '') {
            count += 1;
        }
        // if (document.querySelector("#caraBawa").value != '') {
        //     count += 1;
        // }
        if (count == 3) {
            document.querySelector("#btn-kirim").classList.remove("d-none");
        }else{
            document.querySelector("#btn-kirim").classList.add("d-none");
        }
    }
</script>