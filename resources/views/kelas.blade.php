@extends('layouts.kelas-style')

@section('content')
<div class="container">
    <h2>Data Kelas</h2>
    <button class="btn btn-success mb-3" id="addKelas"><i class="fa-solid fa-plus"></i> Tambah Kelas</button>
    <table id="kelasTable" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kelas</th>
                <th>Aksi</th>
            </tr>
        </thead>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="kelasModal" tabindex="-1" aria-labelledby="kelasModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="kelasForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="kelasModalLabel">Tambah/Edit Kelas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="kelasId">
                    <div class="form-group">
                        <label for="namaKelas">Nama Kelas</label>
                        <input type="text" id="namaKelas" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        let table = $('#kelasTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("kelas.data") }}',
            columns: [
                {
                    data: null, 
                    name: 'nomor',
                    render: function(data, type, row, meta) {
                        return meta.row + 1 + meta.settings._iDisplayStart;
                    }
                },
                { data: 'nama_kelas', name: 'nama_kelas' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
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
        });

        // Open Modal for Add
        $('#addKelas').on('click', function() {
            $('#kelasForm')[0].reset();
            $('#kelasId').val('');
            $('#kelasModalLabel').text('Tambah Kelas');
            $('#kelasModal').modal('show');

            $('#kelasModal').on('shown.bs.modal', function () {
                let input = $('#namaKelas');
                input.focus();
            });
        });

        // Submit Add/Edit Form
        $('#kelasForm').on('submit', function(e) {
            e.preventDefault();
            let id = $('#kelasId').val();
            let url = id ? '/kelas/' + id : '/kelas';
            let method = id ? 'PUT' : 'POST';
            let data = { nama_kelas: $('#namaKelas').val() };

            $.ajax({
                url: url,
                method: method,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: data,
                success: function(response) {
                    $('#kelasModal').modal('hide');
                    table.ajax.reload();
                    Swal.fire('Sukses!', response.success, 'success');
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;
                    if (errors && errors.nama_kelas) {
                        Swal.fire('Gagal!', errors.nama_kelas[0], 'error');
                    }
                }
            });
        });

        // Open Modal for Edit
        $('#kelasTable').on('click', '.editKelas', function() {
            let id = $(this).data('id');
            let nama = $(this).data('nama');

            $('#kelasId').val(id);
            $('#namaKelas').val(nama);
            $('#kelasModalLabel').text('Edit Kelas');
            $('#kelasModal').modal('show');

            // Auto focus dan blok semua teks
            $('#kelasModal').on('shown.bs.modal', function () {
                let input = $('#namaKelas');
                input.focus();
                // input.select(); // Blok semua teks
            });
        });

        // Delete Kelas
        $('#kelasTable').on('click', '.deleteKelas', function() {
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
                url: '/kelas/' + id,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // Tampilkan pesan SweetAlert bahwa data berhasil dihapus
                    Swal.fire(
                    'Terhapus!',
                    'Kelas berhasil dihapus.',
                    'success'
                    );
                    // Reload tabel setelah berhasil menghapus
                    table.ajax.reload();
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
    });
</script>
@endpush
