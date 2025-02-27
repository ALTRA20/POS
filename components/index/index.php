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
<div class="bg-success h-vh-93 overflow-hidden d-flex px-3">
    <div class="bg-success p-4" id="cart" style="width:25%;">
        <!--<h5 class="">Keranjang Belanja</h5>-->
        <div class="overflow-auto" id="cartMain" style="height: 83%;">
            <div class="d-flex align-items-center gap-3 pt-3 " id="cart-name">
                <div class="pointer animasi p-2" data-bs-toggle="modal" data-bs-target="#cart-name-modal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-person-vcard-fill" viewBox="0 0 16 16">
                        <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm9 1.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4a.5.5 0 0 0-.5.5M9 8a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4A.5.5 0 0 0 9 8m1 2.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 0-1h-3a.5.5 0 0 0-.5.5m-1 2C9 10.567 7.21 9 5 9c-2.086 0-3.8 1.398-3.984 3.181A1 1 0 0 0 2 13h6.96q.04-.245.04-.5M7 6a2 2 0 1 0-4 0 2 2 0 0 0 4 0"/>
                    </svg>
                </div>
                <div class="modal fade" id="cart-name-modal" tabindex="-1" aria-labelledby="cart-name-modalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-md">
                        <div class="modal-content bg-lightgray">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Cari customer</h1>
                                <button type="button" class="btn-close" id="btn-close-cart-name-modal" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input autocomplete="off" type="text" class="form-control text-primary border border-dark" placeholder="masukan nama" oninput="cariCustomer(this)" onclick="cariCustomer(this)">
                                <div class="hr bg-dark"></div>
                                <div class="mt-3" id="customers">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="m-0" id="namaCustomer"></p>
                <p class="m-0 d-none" id="ic"></p>
            </div>
            
            <div class="" id="cart-produk" style="background: #206320;padding:15px">

            </div>
            <div class="" id="cart-produk-custom" style="background: #206320;padding:15px">
            </div>
            <div class="d-none d-md-flex" role="search">
                <div class="bg-danger py-2 px-3 rounded-start pointer" id="tag">#</div>
                <input onclick='this.select();' style='background-color: white !important;color:green !important' autocomplete="off" class="form-control me-2 text-light rounded-end" id="search" type="search" placeholder="Search" aria-label="Search" style='background-color:white'/>
            </div>
        </div>
        <div class="w-100 d-flex justify-content-center" style="height: 7%;">
            <div class="modal fade" id="addCustomBarang" tabindex="-1" aria-labelledby="addCustomBarangLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <form class="modal-content text-dark" id="upload-form" action="" method="post" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Add Custom Produk</h1>
                            <button type="button" class="btn-close" id="closeAddCustomProduk" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="file" class="form-control" name="video" id="file-input" accept="image/*">
                            <span class="d-none" id="src"></span>
                            <div id="file-list"></div>
                            <img src="" alt="" id="img-produk-custom" class="w-100">
                            <div class="">
                                <label for="namaProdukCustomProduk" class="">Nama Produk</label>
                                <input type="text" id="namaProdukCustomProduk" name="" autocomplete="off" class="form-control border-dark">
                            </div>
                            <div class="row">
                                <div class="col-3 pe-0">
                                    <label for="jumlahCustomProduk" class="">jumlah</label>
                                    <input type="text" id="jumlahCustomProduk" tabindex="-1" name="" autocomplete="off" value="1" class="form-control border-dark">
                                </div>
                                <div class="col-9">
                                    <label for="harga" class="">Harga</label>
                                    <input type="text" id="hargaCustomProduk" name="" autocomplete="off" class="form-control border-dark">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" onclick="addCustomProduk()" id="upload-button" name="insert-video">Tambah</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center justify-content-between d-none" id="btn-lanjut" style="height: 10%;">
            <h5 class="m-0">
                <p class="m-0">Total</p>
                <p class="" id="priceDisplay">Rp 0</p>
                <p class="d-none" id="price">0</p>
            </h5>
            <button class="btn btn-primary" onclick="toPesanan(<?=$userId?>)">Lanjut</button>
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
        <div class="d-flex justify-content-center flex-wrap gap-2 bg-light py-4 h-90 overflow-auto" id="overlay"></div>
        <div class="w-100 d-flex justify-content-center gap-2 bg-light px-5 py-3" id="pages">
        </div>
    </section>
