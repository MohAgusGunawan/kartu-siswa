<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Siswa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f5f5f5;
        }
        .card-container {
            width: 85.6mm; /* Panjang */
            height: 53.98mm; /* Lebar */
            /* position: absolute; */
            background: url('{{ asset("storage/images/siswa/depan1.jpg") }}') no-repeat center;
            background-size: cover;
            border: 1px solid #ccc;
            border-radius: 10px;
            /* box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); */
            overflow: hidden;
        }
        .card-container:not(:last-child) {
            page-break-after: always; /* Tambahkan pemisahan hanya jika bukan elemen terakhir */
        }
        .photo {
            /* position: absolute; */
            top: 150px;
            /* left: 10px; */
            width: 80px;
            height: 100px;
            border-radius: 5px;
            /* overflow: hidden; */
            border: 1px solid #ccc;
            /* margin-top: 75px; */
        }
        /* .photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        } */
        .card-content {
            /* position: absolute; */
            color: #000;
            font-size: 7px; /* Ukuran font lebih besar */
            /* line-height: 1.5; */
            font-weight: bold;
        }
        .light-text {
  font-weight: 200; /* Lebih tipis dari normal */
}
        .card-content div {
            margin-bottom: 5px; /* Tambahkan spasi antar baris informasi */
        }
    </style>
</head>
<body>
    <div class="card-container" style="width: 85.6mm; height: 53.98mm;">
        <table style="width: 100%; height: 100%; border-collapse: collapse; margin-top: 72px">
            <tr>
                <!-- Kolom Kiri: Foto -->
                <td style="width: 30%; vertical-align: top; text-align: left; padding-left: 5px;">
                    <div class="photo" style="width: 100%; height: auto; text-align: center;">
                        <img src="{{ asset('storage/images/siswa/' . $dataSiswa->foto) }}" 
                             alt="Foto Siswa" 
                             style="width: 80px; height: 100px; object-fit: cover;">
                    </div>
                </td>
                 <!-- Kolom Kanan: Informasi -->
                <td style="width: 70%; vertical-align: top; font-size: 8px; padding-left: -5px;">
                    <div class="card-content">
                        <table style="width: 100%; border-collapse: collapse; font-size: 8px; font-weight: bold; margin-top: -2.5px;">
                            <tr>
                                <td>NIS</td>
                                <td>:</td>
                                <td>{{ $dataSiswa->nis }}</td>
                            </tr>
                            <tr>
                                <td>Nama</td>
                                <td>:</td>
                                <td>{{ $dataSiswa->nama }}</td>
                            </tr>
                            <tr>
                                <td>TTL</td>
                                <td>:</td>
                                <td>{{ $dataSiswa->ttl }}</td>
                            </tr>
                            <tr>
                                <td>Jenis Kelamin</td>
                                <td>:</td>
                                <td>{{ $dataSiswa->gender }}</td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td>:</td>
                                <td>{{ $dataSiswa->alamat }}</td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
    </div>  
</body>
</html>
