<style>
    .foto-nota{
        display: flex;
        justify-content: center;
        align-items : center;
        min-width: 370px;
        min-height: 370px;
        border: 2px dashed black;
    }
    </style>
<script>
    document.title = 'History GS';
</script>
<section class="p-md-5 text-dark">
    <div class="row">
        <div class="col-7">
            <h5 class="">Fotoform</h5>
            <label for="fotoform" class="foto-nota position-relative pointer">
                <h5 class="w-fit h-fit position-absolute">+ Tambah foto</h5>
                <?php
                $foto_nota_stock = $db->query("SELECT * FROM `foto_nota_stock` WHERE `user_id` = '$userId' AND `used` = '0' ORDER BY `id` DESC LIMIT 1")->fetch_assoc();   
                $namaFile = $foto_nota_stock['file'];   
                $id = $foto_nota_stock['id'];   
                ?>
                <img src="/public/nota-stock/<?=$namaFile?>" alt="" id="img-nota" class="w-100">
                <form action="" id="upload-form">
                    <input type="file" class="d-none" id="fotoform" accept="image/*">
                    <input type="text" class="d-none" value="<?=$id?>" id="iFF">
                </form>
            </label>
        </div>
        <div class="col-5">
            <div class="my-2">
                <h5 class="m-0" id="nama-vendor"></h5>
                <button type="button" class="btn btn-primary" id="btn-modal-vendor" data-bs-toggle="modal" data-bs-target="#exampleModal">Vendor</button>
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Pilih Vendor yg Datang</h1>
                                <button type="button" class="btn-close" id="btn-close-vendor" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="text" class="form-control" id="vendor" autocomplete="off" autofocus placeholder="nama vendor">
                                <button class="btn btn-primary d-none" id="btn-vendor">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <p class="m-0">Tanggal: </p>
                <input type="date" class="form-control border border-dark" id="inputTanggalBeli" autocomplete="off" style="width:fit-content;">
                <p class="d-none m-0" id="tanggalBeli"></p>
            </div>
            <div class="d-flex align-items-center gap-3">
                <label for="asliNota" class="">Total Asli nota</label>
                <input type="text" id="inputAsliNota" class="form-control border border-dark" value="0" onfocus="use_number(this); this.select()" onblur="use_text(this)">
                <p class="d-none m-0" id="asliNota"></p>
            </div>
            <div class="my-2 d-none" id="overlayTambahDetail">
                <div class="d-none justify-content-end" id="btn-hapus-semua">
                    <div class="btn btn-danger" onclick="removeAll()">Hapus Semua</div>
                </div>
                <div class="" id="listBarang">
                </div>
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalOuter">+Tambah Detail</button>
                <div class="modal fade" id="modalOuter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Isi Nota Datang</h5>
                                <button type="button" class="close" id="closeIsiNotaDatang" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="text" class="form-control border border-dark d-none" id="ip" placeholder="tulis nama bahan custom" autocomplete="off" oninput="validasi()">
                                <div class="d-flex gap-2 my-2">
                                    <div class="border border-dark d-none" style="width:100px;height:100px;" id="overlayGambar">
                                        <img src="" alt="" class="w-100 h-100">
                                    </div>
                                    <div class="">
                                        <div class="d-flex align-items-center gap-1">
                                            <p class="m-0 py-1 px-2 bg-dark text-light rounded d-none" id="idDisplay"></p>
                                            <p class="m-0 d-none" id="namaDisplay"></p>
                                        </div>
                                        <button class="btn btn-success" id="pilihBarang-tab" data-toggle="modal" data-target="#modalInner">Pilih Barang</button>
                                        <button class="btn btn-info" id="custom-tab">Custom</button>
                                    </div>
                                </div>
                                <div class="d-none" id="custom">
                                    <div class="row">
                                        <div class="col-3">
                                            <p class="m-0">Qty</p>
                                            <input type="int" class="form-control border border-dark" placeholder="qty" id="qty" autocomplete="off" oninput="validasiTambahBaris()">
                                        </div>
                                        <div class="col-6">
                                            <p class="m-0">Unit:</p>
                                            <select class="form-select border border-dark" name="unit" id="unit" aria-label="Default select example" onchange="validasiTambahBaris()">
                                                <option value="--">--pilih unit--</option>
                                                <option value="pcs">pcs</option>
                                                <option value="bal">bal</option>
                                                <option value="kg">kg</option>
                                                <option value="karung">karung</option>
                                                <option value="kardus">kardus</option>
                                                <option value="lainnya">lainnya</option>
                                            </select>
                                        </div>
                                        <div class="col-3">
                                            <p class="m-0">Harga Beli:</p>
                                            <input type="int" class="form-control border border-dark" id="harbel" placeholder="harbel" oninput="validasiTambahBaris()" autocomplete="off">
                                        </div>
                                    </div>
                                    <button class="btn btn-primary d-none" id="btn-tambah-baris" onclick="tambahDetail(<?=$userId?>)">Tambah Baris</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="modalInner" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Pilih Bahan yg Datang</h5>
                                <button type="button" class="close" id="innerClose" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="text" class="form-control border-dark" oninput="ambilBahanDatang(this.value)" autofocus>
                                <div class="" id="listbahanDatangModal" autocomplete="off">

                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end my-2 d-none" id="btn-kirim">
                <button type="button" class="btn btn-primary" onclick="insertLogStock(<?=$userId?>)">Kirim</button>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/plupload/3.1.2/plupload.full.min.js"></script>
