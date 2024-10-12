<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Siswa</title>
    <!-- Tambahkan CSS Bootstrap atau custom CSS sesuai kebutuhan -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/edit.css') }}">
</head>
<body>
    <div class="container">
        <h2>Edit Data Siswa</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('form.update', $data->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="nis">NIS</label>
                <input type="number" class="form-control" id="nis" name="nis" min="10000" max="99999" placeholder="5 angka" value="{{ $data->nis }}" required>
            </div>

            <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Lengkap" value="{{ $data->nama }}" required>
            </div>
            <div class="form-group">
                <label for="tempat_lahir">Tempat Lahir</label>
                <select class="form-control" id="tempat_lahir" name="tempat_lahir" required>
                    <option value="Pamekasan" {{ $tempat_lahir == 'Pamekasan' ? 'selected' : '' }}>Pamekasan</option>
                    <option value="Sumenep" {{ $tempat_lahir == 'Sumenep' ? 'selected' : '' }}>Sumenep</option>
                    <option value="Sampang" {{ $tempat_lahir == 'Sampang' ? 'selected' : '' }}>Sampang</option>
                </select>
            </div>
            <div class="form-group">
                <label for="tanggal_lahir">Tanggal Lahir</label>
                <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="{{ $tanggal_lahir }}" onfocus="this.showPicker()" required>
            </div>
            <div class="form-group">
                <label for="gender">Jenis Kelamin</label>
                <select class="form-control" id="gender" name="gender" required>
                    <option value="Laki-laki" {{ $data->gender == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="Perempuan" {{ $data->gender == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>
            <div class="form-group">
                <label for="alamat">Alamat</label>
                <input type="text" class="form-control" id="alamat" name="alamat" maxlength="50" placeholder="JL. MESIGIT II /17" value="{{ $data->alamat}}" required>
            </div>
            <div class="form-group">
                <label for="wa">Nomor HP(WA)</label>
                <input type="number" class="form-control" id="wa" name="wa" pattern="[0-9]{10,15}" placeholder="08XXXXXXXXXXX" value="{{ $data->wa }}" required>
            </div>
            <div class="form-group">
                <label for="kelas">Kelas</label>
                <select class="form-control" id="kelas" name="kelas" required>
                    <option value="IPA IA" {{ $data->kelas == 'IPA IA' ? 'selected' : '' }}>IPA IA</option>
                    <option value="IPA IB" {{ $data->kelas == 'IPA IB' ? 'selected' : '' }}>IPA IB</option>
                    <option value="IPA IC" {{ $data->kelas == 'IPA IC' ? 'selected' : '' }}>IPA IC</option>
                </select>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="emailmu@gmail.com" value="{{ $data->email }}" required>
            </div>
            <div class="form-group">
                <label for="foto">Foto</label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="foto" name="foto" accept=".jpg, .jpeg, .png" onchange="previewImage(event)">
                    <label class="custom-file-label" for="foto">Choose file</label>
                </div>
                <img id="img-preview" src="{{ asset('storage/' . $data->foto) }}" alt="Foto Pelajar" class="img-thumbnail img-preview mt-2">
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="{{ asset('js/edit.js') }}"></script>
</body>
</html>
