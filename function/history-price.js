function priceHistoryJual(productId) {
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(loadData);

    function loadData() {
        // Meminta data dari PHP
        fetch('/components/tambah-product/getHargaJual.php', {
            method: 'POST',
            body: JSON.stringify({productId : productId}),
            headers: {
                'Content-type': 'application/json; charset=UTF-8',
            }   
        })
        .then(response => response.json())
        .then(function(data){
            drawChart(data);
        })
        .catch(error => console.error('Error:', error)); 
    }

    function drawChart(data) {
        // Membuat tabel data untuk Google Charts
        var dataTable = new google.visualization.DataTable();
        dataTable.addColumn('datetime', 'Waktu');
        dataTable.addColumn('number', 'Harga Jual');

        // Memasukkan data ke dalam tabel
        data.forEach(item => {
            const [year, month, day] = item.tanggal.split('-');
            dataTable.addRow([new Date(year, month - 1, day), parseFloat(item.harga)]);
        });

        // Opsi chart
        var options = {
            title: 'Perubahan Harga Jual',
            width: 800,
            height: 600,
            hAxis: {
                title: 'Waktu',
                format: 'MM d'
            },
            vAxis: {
                title: 'Harga Jual'
            }
        };

        // Menggambar chart
        var chart = new google.visualization.LineChart(document.getElementById('chart-price-history'+productId));
        chart.draw(dataTable, options);
    }

}