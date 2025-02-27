<?php $currentURL = $_SERVER[REQUEST_URI]; 

$navList = [
    "0" => [
        "nama" => "Home",
        "url" => "/",
        "jabatan" => "cs,super-admin"
    ],
    "1" => [
        "nama" => "Jelajah",
        "url" => "/pages/",
        "jabatan" => "cs,super-admin"
    ],
    "2" => [
        "nama" => "Jelajah QR",
        "url" => "/pages/qr/",
        "jabatan" => "kasir,super-admin"
    ],
    "3" => [
        "nama" => "History Penjualan",
        "url" => "/pages/history/",
        "jabatan" => "kasir,cs,super-admin"
    ],
    "4" => [
        "nama" => "Userman",
        "url" => "/pages/userman/",
        "jabatan" => "super-admin"
    ],
];

$navList = array_filter($navList, function ($item) {
    global $jabatan;
    // Ubah string jabatan menjadi array
    $jabatanArray = array_map('trim', explode(',', $item['jabatan']));
    
    // Cek apakah "super-admin" ada di dalam array
    return in_array($jabatan, $jabatanArray);
});

?>
<nav class="navbar navbar-expand-lg bg-success sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand text-light d-none d-md-block" href="/">Navbar</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <div class="w-100 d-md-flex justify-content-between">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <?php foreach ($navList as $key => $list) : ?>
                    <li class="nav-item">
                        <a class="nav-link active text-light" aria-current="page" href="<?=$list['url']?>"><?=$list['nama']?></a>
                    </li>
                    <?php endforeach ?>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <span class="me-2 fw-bold"><?=$username?></span>    
                        <a href="/pages/logout.php" class="btn btn-danger">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
<script type="text/javascript" src="/function/convertIdr.js"></script>