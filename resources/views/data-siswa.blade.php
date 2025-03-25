<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Data Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body class="bg-light">
    <div class="container text-center mt-5">
        <h1 class="mb-4">Cek Data Siswa</h1>
        <button id="checkData" class="btn btn-primary">Cek Data</button>
        <div id="result" class="mt-4"></div>
    </div>

    <script>
        $(document).ready(function() {
            $('#checkData').click(function() {
                $('#result').html('<p>Loading data...</p>'); // Tampilkan pesan loading
                $.getJSON('/api/siswa', function(data) {
                    if (data.length > 0) {
                        let html = '<div class="row">';
                        data.forEach(function(siswa) {
                            html += `
                                <div class="col-md-4 mb-3">
                                    <div class="card">
                                        <img src="${siswa.foto}" class="card-img-top" alt="Foto ${siswa.nama}">
                                        <div class="card-body">
                                            <h5 class="card-title">${siswa.nama}</h5>
                                            <p class="card-text">
                                                <strong>NIS:</strong> ${siswa.nis}<br>
                                                <strong>TTL:</strong> ${siswa.ttl}<br>
                                                <strong>Alamat:</strong> ${siswa.alamat}<br>
                                                <strong>Gender:</strong> ${siswa.gender}<br>
                                                <strong>ID Card:</strong> ${siswa.id_card}
                                            </p>
                                        </div>
                                    </div>
                                </div>`;
                        });
                        html += '</div>';
                        $('#result').html(html);
                    } else {
                        $('#result').html('<p class="text-danger">Tidak ada data siswa.</p>');
                    }
                }).fail(function(err) {
                    console.log(err)
                    $('#result').html('<p class="text-danger">Gagal memuat data.</p>');
                });
            });
        });
    </script>
</body>
</html>
