<!DOCTYPE html>
<html>
<head>
    <title>Form Berhasil Disimpan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: auto;
        }
        h1 {
            color: #4CAF50;
            font-size: 24px;
            margin-bottom: 20px;
        }
        p {
            font-size: 16px;
            line-height: 1.6;
            color: #555;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        th, td {
            text-align: left;
            padding: 8px 12px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
            color: #333;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            font-size: 16px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .button:hover {
            background-color: #45a049;
        }
        .footer {
            margin-top: 30px;
            font-size: 14px;
            color: #999;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Selamat, {{ $siswa->nama }}!</h1>
        <p>Isi form telah berhasil disimpan dengan baik. Berikut adalah data yang telah Anda masukkan:</p>

        <table>
            <tr>
                <th>NIS</th>
                <td>{{ $siswa->nis }}</td>
            </tr>
            <tr>
                <th>Nama</th>
                <td>{{ $siswa->nama }}</td>
            </tr>
            <tr>
                <th>Tempat Lahir</th>
                <td>{{ explode(', ', $siswa->ttl)[0] }}</td>
            </tr>
            <tr>
                <th>Tanggal Lahir</th>
                <td>{{ \Carbon\Carbon::parse(explode(', ', $siswa->ttl)[1])->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <th>Gender</th>
                <td>{{ $siswa->gender }}</td>
            </tr>
            <tr>
                <th>Alamat</th>
                <td>{{ $siswa->alamat }}</td>
            </tr>
            <tr>
                <th>WhatsApp</th>
                <td>{{ $siswa->wa }}</td>
            </tr>
            <tr>
                <th>Kelas</th>
                <td>{{ $siswa->kelas }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $siswa->email }}</td>
            </tr>
        </table>

        <a href="{{ url('https://kartu-pelajar.gunawans.web.id/form/' . $siswa->id . '/edit') }}" class="button">
            Edit Data
        </a>

        <p class="footer">Terima kasih,<br>SMA NEGERI 1 PAMEKASAN</p>
    </div>

</body>
</html>
