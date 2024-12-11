<style>
    .table-responsive {
        overflow: visible;
    }
    #userTable {
        width: 100% !important;
    }
</style>
<div class="container mt-5">
    <div id="formtable_cabang">
        <h5>Cabang Table</h5>
        <div class="row mb-3">
            <div class="col-md-3">
                <input type="date" id="startDate" class="form-control" placeholder="Start Date">
            </div>
            <div class="col-md-3">
                <input type="date" id="endDate" class="form-control" placeholder="End Date">
            </div>
            <div class="col-md-3">
                <input type="text" id="searchBox" class="form-control" placeholder="Search">
            </div>
            <div class="col-md-3">
                <button id="filterBtn" class="btn btn-primary">Filter</button>
            </div>
        </div>
        <div class="table-responsive">
            <table id="cabang_datatables" class="display table table-bordered mb-2">
                <thead>
                    <tr>
                        <th>ID Cabang</th>
                        <th>Nama Cabang</th>
                        <th>Alamat</th>
                        <th>No telepon</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data akan diisi oleh DataTables -->
                </tbody>
            </table>
        </div>
    </div>

    <div id="formedit_cabang" class="d-none">
        <div class="container master-edit-register"><br>
            <div class="col-md-6 col-md-offset-3">
                <h2 class="text-center">FORM EDIT USER</h2>
                <hr>
                {{-- @if(session('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
                @endif --}}
                <h3 id="message"></h3>

                <form action="#" id="cabang_form" method="post">
                @csrf
                    <div class="form-group">
                        <label><i class="fa fa-user"></i> ID Cabang</label>
                        <input type="text" name="id_cabang" id="id_cabang" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label><i class="fa fa-user"></i> Nama Cabang</label>
                        <input type="text" name="nama_cabang"  id="nama_cabang" class="form-control" placeholder="Nama Cabang" required="">
                    </div>
                    <div class="form-group">
                        <label><i class="fa fa-key"></i> Alamat</label>
                        <input type="text" name="alamat_cabang" id="alamat_cabang" class="form-control" placeholder="Alamat" required="">
                    </div>
                    <div class="form-group">
                        <label><i class="fa fa-key"></i> Nomor Telepon</label>
                        <input type="text" name="telp_cabang" id="telp_cabang" class="form-control" placeholder="Telepon Number" required="">
                    </div>
                    <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-user" id="update_cabang"></i> Update</button>
                </form>
            </div>
        </div>
    </div>

</div>
<script>
$(document).ready(function() {
    $('#cabang_datatables').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: '{{ route("get_cabang_api_datatables") }}',
            dataSrc: ''
        },
        columns: [
            { data: 'cabang_id', name: 'cabang_id' },
            { data: 'nama', name: 'nama' },
            { data: 'alamat', name: 'alamat' },
            { data: 'telp', name: 'telp' },
            {
                data: null,
                render: function (data, type, row) {
                    return '<button class="btn btn-primary btn-sm edit_btn_cabang" data-id="' + row.id + '">' + '<i class="fas fa-pencil-alt"></i>' + '</button> ' + '<button class="btn btn-danger btn-sm delete_btn_cabang" data-id="' + row.id + '">' + '<i class="fas fa-trash"></i>' + '</button>';
                }
            }
        ]
    });

    // ### Tampilkan Edit
    $(document).on('click', '.edit_btn_cabang', function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        let url = '{{ route("edit_list_cabang", ":id") }}';
        url = url.replace(':id', id);
        $.ajax({
            url: url, // Route to load the form
            type: 'GET',
            success: function(data) {
                $('#id_cabang').val(data.cabang_id);
                $('#nama_cabang').val(data.nama);
                $('#alamat_cabang').val(data.alamat);
                $('#telp_cabang').val(data.telp);
                // Tampilkan form
                $('#formtable_cabang').hide();
                $('#formedit_cabang').removeClass('d-none');
            },
            error: function() {
                $('.master-page').html('<p>Error loading form.</p>');
            }
        });
    });

    // ============================= Submit list Cabang ==================================
    $('#cabang_form').on('submit', function (e) {
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        let form_data = $(this).serialize();
        $.ajax({
            url: "{{ route('update_cabang') }}",
            type: "POST",
            data: form_data,
            success: function (response) {
                // console.log('Success:', response);
                $('#formedit_cabang').addClass('d-none');
                $('#formtable_cabang').show();

                $('#cabang_datatables').DataTable().ajax.reload();
                Swal.fire({
                    title: 'Sukses!',
                    text: 'Data berhasil diupdate!',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            },
            error: function(response) {
                console.error('Error:', xhr.responseText);
                $('#message_list_register').html('<p>' + response.responseJSON.pesan + '</p>');
            }
        });
    });
    // ============================= End Of Submit list Cabang ==================================
    // ============================= delete list Cabang ==================================
    $(document).on('click','.delete_btn_cabang', function(e){
        e.preventDefault();
        let row = $(this).closest('tr');
        let id = $(this).data('id');
        let url = '{{ route("delete_list_cabang", ":id") }}';
        url = url.replace(':id', id);

        Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data ini akan dihapus secara permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#cabang_datatables').DataTable().row(row).remove().draw(false);

                        Swal.fire(
                            'Terhapus!',
                            'Data telah berhasil dihapus.',
                            'success'
                        );
                    },
                    error: function(xhr) {
                        Swal.fire(
                            'Error!',
                            'Terjadi kesalahan saat menghapus data.',
                            'error'
                        );
                    }
                });
            }
        });
    })
    // ========================== end of delete list Cabang ===============================

});
</script>
