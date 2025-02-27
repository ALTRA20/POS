<?php include $_SERVER['DOCUMENT_ROOT'].'/components/header/index.php'; 
if(!isset($_SESSION["username"])){
    echo "<script>window.location.href = '/pages/login.php'</script>";
}
$_SESSION['last_url'] = $_SERVER[REQUEST_URI];
?>
<style>
    .hr {
        border: none;
        height: 1px;
        background-color: black;
        margin: 20px 0;
    }
</style>
<script>
    document.title = 'History GS';
</script>
<section class="container text-dark py-5">
    <h2 class="">Transfer Split</h2>
    <?php

    ?>
    <div class="text-dark">
        <div class="">
            <div class="row justify-content-between mt-3 px-3">
                <div class="col-md-12">
                    <input type="text" class="d-none" name="edit" value="<?=($status == 'IS NOT NULL') ? '1' : '0'?>">
                    <input type="text" class="d-none" name="userId" value="<?=$userId?>">
                    <input type="text" class="d-none" name="tr_id" id="tr_id">
                    <input type="text" class="d-none" name="kode_bayar" id="kode_bayar">
                    <input type="text" class="d-none" name="bayarId" id="bayarId">
                </div>
                <div class="col-2">
                    <select class="form-select" aria-label="Default select example" name="bank" id="bank" onclick="getBanksTransfer()" onchange="getBanksTransfer()">
                        <option value="bca">BCA</option>
                        <option value="split">SPLIT</option>
                    </select>
                </div>
                <div class="col-4">
                    <input type="text" class="w-100 form-control border-dark" placeholder="Cari dengan nama buyer" oninput="getBanksTransfer(this.value)">
                </div>
            </div>
            <div class="hr"></div>
            <div class="row mx-3 rounded">
                <div class="col-1 d-flex justify-content-center align-items-center">Kode Bayar</div>
                <div class="col-7 d-flex justify-content-center align-items-center">Keterangan</div>
                <div class="col-2 d-flex justify-content-center align-items-center">Nominal</div>
                <div class="col-2 d-flex justify-content-center align-items-center">Tanggal Transaksi</div>
            </div>
            <div class="w-100 d-flex justify-content-end px-3" id="refresh" onclick="getBanksTransfer()">
                <div class="d-flex gap-2 my-3 alert alert-danger p-2 pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-arrow-repeat" viewBox="0 0 16 16">
                        <path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41m-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9"/>
                        <path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5 5 0 0 0 8 3M3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9z"/>
                    </svg>
                    <p class="m-0">Refresh</p>
                </div>
            </div>
            <div class="px-4" id="transferList">
                
            </div>
        </div>
        <div class="modal-footer d-none" id="modal-footer-bank">
            <button type="submit" class="btn btn-primary">Aprove</button>
        </div>
    </div>
</section>
<script>
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
        console.log(datas);
        fetch('splitAction.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(datas),
        })
        .then(response => response.text()) // Parse the JSON response
        .then(response => {
            console.log(response);
            getBanksTransfer();
            document.querySelector("#btn-close"+id).click();
        })
        .catch((error) => {
            console.error('Error:', error);
        });
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

    function getBanksTransfer(search) {
        let bank = (document.querySelector("#bank").value != "--Banks--") ? document.querySelector("#bank").value : 'bca';
        const datas = {
            bank: bank,
            search: search
        };
        fetch('getBanksTransfer.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(datas),
        })
        .then(response => response.json()) // Parse the JSON response
        .then(response => {
            document.querySelector("#transferList").innerHTML = '';
            document.querySelector("#refresh").classList.add("d-none");
            let transferList = '';
            response.forEach(data => {
                let idtf = data['id'];
                let kode_bayar = data['kode_bayar'];
                let keterangan = data['keterangan'];
                let nominal = (data['duit_in']) ? data['duit_in'] : data['nominal'];
                let tanggal_transaksi = data['tanggal_transaksi'];
                if (data['tr']) {
                    kode_bayar = data['tr']['kode_bayar'];
                    keterangan = data['tr']['keterangan'];
                    tanggal_transaksi = data['tr']['tanggal_transaksi'];
                }
                transferList += `<div class="row customerCard border border-dark p-3 rounded my-3 position-relative" id="transfer" style="background:#accfa4;" ${(!data['id_bayar']) ? `data-bs-toggle="modal" data-bs-target="#transferSplit${idtf}"` : ''}>
                    <div class="col-1 d-flex align-items-center">${kode_bayar}</div>
                    <div class="col-7 d-flex align-items-center">${keterangan}</div>
                    <div class="col-2 d-flex align-items-center">${nominal}</div>
                    <div class="col-2 d-flex align-items-center">${tanggal_transaksi}</div>
                    ${(data['id_bayar']) ? `<div class="position-absolute" style="top:45%;left:-2%"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-check-circle-fill text-primary bg-light" viewBox="0 0 16 16"><path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/></svg></div>` : ''}
                    </div>`;
                if(!data['id_bayar']){
                    transferList += `<div class="modal fade" id="transferSplit${idtf}" tabindex="-1" aria-labelledby="transferSplit${idtf}Label" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="transferSplit${idtf}Label">${kode_bayar}</h1>
                                    <button type="button" class="btn-close" id="btn-close${idtf}" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <b>Nominal : ${rupiah(nominal)}</b>
                                        <b id="nominal${idtf}" class="d-none">${nominal}</b>
                                    </div>
                                    <div class="hr"></div>
                                    <div class="" id="nominalsSplit${idtf}">
                                        <input type="number" class="form-control" id="nominalInput${idtf}" autocomplete="off" placeholder="Masukkan Nominal" oninput="split(${idtf},this)">
                                        <input type="number" class="form-control d-none" id="nominalInputSisa${idtf}" autocomplete="off" placeholder="Masukkan Nominal" oninput="split(${idtf},this)">
                                    </div>
                                    <div class="text-center pointer" onclick="addSplit(${idtf})">+Tambah split</div>
                                    <div>
                                        <p class="" id="nominalAkhir${idtf}" class="d-none">${nominal}</p>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" onclick="splitNow(${idtf},'${bank}')">Split</button>
                                </div>
                            </div>
                        </div>
                    </div>`;
                }
            });
            document.querySelector("#transferList").innerHTML = transferList;
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    }
    getBanksTransfer();

    function ubahModalCash(idBayar,name,nominal,id_pesanan) {
        document.querySelector("#namaCash").innerHTML = name;
        document.querySelector("#nominal_bayar").innerHTML = rupiah(nominal);
        document.querySelector("#bayarCashId").value = idBayar;
        document.querySelector("#idPesanan").value = id_pesanan;
    }

    function ubahModal(idBayar,name,nominal,id_pesanan) {
        document.querySelector("#nama").innerHTML = name;
        document.querySelector("#bayarId").value = idBayar;
    }

    document.querySelector("#status").addEventListener('change', function() {
        window.location.href = '/pages/finance/index.php?s='+this.value;
    });

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
</script>