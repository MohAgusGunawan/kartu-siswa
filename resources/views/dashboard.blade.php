<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Input Siswa</title>
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
    
  @extends('partial.sidebar')
    <div class="container mt-4">
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
    </div>   

    <div class="container d-flex justify-content-center align-items-center">
        <div class="table-container">
            <div class="head d-flex justify-content-between align-items-center mb-2">
                <h4 class="mb-0">Data Siswa</h4>
                <div class="d-flex gap-2">
                    <form action="#" method="GET" id="form-download-class" class="mb-0">
                        <div class="btn-group">
                            <button type="button" class="btn btn-danger btn-sm dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-regular fa-file-pdf p-2"></i>
                                <span class="spann" style="font-size: 1rem;">Unduh Perkelas</span>
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

    <div class="modal fade" id="modalImage" tabindex="-1" aria-labelledby="modalImageLabel" aria-hidden="true">
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
    </div>

    @extends('partial.footer')

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/dashboard.js') }}"></script>
    
    @include('partial.data')
</body>
</html>
