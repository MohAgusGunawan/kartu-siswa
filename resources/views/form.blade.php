<?php
// Proses validasi Turnstile di backend
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['token'])) {
    $secretKey = "0x4AAAAAAA6j7_uuC4IiGCFHKgjuWg7g6ZQ"; // Ganti dengan Secret Key Anda
    $token = $_POST['token'];

    $url = "https://challenges.cloudflare.com/turnstile/v0/siteverify";
    $data = [
        'secret' => $secretKey,
        'response' => $token,
    ];

    $options = [
        'http' => [
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data),
        ],
    ];

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    $result = json_decode($result);

    if ($result->success) {
        $statusMessage = "Anda adalah manusia!";
    } else {
        $statusMessage = "Verifikasi gagal. Silakan coba lagi.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Input Siswa</title>
    <link rel="icon" type="image/png" href="{{ asset('storage/images/logo/logo.jpg') }}" sizes="16x16" />
    <!-- Tambahkan CSS Bootstrap atau custom CSS sesuai kebutuhan -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    <!-- Include Select2 CSS CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
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


            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>    
    
    {{-- <div class="container mt-4">
        <h4>Rekap Data Siswa per Kelas</h4>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kelas</th>
                    <th>Jumlah Siswa</th>
                </tr>
            </thead>
            <tbody>
                @php $nomer = 1; @endphp
                @foreach($rekapKelas as $rekap)
                    <tr>
                        <td>{{ $nomer++ }}</td>
                        <td>{{ $rekap->kelas }}</td>
                        <td>{{ $rekap->jumlah }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>    --}}

    {{-- <div class="container d-flex justify-content-center align-items-center">
        <div class="table-container">
            <div class="head d-flex justify-content-between align-items-center mb-2">
                <h4 class="mb-0">Data Siswa</h4>
                <div class="d-flex gap-2 d-none">
                    <form action="#" method="GET" id="form-download-class" class="mb-0">
                        <div class="btn-group">
                            <button type="button" class="btn btn-danger btn-sm dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-regular fa-file-pdf p-2"></i>
                                <span class="spann" style="font-size: 1rem;">Cetak Perkelas</span>
                            </button>
                            <ul class="dropdown-menu">
                                @forelse($kelas as $kls)
                                    <li>
                                        <a class="dropdown-item" href="{{ route('form.downloadClassPdf', ['kelas' => $kls->kelas]) }}">
                                            Kelas {{ $kls->kelas }}
                                        </a>
                                    </li>
                                @empty
                                    <li><span class="dropdown-item text-muted">Kelas tidak tersedia</span></li>
                                @endforelse
                            </ul>
                        </div>
                    </form>
                    <form action="{{ route('form.downloadReport') }}" method="GET" class="mb-0">
                        <button type="submit" class="btn btn-success btn-sm d-flex align-items-center" id="bt-download">
                            <i class="fa-regular fa-file-excel p-2"></i>
                            <span class="spann" style="font-size: 1rem;">Unduh Semua</span>
                        </button>
                    </form>
                    <form action="#" method="GET" id="form-download-class" class="mb-0">
                        <div class="btn-group">
                            <button type="button" class="btn btn-danger btn-sm dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-print p-2"></i>
                                <span class="spann" style="font-size: 1rem;">Cetak Kartu</span>
                            </button>
                            <div class="dropdown-menu p-3" style="min-width: 130px;">
                                <input type="text" class="form-control mb-2" id="search-dropdown" placeholder="Cari NIS...">
                                <div id="dropdown-options">
                                    @forelse($nis as $nis_siswa)
                                        <a class="dropdown-item" href="{{ route('form.downloadCardPdf', ['nis' => $nis_siswa->nis]) }}">
                                            {{ $nis_siswa->nis }}
                                        </a>
                                    @empty
                                        <span class="dropdown-item text-muted">NIS tidak tersedia</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </form>                                
                </div>
            </div>
            <div class="table-wrapper">
                <table id="tbSiswa" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama</th>
                            <th>Tempat, Tanggal Lahir</th>
                            <th>Jenis Kelamin</th>
                            <th>Alamat</th>
                            <th>Nomor HP</th>
                            <th>Kelas</th>
                            <th>Email</th>
                            <th>Foto</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>  
            </div>
        </div>
    </div>    
</div> --}}

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

    {{-- <div class="modal fade" id="modalImage" tabindex="-1" aria-labelledby="modalImageLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalImageLabel">File Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img id="modalImageContent" src="" alt="File Image" class="img-fluid">
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Widget Turnstile -->
  <div id="turnstile-widget" class="cf-turnstile" data-sitekey="0x4AAAAAAA6j75MpRvhSaHTH"></div>

  <!-- Pesan status -->
  <p id="status-message"><?php echo $statusMessage ?? ''; ?></p>

  <script>
    // Fungsi untuk menangani respons Turnstile
    function handleTurnstileCallback(token) {
      // Kirim token ke backend untuk validasi
      fetch(window.location.href, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `token=${encodeURIComponent(token)}`,
      })
      .then(response => response.text())
      .then(() => {
        // Refresh halaman untuk menampilkan pesan status
        window.location.reload();
      })
      .catch(error => {
        console.error('Error:', error);
        document.getElementById('status-message').textContent = "Terjadi kesalahan. Silakan refresh halaman.";
      });
    }

    // Tambahkan event listener untuk menerima token dari Turnstile
    window.onload = function() {
      turnstile.render('#turnstile-widget', {
        sitekey: '0x4AAAAAAA6j75MpRvhSaHTH', // Ganti dengan Site Key Anda
        callback: handleTurnstileCallback,
      });
    };
  </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    <script src="{{ asset('js/form.js') }}"></script>
    
    @include('partial.data2')
</body>
</html>
