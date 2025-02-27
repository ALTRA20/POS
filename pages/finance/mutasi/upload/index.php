<?php
session_start();
$_SESSION['last_url'] = $_SERVER[REQUEST_URI];
?>
<?php include $_SERVER['DOCUMENT_ROOT'].'/components/header/index.php';?>
<style>
    .bg-orange{
        background-color: #ff4800;
    }
    .text-orange{
        color: #ff4800;
    }
    .text-blue{
        color: rgb(13 27 240) !important;
    }
    .text-red{
        color: rgb(255 0 0) !important;
    }
</style>
<section class="w-100 p-5 pt-2 bg-primary-me">
    <div class="container">
        <?php
        function choseAlphabet($number) {
            $huruf = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
            if ($number == 0) {
                return "X";
            }else{
                return $huruf[$number - 1];
            }
        }

        // if (isset($_POST['btn-upload'])) {
        //     $fileTmpPath = $_FILES['file']['tmp_name'];
        //     $fileType = $_FILES['file']['type'];
        
        //     // Validasi apakah file sudah diunggah dan memiliki tipe CSV
        //     if (is_uploaded_file($fileTmpPath) && $fileType === 'text/csv') {
        //         $csvData = [];
        //         if (($handle = fopen($fileTmpPath, "r")) !== FALSE) {
        //             while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        //                 $csvData[] = $data;
        //             }
        //             fclose($handle);
        //         }
        
        //         // Hapus header atau bagian tidak relevan
        //         $csvDatas = array_slice($csvData, 5);
        
        //         // Fungsi untuk menghasilkan kode bayar unik
        //         function generateUniqueKodeBayar($db, $prefix = "W") {
        //             do {
        //                 $kodeBayar = $prefix . choseAlphabet(rand(0, 25)) . choseAlphabet(rand(0, 25)) . choseAlphabet(rand(0, 25));
        //                 $stmt = $db->prepare("SELECT 1 FROM `tr_bca` WHERE `kode_bayar` = ? LIMIT 1");
        //                 $stmt->bind_param("s", $kodeBayar);
        //                 $stmt->execute();
        //                 $result = $stmt->get_result();
        //             } while ($result->num_rows > 0);
        //             return $kodeBayar;
        //         }
        
        //         // Fungsi untuk menentukan tanggal
        //         function getValidDate($rawDate, &$bufferDate) {
        //             $rawDate = trim(str_replace("'", "", $rawDate)); // Hilangkan spasi di sekitar nilai
        //             if ($rawDate === "PEND") {
        //                 $date = (new DateTime($bufferDate))->modify('+1 day');
        //                 $dateFix = $date->format('Y-m-d');
        //                 return $dateFix;
        //             }
                
        //             // Jika rawDate bukan "PEND", gunakan bufferDate + 1
        //             $date = DateTime::createFromFormat('d/m/Y', $rawDate);
        //             if ($date && $date->format('d/m/Y') === $rawDate) {
        //                 $bufferDate = $date->format('Y-m-d');
        //                 return $bufferDate;
        //             }
        //         }                
        
        //         $datasStatus = [];
        //         $bufferDate = ''; // Buffer tanggal untuk kasus `PEND`
        
        //         // Fungsi untuk memproses baris CSV
        //         function processRow($db, $csvRow, $column, &$bufferDate) {
        //             global $datasStatus;
        
        //             $tanggal = getValidDate(trim($csvRow[0]), $bufferDate);
        //             $keterangan = $csvRow[1] ?? '';
        //             $status = $csvRow[4] ?? '';
        //             $jumlah = explode('.', $csvRow[3])[0];
        
        //             // Cek apakah data sudah ada di database
        //             $stmt = $db->prepare("SELECT * 
        //             FROM tr_bca 
        //             WHERE tanggal_transaksi BETWEEN DATE_SUB(?, INTERVAL 2 DAY) AND DATE_ADD(?, INTERVAL 2 DAY) 
        //             AND $column = ? LIMIT 1");

        //             // Mengikat parameter untuk tanggal dan jumlah
        //             $stmt->bind_param("sss", $bufferDate, $bufferDate, $jumlah);
        //             $stmt->execute();
        //             $result = $stmt->get_result();
        
        //             if ($result->num_rows === 0) {
        //                 // var_dump($tanggal);
        //                 $kodeBayar = generateUniqueKodeBayar($db);
        //                 $insert_stmt = $db->prepare("INSERT INTO `tr_bca` (`kode_bayar`, `$column`, `keterangan`, `tanggal_transaksi`, `created_at`, `status`) 
        //                                              VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP(), ?)");
        //                 $insert_stmt->bind_param("sssss", $kodeBayar, $jumlah, $keterangan, $tanggal, $status);
        //                 if ($insert_stmt->execute()) {
        //                     $csvRow['status'] = "berhasil";
        //                 } else {
        //                     $csvRow['status'] = "gagal";
        //                 }
        //                 $datasStatus[] = $csvRow;
        //             }else{
        //                 $csvRow['status'] = "duplicate";
        //                 $datasStatus[] = $csvRow;
        //             }
        //         }
        //         // Proses semua baris CSV
        //         foreach ($csvDatas as $csvRow) {
        //             if (isset($csvRow[0]) && $csvRow[0] !== "Saldo Awal" && $csvRow[1] !== "=") {
        //                 $column = ($csvRow[4] === "CR") ? "duit_in" : "duit_out";
        //                 processRow($db, $csvRow, $column, $bufferDate);
        //             }
        //         }
        
        //         // Tampilkan hasil proses
        //         // echo "<script>alert('Proses selesai. Data telah diunggah.')</script>";
        //         // echo "<script>getMutasi(document.querySelector('#banks').value, this.value)</script>";
        //     } else {
        //         echo "<p class='text-danger'>Error: File tidak valid atau tidak dapat diunggah.</p>";
        //     }
        // }
        ?>
        <div class="row justify-content-between align-items-center my-4">
        <div class="col-2">
                <select class="form-select bg-info" id="banks" aria-label="Default select example">
                    <option value="BCA">BCA</option>
                    <option value="SPLIT">SPLIT</option>
                </select>
            </div>
            <div class="col-3 d-flex">
                <h2 class="">Mutasi Rekening </h2> &nbsp;
                <div class="pointer" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z"/>
                    </svg>
                </div>
            </div>
            <div class="col-7 pe-5">
                <input type="text" class="w-100 form-control bg-warning text-center mb-2" placeholder="cari nominal atau deskripsi transfer" id="search" oninput="getMutasi(document.querySelector('#banks').value,this.value)" autocomplete="off">
            </div>
            
        </div>
        <div class="" id="result"></div>
        <div class="" id="mutasi">
            
        </div>
        <div class="w-100 d-flex justify-content-center">
            <div class="modal fade text-primary-me" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Upload Mutasi</h1>
                        <button type="button" class="btn-close" id="closeUploadMutasi" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="modal-body" id="uploadForm">
                        <input type="file" class="form-control text-primary-me" id="fileInput" name="file" accept="csv" oninput="validasi(this)">
                        <div class="w-100 d-flex justify-content-end mt-3 d-none" id="mutasiBtn">
                            <button class="btn btn-success bg-primary-me px-4" name="btn-upload">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
            </div>
        </div>
    </div>
