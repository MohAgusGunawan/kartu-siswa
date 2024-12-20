<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Siswa</title>
    <!-- Tambahkan CSS Bootstrap atau custom CSS sesuai kebutuhan -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/edit.css') }}">
    <!-- Include Select2 CSS CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
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

        <form action="{{ route('dashboard.update', $data->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="nis">NIS</label>
                <input type="number" class="form-control" id="nis" name="nis" min="10000" max="99999" maxlength="5" placeholder="5 angka" value="{{ old('nis', $data->nis) }}" required>
            </div>
            
            <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Lengkap" value="{{ old('nama', $data->nama) }}" required>
            </div>
            
            <div class="form-group">
                <label for="tempat_lahir">Tempat Lahir</label>
                <select class="form-control select2" id="tempat_lahir" name="tempat_lahir" required style="width: 100%;">
                    <option value="">Pilih Kota Kelahiran</option>
                    @foreach($kota as $namaKota)
                        <option value="{{ $namaKota }}" {{ $tempat_lahir == $namaKota ? 'selected' : '' }}>{{ $namaKota }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <label for="tanggal_lahir">Tanggal Lahir</label>
                <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $tanggal_lahir) }}" onfocus="this.showPicker()" required>
            </div>
            
            <div class="form-group">
                <label>Jenis Kelamin</label><br>
                <div>
                    <input type="radio" id="laki-laki" name="gender" value="Laki-laki" {{ $data->gender == 'Laki-laki' ? 'checked' : '' }} required>
                    <label for="laki-laki">Laki-laki</label>
                </div>
                <div>
                    <input type="radio" id="perempuan" name="gender" value="Perempuan" {{ $data->gender == 'Perempuan' ? 'checked' : '' }} required>
                    <label for="perempuan">Perempuan</label>
                </div>
            </div>
            
            <div class="form-group">
                <label for="alamat">Alamat</label>
                <input type="text" class="form-control" id="alamat" name="alamat" maxlength="50" placeholder="JL. MESIGIT II /17" value="{{ old('alamat', $data->alamat) }}" required>
            </div>
            
            <div class="form-group">
                <label for="wa">Nomor HP(WA)</label>
                <input type="number" class="form-control" id="wa" name="wa" pattern="[0-9]{10,15}" placeholder="08XXXXXXXXXXX" value="{{ old('wa', $data->wa) }}" required>
            </div>
            
            <div class="form-group">
                <label for="kelas">Kelas</label>
                <select class="form-control" id="kelas" name="kelas" required>
                    <option value="" disabled selected>Pilih Kelas</option>
                    @foreach ($kelas as $item)
                        <option value="{{ $item->id }}" 
                            @if(old('kelas', $data->kelas_id) == $item->id) selected @endif>
                            {{ $item->nama_kelas }}
                        </option>
                    @endforeach
                </select>                
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="emailmu@gmail.com" value="{{ old('email', $data->email) }}" required>
            </div>
            
            <div class="form-group">
                <label for="foto">Foto</label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="foto" name="foto" accept=".jpg, .jpeg, .png" onchange="previewImage(event)">
                    <label class="custom-file-label" for="foto">Choose file</label>
                </div>
                <img id="img-preview" src="{{ asset('storage/images/siswa/' . $data->foto) }}" alt="Foto Pelajar" class="img-thumbnail img-preview mt-2">
            </div>            

            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>

    <!--<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>-->
    {{-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> --}}
    <!-- Load jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/edit.js') }}"></script>

<!-- Include Select2 JS CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    $(document).ready(function () {
        $('.select2').select2();
    });
</script>
</body>
</html>
