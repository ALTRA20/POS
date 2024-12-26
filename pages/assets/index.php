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
</head>
<body>
    <?php
    function format_rupiah($number) {
        return 'Rp ' . number_format($number, 0, ',', '.');
    }
    include $_SERVER['DOCUMENT_ROOT'].'/components/header/navbar2.php'; 
    $produks = [
        "Resleting Amco Gold No 3",
        "Plisir Spunbond Warna Putih 75gsm Lebar 2,5cm",
        "Resleting Amco Bronze No 3",
        "Prada Botega Bronze Metalik per Meter",
        "Plastik UV Q5 4m x 170mic x 100meter",
        "Prada Botega Mocasin Metalik per Meter",
        "Prada Botega Army Metalik per Meter",
        "Prada Botega Maroon Metalik per Meter",
    ];
    $harga = [
        [
            "jumlah" => "1",
            "harga" => "100000"
        ],
        [
            "jumlah" => "3",
            "harga" => "70000"
        ],
        [
            "jumlah" => "10",
            "harga" => "50000"
        ]
    ];
    ?>
<script>
    document.title = 'Jelajah GS';
</script>
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
<script>
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
    document.querySelector('.navbar-expand').style.backgroundColor = "purple";
</script>
<div class="overflow-hidden d-flex px-3" style="height:94vh; background-color:purple;">
    <section style="width: 100%; height:96%">
        <div class="d-flex my-2" role="search">
            <div class="bg-danger py-2 px-3 rounded-start pointer" id="tag">#</div>
            <input onclick='this.select();' style='background-color: white !important;color:purple !important' autocomplete="off" class="form-control me-2 text-light rounded-end" id="search" type="search" placeholder="Search" aria-label="Search" style='background-color:white'/>
        </div>
        <div class="d-flex justify-content-center flex-wrap gap-2 bg-light py-4 overflow-auto" id="overlay" style="height:86%;"></div>
        <div class="w-100 d-flex justify-content-center gap-2 bg-light px-5 py-3" id="pages">
        </div>
    </section>