<script>
    
    document.querySelector("#ip").addEventListener('input', function () {
        document.querySelector("#idDisplay").innerHTML = this.value;
    })

    function ambilBahanDatang(where) {
        let namaVendor = document.querySelector("#nama-vendor").innerHTML;
        var dataToSend = { 
            where: where
        };
        fetch('/components/stock/bahanDatang.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(dataToSend),
        })
        .then(response => response.json())
        .then(datas => {
            let element = '';
            datas.forEach(data => {
                console.log(JSON.parse(data[2])[0]['harga']);
                // console.log(JSON.parse(data[2][0]));
                let harga = (data[2]) ? JSON.parse(data[2])[0]['harga'] : '';
                let gambar = '';
                if(data[16]) {
                    gambar = '/public/foto/md/'+data[16]+'.jpg';
                }else{
                    gambar = '/public/404.png';
                }
                element += `<div class="row mt-3">
                    <div class="col-3">
                        <div class="border border-dark" style="width:100px;height:100px;">
                            <img src="${gambar}" alt="" class="w-100 h-100">
                        </div>
                    </div>
                    <div class="col-9">
                        <div class="">
                            <p class="m-0">${data[1]}</p>
                            <p class="text-success">${harga}</p>
                        </div>
                        <button class="btn btn-primary mt-2" onclick="inputId(${data[0]},'${data[1]}','${gambar}')">Gunakan</button>
                    </div>
                </div>`;
            });
            document.querySelector("#listbahanDatangModal").innerHTML = element;
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    }
    ambilBahanDatang();
    document.querySelector("#history").href = '/pages/stock/history.php';
    document.querySelector("#history").innerHTML = 'History Stock';
    function insertLogStock(userId) {
        let namaVendor = document.querySelector("#nama-vendor").innerHTML;
        let asliNota = document.querySelector("#inputAsliNota").value;
        asliNota = asliNota.replace(",", "");
        var dataToSend = { 
            userId: userId,
            namaVendor: namaVendor,
            asliNota: asliNota
        };
        fetch('/components/stock/addStock.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(dataToSend),
        })
        .then(response => response.json())
        .then(datas => {
            window.location.href = "/pages/stock/history.php";
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    }
    document.querySelector("#vendor").addEventListener("input", function () {
        if(this.value != ''){
            document.querySelector("#btn-vendor").classList.remove("d-none");
        }else{
            document.querySelector("#btn-vendor").classList.add("d-none");
        }
    });
    document.querySelector("#btn-vendor").addEventListener("click", function () {
        let namaVendor = document.querySelector("#vendor").value;
        document.querySelector("#nama-vendor").innerHTML = namaVendor;
        document.querySelector("#overlayTambahDetail").classList.remove("d-none");
        document.querySelector("#btn-close-vendor").click();
    })
    document.querySelector("#inputTanggalBeli").addEventListener("input", function () {
        // console.log(this.value);
        document.querySelector("#tanggalBeli").innerHTML = this.value;
    })
    
    function getListNotaTemp(idUser) {
        let totalHarga = 0;
        var dataToSend = { 
            userId: idUser
        };
        fetch('/components/stock/getTemp.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(dataToSend),
        })
        .then(response => response.json())
        .then(datas => {
            if(datas.length > 0){
                document.querySelector("#btn-hapus-semua").classList.remove("d-none");
                document.querySelector("#btn-hapus-semua").classList.add("d-flex");
                document.querySelector("#overlayTambahDetail").classList.remove("d-none");
                document.getElementById('btn-kirim').classList.remove("d-none");
                document.getElementById('btn-modal-vendor').classList.add("d-none");
                document.getElementById('nama-vendor').innerHTML = datas[0][3] ;
                document.getElementById('inputTanggalBeli').classList.add("d-none");
                document.getElementById('tanggalBeli').classList.remove("d-none");
                document.getElementById('tanggalBeli').innerHTML = datas[0][8] ;
                document.getElementById('inputAsliNota').classList.add("d-none");
                document.getElementById('asliNota').classList.remove("d-none");
                document.getElementById('asliNota').innerHTML = datas[0][9] ;
                let stocks = '';
                let totalHarga = 0;
                datas.forEach(data => {
                    stocks += `<div class="row align-items-center my-2" id="listProduk${data[0]}">
                        <div class="col-1 d-flex align-items-center justify-content-center"> 
                            <div class="" id="hapusProdukInList" onclick="removeLogStockTemp(${data[0]})">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-trash3-fill pointer" viewBox="0 0 16 16">
                                <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
                                </svg>
                            </div>
                        </div>
                        <div class="col-9 d-flex gap-3">
                            <div class="" style="width:100px; height:100px">
                                <img src="${(data[12]) ? '/public/foto/md/'+data[12]+'.jpg' : '/public/404.png'}" class="w-100 rounded-circle">
                            </div>
                            <div class="w-100">
                                <p class="m-0">${data[10]}</p>
                                <p class="text-primary m-0">${data[4]} ${data[6]} <span class="m-0 text-success fw-bold">@${rupiah(data[5])}</span></p>
                                <p class="text-danger fw-bold" id="listProdukHarga">${rupiah(data[5] * data[4])}</p>
                            </div>
                        </div>
                    </div>`;
                    totalHarga += data[5] * data[4];
                });
                stocks += `<div class="d-flex align-items-center justify-content-end">
                            <h5 class="m-0 text-danger fw-bold">${rupiah(totalHarga)}</h5>
                        </div>`;
                document.querySelector("#listBarang").innerHTML = stocks;
            }
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    }
    function removeAll() {
        document.querySelectorAll("#hapusProdukInList").forEach(element => {
            element.click();
        });
        window.location.href = '/pages/stock/tambah.php';
    }
    function removeLogStockTemp(id){
        var dataToSend = { 
            id: id
        };
        fetch('/components/stock/hapusTemp.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(dataToSend),
        })
        .then(response => response.text())
        .then(datas => {
            getListNotaTemp(<?=$userId?>);
            document.getElementById("listProduk"+id).remove();
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    }
    getListNotaTemp(<?=$userId?>);
    function resetInput() {
        let qty = document.getElementById('qty').value = '';
        let unit = document.getElementById('unit').value = '';
        let harbel = document.getElementById('harbel').value = '';
    }
    function validasiTambahBaris() {
        let count = 0;
        if (document.getElementById('qty').value != '') {
            count += 1; 
        }
        if (document.getElementById('unit').value != '') {
            count += 1;
        }
        if (document.getElementById('harbel').value != '') {
            count += 1;
        }

        if (count == 3) {
            document.getElementById('btn-tambah-baris').classList.remove("d-none");
        }else{
            document.getElementById('btn-tambah-baris').classList.add("d-none");
        }
    }
    function tambahDetail(userId) {
        let idProduk = document.getElementById('idDisplay').innerHTML.replace(/#/g, '');
        let namaVendor = document.getElementById('nama-vendor').innerHTML;
        let asliNota = document.getElementById('inputAsliNota').value;
        let qty = document.getElementById('qty').value;
        let unit = document.getElementById('unit').value;
        let harbel = document.getElementById('harbel').value;
        let tanggalBeli = document.getElementById('tanggalBeli').innerHTML;
        let idFotoNota = document.getElementById('iFF').value;
        resetInput();
        document.getElementById('idDisplay').classList.add('d-none');
        document.getElementById('namaDisplay').classList.add('d-none');
        document.getElementById('overlayGambar').classList.add('d-none');
        document.getElementById('custom').classList.add('d-none');
        document.getElementById('closeIsiNotaDatang').click();
        var dataToSend = { 
            userId: userId,
            idProduk: idProduk,
            namaVendor: namaVendor,
            asliNota: asliNota,
            qty: qty,
            harbel: harbel,
            unit: unit,
            tanggalBeli: tanggalBeli,
            idFotoNota: idFotoNota
        };
        console.log(dataToSend);
        fetch('/components/stock/addStockTemp.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(dataToSend),
        })
        .then(response => response.json())
        .then(datas => {
            getListNotaTemp(<?=$userId?>);
        })
        .catch((error) => {
            console.error('Error:', error);
        });
        document.getElementById('btn-kirim').classList.remove("d-none");
    }
    function inputId(id,nama,gambar) {
        document.getElementById('ip').value = id;
        document.getElementById('idDisplay').innerHTML = '#'+id;

        document.getElementById('overlayGambar').classList.remove('d-none');
        document.getElementById('idDisplay').classList.remove('d-none');
        document.getElementById('namaDisplay').classList.remove('d-none');
        console.log(gambar);
        document.querySelector('#overlayGambar img').src = gambar;
        document.getElementById('namaDisplay').innerHTML = nama;

        document.getElementById('innerClose').click();
        validasi();
    }
    document.getElementById('custom-tab').addEventListener("click", function () {
        document.getElementById('ip').classList.remove('d-none');
        document.getElementById('custom').classList.remove('d-none');
    });
    function validasi() {
        console.log(document.getElementById('ip'));
        if (document.getElementById('ip').value == '') {
            document.getElementById('custom').classList.add('d-none');
        }else{
            document.getElementById('custom').classList.remove('d-none');
        }
    }
    document.getElementById('fotoform').addEventListener('change', function(e) {
        var file = e.target.files[0];
        var reader = new FileReader();

        reader.onload = function(e) {
            var imgElement = document.getElementById('img-nota');
            imgElement.src = e.target.result;
        };
        reader.readAsDataURL(file);

        // Tambahkan fungsi untuk mengupload file
        uploadFile(file);
    });

    function uploadFile(file) {
        var formData = new FormData();
        formData.append('file', file);

        // Lakukan request AJAX untuk mengirim file ke server
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/components/stock/upload-nota.php', true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                // File berhasil diupload, tambahkan logika sesuai kebutuhan Anda
                window.location.href = window.location.href;
                console.log('File berhasil diupload:', xhr.responseText);
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

</script>