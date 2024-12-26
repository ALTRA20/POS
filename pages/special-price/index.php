<?php
session_start();
$_SESSION['last_url'] = $_SERVER[REQUEST_URI];
?>
<?php include $_SERVER['DOCUMENT_ROOT'].'/components/header/index.php'; 
if(!isset($_SESSION["username"])){
    echo "<script>window.location.href = '/pages/login.php'</script>";
}?>
<section class="bg-success">
    <div class="container py-5">
        <div class="d-flex justify-content-between">
            <h2 class="">Special Price</h2>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">+</button>
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog text-dark">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Buat Special Price</h1>
                            <button type="button" class="btn-close" id="btn-close-insert" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="">
                                <label for="searchCustomer" class="">Nama</label>
                                <input type="text" class="form-control d-none" id="idCustomer" name="idCustomer">
                                <input type="text" class="form-control border border-dark" id="searchCustomer" onclick="searchCustomer(this.value); this.select();" oninput="searchCustomer(this.value)" placeholder="">
                                <div class="" id="list-customer"></div>
                            </div>
                            <div class="">
                                <label for="searchProduk" class="">produk</label>
                                <input type="text" class="form-control d-none" id="idProduk" name="idProduk">
                                <input type="text" class="form-control border border-dark" id="searchProduk" onclick="searchProduk(this.value); this.select();" oninput="searchProduk(this.value)" placeholder="">
                                <div class="" id="list-produk"></div>
                            </div>
                            <div class="">
                                <label for="markup" class="">Markup</label>
                                <input type="number" class="form-control border border-dark" id="markup" name="markup" placeholder="">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" id="simpan" onclick="simpan()" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php 
        $specialsPrice = $db->query("SELECT `special_price`.id, `customer`.nama AS customer, `produk`.nama AS produk, `special_price`.markup FROM `special_price` LEFT JOIN customer ON `customer`.id = `special_price`.user_id LEFT JOIN produk ON `produk`.id = `special_price`.id_produk");
        foreach ($specialsPrice as $key => $specialPrice) :
        ?>
        <div class="row bg-light text-dark p-3 my-2">
            <div class="col-3"><?=$specialPrice['customer']?></div>
            <div class="col-7"><?=$specialPrice['produk']?></div>
            <div class="col-1"><?=$specialPrice['markup']?></div>
            <div class="col-1">
                <button type="button" class="btn btn-danger" onclick="hapus(<?=$specialPrice['id']?>)">Hapus</button>
            </div>
        </div>
        <?php endforeach ?>
    </div>
</section>
<script>
    function hapus(id) {
        let konfirmasi = confirm("Apakah anda yakin ingin menghapus?");
        if (konfirmasi) {
            var dataToSend = { 
                id: id
            };
            fetch('hapus.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(dataToSend),
            })
            .then(response => response.json())
            .then(datas => {
                alert(datas.status); 
                window.location.href = window.location.href;
            })
            .catch((error) => {
                console.error('Error:', error);
            });
        }
    }
    function simpan() {
        let idCustomer = document.querySelector('#idCustomer').value;
        let idProduk = document.querySelector('#idProduk').value;
        let markup = document.querySelector('#markup').value;
        var dataToSend = { 
            idCustomer: idCustomer,
            idProduk: idProduk,
            markup : markup
        };
        fetch('tambah.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(dataToSend),
        })
        .then(response => response.json())
        .then(datas => {
            console.log(datas); 
            document.querySelector("#btn-close-insert").click();
            window.location.href = window.location.href;
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    }
    function chooseCustomer(id,nama) {
        document.querySelector('#idCustomer').value = id;
        document.querySelector('#searchCustomer').value = nama;
        document.querySelector('#list-customer').innerHTML = '';
    }
    function chooseProduk(id,nama) {
        document.querySelector('#idProduk').value = id;
        document.querySelector('#searchProduk').value = nama;
        document.querySelector('#list-produk').innerHTML = '';
    }
    function searchCustomer(search) {
        var dataToSend = { 
            name: search
        };
        fetch('/components/customer/get.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(dataToSend),
        })
        .then(response => response.json())
        .then(datas => {
            let elements = '';
            datas.forEach(data => {
                elements += `<div class="my-1 p-2 border border-dark customerCard" onclick="chooseCustomer(${data[0]},'${data[1]}')">
                        <p class="m-0">${data[1]} - ${data[3]}</p>
                    </div>`;
            });
            console.log(datas);    
            document.querySelector('#list-customer').innerHTML = elements;
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    }

    function searchProduk(search) {
        var dataToSend = { 
            search: search,
            limit : 5
        };
        fetch('/components/tambah-product/getProduk.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(dataToSend),
        })
        .then(response => response.json())
        .then(datas => {
            let elements = '';
            datas.forEach(data => {
                elements += `<div class="row mt-3" onclick="chooseProduk(${data['id']},'${data['nama']}')">
                        <div class="col-2">
                            <div class="border border-dark" style="width:70px;height:70px;">
                                <img src="/public/foto/md/${data['foto']}.jpg" alt="" class="w-100 h-100">
                            </div>
                        </div>
                        <div class="col-10 d-flex align-items-center">
                            <p class="m-0">${data['nama']}</p>
                        </div>
                    </div>`;
            });
            console.log(datas);    
            document.querySelector('#list-produk').innerHTML = elements;
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    }
</script>
<?php include $_SERVER['DOCUMENT_ROOT'].'/components/footer/index.php'; ?>