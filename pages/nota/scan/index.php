<?php $_SESSION['last_url'] = $_SERVER[REQUEST_URI]; ?>
<?php include $_SERVER['DOCUMENT_ROOT'].'/components/header/index.php'; ?>
<section class="h-vh-100 container row p-5">
    <?php
    $id = $_GET['jb'];
    $historys = $db->query("SELECT `pesanan`.*, 
    DATE(`pesanan`.created_at) AS pesan_at,
    `customer`.nama, 
    `customer`.alamat, 
    `customer`.wa,
    `bayar`.caraBawa
    FROM `pesanan`
    JOIN `customer` ON `customer`.id = `pesanan`.`customer_id`
    JOIN `bayar` ON `bayar`.pesanan_id = `pesanan`.`id`
    WHERE `pesanan`.id = '$id'");
    foreach ($historys as $key => $history) :
    ?>
    <div class="row bg-lightgray border-bottom border-dark">
        <div class="col-4">
            <h4 class="">NOTA JB<?=$history['id']?></h4>
            <p class="m-0"><?=$history['nama']?> | <?=$history['alamat']?> | <?=$history['wa']?></p>
        </div>
        <div class="col-4">
            <button class="btn btn-danger h-fit" data-bs-toggle="modal" data-bs-target="#level">Level 1</button>
            <div class="modal fade" id="level" tabindex="-1" aria-labelledby="levelLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content bg-dark text-light">
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
                            <div class="row m-0 <?=($key != 0) ? 'border-top pt-3 mt-3' : ''?>">
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
            <p class="m-0">TglPes: <?=$history['pesan_at']?></p>
            <p class="m-0"><?=$history['caraBawa']?></p>
        </div>
        <div class="col-3">
            <p class="m-0">totalbel : <?= $history['nominal_pesanan'] ?></p>
            <p class="m-0">kb : <?= $history['nominal_pesanan']  ?></p>
        </div>
        <div class="col-1"></div>
    </div>
    <?php endforeach ?>
</section>