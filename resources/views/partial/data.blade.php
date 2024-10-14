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
            ajax: "{{ route('form.index') }}",
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
                    data: 'nis'
                },
                {
                    data: 'nama'
                },
                {
                    data: 'ttl'
                },
                {
                    data: 'gender'
                },
                {
                    data: 'alamat'
                },
                {
                    data: 'wa',
                    render: function (data, type, row, meta) {
                        return '<a href="https://wa.me/' + data + '" target="_blank">' + data +
                            '</a>';
                    },
                },
                {
                    data: 'kelas'
                },
                {
                    data: 'email'
                },
                {
                    data: 'foto',
                    render: function(data, type, row, meta) {
                        if (data !== '---') {
                            return '<button type="button" class="btn btn-secondary btn-sm btn-icon-text" onclick="openModal(\'/storage/images/siswa/' +
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
                        return '<a href="/form/' + row.id + '/edit" class="btn btn-primary">Edit</a>' +
                            ' <button type="button" class="btn btn-danger delete-btn" data-id="' + row.id + '">Delete</button>';
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
                zeroRecords: "Tidak ada data pegawai!",
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
              'Data pegawai berhasil dihapus.',
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
</script>