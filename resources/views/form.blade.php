<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Input Siswa</title>
    <link rel="icon" type="image/png" href="{{ asset('storage/app/public/images/logo/logo.jpg') }}" sizes="16x16" />
    <!-- Tambahkan CSS Bootstrap atau custom CSS sesuai kebutuhan -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    <!-- Include Select2 CSS CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    @if(Session::has('success'))
      <script>
          document.addEventListener('DOMContentLoaded', function () {
            const Toast = Swal.mixin({
              toast: true,
              position: "top",
              showConfirmButton: false,
              timer: 3000,
              timerProgressBar: true,
              didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
              }
            });
            Toast.fire({
              icon: 'success',
              title: '{{ Session::get('success') }}'
            });
          });
      </script>
  @endif
    <div class="container">
        <h2 style="margin-bottom: 30px">SMA Negeri 1 Pamekasan</h2>
        <h4 class="mt-4" style="margin-top: -15px !important;">Form Input Data Siswa</h4>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('form.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="nis">NIS</label>
                <input type="number" class="form-control" id="nis" name="nis" min="10000" max="99999" maxlength="5" placeholder="5 angka" value="{{ old('nip') }}" required>
            </div>

            <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Lengkap" value="{{ old('nama') }}" required>
            </div>
            <div class="form-group">
                <label for="tempat_lahir">Tempat Lahir</label>
                <select class="form-control select2" id="tempat_lahir" name="tempat_lahir" required style="width: 100%;">
                    <option value="">Pilih Kota Kelahiran</option>
                    @foreach($kota as $namaKota)
                        <option value="{{ $namaKota }}">{{ $namaKota }}</option>
                    @endforeach
                </select>
            </div>                    
            <div class="form-group">
                <label for="tanggal_lahir">Tanggal Lahir</label>
                <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" onfocus="this.showPicker()" required>
            </div>
            
            <script>
                document.getElementById('tanggal_lahir').max = new Date(new Date().setFullYear(new Date().getFullYear() - 15)).toISOString().split('T')[0];
            </script>
            <div class="form-group">
                <label>Jenis Kelamin</label><br>
                <div>
                    <input type="radio" id="laki-laki" name="gender" value="Laki-laki" required>
                    <label for="laki-laki">Laki-laki</label>
                </div>
                <div>
                    <input type="radio" id="perempuan" name="gender" value="Perempuan" required>
                    <label for="perempuan">Perempuan</label>
                </div>
            </div>            
            <div class="form-group">
                <label for="alamat">Alamat</label>
                <input type="text" class="form-control" id="alamat" name="alamat" maxlength="50" placeholder="JL. MESIGIT II /17" value="{{ old('alamat') }}" required>
            </div>
            <div class="form-group">
                <label for="wa">Nomor HP(WA)</label>
                <input type="number" class="form-control" id="wa" name="wa" pattern="[0-9]{10,15}" placeholder="08XXXXXXXXXXX" value="{{ old('wa') }}" required>
            </div>
            <div class="form-group">
                <label for="kelas">Kelas</label>
                <select class="form-control" id="kelas" name="kelas" required>
                    <option value="" disabled selected>Pilih Kelas</option>
                    @foreach ($kelas as $item)
                        <option value="{{ $item->id }}">{{ $item->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="emailmu@gmail.com" value="{{ old('email') }}" required>
            </div>
            <div class="form-group">
                <label for="foto">Foto</label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="foto" name="foto" accept=".jpg, .jpeg, .png" onchange="previewImage(event)" required>
                    <label class="custom-file-label" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap; display: inline-block; width: 100%" for="foto">Choose file</label>
                </div>
                <img id="img-preview" src="https://cdn-icons-png.flaticon.com/512/847/847969.png" alt="Foto Pelajar" class="img-thumbnail img-preview mt-2">
            </div>

            <div class="form-group">
                <div class="g-recaptcha" data-sitekey="6Ldea8YqAAAAANI0SwXXs-OxOE3IehdyPJ37mV2W"></div>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>    

<!--<div class="container mt-5">-->
        <div class="d-flex justify-content-center align-items-center">
            <div class="text-center">
                <h5>Butuh bantuan?</h5>
                <a href="https://wa.me/6281358750738?text=Halo,%20aku%20butuh%20bantuan" class="btn btn-success" target="_blank">
                    <i class="fab fa-whatsapp"></i> Contact via WhatsApp
                </a>
            </div>
        </div>
        <br>
    <!--</div>-->

    <script>
        function enableForm() {
            // Hapus reCAPTCHA setelah berhasil
            document.getElementById('captchaContainer').style.display = 'none';
            
            // Aktifkan form
            document.getElementById('formInput').disabled = false;
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/form.js') }}"></script>
    
    @include('partial.data2')
</body>
</html>