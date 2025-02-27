<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<div class="" style="   ">
    <div class="d-flex flex-wrap gap-3" id="listFoto" style=""></div>   
</div>
<form action="" id="upload-form" class="w-100 text-center">
    <label for="fotoform" class="btn btn-primary p-4" style="width:100%;">
        <h1 class="">+ Tambah Foto</h1>
    </label>
    <input type="file" class="w-100 form-control" id="fotoform" accept="image/*" style="display:none;">
</form>
<script>    
function ambilFoto() {
    var dataToSend = '';
    fetch('/pages/pasteFoto/get.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(dataToSend),
    })
    .then(response => response.json())
    .then(datas => {
        let element = '<div class="d-flex flex-wrap gap-3">';
        datas.forEach(data => {
            element += `<img src="/public/foto-temp/${data[1]}.jpg" class="" style="width:400px;">`;
        });
        element += '</div>'
        document.querySelector("#listFoto").innerHTML = element;
    })
    .catch((error) => {
        console.error('Error:', error);
    });
}
ambilFoto();
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
    xhr.open('POST', '/pages/pasteFoto/upload.php', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            // File berhasil diupload, tambahkan logika sesuai kebutuhan Anda
            console.log('File berhasil diupload:', xhr.responseText);
            ambilFoto();
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