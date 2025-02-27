<?php include $_SERVER['DOCUMENT_ROOT'].'/components/header/index.php'; ?>
<section class="container bg-light my-5 ">
    <form action="" method="post" class="modal-content p-5 rounded text-dark">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah produk</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="">
                <label for="nama" class="f  w-bold">Nama</label>
                <input autocomplete="off" type="text" class="form-control border border-dark mb-4" id="nama" autofocus>
            </div>
            <div class=" mb-4">
                <div class="" id="hargaJual">
                    <p class="fw-bold m-0">Harga Jual</p>
                    <div class="row">
                        <div class="d-none col-6">
                            <label for="jumlahBarang" class="">Jumlah Barang</label>
                            <input autocomplete="off" type="number" class="form-control border border-dark mb-4" id="jumlahBarang" value="1">
                        </div>
                        <div class="col-12">
                            <label for="hargaBarang" class="">Harga</label>
                            <input autocomplete="off" type="text" class="form-control border border-dark mb-4" id="hargaBarang" value="0">
                        </div>
                    </div>
                </div>
                <div class="w-100 d-flex justify-content-end mt-3 d-none">
                    <button type="button" class="btn btn-primary" onclick="tambahJual()">Tambah Harga Jual</button>
                </div>
            </div>
            <div class="accordion mb-4" id="detail1">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Detail 1
                    </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#detail1">
                    <div class="accordion-body">
                        <div class="">
                            <label for="hargaBeli" class="fw-bold">Harga Beli</label>
                            <input autocomplete="off" type="text" class="form-control border border-dark mb-4" id="hargaBeli" value="0">
                        </div>
                        <div class="mb-4">
                            <label for="talkingPoint" class="fw-bold">Talking Point</label>
                            <textarea name="" id="talkingPoint" cols="" rows="15" class="w-100 form-control border-dark"></textarea>
                        </div>
                        <div class="mb-4">
                            <label for="idBarcode" class="fw-bold">ID Barcode</label>
                            <input type="text" class="form-control border border-dark text-dark mb-4" id="idBarcode" autocomplete="off">
                        </div>
                    </div>
                    </div>
                </div>
            </div>
            <div class="accordion" id="accordionExample">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOneTambahProduk" aria-expanded="true" aria-controls="collapseOneTambahProduk">Detail</button>
                    </h2>
                    <div id="collapseOneTambahProduk" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label for="statusBarang" class="fw-bold">Parent</label>
                                </div>
                                <div class="" id="isChild">
                                    <input type="text" class="form-control border-dark" oninput="productsForChild(<?=$userId?>, this.value, 1)" placeholder="Masukkan nama product">
                                    <input type="text" class="d-none" id="idParent" value="0">
                                    <div id="productsForChild">
                                        <div class="w-100 d-flex gap-2 align-items-center border border-dark pointer my-2">
                                            <img src="/public/404.png" alt="" class="p-0 m-0" style="width:45px;height:45px">
                                            <b class="m-0 text-center w-100">--------------null--------------</b>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <h5 class="mt-3">Kategori Produk</h5>
                                <select class="form-select" aria-label="Default select example" id="kategori_produk" name="kategori_produk">
                                    <option value="" class="">--pilih kategori--</option>
                                    <?php $kategoris = $db->query("SELECT DISTINCT `kategori` FROM `produk` WHERE `kategori` IS NOT NULL AND `kategori` != ''"); ?>
                                    <?php foreach ($kategoris as $kategori) : ?>
                                    <option value="<?=$kategori['kategori']?>"><?=$kategori['kategori']?></option>
                                    <?php endforeach ?>
                                </select>
                                <div class="mb-3" id="addFiturAddMenu">
                                    <span class="me-2">ingin menambahkan kategori menu baru?</span>
                                    <span class="text-primary pointer" onclick="addFiturAddMenu()">Klik disini</span>
                                </div>
                                <div class="tambahKategoriMenu d-none my-2">
                                    <div class="d-flex gap-2">
                                        <input autocomplete="off" type="text" class="form-control" placeholder="masukkan kategori menu yang baru disini">
                                        <button type="button" class="btn btn-primary" onclick="addSelect()">Tambah</button>
                                    </div>
                                    <p class="d-none p-2 alert-fade mt-2 rounded w-fit" id="alertSuccessAddKategoriMenu">Kategori menu baru udah ditambahkan</p>
                                </div>
                            </div>
                            <div class="">
                                <label for="dimensi" class="fw-bold">Dimensi</label>
                                <input autocomplete="off" type="text" class="form-control border border-dark mb-4" id="dimensi">
                            </div>
                            <div class="">
                                <label for="berat" class="fw-bold">Berat</label>
                                <input autocomplete="off" type="text" class="form-control border border-dark mb-4" id="berat">
                            </div>
                            <div class="">
                                <label for="stock" class="fw-bold">Stock</label>
                                <input autocomplete="off" type="text" class="form-control border border-dark mb-4" id="stock" value="0">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" name="" class="btn btn-primary" onclick="submitForm()">Kirim</button>
        </div>
    </form>
</section>
<script>
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

            window.location.href = '/pages';
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
</script>
<?php include $_SERVER['DOCUMENT_ROOT'].'/components/footer/index.php'; ?>