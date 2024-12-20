@stack('script')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Load jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Include Select2 JS CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<!-- Load DataTables -->
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

<script>
    function openModal(imageSrc) {
        document.getElementById('modalImageContent').src = imageSrc;
        var modal = new bootstrap.Modal(document.getElementById('modalImage'));
        modal.show();
    }
    // data table
    var tabel;
    // read data pengguna
    $(document).ready(function () {
        $('.select2').select2();
        // console.log("AJAX URL: ", "{{ route('form.index') }}");
        tabel = $('#tbSiswa').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            scrollX: true,
            fixedHeader: {
                header: true,
                footer: true
            },
            ajax: "{{ route('dashboard.index') }}",
            columns: [
                {
                    data: null,
                    name: 'id',
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row, meta) {
                        var pageInfo = $('#tbSiswa').DataTable().page.info();
                        return pageInfo.start + meta.row + 1;
                    }
                },
                {
                    data: 'id_card',
                    render: function(data, type, row, meta) {
                        return data + 
                            ' <button type="button" class="btn btn-primary btn-sm btn-icon-text edit-btn" data-id="' + 
                            row.id + '" data-value="' + data + '"><i class="fa-solid fa-pen-to-square"></i></button>';
                    }
                },
                {
                    data: 'nis'
                },
                {
                    data: 'nama'
                },
                {
                    data: 'ttl'
                },
                {
                    data: 'gender',
                },
                {
                    data: 'alamat'
                },
                {
                    data: 'wa',
                    render: function (data, type, row, meta) {
                    // Sensor sebagian nomor WA, menampilkan hanya 4 digit terakhir
                    let waMasked = data.slice(0, -4).replace(/[0-9]/g, 'x') + data.slice(-4);
                    return '<a href="https://wa.me/+62' + data + '" target="_blank">' + waMasked + '</a>';
                    }
                },
                {
                    data: 'kelas'
                },
                {
                    data: 'email',
                    render: function (data, type, row, meta) {
                    // Sensor sebagian email, menampilkan karakter sebelum '@' secara parsial
                    let emailParts = data.split('@');
                    let emailMasked = emailParts[0].slice(0, 4).replace(/./g, 'x') + emailParts[0].slice(4) + '@' + 
                    emailParts[1];
                    return emailMasked;
                    }
                },
                {
                    data: 'foto',
                    render: function(data, type, row, meta) {
                        if (data !== '---') {
                            return '<button type="button" class="btn btn-secondary btn-sm btn-icon-text" onclick="openModal(\'storage/images/siswa/' +
                                data + '\')">Lihat Foto</button>';
                        } else {
                            return '---';
                        }
                    }  
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row, meta) {
                        return '<a href="/form/' + row.id + '/edit" class="btn btn-primary"><i class="fa-solid fa-pen-to-square"></i></a>' +
                            ' <button type="button" class="btn btn-danger delete-btn" data-id="' + row.id + '"><i class="fa-solid fa-trash"></i></button>';
                    }
                }
            ],
            aLengthMenu: [
                [5, 10, 15, -1],
                [5, 10, 15, "All"]
            ],
            iDisplayLength: 10,
            language: {
                paginate: {
                    previous: "Sebelumnya",
                    next: "Selanjutnya"
                },
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ entri",
                zeroRecords: "Tidak ada data siswa!",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 entri",
                infoFiltered: "(disaring dari _MAX_ entri keseluruhan)"
            },
            responsive: true,
            columnDefs: [
                {
                    orderable: false,
                    targets: 0
                }
            ]
        });

        $('#tbSiswa').each(function () {
            var datatable = $(this);
            var search_input = datatable.closest('.dataTables_wrapper').find('div[id$=_filter] input');
            search_input.attr('placeholder', 'Cari');
            search_input.removeClass('form-control-sm');
            var length_sel = datatable.closest('.dataTables_wrapper').find('div[id$=_length] select');
            length_sel.removeClass('form-control-sm');
        });
    });

    $('#tbSiswa').on('click', '.delete-btn', function() {
    var id = $(this).data('id');
    Swal.fire({
      title: 'Apakah Anda yakin?',
      text: "Anda tidak akan dapat mengembalikan ini!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, hapus saja!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: '/form/' + id,
          type: 'DELETE',
          data: {
            _token: '{{ csrf_token() }}'
          },
          success: function(response) {
            // Tampilkan pesan SweetAlert bahwa data berhasil dihapus
            Swal.fire(
              'Terhapus!',
              'Data Siswa berhasil dihapus.',
              'success'
            );
            // Reload tabel setelah berhasil menghapus
            $('#tbSiswa').DataTable().ajax.reload();
          },
          error: function(xhr, status, error) {
            console.error(xhr);
            Swal.fire(
              'Gagal!',
              'Terjadi kesalahan saat menghapus data: ' + error,
              'error'
            );
          }
        });
      }
    });
  });

  $(document).on('click', '.edit-btn', function() {
    var id = $(this).data('id'); // Ambil ID data dari atribut tombol
    var value = $(this).data('value'); // Ambil nilai data dari atribut tombol
    
    // Isi modal dengan data yang diambil
    $('#dataField').val(value); // Masukkan nilai ke input di modal
    
    // Ubah URL form dengan ID data
    var updateUrl = "/route/update/" + id; // Ganti '/route/update/' dengan route update Anda
    $('#editForm').attr('action', updateUrl);

    // Tampilkan modal
    $('#editModal').modal('show');
});

$('#editModal').on('shown.bs.modal', function() {
        var input = $('#dataField');
        input.focus(); // Fokus pada input
        input.select(); // Blok semua teks dalam input
    });

// Deteksi event paste di input
$('#dataField').on('paste', function() {
        var input = $(this); // Ambil elemen input

        // Tunggu hingga paste selesai, lalu submit form
        setTimeout(function() {
            $('#editForm').submit(); // Submit form secara otomatis
        }, 100); // Tunggu 100ms untuk memastikan paste selesai
    });

$(document).on('submit', '#editForm', function(e) {
    e.preventDefault(); // Mencegah reload halaman
    
    var form = $(this);
    var url = form.attr('action'); // URL update dari atribut form
    var data = form.serialize(); // Ambil data form

    $.ajax({
        type: 'POST', // Sesuaikan dengan metode Anda (PUT jika RESTful)
        url: url,
        data: data,
        success: function(response) {
            // Tampilkan pesan sukses jika ada
            // alert('Data berhasil diperbarui!');
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
              title: 'Data berhasil diperbarui!'
            });

            // Tutup modal
            $('#editModal').modal('hide');

            // Perbarui baris data di DataTable
            $('#tbSiswa').DataTable().ajax.reload();
        },
        error: function(xhr) {
            // alert('Terjadi kesalahan. Silakan coba lagi.');
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
              icon: 'error',
              title: 'Terjadi kesalahan. Silakan coba lagi.'
            });
            console.error(xhr.responseText); // Debugging jika ada error
        }
    });
});

</script>