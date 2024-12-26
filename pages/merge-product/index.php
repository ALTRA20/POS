<?php include $_SERVER['DOCUMENT_ROOT'].'/components/header/index.php'; 
if(!isset($_SESSION["username"])){
    echo "<script>window.location.href = '/pages/login.php'</script>";
}
?>
<?php
$_SESSION['last_url'] = $_SERVER[REQUEST_URI];
?>
<style>
    .hr {
        border: none;
        height: 1px;
        background-color: white;
        margin: 20px 0;
    }
</style>
<script>
    document.title = 'Merge Product';
</script>
<div class="text-dark">
    <h1 class="text-center my-4">Merge Products</h1>
    <div class="row h-vh-70 text-dark">
        <div class="col-6 border-end border-dark d-flex flex-column justify-content-center align-items-center position-relative">
            <h2 class="">
                Produk Utama
                <span class="pointer"  id="chooseProsuk" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                    </svg>
                </span>
            </h2>
            <div class="position-relative" style="width:400px;">
                <img src="" alt="" id="foto1" class="w-100">
                <input type="text" class="d-none" id="id1">
            </div>
            <h5 class="" id="nama1"></h5>
            <div class="position-absolute bg-light p-2 rounded-circle" style="right:-1.3em">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                </svg>
            </div>
        </div> 
        <div class="col-6 border-end border-dark d-flex flex-column justify-content-center align-items-center position-relative">
            <h2 class="">
                Produk Ke 2
                <span class="pointer"  id="chooseProsuk" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                    </svg>
                </span>
            </h2>
            <div class="position-relative" style="width:400px;">
                <img src="" alt="" id="foto2" class="w-100">
                <input type="text" class="d-none" id="id2">
            </div>
            <h5 class="" id="nama2"></h5>
        </div> 
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Pilih Produk</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" id="closePilihProduk" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control border-dark" id="searchProduct" placeholder="Masukkan nama barang" onclick="produks(this.value);this.select();" oninput="produks(this.value)" autocomplete="off">
                    <input type="text" id="produkke" class="d-none" value="1">
                    <div class="hr bg-dark"></div>
                    <div class="" id="products">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <button class="w-100 btn btn-lg btn-primary py-3 mt-3" id="merge">Merge</button>
</div>
<script>
    document.querySelectorAll("#chooseProsuk").forEach((produk, index) => {
        produk.addEventListener('click', function(){
            document.querySelector("#produkke").value = index+1;
        })
    });
    function produks(search) {
        var datas = { 
            search: search,
            page: 1,
            userId: <?=$userId?>
        };
        fetch('/components/index/products.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(datas),
        })
        .then(response => response.json()) // Parse the JSON response
        .then(response => {
            let products = '';
            let datas = response['datas'];
            datas.forEach(data => {
                let gambarsProduct = data['fotoProduk'];
                let gambar = '';
                if (gambarsProduct.length > 0) {
                    gambar = '/public/foto/md/' + gambarsProduct[0][0] + '.jpg';
                } else {
                    gambar = '/public/404.png';
                }
                products += `<div class="w-100 d-flex gap-2 align-items-center border border-dark rounded customerCard p-2 my-2" onclick="choose(${data['id']},'${data['nama']}','${gambar}')">
                <img class="" src="${gambar}" style="width:50px;"/>
                <p class="m-0">${data['nama']}</p>
                </div>`;
            });
            document.querySelector("#products").innerHTML = products;
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    }
    function choose(id, nama, img) {
        let produkke = document.querySelector("#produkke").value;
        document.querySelector("#foto"+produkke).src = img;
        document.querySelector("#nama"+produkke).innerHTML = nama;
        document.querySelector("#id"+produkke).value = id;
        document.querySelector("#closePilihProduk").click();
    }

    document.querySelector("#merge").addEventListener('click', function(){
        let id1 = document.querySelector("#id1").value;
        let id2 = document.querySelector("#id2").value;
        var datas = { 
            id1: id1,
            id2: id2
        };
        console.log(datas);
        fetch('mergeAction.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(datas),
        })
        .then(response => response.text()) // Parse the JSON response
        .then(response => {
            window.location.href = '/pages/?ikan='+response;
            console.log(response);
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    })
</script>
<?php include $_SERVER['DOCUMENT_ROOT'].'/components/footer/index.php'; ?>