</div>
<script type="text/javascript" src="/lib/loader.js"></script>
<script type="text/javascript" src="/function/chart.js"></script>
<script type="text/javascript" src="/function/history-price.js"></script>
<script type="text/javascript" src="/lib/plupload.full.min.js"></script>

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
                document.querySelector('#cart-produk').innerHTML = 'Keranjang kosong';
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
                console.log(data);
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

    function timeAgo(date) {
        if (typeof date === "string") {
            date = new Date(date.replace(" ", "T"));
        }
        const now = new Date();
        const seconds = Math.floor((now - date) / 1000);

        const intervals = {
            tahun: 31536000,
            bulan: 2592000,
            minggu: 604800,
            hari: 86400,
            jam: 3600,
            menit: 60,
            detik: 1
        };

        for (let key in intervals) {
            const interval = Math.floor(seconds / intervals[key]);
            if (interval >= 1) {
                return `${interval} ${key} yang lalu`;
            }
        }
        return "just now";
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
        let card = `<div class="row p-3 h-fit" data-bs-toggle="modal" data-bs-target="#card${id}">
            <div class="col-8 position-relative">
                <img src="${gambar}" alt="" class="w-100 h-100 p-0 m-0">
                <?php if($jabatan == 'super-admin' || $jabatan == 'cs') : ?>
                    ${(harga != 0 ? `<div class="w-fit p-3 border border-3 border-primary rounded-circle pointer bg-light position-absolute bottom-0 end-0 keranjangIcon" data-bs-toggle="modal" data-bs-target="#exampleModal${id}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-cart-plus text-primary" viewBox="0 0 16 16">
                            <path d="M9 5.5a.5.5 0 0 0-1 0V7H6.5a.5.5 0 0 0 0 1H8v1.5a.5.5 0 0 0 1 0V8h1.5a.5.5 0 0 0 0-1H9z"/>
                            <path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1zm3.915 10L3.102 4h10.796l-1.313 7zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0m7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0"/>
                        </svg>  
                    </div>` : '')}
                <?php endif ?>
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
                <p class="text-dark" id="updateHarga">${timeAgo(data['harga_beli_created_at'])}</p>
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
                                    <span class="bg-success text-light rounded-circle" onclick="document.querySelector('#markupOverlay${id}').classList.toggle('d-none')"><svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16"><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/></svg></span>
                                    </h5>
                                    <div class="d-flex gap-2 mb-2 ${(!data['markup'] || data['markup'] == '0') ? 'd-none' : ''}" id="markupOverlay${id}">
                                        <label for="hargaMarkup${id}" style="white-space: nowrap">Mark Up</label>
                                        <input type="number" class="form-control border-dark" id="hargaMarkup${id}" value="${(data['markup']) ? data['markup'] : '0'}" onclick="this.select()" placeholder="masukkan mark-up an">
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex gap-4">
                                <p class="m-0">Quantity</p>
                                <input autocomplete="off" type="number" autocomplete="on" id="quantity${id}" class="form-control border border-dark text-dark w-fit" value="${(jumlahDiKeranjang) ? jumlahDiKeranjang : '1'}" onfocus='this.select();'>
                            </div>
                            ${request}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" onclick="submit(${id},<?=$userId?>)">Add to cart</button>
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
            if(gambar[5] == 1){
                let urlGambar = '/public/foto/lg/'+gambar[0]+'.mp4';
                gambars += `<div class="position-relative col-6 p-2" id="gambar_md${idGambar}">
                    <video class="" controls style="width:100%; height: 150px; object-fit: contain;">
                        <source src="${urlGambar}">
                    </video>
                    <div class="position-absolute w-fit p-1 bg-warning" onclick="isFavorite(${idGambar},${id})" style="top:10px; right:10px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-heart-fill ${(gambar[1] == 1 ? 'text-danger' : '')}" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314"/>
                        </svg>
                    </div>
                </div>`;
            }else{
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
            }
        });
        let modal = `<div class="modal fade" id="card${id}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl text-dark" role="document">
                <div class="modal-content mt-5">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="card${id}Label">${judul}</h1>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 d-md-none">
                                <div class="d-flex justify-content-between">
                                    <p class="m-0">Foto produk</p>
                                </div>
                                <div class="d-flex flex-wrap align-items-start">
                                    ${gambars}
                                </div>
                            </div>
                            <div class="col-md-6">
                            <!--tersangka-->
                            <img style='height:fit-content;object-fit: contain;' src="${(gambarsProduct.length  !== 0 ? '/public/foto/lg/'+gambarsProduct[0][0]+'.jpg' : '/public/404.png')}" id="img-cover${id}">
                                <div class="d-flex justify-content-between">
                                    <p class="m-0">Foto produk</p>
                                    <?php if($jabatan == 'super-admin') : ?>
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalInner${id}">Tambah Foto</button>
                                    <?php endif ?>
                                </div>
                                <div class="d-flex flex-wrap align-items-start">
                                    ${gambars}
                                </div>
                            </div>
                            <div class="col-md-6 d-none d-md-block">
                                <div class="">
                                    <input type="text" class="form-control d-none" id="i${id}" value="${id}">
                                    <label for="namaEdit" class="fw-bold">Nama</label>
                                    <input type="text" class="form-control border border-dark text-dark mb-4" id="namaEdit${id}" value="${data['nama']}" autofocus autocomplete="off" <?=($jabatan != 'super-admin') ? 'readonly' : '' ?>>
                                </div> 
                                <div class="mb-4">
                                    <div class="" id="hargaJualEdit${id}">
                                        <p class="fw-bold m-0">Harga Jual</p>
                                        ${modal_harga_jual}
                                    </div>
                                    <?php if($jabatan == 'super-admin') : ?>
                                    <div class="w-100 d-flex justify-content-end mt-3  d-none">
                                        <button type="button" class="btn btn-primary" onclick="tambahJualEdit(${id})">Tambah Harga Jual</button>
                                    </div>
                                    <?php endif ?>
                                </div>
                                <div class="mb-4">
                                    <label for="idBarcodeEdit" class="fw-bold">ID Barcode</label>
                                    <input type="text" class="form-control border border-dark text-dark mb-4" id="idBarcodeEdit${id}" value="${idBarcode}" autofocus autocomplete="off" <?=($jabatan != 'super-admin') ? 'readonly' : '' ?>>
                                </div>
                                <div class="mb-4">
                                    <div onclick="document.querySelector('#talkingPointEdit${id}').classList.toggle('d-none')">
                                        <label for="talkingPointEdit" class="fw-bold">Talking Point</label>
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-compact-down" viewBox="0 0 16 16">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-down" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708"/>
                                            </svg>
                                        </span>
                                    </div>
                                    <textarea id="talkingPointEdit${id}" class="${(talking_point) ? '' : 'd-none'} talkingPoint w-100" rows="15" class="form-control border-dark d-none">${talking_point}</textarea>
                                </div>
                                <div class="accordion" id="accordionExample">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne${id}" aria-expanded="true" aria-controls="collapseOne${id}">Detail</button>
                                        </h2>
                                        <div id="collapseOne${id}" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            ${is_child}
                                            <div class="d-none">
                                                <h5 class="mt-3">Kategori Produk</h5>
                                                <?php if($jabatan != 'super-admin') : ?>
                                                    <input type="text" class="form-control border border-dark text-dark mb-4" id="Kategori${id}" value="${kategori}" autofocus autocomplete="off" readonly>
                                                <?php else : ?>
                                                <select class="form-select" aria-label="Default select example" id="kategori_produkEdit${id}" name="kategori_produkEdit">
                                                    <option value="" class="">--pilih kategori--</option>
                                                    <?php $kategoris = $db->query("SELECT DISTINCT `kategori` FROM `produk` WHERE `kategori` IS NOT NULL AND `kategori` != ''"); ?>
                                                    <?php foreach ($kategoris as $kategori) : ?>
                                                        <option value="<?=$kategori['kategori']?>" <?=($produk['kategori'] == $kategori['kategori'] ? 'selected' : '')?>><?=$kategori['kategori']?></option>
                                                    <?php endforeach ?>
                                                </select>
                                                <div class="mb-3" id="addFiturAddMenu">
                                                    <span class="me-2">ingin menambahkan kategori menu baru?</span>
                                                    <span class="text-primary pointer" onclick="addFiturAddMenuEdit(${id})">Klik disini</span>
                                                </div>
                                                <div class="tambahKategoriMenuEdit${id} d-none my-2">
                                                    <div class="d-flex gap-2">
                                                        <input type="text" class="form-control border-dark text-dark" placeholder="masukkan kategori menu yang baru disini" autocomplete="off">
                                                        <button type="button" class="btn btn-primary" onclick="addSelectEdit(${id})">Tambah</button>
                                                    </div>
                                                    <p class="d-none p-2 alert-fade mt-2 rounded w-fit" id="alertSuccessAddKategoriMenuEdit">Kategori menu baru udah ditambahkan</p>
                                                </div>
                                                <?php endif ?>
                                            </div>
                                            <div class="">
                                                <label for="hargaBeliEdit" class="fw-bold">Harga Beli</label>
                                                <input type="text" class="form-control border border-dark text-dark mb-4" id="hargaBeliEdit${id}" value="${hargaBeli}" autocomplete="off" <?=($jabatan != 'super-admin') ? 'readonly' : '' ?>>
                                            </div>
                                            <div class="">
                                                <label for="dimensiEdit" class="fw-bold">Dimensi</label>
                                                <input type="text" class="form-control border border-dark text-dark mb-4" id="dimensiEdit${id}" value="${dimensi}" autocomplete="off" <?=($jabatan != 'super-admin') ? 'readonly' : '' ?>>
                                            </div>
                                            <div class="">
                                                <label for="beratEdit" class="fw-bold">Berat</label>
                                                <input type="text" class="form-control border border-dark text-dark mb-4" id="beratEdit${id}" value="${berat}"  autocomplete="off" <?=($jabatan != 'super-admin') ? 'readonly' : '' ?>>
                                            </div>
                                            <div class="">
                                                <label for="stockEdit" class="fw-bold">Stock</label>
                                                <input type="text" class="form-control border border-dark text-dark mb-4" id="stockEdit${id}" value="${stock}"  autocomplete="off" <?=($jabatan != 'super-admin') ? 'readonly' : '' ?>>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-between mt-5">
                                    <?php if($jabatan == 'super-admin') : ?>
                                    <div class="col-5 d-flex justify-content-center gap-2 btn btn-danger" onclick="delist(<?=$userId?>,${id})">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                            <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                                        </svg>
                                        <span>Hapus</span>
                                    </div>
                                    <div class="col-5 d-flex justify-content-end">
                                        <div class="w-100 btn bg-pink" onclick="submitFormEdit(${id})">Ubah</div>
                                    </div>
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-none" id="edit-mode-unclock${id}">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" onclick="submitFormUpdate(${id})">Simpan Perubahan</button>
                    </div>
                </div>
            </div>
        </div>
            <div class="modal fade text-dark" id="modalInner${id}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <form action="/components/tambah-product/tambah_foto_action.php" method="post" class="modal-content" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah foto</h1>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="text" name="id_user" class="d-none" value="<?=$userId?>">
                        <input type="text" name="id_produk" class="d-none" value="${id}">
                        <input type="text" name="judul" class="d-none" value="${judul}">
                        <label for="upload-thumbnail${id}" class="border border-dash border-dark d-flex justify-content-center align-items-center pointer position-relative" style="width:360px; height:240px;">
                            <img src="" alt="" class="d-none position-absolute w-100 h-100" id="uploadedThumbnail${id}">
                            <p class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center m-0" id="foto-overlay${id}">+ Foto</p>
                            <input type="file" id="upload-thumbnail${id}" onchange="uploadThumbnail(${id})" class="d-none" name="foto" accept="image/*">
                        </label>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>`;
        let charts = `<div class="pointer position-absolute bottom-0 end-0 bg-secondary" data-bs-toggle="modal" data-bs-target="#chart${id}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-graph-up" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M0 0h1v15h15v1H0zm14.817 3.113a.5.5 0 0 1 .07.704l-4.5 5.5a.5.5 0 0 1-.74.037L7.06 6.767l-3.656 5.027a.5.5 0 0 1-.808-.588l4-5.5a.5.5 0 0 1 .758-.06l2.609 2.61 4.15-5.073a.5.5 0 0 1 .704-.07"/>
                    </svg>
                </div>
                <div class="modal fade" id="chart${id}" tabindex="-1" aria-labelledby="chart${id}Label" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-4 d-flex align-items-center">
                                        <img src="${(gambarsProduct.length  !== 0 ? '/public/foto/lg/'+gambarsProduct[0][0]+'.jpg' : '/public/404.png')}" alt="" id="img-cover${id}" class="w-100 h-100">
                                    </div>
                                    <div class="col-8">
                                        <div class="w-100" id="chart_div${id}"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`
        let parent = `<div class="pointer position-absolute bottom-0 bg-primary m-1 p-1 px-2" style="right:42px;"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-diagram-3-fill" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M6 3.5A1.5 1.5 0 0 1 7.5 2h1A1.5 1.5 0 0 1 10 3.5v1A1.5 1.5 0 0 1 8.5 6v1H14a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-1 0V8h-5v.5a.5.5 0 0 1-1 0V8h-5v.5a.5.5 0 0 1-1 0v-1A.5.5 0 0 1 2 7h5.5V6A1.5 1.5 0 0 1 6 4.5zm-6 8A1.5 1.5 0 0 1 1.5 10h1A1.5 1.5 0 0 1 4 11.5v1A1.5 1.5 0 0 1 2.5 14h-1A1.5 1.5 0 0 1 0 12.5zm6 0A1.5 1.5 0 0 1 7.5 10h1a1.5 1.5 0 0 1 1.5 1.5v1A1.5 1.5 0 0 1 8.5 14h-1A1.5 1.5 0 0 1 6 12.5zm6 0a1.5 1.5 0 0 1 1.5-1.5h1a1.5 1.5 0 0 1 1.5 1.5v1a1.5 1.5 0 0 1-1.5 1.5h-1a1.5 1.5 0 0 1-1.5-1.5z"/>
        </svg></div>`;
        
        let priceHistory = `<div class="position-absolute bottom-0" style="right:44px;">
            <img src="/public/foto/md/history-harga.jpeg" clas="" style="width:40px;height:40px;" data-bs-toggle="modal" data-bs-target="#price-history${id}">
            <div class="modal fade" id="price-history${id}" tabindex="-1" aria-labelledby="price-historyLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="price-historyLabel">Modal title</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-4 d-flex align-items-center">
                                    <img src="${(gambarsProduct.length  !== 0 ? '/public/foto/lg/'+gambarsProduct[0][0]+'.jpg' : '/public/404.png')}" alt="" id="img-cover${id}" class="w-100 h-100">
                                </div>
                                <div class="col-8">
                                    <div class="w-100" id="chart-price-history${id}"></div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
        let barcodeIcon = '';
        if (idBarcode) {
            barcodeIcon = `<div class="position-absolute top-0 end-0" style="width:50px;"><img src="/public/foto/icon/barcode.jpg"></div>`;
        }
        card = '<div class="card-produk position-relative">'+card+modal+modalInputPesanan+charts+priceHistory+barcodeIcon+'</div>';
        let tempDiv = document.createElement('div');
        tempDiv.innerHTML = card;
        // Append the first child of the temporary div (which is the card element) to the #overlay element
        document.querySelector('#overlay').appendChild(tempDiv.firstChild);
        makeChart(id);
        priceHistoryJual(id);
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