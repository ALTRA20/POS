<?php
session_start();
if (isset($_SESSION['userId'])) {
    $userId = $_SESSION['userId'];
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    }else{
        echo "<script>window.location.href = '/pages/assets/'</script>";
    }
}else{
    echo "<script>window.location.href = '/pages/assets/'</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Batch File Upload</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <style>
        #preview img {
            width: 100px; /* Atur lebar gambar */
            margin: 5px;
        }
        canvas { 
            border: 1px solid black; 
            cursor: crosshair; 
            margin: auto;
        }
    </style>
</head>
<body>
    <?php include $_SERVER['DOCUMENT_ROOT'].'/components/header/navbar2.php'; ?>
    <div class="container p-5" style="width:90%;">
        <?php
        include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
        $produk = $db->query("SELECT *  FROM `produk` WHERE `id` = '$id'")->fetch_assoc();
        $nama = $produk['nama'];
        $deskripsi = $produk['talking_point'];
        $fotos = $db->query("SELECT * FROM `foto` WHERE `id_produk` = '$id' AND `is_active` = 1 ORDER BY `id` DESC");
        ?>
        <div class="d-flex justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <a href="/pages/assets/" class="">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-chevron-double-left" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8.354 1.646a.5.5 0 0 1 0 .708L2.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0"/>
                        <path fill-rule="evenodd" d="M12.354 1.646a.5.5 0 0 1 0 .708L6.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0"/>
                    </svg>
                </a>
                <h1 class="m-0">Upload Foto Dan Video <span id="namaEdit<?=$id?>" class="text-success"><?=$nama?></span></h1>
            </div>
            <input type="range" class="w-fit form-control border border-dark" id="controlWidth" oninput="controlWidth('potoAwal',this)" onchange="controlWidth('potoAwal',this)" style="width:fit-content; height:fit-content;" value="200" min="200" max="300">
        </div>
        <h3 class="">Foto Produk</h3>
        <div class="row">
            <?php if ($fotos->num_rows > 0) : ?>
            <div class="col-7 d-flex align-items-center flex-wrap gap-5 mb-5">
                <?php foreach ($fotos as $key => $foto) : ?>
                    <?php 
                    $is_cover = $foto['is_cover'];
                    if($foto['is_video'] == 1) :
                    ?>
                    <video controls style="width:200px; height:200px;">
                        <source src="/public/foto/lg/<?=$foto['id']?>.mp4" type="video/mp4">
                    </video>
                    <?php else : ?>
                        <?php 
                        $idGambar = $foto['id'];
                        $is_cover = $foto['is_cover'];
                        ?>
                        <div class="position-relative">
                            <div class="position-absolute z-100 bg-success text-light rounded-circle pointer" style="top:-10px; right:-10px; cursor:pointer;" onclick="deletePoto(<?=$foto['id']?>)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                                </svg>
                            </div>
                            <img src="/public/foto/lg/<?=$foto['id']?>.jpg" alt="" id="potoAwal" class="" style="width:200px;">
                            <div class="position-absolute w-fit p-1 bg-warning pointer" onclick="isFavorite(<?=$idGambar?>,<?=$id?>)" style="bottom:0px; right:0px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-heart-fill <?=($is_cover == 1) ? 'text-danger' : ''?>" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314"/>
                                </svg>
                            </div>
                        </div>
                    <?php endif ?>
                <?php endforeach; ?>
            </div>
            <?php else : ?>
            <div class="w-full bg-dark text-danger p-3 mb-5">
                <h5 class="">Tidak ada foto atau video produk</h5>
            </div>
            <?php endif; ?>
        </div>
        <div class="">
            <div class="d-flex justify-content-between">
                <h3>Uploaded Images:</h3>
                <input type="range" class="w-fit form-control border border-dark" id="controlWidth" oninput="controlWidth('potoPrev',this)" onchange="controlWidth('potoPrev',this)" style="width:fit-content; height:fit-content;" value="100" min="100" max="400">
            </div>
            <div class="d-flex gap-2" id="preview"></div>
            
            <div id="response"></div>
            <form id="uploadForm" enctype="multipart/form-data">
                <input type="text" class="form-control d-none" name="idProduk" value="<?=$id?>">
                <input type="text" class="form-control d-none" name="idUser" value="<?=$userId?>">
                <input type="file" class="form-control" id="fileInput" name="files[]" multiple accept="*" />
                <div class="d-flex justify-content-end mt-3" style="width:100%">
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
            <div class="position-absolute d-flex flex-column justify-content-center align-items-center bg-danger top-0 start-0 end-0 bottom-0 d-none" id="canvasOverlay" style="z-index:1021;">
                <canvas id="drawingCanvas" width="1200" height="1200" class="border border-dark"></canvas>
                <button class="btn btn-success mb-5" id="saveBtn">Save as Image</button>
            </div>

            <script>
                const canvas = document.getElementById('drawingCanvas');
                const ctx = canvas.getContext('2d');
                let drawing = false;

                function resizeCanvas(width, height) {
                    canvas.width = width;
                    canvas.height = height;
                }

                document.addEventListener('paste', (event) => {
                    const items = (event.clipboardData || event.originalEvent.clipboardData).items;
                    for (const item of items) {
                        if (item.type.indexOf('image') !== -1) {
                            const blob = item.getAsFile();
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                const img = new Image();
                                img.onload = () => {
                                    const canvas = document.getElementById('drawingCanvas');
                                    const ctx = canvas.getContext('2d');

                                    // Fix ukuran canvas
                                    const fixedWidth = 600;
                                    const fixedHeight = 600;
                                    canvas.width = fixedWidth;
                                    canvas.height = fixedHeight;

                                    // Resize gambar agar fit ke canvas
                                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                                    ctx.drawImage(img, 0, 0, fixedWidth, fixedHeight);
                                };
                                img.src = e.target.result;
                                document.querySelector("#canvasOverlay").classList.remove("d-none");
                            };
                            reader.readAsDataURL(blob);
                        }
                    }
                });


                canvas.addEventListener('mousedown', (event) => {
                    drawing = true;
                    ctx.beginPath();
                    ctx.moveTo(event.offsetX, event.offsetY);
                });

                canvas.addEventListener('mousemove', (event) => {
                    if (!drawing) return;
                    ctx.lineTo(event.offsetX, event.offsetY);
                    ctx.lineWidth = 10;
                    ctx.lineCap = 'round';
                    ctx.strokeStyle = 'red';
                    ctx.stroke();
                });

                canvas.addEventListener('mouseup', () => {
                    drawing = false;
                });

                canvas.addEventListener('mouseleave', () => {
                    drawing = false;
                });

                document.getElementById('saveBtn').addEventListener('click', () => {
                    const canvas = document.getElementById('drawingCanvas');
                    canvas.toBlob((blob) => {
                        const formData = new FormData(document.getElementById('uploadForm'));

                        // Tambahkan gambar dari canvas sebagai file
                        formData.append('files[]', blob, 'canvas-drawing.png');

                        // Kirim form langsung ke backend
                        fetch('upload.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then((data) => {
                            // console.log(data.message);
                            // alert(data.message);
                            window.location.href = window.location.href;
                        })
                        .catch(error => console.error('Error:', error));
                    }, 'image/png');
                });
            </script>

            <form action="/pages/produk/ubahDeskripsi.php" method="POST" class="col-5 d-none">
                <h5 class="">Talking Point</h5>
                <input type="text" class="d-none" value="<?=$_GET['id'] ?>" name="id">
                <textarea name="deskripsi" id="" cols="" rows="10" class="form-control border-dark"><?=$deskripsi?></textarea>
                <div class="d-flex justify-content-end mt-3" style="width:100%">
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function isFavorite(idGambar, idProduk) {
            let judul = document.querySelector("#namaEdit"+idProduk).innerHTML;
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
                window.location.href = window.location.href;
            })
            .catch((error) => {
                console.error('Error:', error);
            });
        }
        
        function deletePoto(id) {
            let konfirmasi = confirm("Apakah anda yakin ingin menghapus foto ini?");
            if (konfirmasi) {
                window.location.href = "deletePoto.php?idPoto="+id+'&idProduk='+<?=$id?>;
            }
        }

        function controlWidth(id, input) {
            document.querySelectorAll('#'+id).forEach(element => {
                element.style.width = input.value+'px';
            });
        }

        document.getElementById('fileInput').addEventListener('change', function(event) {
            const preview = document.getElementById('preview');
            preview.innerHTML = ''; // Kosongkan preview sebelumnya

            for (let i = 0; i < event.target.files.length; i++) {
                const file = event.target.files[i];

                // Jika file adalah gambar, tampilkan pratinjau
                if (file.type.startsWith('image/')) {
                    const imgContainer = document.createElement('div');
                    imgContainer.className = 'position-relative';
                    imgContainer.style = 'width: 100px; margin: 5px;';

                    const img = document.createElement('img');
                    img.src = URL.createObjectURL(file);
                    img.style = 'width: 100px; margin: 5px;';

                    const deleteButton = document.createElement('button');
                    deleteButton.className = 'btn btn-danger btn-sm position-absolute z-index-100 top-0 end-0';
                    deleteButton.style = 'margin-left: 5px;';
                    deleteButton.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                            <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                        </svg>
                    `;

                    // Event listener untuk menghapus file
                    deleteButton.addEventListener('click', function() {
                        const dataTransfer = new DataTransfer();
                        
                        // Salin file yang tersisa ke dataTransfer
                        for (let j = 0; j < event.target.files.length; j++) {
                            if (j !== i) {
                                dataTransfer.items.add(event.target.files[j]);
                            }
                        }

                        event.target.files = dataTransfer.files; // Update input file
                        preview.removeChild(imgContainer); // Hapus pratinjau
                    });

                    imgContainer.appendChild(img);
                    imgContainer.appendChild(deleteButton);
                    preview.appendChild(imgContainer);
                }
            }
        });

        document.getElementById('uploadForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const submitButton = document.querySelector('button[type="submit"]');
            submitButton.disabled = true; // Disable button to prevent double submission

            const formData = new FormData(this);
            
            fetch('upload.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                document.getElementById('response').innerText = `Success: ${data.message}`;
                window.location.href = window.location.href; // Refresh page
            })
            .catch(error => {
                document.getElementById('response').innerText = `Error: ${error.message}`;
                submitButton.disabled = false; // Re-enable button on error
            });
        });

        // Memuat gambar yang telah diunggah sebelumnya (misalnya dari server)
        // function loadPreviousImages() {
        //     // Contoh: Anda dapat memuat gambar dari server
        //     const previousImages = [
        //     ];
        //     const preview = document.getElementById('preview');

        //     previousImages.forEach(imagePath => {
        //         const img = document.createElement('img');
        //         img.src = imagePath; // Ganti dengan path yang sesuai
        //         preview.appendChild(img);
        //     });
        // }

        // // Panggil fungsi untuk memuat gambar sebelumnya
        // loadPreviousImages();
    </script>
</body>
</html>