</section>
<script>
    document.getElementById('uploadForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        const fileInput = document.getElementById('fileInput');
        if (!fileInput.files[0]) {
            alert('Please select a file!');
            return;
        }

        const formData = new FormData();
        formData.append('file', fileInput.files[0]);

        try {
            const response = await fetch('proses.php', {
                method: 'POST',
                body: formData,
            });

            const result = await response.json();
            console.log(result);
            if (result.status == "success") {
                let card = '';
                result.datas.forEach((data,index) => {
                    let status = data['status'];
                    let upload = data['upload'];
                    let bg = (status == 'berhasil') ? 'bg-success' : ((status == 'duplicate') ? 'bg-orange' : 'bg-danger');
                    let tanggal_transaksi = data['tanggal'];
                    let Keterangan = data[1];
                    let nominal = data[3];
                    let btn = (upload) ? `<button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#insertManual${index}">insert manual</button>` : '';
                    card += `<div class="row border-bottom ${bg}" id="cardStatus${index}">
                        <div class="col-2 p-4">${tanggal_transaksi}</div>
                        <div class="col-2 p-4">${rupiah(nominal)}</div>
                        <div class="col-5 p-4">${Keterangan}</div>
                        <div class="col-1 p-4" id="cardStatusStatus${index}">${status}</div>
                        <div class="col-2 d-flex justify-content-center p-4" id="cardStatusBtns${index}">${btn}</div>
                    </div>`;
                    card += `<div class="modal fade" id="insertManual${index}" tabindex="-1" aria-labelledby="insertManual${index}Label" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content text-dark">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="insertManual${index}Label">Alasan menginsert manual</h1>
                                    <button type="button" class="btn-close" id="close-btn-insert-manual${index}" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <textarea rows="7" class="w-100 form-control bg-orange" id="alasan${index}" placeholder="alasannya ......"></textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-success" onclick="uploadLagi('${Keterangan}', '${nominal}', '${tanggal_transaksi}', '${index}', '<?=$userId?>')">Insert</button>
                                </div>
                            </div>
                        </div>
                    </div>`;
                });
                document.getElementById('result').innerHTML = card;
                getMutasi(document.querySelector("#banks").value);
                document.querySelector("#closeUploadMutasi").click();
            }else{  
                alert(result.message);
            }
        } catch (error) {
            console.error('Error:', error);
            document.getElementById('response').innerText = 'An error occurred!';
        }
    });

    function uploadLagi(keterangan, nominal, tanggal, id, userId) {
        let konfirmasi = confirm("Apakah yakin ingin mengupload data ini?");
        let alasan = document.querySelector("#alasan"+id).value;
        if (konfirmasi) {
            var dataToSend = { 
                keterangan: keterangan,
                nominal: nominal,
                tanggal: tanggal,
                alasan: alasan,
                userId: userId,
            };
            fetch('uploadUlang.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(dataToSend),
            })
            .then(response => response.json())
            .then(datas => {
                if (datas['status'] == "success") {
                    document.querySelector("#cardStatus"+id).classList.add("bg-success");
                    document.querySelector("#cardStatusStatus"+id).innerHTML = 'berhasil';
                    document.querySelector("#cardStatusBtns"+id).innerHTML = '';
                    document.querySelector("#close-btn-insert-manual"+id).click();
                    getMutasi(document.querySelector("#banks").value, document.querySelector("#search").value);
                }
            })
            .catch((error) => {
                console.error('Error:', error);
            });
        }
    }


    document.querySelector("#banks").addEventListener("change", function () {
        let bank = document.querySelector("#banks").value;
        getMutasi(bank, document.querySelector("#search").value);
    });

    function delist(id, kodeBayar, bank) {
        let konfirmasi = confirm(`Apakah anda yakin ingin menghapus mutasi dengan kode bayar ${kodeBayar}?`);
        if (konfirmasi) {
            let datas = {
                id : id,
                bank : bank
            }
            fetch('/pages/finance/mutasi/hapus.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(datas),
            })
            .then(response => response.json())
            .then(datas => {
                let bank = document.querySelector("#banks").value;
                getMutasi(bank, document.querySelector("#search").value);
            })
            .catch((error) => {
                console.error('Error:', error);
            });
        }
    }

    function cashCheck(jumlah) {
        if (jumlah) {
            document.querySelector("#modal-footer-cash").classList.remove("d-none");
        } else {
            document.querySelector("#modal-footer-cash").classList.add("d-none");
        }
    }
    function pengurangan(id) {
        let kurangi = 0;
        
        document.querySelectorAll("#nominalInput" + id).forEach((input) => {
            if (parseInt(input.value)) {
                kurangi += parseInt(input.value);
            }
        });
        return kurangi;
    }
    function split(id,inputNow) {
        if(inputNow.value < 0){
            inputNow.value = 0;
        }else{
            let nominalMaksimal = parseInt(document.querySelector("#nominal" + id).innerHTML);
            let kurangi = pengurangan(id);
            let sisa = nominalMaksimal - kurangi;
            if (sisa < 0) {
                inputNow.value = 0;
                document.querySelector("#nominalAkhir" + id).innerHTML = nominalMaksimal - pengurangan(id);
                document.querySelector("#nominalInputSisa" + id).value = nominalMaksimal - pengurangan(id);
            } else {
                document.querySelector("#nominalAkhir" + id).innerHTML = nominalMaksimal - kurangi;
                document.querySelector("#nominalInputSisa" + id).value = nominalMaksimal - kurangi;
            }
        }
    }

    function splitNow(id,bank) {
        let transfers = [];
        document.querySelectorAll("#nominalInput" + id).forEach((input) => {
            if (parseInt(input.value)) {
                transfers.push(parseInt(input.value));
            }
        });
        let nominalInputSisa = document.querySelector("#nominalInputSisa"+id).value;
        transfers.push(parseInt(nominalInputSisa));

        let datas = {
            "transfers" : transfers,
            "bank"  : bank,
            "id_tr"  : id
        }
        fetch('/pages/finance/splitAction.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(datas),
        })
        .then(response => response.text()) // Parse the JSON response
        .then(response => {
            getMutasi(document.querySelector("#banks").value, document.querySelector("#search").value);
            document.querySelector("#close-split"+id).click();
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    }

    function choose(elementchoose,idBayar,nominal,idPesanan) {
        document.querySelector("#bayar_id").value = idBayar;
        document.querySelector("#nominal_bayar_input").value = nominal;

        document.querySelectorAll("#transfer").forEach(element => {
            if(element != elementchoose){
                element.remove();
            }else{
                element.classList.add("bg-success");
                element.classList.add("text-light");
            }
            document.querySelector("#refresh").classList.remove("d-none");
            document.querySelector("#modal-footer-bayar").classList.remove("d-none");
        });
    }

    function ubahModal(mutasiId,kodeBayar,nominal) {
        document.querySelector("#nominal_bayar").innerHTML = nominal;
        document.querySelector("#mutasi_id").value = mutasiId;
        document.querySelector("#kodeBayar").value = kodeBayar;
        document.querySelector("#kode_bayar").innerHTML = kodeBayar;
        getBayar();
    }

    function getBayar(search) {
        search = document.querySelector("#nominal_bayar").innerHTML;
        // console.log(search);
        document.querySelector("#bayar_id").value = '';
        document.querySelector("#nominal_bayar_input").value = '';
        document.querySelector("#modal-footer-bayar").classList.add("d-none");
        document.querySelector("#refresh").classList.add("d-none");
        let datas = {
            search : search
        }
        // console.log(datas);
        fetch('/pages/finance/bayar/get.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(datas),
        })
        .then(response => response.json())
        .then(datas => {
            // console.log(datas);
            let cards = '';
            datas.forEach(data => {
                let created_at = data['created_at'];
                let nominal_bayar = data['nominal_bayar'];
                let pesanan_id = data['pesanan_id'];
                let bayar_id = data['id'];
                let jalur = data['jalur'];
                let nama = data['nama'];
                cards += `<div class="row customerCard border border-dark p-3 rounded my-3" id="transfer" onclick="choose(this,${bayar_id},'${nominal_bayar}',${pesanan_id})" style="background:#accfa4;">
                    <div class="col-2 d-flex align-items-center fw-bold text-orange">${created_at}</div>
                    <div class="col-3 d-flex align-items-center">${rupiah(nominal_bayar)}</div>
                    <div class="col-5 d-flex align-items-center">
                        <div class="fw-bold">
                            <p class="">GS${pesanan_id}</p>
                            <p class="text-red">${nama}</p>
                        </div>
                    </div>    
                    <div class="col-2 d-flex align-items-center text-blue">${jalur}</div>
                </div>`;
            });
            document.querySelector("#resultBayars").innerHTML = cards;
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    }

    function getMutasi(bank, nominal = '') {
        bank = bank.toLowerCase();
        let datas = {
            bank : bank,
            n : nominal
        }
        // console.log(datas);
        fetch('/pages/finance/mutasi/get.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(datas),
        })
        .then(response => response.json())
        .then(datas => {
            let mutasiBankCards = `<div class="row border-bottom">
                <div class="col-2 p-4">tanggal transaksi</div>
                <div class="col-1 p-4">Kode Bayar</div>
                <div class="col-2 p-4">Cash In</div>
                <div class="col-5 p-4">Keterangan</div>
                <div class="col-1 p-4">Opsi</div>
            </div>`;
            let mutasiSplitCards = `<div class="row border-bottom">
                <div class="col-2 p-4">tanggal transaksi</div>
                <div class="col-2 p-4">tanggal split</div>
                <div class="col-1 p-4">Cash In</div>
                <div class="col-6 p-4">Keterangan</div>
                <div class="col-1 p-4">Kode Bayar</div>
            </div>`;
            let fixCards = '';
            // console.log(datas);
            datas.data.forEach(data => {
                let bg = '';
                if (data['id_bayar']) {
                    bg = 'bg-success';
                }else if (data['is_active'] == '0') {
                    bg = 'bg-danger';
                }
                if (bank == "bca") {
                    let id = data['id'];
                    let kode_bayar = data['kode_bayar'];
                    let nominal = data['duit_in'];
                    let btn = '';
                    let btnGunakan = '';
                    // console.log(data);
                    if (data['is_active'] == '1') {
                        btn = `<button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#transferSplit${id}">split</button>
                        <button class="btn btn-danger" onclick="delist(${id}, '${kode_bayar}', '${document.querySelector('#banks').value}')">delist</button>`;
                    }else{
                        btn = `<h5 class="text-center">Ini Delist</h5>`;
                    }

                    if(data['split']){
                        btn = `<h5 class="text-center">Ini SpLiT</h5>`;
                        bg = 'bg-orange';
                    }
                    if (data['id_bayar']) {
                        btn = `<h5 class="text-center">Dipakai</h5>`;
                    }

                    if (data['is_active'] == '1' && !data['split'] && !data['id_bayar']) {
                        btnGunakan = `<button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalBayar" onclick="ubahModal(${id},'${kode_bayar}','${nominal}')">Gunakan</button>`;
                    }
                    // if(!data['id_bayar']){
                        mutasiBankCards += `<div class="row mb-4 ${bg}">
                            <div class="col-2 p-4">${data['tanggal_transaksi']}</div>
                            <div class="col-1 p-4">${data['kode_bayar']}</div>
                            <div class="col-2 p-4">
                                <p class="m-0">${rupiah(nominal)}</p>
                                ${btnGunakan}
                            </div>
                            <div class="col-5 p-4">${data['keterangan']}</div>
                            <div class="col-1 p-4 d-flex gap-2">
                                ${btn}
                            </div>
                        </div>
                        <div class="modal fade" id="transferSplit${id}" tabindex="-1" aria-labelledby="transferSplit${id}Label" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content text-dark">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="transferSplit${id}Label">${kode_bayar}</h1>
                                        <button type="button" class="btn-close" id="close-split${id}" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <b>Nominal : ${rupiah(nominal)}</b>
                                            <b id="nominal${id}" class="d-none">${nominal}</b>
                                        </div>
                                        <div class="hr"></div>
                                        <div class="" id="nominalsSplit${id}">
                                            <input type="number" class="form-control" id="nominalInput${id}" autocomplete="off" placeholder="Masukkan Nominal" oninput="split(${id},this)">
                                            <input type="number" class="form-control d-none" id="nominalInputSisa${id}" autocomplete="off" placeholder="Masukkan Nominal" oninput="split(${id},this)">
                                        </div>
                                        <div class="text-center pointer" onclick="addSplit(${id})">+Tambah split</div>
                                        <div>
                                            <p class="" id="nominalAkhir${id}" class="d-none">${nominal}</p>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" onclick="splitNow(${id},'${bank}')">Split</button>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                    // }
                    fixCards = mutasiBankCards;
                }else{
                    // console.log(data);   
                    let id = data['splitId'];
                    let nominal = data['nominalSplit'];
                    let kb = data['kb'];
                    let kode_bayar = data['kode_bayar'];
                    let btnGunakan = '';
                    if (data['is_active'] == '1' && !data['idBayarSplit']) {
                        btnGunakan = `<button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalBayar" onclick="ubahModal(${id},'${kb}','${nominal}')">Gunakan</button>`;
                    }
                    // console.log(data['kode_bayar']);
                    let mutasiCard = `<div class="row mb-4 ${(data['idBayarSplit']) ? 'bg-success' : ''}">
                        <div class="col-2 p-4">${data['tanggal_transaksi']}</div>
                        <div class="col -2 p-4">${data['created_at']}</div>
                        <div class="col-1 p-4">
                            <p class="">${rupiah(data['nominalSplit'])}</p>
                            <div class="">${btnGunakan}</div>
                        </div>
                        <div class="col-6 p-4">${data['keterangan']}</div>
                        <div class="col-1 p-4">${kb}</div>
                    </div>`;
                    mutasiSplitCards += mutasiCard;
                    fixCards = mutasiSplitCards;
                }
            });
            
            fixCards += `<div class="modal fade" id="modalBayar" tabindex="-1" aria-labelledby="modalBayarLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content text-dark">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="modalBayarLabel">Daftar bayar</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" id="btn-close-gunakan-bayar" aria-label="Close"></button>
                        </div>
                        <div class="modal-body"">
                            <div class="row d-flex justify-content-center align-items-center px-4">
                                <div class="col-6">
                                    <div class="d-flex gap-2">
                                        <p class="m-0">Nominal : </p>
                                        <p class="m-0 fw-bold" id="nominal_bayar">123</p>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <p class="m-0">Kode Bayar : </p>
                                        <p class="m-0 fw-bold" id="kode_bayar">6969</p>
                                    </div>
                                </div>
                                <div class="col-6 d-none">
                                    <input class="form-control border-dark" placeholder="Cari menggunakan nominal" oninput="getBayar(this.value)" disabled>
                                </div>
                            </div>
                            <div class="w-100 d-flex justify-content-end px-3 d-none" id="refresh">
                                <div class="d-flex gap-2 my-3 alert alert-danger p-2 pointer" onclick="getBayar()">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-arrow-repeat" viewBox="0 0 16 16">
                                        <path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41m-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9"/>
                                        <path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5 5 0 0 0 8 3M3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9z"/>
                                    </svg>
                                    <p class="m-0">Refresh</p>
                                </div>
                            </div>
                            <div class="d-none">
                                <input type="text" id="mutasi_id" placeholder="ini id mutasi">
                                <input type="text" id="kodeBayar" placeholder="ini kode bayar">
                                <input type="text" id="bayar_id" placeholder="ini id bayar">
                                <input type="text" id="nominal_bayar_input" placeholder="ini nominal">
                            </div>
                            <div id="resultBayars" class="px-4"></div>
                        </div>
                        <div class="modal-footer d-none" id="modal-footer-bayar">
                            <button type="button" class="btn btn-success" onclick="gunakanBayar(<?=$userId?>,'${bank}')">Gunakan</button>
                        </div>
                    </div>
                </div>
            </div>`;
            document.querySelector("#mutasi").innerHTML = fixCards;
            setTimeout(() => {
                getBayar();
            }, 100);
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    }

    function gunakanBayar(userId, bank) {
        let mutasi_id = document.querySelector("#mutasi_id").value;
        let kodeBayar = document.querySelector("#kodeBayar").value;
        let bayar_id = document.querySelector("#bayar_id").value;
        let nominal_bayar_input = document.querySelector("#nominal_bayar_input").value;
        let datas = {
            bank : bank,
            mutasi_id : mutasi_id,
            kodeBayar : kodeBayar,
            bayar_id : bayar_id,
            nominal_bayar_input : nominal_bayar_input,
            userId : userId
        }

        fetch('/pages/finance/bayar/gunakan.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(datas),
        })
        .then(response => response.json())
        .then(datas => {
            alert(datas.status);
            document.querySelector("#btn-close-gunakan-bayar").click();
            getMutasi(document.querySelector("#banks").value);
            getBayar();
            // console.log(datas);
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    }
    getMutasi(document.querySelector("#banks").value);

    function addSplit(id) {
        let valueInputTerakhir = document.querySelectorAll("#nominalInput" + id)[document.querySelectorAll("#nominalInput" + id).length - 1].value;
        if(valueInputTerakhir > 0){
            let nominalsSplit = document.querySelector("#nominalsSplit"+id);
            document.querySelectorAll("#nominal")[document.querySelectorAll("#nominal").length - 1];
            let nominal = document.createElement("input");
            nominal.setAttribute("type", "number");
            nominal.setAttribute("id", "nominalInput"+id);
            nominal.setAttribute("name", "nominal");
            nominal.setAttribute("placeholder", "Masukkan Nominal");
            nominal.setAttribute("class", "form-control");
            nominal.addEventListener("input", function() {
                split(id,this);
            });
            nominalsSplit.appendChild(nominal);
        }else{
            alert("Isi inputan sebelumnya terlebih dahulu");
        }
    }

    function addParameter(key,value,resetParam = false) {
        let url = new URL(window.location.href);
        if (resetParam) {
            url.search = '';
        }
        url.searchParams.set(key, value);
        window.history.pushState({}, '', url);
    }
    function validasi(input) {  
        if (input.value != "") {
            document.getElementById('mutasiBtn').classList.remove('d-none');
        }
    }
    document.getElementById('date').addEventListener('change', function() {
        addParameter('date', this.value, true);
    })
</script>