</div>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/plupload/3.1.2/plupload.full.min.js"></script>

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

    function submitForm() {
        let nama = document.querySelector("#nama").value;
        let kategoriProduk = document.getElementById('kategori_produk').value;
        let jumlahBarangs = [];
        let hargaBeli = document.querySelector("#hargaBeli").value;
        let dimensi = document.querySelector("#dimensi").value;
        let berat = document.querySelector("#berat").value;
        let stock = document.querySelector("#stock").value;
        let idParent = document.querySelector("#idParent").value;
        let talkingPoint = document.querySelector("#talkingPoint").value;
        let idBarcode = document.querySelector("#idBarcode").value;
    
        document.querySelectorAll("#jumlahBarang").forEach((jumlahBarang, index) => {
            let jumlahBarangValue = jumlahBarang.value;
            jumlahBarangs.push({ jumlah: jumlahBarangValue });
        });

        document.querySelectorAll("#hargaBarang").forEach((hargaBarang, index) => {
            let hargaBarangValue = hargaBarang.value;
            jumlahBarangs[index].harga = hargaBarangValue;
        });

        // Mengonversi objek menjadi JSON
        jumlahBarangs = JSON.stringify(jumlahBarangs);

        var dataToSend = { 
            nama: nama,
            kategoriProduk: kategoriProduk,
            hargaJual: jumlahBarangs,
            hargaBeli: hargaBeli,
            dimensi: dimensi,
            berat: berat,
            stock: stock,
            idParent: idParent,
            talkingPoint: talkingPoint,
            idBarcode: idBarcode
        };
        console.log(dataToSend);
        fetch('/components/tambah-product/tambah.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(dataToSend),
        })
        .then(response => response.json())
        .then(datas => {
            console.log(datas);
            document.querySelector('#nama').value = '';
            // document.querySelectorAll('#jumlahBarang').forEach(element => {
            //     element.value = '';
            // }); 
            // document.querySelectorAll('#hargaBarang').forEach(element => {
            //     element.value = '';
            // }); 

            document.querySelector('.btn-close').click();
            sendData();
            // window.location.href = window.location.href;
        })
        .catch((error) => {
            console.error('Error:', error);
        });
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
            getKeranjang(<?=$userId?>);
            getKeranjangCustom(<?=$userId?>);
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
    function hapusGambar(idGambar, idProduk) {
        let judul = document.querySelector("#namaEdit"+idProduk).value;
        datas = {
            idGambar : idGambar
        }
        fetch('/components/tambah-product/hapus_foto_action.php', {
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
        fetch('/components/keranjang/hapus.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(datas),
        })
        .then(response => response.text())
        .then(datas => {
            getKeranjangCustom(<?=$userId?>);
            getKeranjang(<?=$userId?>);
            document.querySelector("#btn-lanjut").classList.add("d-none");
            document.querySelector("#priceDisplay").innerHTML = 'Rp 0';
            document.querySelector("#price").innerHTML = '0';
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
    // addRequest();
    function ubahCustomer(idCus,nama,alamat,noHP) {
        document.querySelector("#namaCustomer").innerHTML = nama + ' | ' + alamat + ' | ' + noHP;
        document.querySelector("#ic").innerHTML = idCus;
        let ids = []; 
        document.querySelectorAll("#i").forEach(id => {
            ids.push(id.innerHTML);
        });
        let datas = {
            idCustomer : idCus,
            ids : ids,
            userId : <?=$userId?>
        }
        console.log(ids);
        fetch('/components/keranjang/update_customer.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(datas),
        })
        .then(response => response.text())
        .then(datas => {
            console.log(datas);
            document.querySelector("#btn-lanjut").classList.remove("d-none");  
        })
        .catch((error) => {
            console.error('Error:', error);
        });
        document.querySelector("#btn-close-cart-name-modal").click();
    }
    function tambahCustomer() {
        let namaCustomerBaru = document.querySelector("#namaCustomerBaru").value;
        let nomorCustomerBaru = document.querySelector("#nomorCustomerBaru").value;
        let alamatCustomerBaru = document.querySelector("#alamatCustomerBaru").value;
        datas = {
            nama : namaCustomerBaru,
            nomor : nomorCustomerBaru,
            alamat : alamatCustomerBaru
        };
        fetch('/components/customer/tambahCustomer.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(datas),
        })
        .then(response => response.json())
        .then(datas => {
            console.log(datas);
            let customersElement = document.querySelector("#customers");
            let customer = '';
            if(datas.length != 0) {
                datas.forEach(data => {
                    customer += `<div class="d-flex gap-2 p-3 border border-dark my-2" onclick="ubahCustomer(${data[0]},'${data[1]}','${data[2]}','${data[3]}')">
                        <div class="">${data[1]}</div>
                        <div class="">|</div>
                        <div class="">${data[2]}</div>
                        <div class="">|</div>
                        <div class="">${data[3]}</div>
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
        fetch('/components/customer/get.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(datas),
        })
        .then(response => response.json())
        .then(datas => {
            let customersElement = document.querySelector("#customers");
            let customer = '';
            if(datas.length != 0) {
                datas.forEach(data => {
                    customer += `<div class="d-flex gap-2 p-3 border border-dark my-2 customerCard" onclick="ubahCustomer(${data[0]},'${data[1]}','${data[2]}','${data[3]}')">
                        <div class="">${data[1]}</div>
                        <div class="">|</div>
                        <div class="">${data[2]}</div>
                        <div class="">|</div>
                        <div class="">${data[3]}</div>
                    </div>`;
                });
            }else{
                customer = `<div class="row">
                    <h5>Tambah Customer Baru</h5>
                    <div class="col-md-7 my-2">
                        <label for="namaCustomerBaru">Nama</label>
                        <input autocomplete="off" id="namaCustomerBaru" name="namaCustomerBaru" class="form-control bg-light" value="${name}"/>
                    </div>
                    <div class="col-md-7 my-2">
                        <label for="nomorCustomerBaru">Nomor</label>
                        <input autocomplete="off" id="nomorCustomerBaru" name="nomorCustomerBaru" class="form-control bg-light" placeholder="📱 HP"/>
                    </div>
                    <div class="col-md-7 my-2 d-none">
                        <label for="alamatCustomerBaru">🏘 domisili / alamat singkat. contoh: kasongan,wates,solo,sleman</label>
                        <input autocomplete="off" id="alamatCustomerBaru" name="alamatCustomerBaru" class="form-control bg-light" placeholder="alamat"/>
                    </div>
                    <button class="btn btn-success" onclick="tambahCustomer()">buat</button>
                </div>`
            }
            customersElement.innerHTML = customer;
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    }

    
    function submit(id, userId) {
        let id_customer = document.querySelector("#ic").innerHTML;
        let datas = [];
        let quantity = document.querySelector("#quantity"+id).value;
        let requests = document.querySelectorAll("#request"+id);
        let hargaMarkup = document.querySelector("#hargaMarkup"+id).value;
        let requestsValue = [];
        // console.log(requests);
        requests.forEach(request => {
            let value = []; // Inisialisasi array value di sini
            let inputs = request.querySelectorAll('input');
            let name = inputs[0].getAttribute("name");
            inputs.forEach(input => {
                if(input.value.trim() !== ''){ // Menggunakan trim() untuk menghapus spasi kosong
                    value.push(input.value);
                }
            });
            if(value.length !== 0){
                requestsValue.push(value);
            }
        });
        requestsValue = JSON.stringify(requestsValue); // Pindahkan baris ini ke luar dari loop forEach
        
        datas = {
            userId : userId,
            id_customer : id_customer,
            product_id : id,
            jumlah : quantity,
            request : requestsValue,
            markup : hargaMarkup
        };
        // console.log(datas);
        fetch('/components/keranjang/tambahKeKeranjang.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(datas),
        })
        .then(response => response.text())
        .then(datas => {
            sendData(<?=$userId?>);
            getKeranjang(<?=$userId?>);
            getKeranjangCustom(<?=$userId?>)
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

    function commitCart() {
        
    }

    function getKeranjang(idUser) {
        function cardCart(datas) {
            let cards = '<div>';
            let hargaPesanan = 0;
            let hargaRequest = 0;
            datas.forEach(data => {
                let quantity = data['jumlah'];
                let listHarga = data['harga_jual'];
                let markUp = parseInt(data['markup']);
                let hargaBarang = menentukanHarga(quantity,listHarga);
                let hargaSementara = (parseInt(hargaBarang) + markUp) * quantity;
                hargaPesanan += (parseInt(hargaBarang) + markUp) * quantity;
                let requestElement = '';
                if (JSON.parse(data['request']) != null) {
                    requestElement += '<div><p class="m-0">Request</p>';
                    JSON.parse(data['request']).forEach((request,index) => {
                        requestElement += `<div class="row">
                            <p class="col m-0">${request[1]}</p>
                            <p class="col m-0">${request[0]}</p>
                            <p class="col m-0">Rp ${request[2]}</p>
                        <http://192.168.0.30/api/transferan-sembako.php?tr=tr_bca/div>`
                        hargaSementara += request[0] * request[2];
                        hargaRequest += request[0] * request[2];
                    });
                    requestElement += '</div>';
                }
                const hargaSementaraConvert = hargaSementara.toLocaleString('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                });
                cards += `<div class="pb-3 border-bottom mb-3" id="produk${data['product_id']}">
                    <div class="d-flex gap-2 mb-2">
                        <img src="${(data['foto'] != null) ? '/public/foto/md/'+data['foto']+'.jpg' : '/public/404.png'}" alt="" class="" style="width:100px; height:80px;  ">
                        <div class="w-80 position-relative">
                            <p class="m-0 d-none" id="ip">#${data['product_id']}</p>
                            <div class="m-0 pointer" onclick="openModalProduk(${data['product_id']},'${data['produk_nama']}')">${data['produk_nama']}<span class="ms-2">(<span id="hj">${rupiah(hargaBarang)}</span>${(data['markup'] != '0') ? ' + ' + data['markup'] : ''})</span></div>
                            <p class="m-0 d-none" id="i">${data['id']}</p>
                            <div class="w-100 d-flex border">
                                <div class="w-20 d-flex align-items-center justify-content-center pointer border-end" onclick="aritmatika(this,'k',${data['product_id']},<?=$userId?>)" target="valueJumlahKeranjang">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-dash" viewBox="0 0 16 16">
                                        <path d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8"/>
                                    </svg>
                                </div>
                                <div class="w-60 p-0">
                                    <input autocomplete="off" type="text" class="form-control text-light border-0 outline-0 text-center" name="qty" id="valueJumlahKeranjang${data['product_id']}" value="${data['jumlah']}" readonly>
                                </div>
                                <div class="w-20 d-flex align-items-center justify-content-center pointer border-start" onclick="aritmatika(this,'t',${data['product_id']},<?=$userId?>)" target="valueJumlahKeranjang">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    ${requestElement}
                    <div class="d-flex align-items-center justify-content-between fw-bold gap-2 mt-2">
                        <div class="w-fit pointer animasi p-2" onclick="hapusDariKeranjang(${data['id']},<?=$userId?>)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                            <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
                            </svg>
                        </div>
                        <p class="m-0">Subtotal</p>
                        <p class="m-0">${hargaSementaraConvert}</p>
                    </div>
                </div>`;
            });
            let hargaYangHarusDibayar = hargaPesanan + hargaRequest;
            const formattedPrice = hargaYangHarusDibayar.toLocaleString('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            });
            document.querySelector("#priceDisplay").innerHTML = formattedPrice;
            document.querySelector("#price").innerHTML = hargaYangHarusDibayar;
            cards += '</div>';
            return cards;
        }
        var dataToSend = { 
            idUser: idUser
        };

        fetch('/components/keranjang/getKeranjang.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(dataToSend),
        })
        .then(response => response.json())
        .then(datas => {
            if (datas.length > 0) {
                // Menampilkan informasi pelanggan
                document.querySelector("#namaCustomer").innerHTML = (datas[0]['customer_nama']) ? `${datas[0]['customer_nama']} | ${datas[0]['wa']} | ${datas[0]['alamat']}` : 'Customer belum dipilih';
                document.querySelector("#catatan").innerHTML = datas[0]['note'] || 'Tidak ada catatan';
                document.querySelector("#catatanTextarea").innerHTML = datas[0]['komentar'] || 'Tidak ada komentar';
                
                // Menampilkan ID Customer dan menghapus class 'd-none' jika ada
                if (datas[0]['id_customer']) {
                    document.querySelector("#ic").innerHTML = datas[0]['id_customer'];
                    document.querySelector("#btn-lanjut").classList.remove("d-none");
                }
                
                // Menampilkan daftar produk di keranjang
                const cartProdukElement = document.querySelector('#cart-produk');
                cartProdukElement.innerHTML = cardCart(datas);
                // Scroll ke bagian bawah keranjang
                setTimeout(() => {
                    cartProdukElement.scrollTop = cartProdukElement.scrollHeight;
                }, 0);
            } else {
                // Jika tidak ada data, bersihkan elemen keranjang
                // document.querySelector('#cart-produk').innerHTML = 'Keranjang kosong';
            }
        })
        .catch((error) => {
            console.error('Error:', error);
        });

    }
    function getKeranjangCustom(idUser) {
        function cardCart(datas) {
            let element = '<div>';
            let harga = parseInt(document.querySelector("#price").innerHTML);

            datas.forEach(data => {
                element += `<div class="pb-3 border-bottom my-3">
                    <div class="d-flex gap-2 mb-2">
                        <img src="${data['foto'] ? '/public/foto/md/'+data['foto']+'.jpg' : (data['customProdukFoto'] ? '/public/foto/temp/'+data['customProdukFoto'] : '/public/foto/md/custom.jpg')}" alt="" class="" style="width:100px; height:80px; object-fit:cover;">
                        <div class="w-80 position-relative">
                            <p class="m-0 d-none" id="">#256</p>
                            <p class="m-0">${data['komentar']}<span class="ms-2">(<span id="">${rupiah(parseInt(data['harga']))}</span>)</span></p>
                            <p class="m-0 d-none" id="i">${data['id']}</p>
                            <p class="m-0">jumlah : ${data['jumlah']}</p>
                        </div>
                    </div>`;

                let hargaYangHarusDibayar = parseInt(data['harga']) * parseInt(data['jumlah']);
                element += `<div class="d-flex align-items-center justify-content-between fw-bold gap-2 mt-2">
                    <div class="w-fit pointer animasi p-2" onclick="hapusDariKeranjang(${data['id']}, <?=$userId?>)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                        <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"></path>
                        </svg>
                    </div>
                    <p class="m-0">Subtotal</p>
                    <p class="m-0">${rupiah(hargaYangHarusDibayar)}</p>
                </div>
                </div>`;
                harga += hargaYangHarusDibayar;
            });

            document.querySelector("#price").innerHTML = harga;
            const formattedPrice = harga.toLocaleString('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            });
            document.querySelector("#priceDisplay").innerHTML = formattedPrice;
            element += '</div>';

            return element;

        }
        var dataToSend = { 
            idUser: idUser
        };
        fetch('/components/keranjang/getKeranjangCustom.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(dataToSend),
        })
        .then(response => response.json())
        .then(datas => {
            document.querySelector('#cart-produk-custom').innerHTML = '';
            if (datas.length > 0) {
                document.querySelector('#cart-produk-custom').innerHTML = cardCart(datas);
            }
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    }
    function openCart() {
        getKeranjang(<?=$userId?>);
        getKeranjangCustom(<?=$userId?>)
        document.querySelector('#cart').classList.toggle('d-none');
    }
    function aritmatika(element, status, id = '', userId) {
        function ubahIsiKeranjang(idProduk, idUser, jumlahBarang) {
            var dataToSend = { 
                idProduk: idProduk, 
                idUser: idUser, 
                jumlahBarang: jumlahBarang 
            };
            fetch('/components/keranjang/tambahJumlahKuantiti.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(dataToSend),
            })
            .then(response => response.text())
            .then(datas => {
                getKeranjang(idUser);  
            })
            .catch((error) => {
                console.error('Error:', error);
            });
        }
        let target = element.getAttribute('target');
        let valueJumlahKeranjang = parseInt(document.querySelector('#'+target+id).value);
        if (status == 't') {
            let jumlahFixed = valueJumlahKeranjang += 1;
            document.querySelector('#'+target+id).value = jumlahFixed;
            ubahIsiKeranjang(id,userId, jumlahFixed);
        }else{
            if (valueJumlahKeranjang > 1) {
                let jumlahFixed = valueJumlahKeranjang -= 1;
                document.querySelector('#'+target+id).value = jumlahFixed;
                ubahIsiKeranjang(id,userId, jumlahFixed);
            }
        }
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

    function sendData(userId,page,search) {
        // Get data from input or any other source
        var dataToSend = { 
            search: search,
            page: page,
            userId: userId
        };
        fetch('/components/index/products.php', {
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
    // Contoh penggunaan
    var search = getParameterByName('ikan');
    window.onload = sendData(<?=$userId?>,1, search ? search : '');
    window.onload = getKeranjang(<?=$userId?>);
    getKeranjangCustom(<?=$userId?>)
    
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
        let hargaBeli = data['harga_beli'];
        let dimensi = data['dimensi'];
        let berat = data['berat'];
        let stock = data['stock'];
        let kategori = data['kategori'];
        let requests = (data['request'] == null || data['request'] == '') ? null : JSON.parse(data['request']);
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
        let arrayDataRequestTerakhir = (requests != null) ? requests.length : '0';
        let request = `<div class="" id="requests">`;
        if (requests != null) {
            requests.forEach(requestsValue => {
                request += `<div class="d-flex gap-3 mt-4" id="request${id}">
                <p class="m-0">Request</p>
                <div class="w-100 row justify-content-between">
                <div class="col-4">
                <input autocomplete="off" type="number" class="form-control text-dark" id="requestInput" name="0" value="${requestsValue[0]}" placeholder="qty">
                </div>
                <div class="col-4">
                <input autocomplete="off" type="text" class="form-control text-dark" id="requestInput" name="0" value="${requestsValue[1]}" placeholder="deskripsi">
                </div>
                <div class="col-4">
                <input autocomplete="off" type="number" class="form-control text-dark" id="requestInput" name="0" value="${requestsValue[2]}" placeholder="harga">
                </div>
                </div>
                </div>`; 
            });
        }
        request += `<div class="w-fit animasi" onclick="document.querySelector('#request${id}').classList.toggle('d-none')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-tools" viewBox="0 0 16 16">
                        <path d="M1 0 0 1l2.2 3.081a1 1 0 0 0 .815.419h.07a1 1 0 0 1 .708.293l2.675 2.675-2.617 2.654A3.003 3.003 0 0 0 0 13a3 3 0 1 0 5.878-.851l2.654-2.617.968.968-.305.914a1 1 0 0 0 .242 1.023l3.27 3.27a.997.997 0 0 0 1.414 0l1.586-1.586a.997.997 0 0 0 0-1.414l-3.27-3.27a1 1 0 0 0-1.023-.242L10.5 9.5l-.96-.96 2.68-2.643A3.005 3.005 0 0 0 16 3q0-.405-.102-.777l-2.14 2.141L12 4l-.364-1.757L13.777.102a3 3 0 0 0-3.675 3.68L7.462 6.46 4.793 3.793a1 1 0 0 1-.293-.707v-.071a1 1 0 0 0-.419-.814zm9.646 10.646a.5.5 0 0 1 .708 0l2.914 2.915a.5.5 0 0 1-.707.707l-2.915-2.914a.5.5 0 0 1 0-.708M3 11l.471.242.529.026.287.445.445.287.026.529L5 13l-.242.471-.026.529-.445.287-.287.445-.529.026L3 15l-.471-.242L2 14.732l-.287-.445L1.268 14l-.026-.529L1 13l.242-.471.026-.529.445-.287.287-.445.529-.026z"/>
                    </svg>
                </div>
                <div class="" id="requests">
                    <div class="" id="">
                        <div class="d-flex gap-3 mt-4 bg-transparent d-none" id="request${id}">
                            <p class="m-0">Request</p>
                            <div class="w-100 row justify-content-between">
                                <div class="col-4">
                                    <input autocomplete="off" type="number" class="form-control text-dark border border-dark" id="requestInput" name="${arrayDataRequestTerakhir}" placeholder="qty">
                                </div>
                                <div class="col-4">
                                    <input autocomplete="off" type="text" class="form-control text-dark border border-dark" id="requestInput" name="${arrayDataRequestTerakhir}" placeholder="deskripsi">
                                </div>
                                <div class="col-4">
                                    <input autocomplete="off" type="number" class="form-control text-dark border border-dark" id="requestInput" name="${arrayDataRequestTerakhir}" placeholder="harga">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;
        request += `</div>`;
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
            <div class="col-8 position-relative">
                <img src="${gambar}" alt="" class="w-100 h-100 p-0 m-0">
            </div>
            <div class="col-4 d-flex flex-column justify-content-center align-items-center">
                <div class="w-fit px-3 py-2 ${bgStock}" style="">
                    Stock ${stock}
                    <p class="m-0">${(tanggal_beli_stock) ? daysAgo(tanggal_beli_stock) : ''}</p>
                </div>
            </div>
            <div class="col-12" style="z-index:999;margin-top:-1px;">
                <h5 class="m-0 text-success">${judul}</h5>
                <h5 class="m-0 text-danger" style='font-size:1em'>${rupiah(harga)} </h5>
                <p class="text-dark" id="updateHarga">${data['harga_beli_created_at']}</p>
            </div>
        </div>`;

        let listHargaJualKeranjang = '';
        harga_juals.forEach(harga => {
            listHargaJualKeranjang += `<h5>Rp ${harga.harga} (${harga.jumlah} pcs)</h5>`
        });


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
        card = '<a href="produk.php?id='+id+'" class="card-produk position-relative">'+card+'</a>';
        let tempDiv = document.createElement('div');
        tempDiv.innerHTML = card;
        // Append the first child of the temporary div (which is the card element) to the #overlay element
        document.querySelector('#overlay').appendChild(tempDiv.firstChild);
    }

    function ubahGambar(img,id) {
        let url = img.src;
        document.querySelector('#img-cover'+id).src= url;    
    }
    function uploadThumbnail(id) {
        var input = document.querySelector("#upload-thumbnail"+id);
        // let idInput = input.id;  
        // document.querySelector("#form-footer" + idInput).classList.remove("d-none");
        var thumbnail = document.getElementById('uploadedThumbnail'+id);
        
        // Pastikan ada file yang dipilih
        if (input.files && input.files[0]) {
            var file = input.files[0];
            
            // Baca file sebagai URL data
            var reader = new FileReader();
            reader.onload = function (e) {
                // Tampilkan thumbnail
                thumbnail.src = e.target.result;
                // document.getElementById('foto-overlay'+id).style.backgroundColor = "rgba(0,0,0,0.7)";
                thumbnail.classList.remove('d-none');
            };
    
            // Baca file sebagai URL data
            reader.readAsDataURL(file);
        }
    }
</script>
<script>
    function delist(userId,id) {
        window.location.href = '/components/tambah-product/hapus_produk_action.php?i='+userId+'>&ip='+id;
    }
    function removeHargaJuals(id) {
        document.getElementById(id).remove();
    }
    const addFiturAddMenuEdit = (idElement) => {
        document.getElementById('addFiturAddMenu').classList.add('d-none');
        document.getElementsByClassName('tambahKategoriMenuEdit'+idElement)[0].classList.toggle('d-none');
    }
    const addSelectEdit = (idElement) => {
        let classElemet = '.tambahKategoriMenuEdit'+idElement;
        // Get the select element by its ID
        var selectElement = document.getElementById('kategori_produkEdit'+idElement);

        // Create a new option element
        var optionElement = document.createElement('option');
        let inputan = document.querySelector(classElemet+' input');
        const alertElement = document.getElementById('alertSuccessAddKategoriMenuEdit');
        if (inputan.value != '') {
            optionElement.value = inputan.value;
            optionElement.textContent = inputan.value;
            // Append the option element to the select element
            selectElement.appendChild(optionElement);
            alertElement.innerHTML = "Kategori menu baru udah ditambahkan";
            alertElement.classList.remove('d-none');
            alertElement.classList.remove('text-light');
            alertElement.classList.remove('bg-danger'); 
            alertElement.classList.add('text-pink');
            alertElement.classList.add('bg-light');
            document.querySelector(classElemet+' input').classList.toggle('d-none');
            document.querySelector(classElemet+' button').classList.toggle('d-none');
            inputan.value = '';
        }else{
            alertElement.classList.remove('text-pink');
            alertElement.classList.remove('bg-light');
            alertElement.classList.add('text-light');
            alertElement.classList.add('bg-danger');
            alertElement.innerHTML = "Tidak boleh kosong";
            document.querySelector(classElemet+' input').classList.toggle('d-none');
            document.querySelector(classElemet+' button').classList.toggle('d-none');
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
                document.querySelector(classElemet+' input').classList.toggle('d-none');
                document.querySelector(classElemet+' button').classList.toggle('d-none');
            }, 300); // Adjust the timing to match your transition duration
        }
        // Show the alert
        showAlert();

        // Hide the alert after 3 seconds
        setTimeout(hideAlert, 3000);
    }
    function tambahJualEdit(idCard) {
        let id = "#hargaJualEdit"+idCard;
        let borderColor = "border-dark text-dark";
        
        let hargaJual = `<div class="row"><div class="col-6"><label for="jumlahBarang" class="">Jumlah Barang</label>
        <input type="number" class="form-control border ${borderColor} mb-4" id="jumlahBarangEdit${idCard}"></div><div class="col-6"><label for="harga" class="">Harga</label><input type="number" class="form-control border ${borderColor} mb-4" id="hargaBarangEdit${idCard}"></div></div>`;
        document.querySelector(id).insertAdjacentHTML('beforeend', hargaJual);
    } 

    function submitFormEdit(idProduk) {
        let id = document.querySelector("#i"+idProduk).value;
        let nama = document.querySelector("#namaEdit"+idProduk).value;
        let kategoriProduk = document.getElementById('kategori_produkEdit'+idProduk).value;
        let jumlahBarangs = [];
        let hargaBeli = document.querySelector("#hargaBeliEdit"+idProduk).value;
        let dimensi = document.querySelector("#dimensiEdit"+idProduk).value;
        let berat = document.querySelector("#beratEdit"+idProduk).value;
        let stock = document.querySelector("#stockEdit"+idProduk).value;
        let idParent = document.querySelector("#idParent"+idProduk).value;
        let talkingPointEdit = document.querySelector("#talkingPointEdit"+idProduk).value;
        let idBarcode = document.querySelector("#idBarcodeEdit"+idProduk).value;

        document.querySelectorAll("#jumlahBarangEdit"+idProduk).forEach((jumlahBarang, index) => {
            let jumlahBarangValue = jumlahBarang.value;
            if (jumlahBarangValue != ''){
                jumlahBarangs.push({ jumlah: jumlahBarangValue });
            }
        });
        
        document.querySelectorAll("#hargaBarangEdit"+idProduk).forEach((hargaBarang, index) => {
            let hargaBarangValue = hargaBarang.value;
            if (hargaBarangValue != ''){
                jumlahBarangs[index].harga = hargaBarangValue.replace(',', '');
            }
        });
        // console.log(jumlahBarangs);
        // Mengonversi objek menjadi JSON
        jumlahBarangs = JSON.stringify(jumlahBarangs);
        var dataToSend = { 
            id: id,
            nama: nama  ,
            kategoriProduk: kategoriProduk,
            hargaJual: jumlahBarangs,
            hargaBeli: hargaBeli,
            dimensi: dimensi,
            berat: berat,
            stock: stock,
            idParent: idParent,
            talkingPoint: talkingPointEdit,
            idBarcode: idBarcode
        };
        fetch('/components/tambah-product/edit.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(dataToSend),
        })
        .then(response => response.json())
        .then(datas => {
            window.location.href = "/pages/?ikan=" + nama;
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    }
</script>
<?php include $_SERVER['DOCUMENT_ROOT'].'/components/footer/index.php'; ?>