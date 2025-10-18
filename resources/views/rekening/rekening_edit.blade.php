<style>
    .table-responsive {
        overflow: visible;
    }
    #rekeningTable {
        width: 100% !important;
    }
</style>
<div class="container mt-5">
    <div id="rekeningFormtable">
        <button class="btn btn-primary mb-3" id="addRekening">Daftar Rekening</button>
        <h5>Rekening Table</h5>
        <div class="table-responsive">
            <table id="rekeningTable" class="display table table-bordered mb-2">
                <thead>
                    <tr>
                        <th>Rekening Id</th>
                        <th>Name</th>
                        <th>No Rekening</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data akan diisi oleh DataTables -->
                </tbody>
            </table>
        </div>
    </div>
    {{-- ### Fungsi Edit Register --}}
    <div id="rekeningFormedit" class="d-none">
        <div class="container master-edit-register"><br>
            <div class="col-md-6 col-md-offset-3">
                <h2 class="text-center">FORM EDIT REKENING</h2>
                <hr>
                <h3 id="message"></h3>
                <form action="" id="editListRekeningForm" method="post">
                    @csrf
                    <div class="form-group">
                    <label><i class="fa fa-user"></i> Rekening Id</label>
                    <input type="text" name="rekening_id" id="rekening_id" class="form-control" value="" required="" readonly>
                    </div>
                    <div class="form-group">
                        <label><i class="fa fa-envelope"></i> Nama Rekening</label>
                        <input type="text" name="rekening_name" id="rekening_name" class="form-control" value="" required="">
                    </div>
                    <div class="form-group">
                        <label><i class="fa fa-user"></i> Nomor Rekening</label>
                        <input type="text" name="rekening_number" id="rekening_number" class="form-control" value="" required="">
                    </div>
                    <button type="submit" class="btn btn-primary btn-block" id="but_edit_list_rekening"><i class="fa fa-user"></i> Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    // ========================== menapilkan list user ===============================
    loadListRekeningForm();
    function loadListRekeningForm() {
        let table = $('#rekeningTable').DataTable({
            ajax: {
                url: '{{ route("filter_rekening") }}',
            },
            columns:[
                { data: 'kode_bank' },
                { data: 'nama_bank' },
                { data: 'no_rekening' },
                {
                    data: null,
                    render: function (data, type, row) {
                        return '<button class="btn btn-primary btn-sm editBtn" data-id="' + row.id + '">' + '<i class="fas fa-pencil-alt"></i>' + '</button> ' + '<button class="btn btn-danger btn-sm deleteBtn" data-id="' + row.id + '">' + '<i class="fas fa-trash"></i>' + '</button>';
                    }
                }
            ],
            searching: false,
            paging: true,
            info: false,
            scrollY: '100vh',  // Menambahkan scrolling vertikal
            scrollCollapse: true,
            scrollX: true,
            fixedHeader: {
                header: true,
                footer: false
            }
        });
    }
    // ========================== end of menapilkan list user ===============================
    // ========================= Tambah Rekening ======================================
    $(document).on('click', '#addRekening', function(e) {
        e.preventDefault();
        load_add_rekening_form();
    });

    function load_add_rekening_form() {
        $.ajax({
            url: '{{ route('rekening_tambah') }}',
            type: 'GET',
            success: function(response) {
                $('.master-page').html(response);
            },
            error: function() {
                $('.master-page').html('<p>Error loading form.</p>');
            }
        });
    }
// ========================= End Of Tambah Rekening ======================================

     // ============================ edit list user =================================
     $(document).on('click', '.editBtn', function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        let url = '{{ route("edit_list_rekening", ":id") }}';
        url = url.replace(':id', id);
        $.ajax({
            url: url, // Route to load the form
            type: 'GET',
            success: function(data) {
                $('#rekening_id').val(data.kode_bank);
                $('#rekening_name').val(data.nama_bank);
                $('#rekening_number').val(data.no_rekening);

                // Tampilkan form
                $('#rekeningFormtable').hide();
                $('#rekeningFormedit').removeClass('d-none');
            },
            error: function() {
                $('.master-page').html('<p>Error loading form.</p>');
            }
        });
    });

    // ========================== end of edit list user ===============================
    // ========================== update list user ===============================
    $(document).off('submit', '#editListRekeningForm');

    $(document).on('submit', '#editListRekeningForm', function(e) {
        e.preventDefault();
        e.stopPropagation();
        console.log('Form submitted');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        let formData = $(this).serialize();
        console.log('Form data:', formData);
        $.ajax({
            url: '{{ route('update_list_rekening') }}', // Route to handle form submission
            type: 'POST',
            data: formData,
            success: function(response) {
                // console.log('Success:', response);
                $('#rekeningFormedit').addClass('d-none');
                $('#rekeningFormtable').show();

                $('#rekeningTable').DataTable().ajax.reload();
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
    // ========================== end of update list user ===============================
    // ============================= delete list user ==================================
    $(document).on('click','.deleteBtn', function(e){
        e.preventDefault();
        let row = $(this).closest('tr');
        let id = $(this).data('id');
        let url = '{{ route("delete_list_rekening", ":id") }}';
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
                        $('#rekeningTable').DataTable().row(row).remove().draw(false);

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
    // ========================== end of delete list user ===============================
});


</script>
