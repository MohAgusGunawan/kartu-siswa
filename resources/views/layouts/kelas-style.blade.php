<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Kelola Kelas')</title>
    <link rel="icon" type="image/png" href="{{ asset('storage/app/public/images/logo/logo.jpg') }}" sizes="16x16" />

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    <style>
        #kelasTable {
            width: 100%;
            border-collapse: collapse;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow-x: hidden;
        }
        @media (max-width: 785px) {
            .container {
                width: 100%;
            }
            h2 {
                margin-top: 35px;
            }
        }
    </style>
    @include('partial.sidebar') <!-- Sidebar -->
    <br>
    <div class="content">
        @yield('content') <!-- Konten utama -->
    </div>
    <br>
    @extends('partial.footer')
    @stack('scripts')
</body>
</html>
