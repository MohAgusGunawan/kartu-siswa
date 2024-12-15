<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eksekusi Migrasi Data</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1>Migrasi Data ke SLiMS</h1>
        <button id="migrateData" class="btn btn-primary">Migrasikan Data</button>
        <div id="migrationResult" class="mt-3"></div>
    </div>

    <div class="container mt-5">
        <h1>Migrasi Foto Siswa ke SLiMS</h1>
        <button id="migrateButton" class="btn btn-primary">Mulai Migrasi</button>
        <div id="result" class="mt-3"></div>
    </div>

    <script>
        $(document).ready(function () {
            $('#migrateButton').click(function () {
                $('#result').html('<p>Proses migrasi sedang berjalan...</p>');

                $.get('/migrate-photos', function (response) {
                    $('#result').html('<p class="text-success">' + response.message + '</p>');
                }).fail(function () {
                    $('#result').html('<p class="text-danger">Gagal menjalankan migrasi.</p>');
                });
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            $('#migrateData').click(function () {
                $('#migrationResult').html('<p>Proses migrasi sedang berjalan...</p>');

                // Kirim permintaan ke server
                $.ajax({
                    url: '/migrate-siswa-to-slims', // Rute yang akan dieksekusi
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Token CSRF Laravel
                    },
                    success: function (response) {
                        $('#migrationResult').html('<p class="text-success">Data berhasil dimigrasikan!</p>');
                    },
                    error: function (xhr) {
                        $('#migrationResult').html('<p class="text-danger">Terjadi kesalahan: ' + xhr.responseText + '</p>');
                    }
                });
            });
        });
    </script>
</body>
</html>