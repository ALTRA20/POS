function makeChart(productId) {
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(function() {
        $.ajax({
            url: '/components/stock/getStock.php',
            method: 'POST',
            data: {productId: productId},
            dataType: 'json',
            success: function(response) {
                var data = new google.visualization.DataTable();
                data.addColumn('date', 'Date');
                data.addColumn('number', 'Terverifikasi'); // Mengganti label sumbu Y menjadi 'Harga'
                data.addColumn({type: 'string', role: 'style'});
                data.addColumn('number', 'Harga Jual'); // Menambahkan kolom untuk harga jual
                // data.addColumn('date', 'Date');

                let datas = [];
                let namaProduk = '';
                if(response['belis']){
                    // Mengisi baris-baris dengan data dari server
                    response['belis'].forEach(function(rowData, index) {
                        let harga = parseInt(rowData['harga']);
                        let tanggalBeli = rowData['tanggal_beli'];
                        let is_verif = rowData['is_verif'];
                        let hargaJual = (response['juals']) ? parseInt(response['juals'][index]['harga_jual']) : null; 
                        let tanggalJual = (response['juals']) ? response['juals'][index]['created_at'] : null; 
                        let color = (is_verif == '1') ? '' : 'red';
                        namaProduk = 'Grafik Harga Pembelian dari ' + rowData['nama'];

                        if (tanggalBeli) {
                            var dates = tanggalBeli.split('-');
                            var tahun = parseInt(dates[0]); // Mengonversi string ke integer
                            var bulan = parseInt(dates[1]) - 1; // Mengurangi 1 dari bulan karena Januari dimulai dari 0
                            var tanggal = parseInt(dates[2]);
                        }

                        if (tanggalJual) {
                            // console.log(tanggalJual);
                            var datesJual = tanggalJual.split('-');
                            var tahunJual = parseInt(datesJual[0]); // Mengonversi string ke integer
                            var bulanJual = parseInt(datesJual[1]) - 1; // Mengurangi 1 dari bulan karena Januari dimulai dari 0
                            var tanggalJualFix = parseInt(datesJual[2]);
                        }

                        let newData = [new Date(tahun, bulan, tanggal), harga, color, hargaJual]; // Menggunakan harga sebagai nilai Y
                        datas.push(newData);
                    });
                    
                    data.addRows(datas);
                
                    var options = {
                        width: 720,
                        height: 620,
                        title: namaProduk,
                        hAxis: {
                            title: 'Date',
                            format: 'MMM d, yyyy',
                            gridlines: {count: -1},
                            ticks: datas.map(function(data) { return data[0]; }),
                            slantedText: true
                        },
                        vAxes: {
                            0: {
                                title: 'Harga',
                                minValue: 0
                            }
                        },
                        bar: {
                            groupWidth: '30%' // Mengurangi lebar kolom
                        },
                        seriesType: 'bars',
                        series: {1: {type: 'line'}} // Menentukan series 1 sebagai garis (line) untuk harga jual
                    };

                    var chart = new google.visualization.ComboChart(
                        document.getElementById('chart_div'+productId));
                
                    chart.draw(data, options);
                }
            },
            
            error: function(xhr, status, error) {
                console.error('Error fetching data from server:', error);
            }
        });
    });
}