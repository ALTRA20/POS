<?php
session_start();
$_SESSION['last_url'] = $_SERVER[REQUEST_URI];
?>
<?php
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
    <script type="text/javascript" src="/function/convertIdr.js"></script>
</head>
<body>
    <?php
    function format_rupiah($number) {
        return 'Rp ' . number_format($number, 0, ',', '.');
    }
    include $_SERVER['DOCUMENT_ROOT'].'/components/header/navbarKatalog.php'; 
    ?>
    <style>
        .hr {
            border: none;
            height: 1px;
            background-color: white;
            margin: 20px 0;
        }
        @keyframes toFull {
            0% {width: 0%;}
            100% {width: 100%;}
        }
        .animasi:hover{
            background: #495057;
        }
        @media (min-width: 1200px){
            .modal-xl {
                --bs-modal-width: 11400px !important;
            }
        }
    </style>
    <?php
        $_SESSION['last_url'] = $_SERVER[REQUEST_URI];
    ?>
    <script>
        document.title = 'Bikin Katalog Sembako';
        function idCard(jumlahBarang = null, img = null, namaProduk = null, hargaSatuan = null, hargaTotal = null) {
            let card = '<div class="row text-dark p-4 mx-0 bg-success">';

            if (jumlahBarang) {
                card += '<div class="col-1">' +
                    '<h5 class="">' + jumlahBarang + '</h5>' +
                    '</div>';
            }

            card += '<div class="col-11 col-md-7 d-flex align-items-center gap-2">';
            if (img) {
                card += '<img src="/public/foto/md/' + img + '.jpg" alt="" class="rounded-circle" style="width:70px; height:70px;">';
            }
            card += '<p class="m-0">' + namaProduk + '</p>' +
                '</div>';

            if (hargaSatuan) {
                card += '<div class="col-md-2 col-6">' +
                    '<p class="m-0">Rp ' + hargaSatuan + '</p>' +
                    '</div>';
            }

            if (hargaTotal) {
                card += '<div class="col-md-2 col-6 text-end text-md-start">' +
                    '<p class="m-0">Rp ' + hargaTotal + '</p>' +
                    '</div>';
            }
            card += '</div>';
            document.write(card);
        }
    </script>
    <div class="h-vh-93 overflow-hidden d-flex px-3" style='background-color:darkblue'>
        <div class="p-4" id="cart" style="width:30%;background-color:darkblue">
            <h5 class=""><a href='../'>&laquo;</a> Katalog</h5>
            <div class="overflow-auto" id="cartMain" style="height: 83%;">
                <div class="d-flex align-items-center gap-3 pt-3 " id="cart-name">
                    <div class="pointer animasi p-2" data-bs-toggle="modal" data-bs-target="#cart-name-modal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-book-fill" viewBox="0 0 16 16">
                            <path d="M8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783"/>
                        </svg>
                    </div>
                    <div class="modal fade" id="cart-name-modal" tabindex="-1" aria-labelledby="cart-name-modalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-md">
                            <div class="modal-content bg-lightgray">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Cari Katalog</h1>
                                    <button type="button" class="btn-close" id="btn-close-cart-name-modal" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input autocomplete="off" type="text" class="form-control text-primary border border-dark" placeholder="masukan nama" oninput="cariCustomer(this)" onclick="cariCustomer(this)">
                                    <div class="hr bg-dark"></div>
                                    <div class="mt-3" id="katalogs">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="w-100 m-0" id="namaCustomer">
                        <span class="text-danger p-2 bg-dark">Pilih Katalog</span>
                    </p>
                    <p class="m-0 d-none" id="ik"></p>
                </div>
                
                <div class="" id="cart-produk" style="background: #206320;padding:15px">

                </div>
                <div class="d-md-none" id="serachOverlay" role="search">
                    <div class="bg-danger py-2 px-3 rounded-start pointer" id="tag">#</div>
                    <input onclick='this.select();' style='background-color: white !important;color:green !important' autocomplete="off" class="form-control me-2 text-light rounded-end" id="search" type="search" placeholder="Search" aria-label="Search" style='background-color:white'/>
                </div>
            </div>
            <div class="w-100 d-flex align-items-center justify-content-end d-none" id="btn-lanjut" style="height: 10%;">
                <button class="btn btn-primary" onclick="toDetail(<?=$userId?>)">Lanjut</button>
            </div>
            <div class="d-flex align-items-center gap-3 py-3" id="cart-comment">
                <div class="" id="" data-bs-toggle="modal" data-bs-target="#cart-comment-modal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-chat-left-quote-fill" viewBox="0 0 16 16">
                        <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4.414a1 1 0 0 0-.707.293L.854 15.146A.5.5 0 0 1 0 14.793zm7.194 2.766a1.7 1.7 0 0 0-.227-.272 1.5 1.5 0 0 0-.469-.324l-.008-.004A1.8 1.8 0 0 0 5.734 4C4.776 4 4 4.746 4 5.667c0 .92.776 1.666 1.734 1.666.343 0 .662-.095.931-.26-.137.389-.39.804-.81 1.22a.405.405 0 0 0 .011.59c.173.16.447.155.614-.01 1.334-1.329 1.37-2.758.941-3.706a2.5 2.5 0 0 0-.227-.4zM11 7.073c-.136.389-.39.804-.81 1.22a.405.405 0 0 0 .012.59c.172.16.446.155.613-.01 1.334-1.329 1.37-2.758.942-3.706a2.5 2.5 0 0 0-.228-.4 1.7 1.7 0 0 0-.227-.273 1.5 1.5 0 0 0-.469-.324l-.008-.004A1.8 1.8 0 0 0 10.07 4c-.957 0-1.734.746-1.734 1.667 0 .92.777 1.666 1.734 1.666.343 0 .662-.095.931-.26z"/>
                    </svg>
                </div>
                <div class="modal fade" id="cart-comment-modal" tabindex="-1" aria-labelledby="cart-comment-modalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content bg-light text-dark">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="cart-comment-modalLabel">Catatan</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <textarea name="" id="catatanTextarea" cols="30" rows="10" class="form-control border border-dark text-dark"></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" id="btn-close-note-modal" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" onclick="tambahCatatan(<?=$userId?>)">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="m-0" id="catatan"></p>
            </div>
        </div>
        <section style="width:75%;">
            <div class="d-flex justify-content-center flex-wrap gap-2 bg-light py-4 h-90 overflow-auto" id="overlay">
                <div class="text-dark text-center">
                    <h1 class="">Pilih Katalog Terlebih Dahulu</h1>
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="currentColor" class="bi bi-book-fill text-dark" viewBox="0 0 16 16">
                        <path d="M8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783"/>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="w-100 d-flex justify-content-center gap-2 bg-light px-5 py-3 position-relative" id="pages">
            </div>
        </section>
    </div>

    <script>
        document.querySelector('#tag').addEventListener('click', function () {
            document.querySelectorAll('input[type="search"]')[1].value = '#';
            document.querySelectorAll('input[type="search"]')[1].focus();
        });
        document.querySelectorAll('input[type="search"]').forEach(element => {
            element.addEventListener('input', function () {
                sendData(<?=$userId?>,1,this.value);
                console.log('a');
            });
            element.addEventListener('change', function () {
                sendData(<?=$userId?>,1,this.value);
            });
        });
        function tambahJual(edit) {
            let id = "#hargaJual";
            let borderColor = "border-dark";
            
            let hargaJual = '<div class="row"><div class="col-6"><label for="jumlahBarang" class="">Jumlah Barang</label><input autocomplete="off" type="number" class="form-control border '+borderColor+' mb-4" id="jumlahBarang"></div><div class="col-6"><label for="harga" class="">Harga</label><input autocomplete="off" type="text" class="form-control border '+borderColor+' mb-4" id="hargaBarang"></div></div>';
            document.querySelector(id).insertAdjacentHTML('beforeend', hargaJual);
        } 

        const addFiturAddMenu = (element) => {
            document.getElementById('addFiturAddMenu').classList.add('d-none');
            document.getElementsByClassName('tambahKategoriMenu')[0].classList.toggle('d-none');
        }
        const addToKategoriMenuEdit = (element,no) => {
            // console.log(element.innerHTML);
            document.getElementById('kategoriEdit'+no).value = element.innerHTML;
        }
        const addSelect = () => {
            // Get the select element by its ID
            var selectElement = document.getElementById('kategori_produk');

            // Create a new option element
            var optionElement = document.createElement('option');
            let inputan = document.querySelector('.tambahKategoriMenu input');
            const alertElement = document.getElementById('alertSuccessAddKategoriMenu');
            if (inputan.value != '') {
                optionElement.value = inputan.value;
                optionElement.textContent = inputan.value;
                // Append the option element to the select element
                selectElement.appendChild(optionElement);
                alertElement.innerHTML = "Kategori menu baru udah ditambahkan";
                alertElement.classList.remove('text-light');
                alertElement.classList.remove('bg-danger');
                alertElement.classList.add('text-success');
                alertElement.classList.add('bg-light');
                document.querySelector('.tambahKategoriMenu input').classList.toggle('d-none');
                document.querySelector('.tambahKategoriMenu button').classList.toggle('d-none');
                inputan.value = '';
            }else{
                alertElement.classList.remove('text-success');
                alertElement.classList.remove('bg-light');
                alertElement.classList.add('text-light');
                alertElement.classList.add('bg-danger');
                alertElement.innerHTML = "Tidak boleh kosong";
                document.querySelector('.tambahKategoriMenu input').classList.toggle('d-none');
                document.querySelector('.tambahKategoriMenu button').classList.toggle('d-none');
            }
            // Function to show the element with animation
            function showAlert() {
                alertElement.classList.remove('d-none');
                alertElement.classList.add('alert-show');
            }

            // Function to hide the element with animation
            function hideAlert() {
                alertElement.classList.remove('alert-show');
                alertElement.classList.add('alert-fade');
                setTimeout(() => {
                    alertElement.classList.add('d-none');
                    alertElement.classList.remove('alert-fade');
                    document.querySelector('.tambahKategoriMenu input').classList.toggle('d-none');
                    document.querySelector('.tambahKategoriMenu button').classList.toggle('d-none');
                }, 300); // Adjust the timing to match your transition duration
            }
            // Show the alert
            showAlert();

            // Hide the alert after 3 seconds
            setTimeout(hideAlert, 3000);
        }



        function choosePerent(element, id, forElement) {
            forElement = (forElement) ? forElement : '';
            document.querySelectorAll("#produksParent"+forElement).forEach(produkParent => {
                if(produkParent != element){
                    produkParent.remove();
                }
            });
            document.querySelector("#idParent"+forElement).value = id;
        }

        function productsForChild(userId, search = null, page = 1, forElement = null) {
            var dataToSend = { 
                search: search,
                page: page,
                userId: userId
            };
            let produks = '';
            fetch('/components/index/products.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(dataToSend),
            })
            .then(response => response.json())
            .then(datas => {
                forElement = (forElement) ? forElement : '';
                datas["datas"].forEach(data => {
                    let gambarsProduct = data['fotoProduk'];
                    let gambar = '';
                    if (gambarsProduct.length > 0) {
                        gambar = '/public/foto/md/' + gambarsProduct[0][0] + '.jpg';
                    } else {
                        gambar = '/public/404.png';
                    }
                    produks += `<div class="w-100 d-flex gap-2 align-items-center border border-dark pointer my-2" id="produksParent${forElement}" onclick="choosePerent(this, ${data['id']}, ${forElement})">
                                <img src="${gambar}" alt="" class="p-0 m-0" style="width:45px;height:45px">
                                <b class="m-0">${data['nama']}</b>
                            </div>`;
                });
                document.querySelector("#productsForChild"+forElement).innerHTML = produks;
            })
            .catch((error) => {
                console.error('Error:', error);
            });
        }

        function openModalProduk(id, nama) {
            sendData(<?=$userId?>, 1, nama);
            setTimeout(() => {
                let modal = document.querySelector('[data-bs-target="#exampleModal'+id+'"]');
                modal.click();
            }, 50);
        }

        function addCustomProduk() {
            let idCustomer = document.querySelector("#ic").innerHTML;
            let namaProduk = document.querySelector("#namaProdukCustomProduk").value;
            let jumlah = document.querySelector("#jumlahCustomProduk").value;
            let harga = document.querySelector("#hargaCustomProduk").value;
        
            let fotoProduk = document.querySelector("#src").value;
            
            let datas = {
                idCustomer: idCustomer,
                namaProduk: namaProduk,
                jumlah: jumlah,
                harga: harga,
                fotoProduk: fotoProduk
            };
            
            fetch('/components/tambah-product/customProduk.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(datas),
            })
            .then(response => response.json())
            .then(datas => {
                document.querySelector("#namaProdukCustomProduk").value = '';
                document.querySelector("#jumlahCustomProduk").value = '1';
                document.querySelector("#hargaCustomProduk").value = '';
                getKeranjang(document.querySelector("#ik").innerHTML,<?=$userId?>);
                document.querySelector("#closeAddCustomProduk").click();
                document.querySelector("#file-input").value = '';
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        function uploadFile(file) {
            var formData = new FormData();
            formData.append('file', file);

            // Lakukan request AJAX untuk mengirim file ke server
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '/components/tambah-product/customProduk.php', true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    document.querySelector("#src").value = xhr.responseText;
                } else {
                    // Terjadi kesalahan saat mengupload file
                    console.error('Terjadi kesalahan saat mengupload file:', xhr.statusText);
                }
            };
            xhr.onerror = function() {
                console.error('Terjadi kesalahan koneksi.');
            };
            xhr.send(formData);
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('file-input').addEventListener('change', function(e) {
                var file = e.target.files[0];
                if (!file) {
                    console.error('No file selected.');
                    return;
                }
                var reader = new FileReader();
                
                reader.onload = function(e) {
                    var imgElement = document.getElementById('img-produk-custom');
                    if (!imgElement) {
                        console.error('Image element not found.');
                        return;
                    }
                    imgElement.src = e.target.result;
                };
                reader.readAsDataURL(file);
                uploadFile(file);
            });
        });
        

        function closeModal(id) {
            document.querySelector("#card"+id).click();
        }
        function isFavorite(idGambar, idProduk) {
            let judul = document.querySelector("#namaEdit"+idProduk).value;
            datas = {
                idGambar : idGambar,
                idProduk : idProduk
            }
            fetch('/components/tambah-product/is_favorit.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(datas),
            })
            .then(response => response.json())
            .then(datas => {
                window.location.href = '/pages/?ikan='+judul;
            })
            .catch((error) => {
                console.error('Error:', error);
            });
        }
        function hapusDariKeranjang(id,userId) {
            datas = {
                id : id
            }
            fetch('detail_temp/hapus.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(datas),
            })
            .then(response => response.text())
            .then(datas => {
                getKeranjang(document.querySelector("#ik").innerHTML,<?=$userId?>);
            })
            .catch((error) => {
                console.error('Error:', error);
            });
        }
        function toPesanan(userId) {
            let datas = [];
            let id_costumer = document.querySelector("#ic").innerHTML;
            let nominal_pemesanan = document.querySelector("#price").innerHTML;
            let catatan = document.querySelector("#catatan").innerHTML;
            nominal_pemesanan = nominal_pemesanan.replace("Rp&nbsp;", "").replace(".", "");
            let barang = [];
            document.querySelectorAll("#hj").forEach((element, index) => {
                let hargaJual = element.innerHTML;
                hargaJual = hargaJual.replace(/Rp\s|Rp&nbsp;|[.]/g, '');
                barang[index] = { "harga-Jual": hargaJual };
            });


            document.getElementsByName("qty").forEach((input, index) => {
                if (barang[index]) {
                    barang[index]["jumlah"] = input.value; // Update the existing object
                } else {
                    barang[index] = { "jumlah": input.value }; // Create a new object if it doesn't exist
                }
            });
            
            document.querySelectorAll("#ip").forEach((input, index) => {
                let ip = input.innerHTML.replace("#", "");
                if (barang[index]) {
                    barang[index]["produk_id"] = ip; // Update the existing object
                } else {
                    barang[index] = { "produk_id": ip }; // Create a new object if it doesn't exist
                }
            });
            datas = {
                id_costumer : id_costumer,
                nominal_pemesanan : nominal_pemesanan,
                note : catatan,
                dataBarang : barang,
                userId : userId
            }
            console.log(datas);
            fetch('/components/pemesanan/insertPemesanan.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(datas),
            })
            .then(response => response.text())
            .then(datas => {
                // console.log(datas);
                window.location.href = '/pages/pemesanan/terimaBayar.php?i='+datas;
            })
            .catch((error) => {
                console.error('Error:', error);
            });
        }
        function tambahCatatan(userId) {
            let note = document.querySelector("#catatanTextarea").value;
            let datas = {
                userId : userId,
                note : note
            }
            fetch('/components/keranjang/tambahNote.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(datas),
            })
            .then(response => response.text())
            .then(datas => {
                document.querySelector("#catatan").innerHTML = note;
            })
            .catch((error) => {
                console.error('Error:', error);
            });
            document.querySelector("#btn-close-note-modal").click();
        }

        function addRequest() {
            let requests = document.querySelector("#requests");
        }
        function ubahCustomer(idKatalog,nama) {
            toTemp(idKatalog,<?=$userId?>);
            setTimeout(() => {
                getKeranjang(idKatalog,<?=$userId?>);
            }, 100);
            var search = getParameterByName('ikan');
            window.onload = sendData(idKatalog,1, search ? search : '');
            document.querySelector("#namaCustomer").innerHTML = nama;
            document.querySelector("#ik").innerHTML = idKatalog;
            document.querySelector("#btn-close-cart-name-modal").click();
            document.querySelector("#serachOverlay").classList.remove("d-md-none");
            document.querySelector("#serachOverlay").classList.add("d-md-flex");
        }
        function tambahCustomer(userId) {
            let namaCustomerBaru = document.querySelector("#namaCustomerBaru").value;
            datas = {
                user_id : userId,
                nama_katalog : namaCustomerBaru
            };
            fetch('tambah.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(datas),
            })
            .then(response => response.json())
            .then(datas => {
                let customersElement = document.querySelector("#katalogs");
                let customer = '';
                if(datas.length != 0) {
                    datas.forEach(data => {
                        customer += `<div class="d-flex gap-2 p-3 border border-dark my-2" onclick="ubahCustomer(${data['id']},'${data["nama_katalog"]}')">
                            <div class="">${data['nama_katalog']}</div>
                        </div>`;
                    });
                }
                customersElement.innerHTML = customer;
            })
            .catch((error) => {
                console.error('Error:', error);
            });
        }
        function cariCustomer(elementInput,userId) {
            let name = elementInput.value;
            datas = {
                name : name
            };
            fetch('get.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(datas),
            })
            .then(response => response.json())
            .then(datas => {
                let customersElement = document.querySelector("#katalogs");
                let customer = '';
                if(datas.length != 0) {
                    datas.forEach(data => {
                        let nama_katalog = data["nama_katalog"];
                        customer += `<div class="d-flex gap-2 p-3 border border-dark my-2 customerCard" onclick="ubahCustomer(${data['id']},'${nama_katalog}')">
                            <div class="">${data['nama_katalog']}</div>
                        </div>`;
                    });
                }else{
                    customer = `<div class="row">
                        <h5>Tambah Customer Baru</h5>
                        <div class="col-md-7 my-2">
                            <label for="namaCustomerBaru">Nama</label>
                            <input autocomplete="off" id="namaCustomerBaru" name="namaCustomerBaru" class="form-control bg-light" value="${name}"/>
                        </div>
                        <button class="btn btn-success" onclick="tambahCustomer(<?=$userId?>)">buat</button>
                    </div>`
                }
                customersElement.innerHTML = customer;
            })
            .catch((error) => {
                console.error('Error:', error);
            });
        }

        
        function submit(id, userId) {
            let katalog_id = document.querySelector("#ik").innerHTML;
            let datas = [];
            let hargaMarkup = document.querySelector("#hargaMarkup"+id).value;
            
            datas = {
                userId : userId,
                katalog_id : katalog_id,
                product_id : id,
                markup : hargaMarkup
            };
            fetch('detail_temp/tambah.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(datas),
            })
            .then(response => response.text())
            .then(datas => {
                sendData(<?=$userId?>);
                getKeranjang(document.querySelector("#ik").innerHTML,<?=$userId?>);
                setTimeout(() => {
                    const cart = document.getElementById('cartMain');
                    cart.scrollTop = cart.scrollHeight;  
                }, 100);
            })
            .catch((error) => {
                console.error('Error:', error);
            });
            document.querySelector("#btn-close-request"+id).click();
        }

        function changePosition(position,idNow,idTemp,userId) {
            let oldPosition = idNow;
            if (position == "down") {
                idNow += 1;
            } else {
                idNow -= 1;
            }
            var dataToSend = { 
                idTemp: idTemp,
                oldPosition: oldPosition,
                idNow: idNow,
                userId: userId
            };
            fetch('detail_temp/changePosition.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(dataToSend),
            })
            .then(response => response.json())
            .then(datas => {
                getKeranjang(document.querySelector("#ik").innerHTML,<?=$userId?>);
                console.log(datas);    
            })
            .catch((error) => {
                console.error('Error:', error);
            });
        }
        function getKeranjang(katalog_id,userId) {
            function cardCart(datas) {
                let cards = '<div>';
                let hargaPesanan = 0;
                let hargaRequest = 0;
                datas.forEach((data,index) => {
                    let arrow = '';
                    if (index == 0) {
                        arrow = `<div class="d-flex align-items-center pointer animasi" onclick="changePosition('down',${data.urutan},${data.id},<?=$userId?>)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-down" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M8 1a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L7.5 13.293V1.5A.5.5 0 0 1 8 1"/>
                            </svg>
                        </div>`;
                    }else if(index == datas.length - 1){
                        arrow = `<div class="d-flex align-items-center pointer animasi" onclick="changePosition('up',${data.urutan},${data.id},<?=$userId?>)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-up" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5"/>
                            </svg>
                        </div>`;
                    }else{
                        arrow = `<div class="d-flex flex-column justify-content-center gap-3">
                            <div class="d-flex align-items-center pointer animasi" onclick="changePosition('up',${data.urutan},${data.id},<?=$userId?>)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-up" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5"/>
                                </svg>
                            </div>
                            <div class="d-flex align-items-center pointer animasi" onclick="changePosition('down',${data.urutan},${data.id},<?=$userId?>)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-down" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M8 1a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L7.5 13.293V1.5A.5.5 0 0 1 8 1"/>
                                </svg>
                            </div>
                        </div>`;
                    }
                    let quantity = 1;
                    let listHarga = data['harga_jual'];
                    let markUp = parseInt(data['markup']);
                    let hargaBarang = menentukanHarga(quantity,listHarga);
                    let hargaSementara = (parseInt(hargaBarang) + markUp) * quantity;
                    const hargaSementaraConvert = hargaSementara.toLocaleString('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    });
                    cards += `<div class="d-flex gap-2 pb-3 border-bottom mb-3" id="produk${data['product_id']}">
                        ${arrow}
                        <div>
                            <div class="d-flex gap-2 mb-2">
                                <img src="${(data['foto'] != null) ? '/public/foto/md/'+data['foto']+'.jpg' : '/public/404.png'}" alt="" class="" style="width:100px; height:80px;  ">
                                <div class="w-80 position-relative">
                                    <p class="m-0 d-none" id="ip">#${data['product_id']}</p>
                                    <div class="m-0 pointer" onclick="openModalProduk(${data['product_id']},'${data['nama']}')">${data['nama']}<span class="ms-2">(<span id="hj">${rupiah(hargaBarang)}</span>${(data['markup'] != '0') ? ' + ' + data['markup'] : ''})</span></div>
                                    <p class="m-0 d-none" id="i">${data['id']}</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between fw-bold gap-2 mt-2">
                                <div class="w-fit pointer animasi p-2" onclick="hapusDariKeranjang(${data['id']},<?=$userId?>)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                                    <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
                                    </svg>
                                </div>
                                <p class="m-0">Harga : </p>
                                <p class="m-0">${hargaSementaraConvert}</p>
                            </div>
                        </div>
                    </div>`;
                });
                let hargaYangHarusDibayar = hargaPesanan + hargaRequest;
                cards += '</div>';
                return cards;
            }
            var dataToSend = { 
                katalog_id: katalog_id,
                userId: userId
            };
            fetch('detail_temp/get.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(dataToSend),
            })
            .then(response => response.json())
            .then(datas => {
                if (datas) {
                    if (datas.length > 0) {
                        document.querySelector("#btn-lanjut").classList.remove('d-none');
                        
                        if (datas[0]['id_customer']) {
                            document.querySelector("#ic").innerHTML = datas[0]['id_customer'];
                        }
                        
                        const cartProdukElement = document.querySelector('#cart-produk');
                        cartProdukElement.innerHTML = cardCart(datas);
                        setTimeout(() => {
                            cartProdukElement.scrollTop = cartProdukElement.scrollHeight;
                        }, 0);
                    } else {
                        console.log(datas.length);
                        document.querySelector("#btn-lanjut").classList.add('d-none');
                        document.querySelector('#cart-produk').innerHTML = 'Keranjang kosong';
                    }
                }
            })
            .catch((error) => {
                console.error('Error:', error);
            });
        }
        // Fungsi untuk mendapatkan nilai parameter berdasarkan namanya
        function getParameterByName(name, url) {
            if (!url) url = window.location.href;
            name = name.replace(/[\[\]]/g, "\\$&");
            var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
                results = regex.exec(url);
            if (!results) return null;
            if (!results[2]) return '';
            return decodeURIComponent(results[2].replace(/\+/g, " "));
        }

        function sendData(idKatalog,page,search) {
            // Get data from input or any other source
            var dataToSend = { 
                search: search,
                page: page,
                idKatalog: idKatalog
            };
            fetch('detail/getProduk.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(dataToSend),
            })
            .then(response => response.json())
            .then(datas => {
                if(datas['datas'].length == 0){
                    let buttonCus = `<div class="rounded-circle p-1 bg-light text-dark w-fit h-fit border border-dark shadow-lg pointer" data-bs-toggle="modal" data-bs-target="#addCustomBarang">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"/>
                        </svg>
                    </div>;`
                    document.querySelector('#overlay').innerHTML = buttonCus;
                }else{
                    document.querySelector('#overlay').innerHTML = '';
                    datas['datas'].forEach(data => {
                        makeCard(data);
                        document.querySelector("#overlay").click();
                    });
                    var pageNow = parseInt(datas['pegeNow']);
                    var mulaiPage = 0;
                    if(pageNow == 2) {
                        mulaiPage = pageNow - 1;
                    }else if(pageNow == 3){
                        mulaiPage = pageNow - 2;
                    }else if(pageNow > 3){
                        mulaiPage = pageNow - 3;
                    }else{
                        mulaiPage = pageNow;
                    }
                    // console.log(Math.ceil(datas['jumlahPage']));

                    document.querySelector('#pages').innerHTML = '';
                    var selectAll = document.createElement('button');
                    selectAll.className = "btn btn-primary position-absolute";
                    selectAll.style.left = "30px";
                    selectAll.onclick = function() {
                        document.querySelectorAll("#addToKatalog").forEach(element => {
                            element.click();
                        });
                    };
                    selectAll.textContent = "Pilih Semua";
                    document.querySelector('#pages').appendChild(selectAll);

                    for (let index = 0; index < 6; index++) {
                        if (mulaiPage > Math.ceil(datas['jumlahPage'])) {
                            return;
                        }
                        var div = document.createElement('div');
                        var button = document.createElement('button');
                        if (mulaiPage == pageNow) {
                            button.className = 'btn btn-primary';
                        }else{
                            button.className = 'btn btn-success';
                        }
                        button.textContent = mulaiPage ;
                        button.onclick = function() {
                            sendData(<?=$userId?>, this.innerHTML, search ? search : '')
                        };
                        div.appendChild(button);
                        document.querySelector('#pages').appendChild(div);
                        mulaiPage += 1;
                    }
                }
            })
            .catch((error) => {
                console.error('Error:', error);
            });
        }
        
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
        function makeCard(data) {
            let id = data['id'];
            let judul = data['nama'].split(";;")[0];
            let arrayTerakhirHarga = JSON.parse(data['harga_jual']).length - 1;
            let jumlahBarangPalingBaynak = JSON.parse(data['harga_jual'])[arrayTerakhirHarga].jumlah;
            let harga = menentukanHarga(jumlahBarangPalingBaynak,data['harga_jual']);
            let harga_juals = JSON.parse(data['harga_jual']);
            let stock = data['stock'];
            let kategori = data['kategori'];
            let idBarcode = data['id_barcode'];
            let talking_point = data['talking_point']; 
            let is_parent = parseInt(data['id_parent']); 
            let jumlahDiKeranjang = data['jumlah'];
            let tanggal_beli_stock = data['tanggal_beli_stock'];
            // return;
            let is_child = '';
            let id_child = '';
            is_child += `<div class="mb-4" id="isChild"><label for="" class="fw-bold">Id Parent</label><input type="text" class="form-control border-dark" oninput="productsForChild(<?=$userId?>, this.value, 1, ${id})" placeholder="Masukkan nama product"><input type="text" class="d-none" id="idParent${id}" value="0"><div id="productsForChild${id}">`;
            if (data['parent'].length > 0) {
                    is_child += `<div class="w-100 d-flex gap-2 align-items-center border border-dark pointer my-2" id="produksParent" onclick="choosePerent(this, 394)">
                        <img src="${(data['parent'][0][15]) ? '/public/foto/md/'+data['parent'][0][15]+'.jpg' : '/public/404.png'}" alt="" class="p-0 m-0" style="width:45px;height:45px">
                        <b class="m-0">${data['parent'][0][1]}</b>
                    </div>
                `;
                id_child = data['parent'][0][0];
            }else{
                id_child = '0';
                is_child += `<input class="d-none" id="idParent${id}" value="${id_child}">`
            }
            is_child += `</div></div>`;
            let gambarsProduct = data['fotoProduk'];
            let gambar = '';
            if (gambarsProduct.length > 0) {
                gambar = '/public/foto/md/' + gambarsProduct[0][0] + '.jpg';
            } else {
                gambar = '/public/404.png';
            }
            function daysAgo(date) {
                // Mendapatkan tanggal saat ini
                const today = new Date();
                
                // Mendapatkan waktu dari parameter date
                const pastDate = new Date(date);
                
                // Menghitung perbedaan waktu dalam milidetik
                const diffTime = Math.abs(today - pastDate);
                
                // Mengubah perbedaan waktu menjadi hari
                let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24))-1;
                if (diffDays == 0) {
                    diffDays = 'today'
                }else{
                    diffDays += ' hari yang lalu';
                }
                return diffDays;
            }
            let bgStock = 'bg-secondary';
            if (stock > 0) {
                bgStock = 'bg-success';
            }
            // return;
            let card = `<div class="row p-3 h-fit">
                <div class="col-12 position-relative">
                    <img src="${gambar}" alt="" class="w-100 h-100 p-0 m-0">
                    <?php if($jabatan == 'super-admin' || $jabatan == 'cs') : ?>
                        ${(harga != 0 ? `<div class="w-fit p-3 border border-3 border-primary rounded-circle pointer bg-light position-absolute bottom-0 end-0 keranjangIcon" data-bs-toggle="modal" data-bs-target="#exampleModal${id}">
                            <span style='color:red;font-size:2em'>&nbsp; + &nbsp;</span>
                        </div>` : '')}
                    <?php endif ?>
                </div>
                <div class="col-12" style="z-index:999;margin-top:-1px;">
                    <h5 class="m-0 text-success">${judul}</h5>
                    <div class="w-fit px-2 py-1 ${bgStock}" style="display:none">
                        Stock ${stock}
                    </div>
                    <h5 class="m-0 text-danger" style='font-size:1em'>${rupiah(harga)} </h5>
                </div>
            </div>`;

            let listHargaJualKeranjang = '';
            harga_juals.forEach(harga => {
                listHargaJualKeranjang += `<h5>Rp ${harga.harga} (${harga.jumlah} pcs)</h5>`
            });
            let modalInputPesanan = `<div class="modal fade modal-static" id="exampleModal${id}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="animation-delay: 0 !important">
                    <div class="modal-dialog modal-md text-dark">
                        <div class="modal-content bg-lightgray shadow-xl">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">${judul}</h1>
                                <button type="button" class="btn-close" id="btn-close-request${id}" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <img src="${gambar}" alt="" class="w-100 h-100 p-0 m-0">
                                    </div>
                                    <div class="col-md-8">
                                        <h5 class="fw-medium text-success">${judul}</h5>
                                        <h5 class="fw-medium text-danger">Rp ${harga}
                                        <span class="bg-success text-light rounded-circle"><svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16"><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/></svg></span>
                                        </h5>
                                        <label for="hargaMarkup${id}" style="white-space: nowrap">Mark Up</label>
                                        <input type="number" class="form-control border-dark" id="hargaMarkup${id}" value="${(data['markup']) ? data['markup'] : '0'}" onclick="this.select()" placeholder="masukkan mark-up an">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" id="addToKatalog" onclick="submit(${id},<?=$userId?>)">Add to katalog</button>
                            </div>
                        </div>
                    </div>
                </div>`
            let modal_harga_jual = `<div id="hargaJual_edit${id}">`;

            harga_juals.forEach(harga => {
                modal_harga_jual += `<div class="row"><div class="col-6 d-none">
                <label for="jumlahBarangEdit${id}" class="">Jumlah Barang</label><input autocomplete="off" type="number" class="form-control border border-dark mb-4 text-dark" id="jumlahBarangEdit${id}" value="${harga.jumlah}" <?=($jabatan != 'super-admin') ? 'readonly' : '' ?>></div><div class="col-12"><label for="hargaBarangEdit${id}" class="">Harga</label><input autocomplete="off" type="number" class="form-control border border-dark mb-4 text-dark" id="hargaBarangEdit${id}" onfocus="use_number(this); this.select()" onblur="use_text(this)" value="${harga.harga}" <?=($jabatan != 'super-admin') ? 'readonly' : '' ?>></div></div>`;
            });
            modal_harga_jual += '</div>';

            let gambars = '';
            gambarsProduct.forEach(gambar => {
                let idGambar = gambar[0];
                let urlGambar = '/public/foto/lg/'+gambar[0]+'.jpg';
                gambars += `<div class="position-relative col-6 p-2" id="gambar_md${idGambar}">
                            <?php if($jabatan == 'super-admin') : ?>
                                <div class="position-absolute w-fit p-2 bg-dark" onclick="hapusGambar(${idGambar},${id})" style="top:10px; left:10px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill text-danger" viewBox="0 0 16 16">
                                        <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
                                    </svg>
                                </div>
                                <div class="position-absolute w-fit p-1 bg-warning" onclick="isFavorite(${idGambar},${id})" style="top:10px; right:10px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-heart-fill ${(gambar[1] == 1 ? 'text-danger' : '')}" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314"/>
                                    </svg>
                                </div>
                            <?php endif ?>
                                <img src="${urlGambar}" alt="" class="w-100 pointer border-white" style="height: 150px; object-fit: contain;" onclick="ubahGambar(this,${id})">
                            </div>`;
            });
            
            let barcodeIcon = '';
            if (idBarcode) {
                barcodeIcon = `<div class="position-absolute top-0 end-0" style="width:50px;"><img src="/public/foto/icon/barcode.jpg"></div>`;
            }
            card = '<div class="card-produk position-relative" style="width:250px;height:350px;">'+card+modalInputPesanan+barcodeIcon+'</div>';
            let tempDiv = document.createElement('div');
            tempDiv.innerHTML = card;
            // Append the first child of the temporary div (which is the card element) to the #overlay element
            document.querySelector('#overlay').appendChild(tempDiv.firstChild);
        }

        function toTemp(katalog_id,idUser) {
            var dataToSend = { 
                katalog_id: katalog_id,
                idUser: idUser
            };
            fetch('detail/toTemp.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(dataToSend),
            })
            .then(response => response.json())
            .then(datas => {
                console.log(datas);    
            })
            .catch((error) => {
                console.error('Error:', error);
            });
        }

        function toDetail(idUser) {
            let katalog_id = document.querySelector('#ik').innerHTML;
            var dataToSend = { 
                idUser: idUser,
                katalog_id: katalog_id
            };
            fetch('detail_temp/toDetail.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(dataToSend),
            })
            .then(response => response.json())
            .then(datas => {
                getKeranjang(katalog_id,<?=$userId?>);  
                window.location.href = "/pages/katalog/history/";
            })
            .catch((error) => {
                console.error('Error:', error);
            });
        }
    </script>
<?php include $_SERVER['DOCUMENT_ROOT'].'/components/footer/index.php'; ?>