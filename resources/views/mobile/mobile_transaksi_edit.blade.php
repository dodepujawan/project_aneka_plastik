{{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" /> --}}
<style>
    /* .product-info {
        display: flex;
        gap: 20px; / Adds space between the items /
    }
    .info-item {
        flex: 1; / Makes each item take up equal space /
    }
    h5 {
        margin: 0; / Removes default margin from h5 /
    } */

    /* table scroll */
    .table-responsive-set {
            overflow: visible;
        }
        .style-table {
            width: 100% !important;
        }
    /* .table-container {
        max-height: 500px;  Sesuaikan tinggi maksimum sesuai kebutuhan
        overflow-y: auto;   Tambahkan scroll vertikal jika konten melebihi tinggi maksimum
        width: 100%;        Pastikan lebar kontainer sesuai dengan tabel
        border: 1px solid #ddd;  Opsional: tambahkan border untuk kontainer tabel
    }

    .table-container table {
        width: 100%;  Pastikan tabel mengambil lebar penuh dari kontainer
        border-collapse: collapse;  Menghindari jarak antara border sel
    }

    .table-container th, .table-container td {
        padding: 8px;  Opsional: tambahkan padding untuk sel tabel
        text-align: left;  Opsional: sesuaikan perataan teks
    } */
    /* end of table scroll */
    /* Styling Select2 */
    /* .select2-result-barang {
        padding: 4px;
    }
    .select2-result-barang__kode {
        font-size: 16px;
        color: #333;
    }
    .select2-result-barang__info {
        font-size: 14px;
        color: #666;
    } */
    /* End Of Styling Select2 */
    /* Product info */
    .product-info {
        background-color: white;
        border-radius: 8px; /* Ukuran lebih kecil */
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Lebih ringan */
        padding: 15px; /* Padding lebih kecil */
        margin-top: 15px; /* Margin atas lebih kecil */
    }

    .info-item {
        text-align: center;
        margin-bottom: 10px; /* Jarak antar item lebih kecil */
    }

    .info-item h5 {
        color: #6c757d;
        font-size: 0.9rem; /* Ukuran font lebih kecil */
        margin-bottom: 0;
        display: inline; /* Ubah ke inline untuk sejajar */
        padding-bottom: 2px;
        border-bottom: 2px solid transparent;
        transition: border-bottom-color 0.3s ease;
    }

    .info-item:hover h5 {
        border-bottom-color: #6c757d;
    }

    .info-item span {
        color: black;
        font-weight: bold;
        display: inline; /* Pastikan sejajar dengan h5 */
        margin-left: 5px; /* Jarak kecil antara teks dan span */
    }

    .underline {
        width: 100%;
        border-bottom: 1px solid #dee2e6;
        margin: 10px 0; /* Margin lebih kecil */
    }

    @media (max-width: 768px) {
        .product-info {
            padding: 10px; /* Padding lebih kecil untuk layar kecil */
        }
        .info-item {
            margin-bottom: 8px; /* Jarak antar item lebih kecil */
        }
        .info-item h5 {
            font-size: 0.85rem; /* Font lebih kecil untuk layar kecil */
        }
    }
    /* End of Product Info */
    /* Display Card */
    .transaksi-card-edit {
    border-radius: 12px;
    border: 1px solid #ddd;
    background-color: #f9f9f9;
    transition: box-shadow 0.3s ease;
    }
    .transaksi-card-edit:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .transaksi-card-edit .card-body div {
        margin-bottom: 6px;
    }
    .editable-jumlah,
    .editable-diskon {
        background-color: #fff3cd;
        padding: 2px 6px;
        border-radius: 4px;
    }
    /* End Of Display Card */
    </style>
    {{-- ### Tabel List Transaksi ### --}}
    <div id="master_table_edit_field">
        <h3>Halaman Edit PO Mobile</h3>
        <div class="button-container" style="display: flex; justify-content: flex-start; gap: 10px;">
            <button type="button" class="btn mt-2 mb-2" id="po_table_edit_refresh" style="background-color: rgba(0, 123, 255, 0.5); border-color: rgba(0, 123, 255, 0.5); color: white;"><i class="fas fa-undo"> Refresh</i></button>
            <button type="button" class="btn mt-2 mb-2" id="po_table_edit_input" style="background-color: rgba(16, 247, 16, 0.5); border-color: rgba(78, 242, 78, 0.5); color: white;"><i class="fas fa-pencil-alt"> Input PO</i></button>
        </div>
        <div class="mt-3 table-container table-responsive table-responsive-set">
            <table id="transaksi_table_edit_field" class="display table table-bordered mb-2 style-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No PO</th>
                        <th>Tgl PO</th>
                        <th>Total PO</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <table id="transaksi_table_edit_field_admin" class="display table table-bordered mb-2 style-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No PO</th>
                        <th>Customer Kode</th>
                        <th>Customer</th>
                        <th>Tgl PO</th>
                        <th>Total PO</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <div class="master_transaksi_field" style="display: none">
        <div class="col-lg-12 alert alert-warning" id="mssakit_warning" role="alert" style="margin-top: 10px; opacity: 0.8;">
            <h5 style="font-weight: bold;">Mode Edit Data PO</h5>
        </div>
        <div class="container master_customer_select_edit">
            <div class="row">
                <div class="form-group col-lg-4 col-md-12 col-sm-12 mb-3">
                    <input type="text" name="kode_user_trans_edit" id="kode_user_trans_edit" class="form-control" placeholder="Kode User" required="" readonly>
                </div>
                <div class="form-group col-lg-4 col-md-12 col-sm-12 mb-3">
                    <input type="text" name="nama_user_trans_edit" id="nama_user_trans_edit" class="form-control" placeholder="Nama User" required="" readonly>
                </div>
                <div class="form-group col-lg-4 col-md-12 col-sm-12 mb-3">
                    <select name="select_user_trans_edit" id="select_user_trans_edit" class="form-control">
                        <option value="">Pilih User</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="container master_transaksi_edit">
            <div class="product-info">
                <input type="hidden" value="" id="kd_barang" readonly>

                <!-- Informasi Barang -->
                <div class="row">
                    <div class="col-md-4 info-item">
                        <h5>Nama: <span id="nama_barang_edit">-</span></h5>
                    </div>
                    <div class="col-md-4 info-item">
                        <h5>Harga: <span id="harga_barang_edit">-</span></h5>
                    </div>
                    <div class="col-md-4 info-item">
                        <h5>Isi: <span id="unit_barang_edit">-</span></h5>
                    </div>
                </div>

                {{-- <!-- Garis Pembatas -->
                <div class="underline"></div>

                <!-- Informasi Harga dan Stok -->
                <div class="row mt-3">
                    <div class="col-md-6 info-item">
                        <h5>Harga Barang: <span id="harga_barang_edit">-</span></h5>
                    </div>
                    <div class="col-md-6 info-item">
                        <h5>Stok Barang: <span id="stok_barang_edit">-</span></h5>
                    </div>
                </div> --}}
            </div>
        </div>
        {{-- Inputan No PO --}}
        <div class="container">
            <input type="text" class="form-control mt-3 col-lg-3" name="no_po_edit" id="no_po_edit" readonly>
        </div>
        {{-- End Of Inputan No PO --}}
        <form action="" class="mt-3">
            <div class="row">
                <div class="col-lg-4 col-md-12 mb-3">
                    <div class="d-flex align-items-center">
                    <button type="button" id="clear_select_edit" class="btn btn-secondary btn-sm me-2 mr-2">
                        <i class="fa fa-eraser" aria-hidden="true"></i>
                    </button>
                    <select name="select_barang_edit" id="select_barang_edit" class="form-control">
                        <option></option>
                        <!-- Options untuk select dropdown -->
                    </select>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                    <select name="select_barang_satuan_edit" id="select_barang_satuan_edit" class="form-control">
                        <option value="">Pilih Satuan</option>
                    </select>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                    <input type="number" id="jumlah_trans_edit" class="form-control" placeholder="Jumlah barang">
                </div>
            </div>
            <div class="row">
                <div class="col-lg-2 col-md-6 col-sm-12 mb-3">
                    <div class="d-flex align-items-center">
                        @php
                            $user = Auth::user();
                            $allowed_roles = ['customer'];
                            $is_customer = in_array($user->roles, $allowed_roles);
                        @endphp
                    <input type="number" id="diskon_barang_edit" class="form-control" placeholder="Disc %" {{ $is_customer ? 'readonly' : '' }}>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                    <div class="d-flex align-items-center">
                        @php
                            $user = Auth::user();
                            $allowed_roles = ['customer'];
                            $is_customer = in_array($user->roles, $allowed_roles);
                        @endphp
                    <input type="number" id="diskon_barang_rp_edit" class="form-control" placeholder="Diskon RP" {{ $is_customer ? 'readonly' : '' }}>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                    <div class="d-flex align-items-center" style="gap: 10px;">
                        <label for="ppn_trans_edit" class="mb-0">PPN</label>
                        <input type="number" id="ppn_trans_edit" class="form-control form-control-md" style="width: 150px;" placeholder="0" disabled>
                    </div>
                    <button type="submit" class="btn btn-success btn-sm ms-2 ml-2 mt-3">
                            {{-- <i class="fa fa-check" aria-hidden="true"></i> --}}Simpan
                    </button>
                </div>
            </div>
        </form>
        {{-- ### Display Card ###  --}}
        <div id="transaksi_card_container_edit" class="mt-3"></div>
        <div class="mt-3">
            <h5>Grand Total: <span id="grand_total_edit">0</span></h5>
        </div>
        {{-- ### End of Display Card ###  --}}
        <div class="button-container" style="display: flex; justify-content: flex-end; gap: 10px;">
            <button type="submit" class="btn btn-primary mt-2 mb-2" id="save_table_transaksi_edit"><i class="fas fa-save"> Proses</i></button>
            <button type="submit" class="btn btn-info mt-2 mb-2" id="reset_table_transaksi_edit"><i class="fas fa-sync-alt"> Reset</i></button>
            <button type="submit" class="btn btn-warning mt-2 mb-2" id="return_table_transaksi_edit"><i class="fas fa-undo"> List Menu</i></button>
        </div>
    </div>
    {{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script> --}}

<script>
$(document).ready(function(){
// ===================================== Show Table PO ==============================================
    let user_role_select = @json(Auth::user()->roles);
    let user_kode_select = @json(Auth::user()->user_kode);
    let user_nama_select = @json(Auth::user()->name);
    // console.log('kode:' + user_role_select);
    // console.log('nama:' + user_nama_select);
    if(user_role_select == 'customer'){
        $("#transaksi_table_edit_field").show();
        $("#transaksi_table_edit_field_admin").hide();
        show_table_po();
        $(".master_customer_select_edit").hide();
        // menghapus halaman sebelumnya lalu reload ulang from sidebar click
        $('#transaksi_table_edit_field').DataTable().state.clear();
        show_table_po();
    }else{
        $("#transaksi_table_edit_field").hide();
        $("#transaksi_table_edit_field_admin").show();
        show_table_po_admin();
        select_user_list_edit();
        // menghapus halaman sebelumnya lalu reload ulang from sidebar click
        $('#transaksi_table_edit_field_admin').DataTable().state.clear();
        show_table_po_admin();
    }
    function show_table_po(){
        if ($.fn.dataTable.isDataTable('#transaksi_table_edit_field')) {
            $('#transaksi_table_edit_field').DataTable().destroy();
        }
        const table = $('#transaksi_table_edit_field').DataTable({
            processing: true,
            serverSide: false,
            stateSave: true, // untuk kembali ke halaman sebelumnya
            ajax: {
                url: '{{ route('get_edit_transaksi_data') }}',
                type: 'GET',
            },
            columns: [
                {
                    data: null,
                    name: 'no',
                    render: (data, type, row, meta) => meta.row + 1, // Nomor otomatis
                },
                { data: 'no_invoice', name: 'no_invoice' },
                { data: 'created_at', name: 'created_at' },
                {
                    data: 'total',
                    name: 'total',
                    render: $.fn.dataTable.render.number(',', '.', 2, 'Rp ') // Format angka jadi Rupiah
                },
                {
                    data: 'no_invoice',
                    orderable: false,
                    render: (data, type, row) => {
                        return `
                            <div style="display: flex; justify-content: center; gap: 0.5rem;">
                                <button class="btn btn-sm btn-primary edit-btn" data-no-invoice="${row.no_invoice}" style="margin-right: 0;">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-success print-btn" id="print_edit_pdf" data-no-invoice="${row.no_invoice}">
                                    <i class="fa fa-print"></i>
                                </button>
                                <button class="btn btn-sm btn-danger delete-btn" data-no-invoice="${row.no_invoice}">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        `;
                    },
                },
            ],
            searching: true,
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
        $('#po_table_edit_refresh').on('click', function() {
            if (table) {
                table.state.clear();
                show_table_po();
            }
        });
    }

    // ### Versi Admin ###
    function show_table_po_admin(){
        if ($.fn.dataTable.isDataTable('#transaksi_table_edit_field_admin')) {
            $('#transaksi_table_edit_field_admin').DataTable().destroy();
        }
        const table = $('#transaksi_table_edit_field_admin').DataTable({
            processing: true,
            serverSide: false,
            stateSave: true, // untuk kembali ke halaman sebelumnya
            ajax: {
                url: '{{ route('get_edit_transaksi_data_admin') }}',
                type: 'GET',
            },
            columns: [
                {
                    data: null,
                    name: 'no',
                    render: (data, type, row, meta) => meta.row + 1, // Nomor otomatis
                },
                { data: 'no_invoice', name: 'no_invoice' },
                { data: 'user_kode', name: 'user_kode' },
                { data: 'nama_cust', name: 'nama_cust' },
                { data: 'created_at', name: 'created_at' },
                {
                    data: 'total',
                    name: 'total',
                    render: $.fn.dataTable.render.number(',', '.', 2, 'Rp ') // Format angka jadi Rupiah
                },
                {
                    data: 'no_invoice',
                    orderable: false,
                    render: (data, type, row) => {
                        return `
                            <div style="display: flex; justify-content: center; gap: 0.5rem;">
                                <button class="btn btn-sm btn-primary edit-btn" data-no-invoice="${row.no_invoice}" style="margin-right: 0;">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-success print-btn" id="print_edit_pdf" data-no-invoice="${row.no_invoice}">
                                    <i class="fa fa-print"></i>
                                </button>
                                <button class="btn btn-sm btn-danger delete-btn" data-no-invoice="${row.no_invoice}">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        `;
                    },
                },
            ],
            searching: true,
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
        $('#po_table_edit_refresh').on('click', function() {
            if (table) {
                table.state.clear();
                show_table_po_admin();
            }
        });
    }
// ================================= End Of Show Table PO ===========================================
// ========================= Input PO Main Transaksi ======================================
    $(document).on('click', '#po_table_edit_input', function(e) {
        e.preventDefault();
        loadMainTransaksilinkEdit();
    });

    function loadMainTransaksilinkEdit() {
        $.ajax({
            url: '{{ route('index_transaksi') }}',
            type: 'GET',
            success: function(response) {
                $('.master-page').html(response);
            },
            error: function() {
                $('.master-page').html('<p>Error loading form.</p>');
            }
        });
    }
// ========================= End Of Input PO Main Transaksi ======================================
// ================================= Click Edit Button ===========================================
    let grandTotal = 0;
    // console.log(grandTotal);
    $(document).on('click', '#transaksi_table_edit_field .edit-btn', function () {
        let no_invoice = $(this).data('no-invoice');
        ajax_click_edit_button(no_invoice);
    });
    $(document).on('click', '#transaksi_table_edit_field_admin .edit-btn', function () {
        let no_invoice = $(this).data('no-invoice');
        ajax_click_edit_button(no_invoice);
    });
    function ajax_click_edit_button(no_invoice) {
        $.ajax({
            url: '{{ route('get_edit_transaksi_to_table') }}',
            type: 'GET',
            data: { no_invoice },
            success: function (response) {
                grandTotal = 0; // Reset total dulu
                $('#transaksi_card_container_edit').empty(); // Hapus semua card sebelumnya

                const invoice = response.data[0];
                const user_role_diskon = @json(Auth::user()->roles);

                $('#no_po_edit').val('Invoice No : ' + invoice.no_invoice);
                $('#save_table_transaksi_edit').val(invoice.no_invoice);
                $('#reset_table_transaksi_edit').val(invoice.no_invoice);
                $('#kode_user_trans_edit').val(invoice.user_kode);
                $('#nama_user_trans_edit').val(invoice.nama_cust);

                response.data.forEach((item, index) => {
                    let harga = parseFloat(item.harga) || 0;
                    let qty = parseFloat(item.qty_order) || 0;
                    let diskon = parseFloat(item.disc) || 0;
                    let diskonrp = parseFloat(item.ndisc) || 0;
                    let ppn = parseFloat(item.ppn) || 0;
                    let satuan = item.satuan || '-';
                    let unit = item.qty_unit || '-';
                    let total = parseFloat(item.total) || 0;

                    grandTotal += total;

                    let cardHtml = `
                    <div class="card mb-3 shadow-sm transaksi-card-edit" data-total="${total}" data-index="${index}">
                        <div class="card-body position-relative">
                            <div><strong>No:</strong> <span class="card-number">${index + 1}</span></div>
                            <div><strong>KD Barang:</strong> <span class="kd-barang">${item.kd_brg}</span></div>
                            <div><strong>Nama:</strong> <span class="nama-barang">${item.nama_brg}</span></div>
                            <div><strong>Harga:</strong> <span class="harga-barang">${format_ribuan(harga)}</span></div>
                            <div><strong>Isi:</strong> <span class="isi-barang">${unit}</span></div>
                            <div><strong>Satuan:</strong> <span class="satuan-barang">${satuan}</span></div>
                            <div><strong>Jumlah:</strong>
                                <span class="editable-jumlah" contenteditable="true">${qty}</span>
                            </div>
                            <div><strong>Diskon:</strong>
                                <span class="editable-diskon" ${user_role_diskon === 'customer' ? '' : 'contenteditable="true"'}>${diskon}</span>
                            </div>
                            <div><strong>Diskon Rp:</strong>
                                <span class="editable-diskon-rp" ${user_role_diskon === 'customer' ? '' : 'contenteditable="true"'}>${diskonrp}</span>
                            </div>
                            <div><strong>PPN:</strong>
                                <span class="editable-ppn" ${user_role_diskon === 'customer' ? '' : 'contenteditable="true"'}>${ppn}</span>
                            </div>
                            <div><strong>Total:</strong> <span class="total-text">${format_ribuan(total)}</span></div>
                            <button class="btn btn-sm btn-danger mt-2 delete-card-edit"><i class="fa fa-trash"></i> Hapus</button>
                        </div>
                    </div>
                    `;

                    $('#transaksi_card_container_edit').append(cardHtml);
                });

                $('#grand_total_edit').text(format_ribuan(grandTotal));
                $('#master_table_edit_field').hide();
                $('.master_transaksi_field').show();
                $('#select_user_trans_edit').val(null).trigger('change');
                select2_call();
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error: ', status, error);
            }
        });
    }
// ================================= End Of Click Edit Button ===========================================
// ==================================== Click Print Button ==============================================
    $(document).on('click', '#print_edit_pdf', function() {
        var invoice_number = $(this).data("no-invoice");

        // Show SweetAlert confirmation
        Swal.fire({
            title: 'Are you sure?',
            text: `Apakah Ingin Print PO: ${invoice_number} ?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya Print !',
            cancelButtonText: 'Batal',
            customClass: {
                cancelButton: 'btn-danger'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // If user confirms, open the PDF
                window.open('{{ route("generate_pdf", ":invoice_number") }}'.replace(':invoice_number', invoice_number), '_blank');
            }
        });
    });
// ================================= End Of Click Print Button ===========================================
// ================================= Click Delete Button ===========================================
    $(document).on('click', '#transaksi_table_edit_field .delete-btn', function () {
        let no_invoice = $(this).data('no-invoice');
        let row = $(this).closest('tr');
        delete_table_edit(no_invoice, row);
    });

    $(document).on('click', '#transaksi_table_edit_field_admin .delete-btn', function () {
        let no_invoice = $(this).data('no-invoice');
        let row = $(this).closest('tr');
        delete_table_edit(no_invoice, row);
    });

    function delete_table_edit(no_invoice, row){
            // Mendapatkan baris terdekat dari tombol delete
        Swal.fire({
            title: 'Anda yakin?',
            text: `Hapus Data dengan No. Invoice ${no_invoice} ?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route('delete_products') }}',
                    type: 'POST',
                    data: {
                        value_invo: no_invoice,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if(user_role_select == 'customer'){
                            $('#transaksi_table_edit_field').DataTable().row(row).remove().draw(false);
                        }else{
                            $('#transaksi_table_edit_field_admin').DataTable().row(row).remove().draw(false);
                        }
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: `Data dengan No. Invoice ${no_invoice} berhasil dihapus.`,
                            showConfirmButton: false,
                            timer: 2000 // Durasi tampil dalam milidetik
                        });
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: `Error: ${xhr.responseJSON.error}`
                        });
                    }
                });
            }
        });
    }
// ================================= End Of Click Delete Button ===========================================
// ==================================== Select User ==============================================
    function select_user_list_edit(){
        $('#select_user_trans_edit').select2({
            // tags: true,
            theme: 'bootstrap4',
            width: '100%',
            ajax: {
                url: '{{ route('get_users') }}',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term // Kirim parameter pencarian ke server
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.map(function(user) {
                            // Potong nama barang jika terlalu panjang
                            let nama_user_cut = user.kd_customer + ' / ' + user.nama_cust || '';
                            if (nama_user_cut.length > 15) {
                                nama_user_cut = nama_user_cut.substring(0, 20) + '...'; // Potong teks
                            }
                            return {
                                id: user.id,
                                text: nama_user_cut,
                                kd_customer: user.kd_customer,
                                nama: user.nama_cust,
                            };
                        })
                    };
                },
                cache: true
            },
            placeholder: 'Pilih User',
            minimumInputLength: 1,
            templateResult: formatUser
        });
        // ### fungsi untuk format text select2
        function formatUser(user) {
            if (!user.id) {
                return user.text;
            }
            var $user = $(
                '<div class="select2-result-user">' +
                    '<div class="select2-result-user__kode"><strong>' + user.kd_customer + '</strong></div>' +
                    '<div class="select2-result-user__info">' +
                        (user.nama ? user.nama : '') +
                        // (barang.kemasan ? ' / ' + barang.kemasan : '') +
                    '</div>' +
                '</div>'
            );
            return $user;
        }

        // ### Event listener saat item dipilih
        $('#select_user_trans_edit').on('select2:select', function(e) {
            var data = e.params.data; // Data yang dipilih
            $('#kode_user_trans_edit').val(data.kd_customer);
            $('#nama_user_trans_edit').val(data.nama);
        });
    };
// ================================= End Of Select User ===========================================
// ================================= Select Barang ===========================================
    function select2_call(){
        $('#select_barang_edit').select2({
            // tags: true,
            theme: 'bootstrap4',
            width: '100%',
            ajax: {
                url: '{{ route('get_barangs') }}',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term // Kirim parameter pencarian ke server
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.map(function(barang) {
                            let nama_barang_cut = barang.kd_barang + ' / ' + barang.nama_barang || '';
                            if (nama_barang_cut.length > 15) {
                                nama_barang_cut = nama_barang_cut.substring(0, 20) + '...'; // Potong teks
                            }
                            return {
                                id: barang.id,
                                text: nama_barang_cut,
                                kd_barang: barang.kd_barang,
                                nama: barang.nama_barang,
                            };
                        })
                    };
                },
                cache: true
            },
            placeholder: 'Pilih Barang',
            minimumInputLength: 1,
            templateResult: formatBarang
        });
    }
    // ### fungsi untuk format text select2
    function formatBarang(barang) {
        if (!barang.id) {
            return barang.text;
        }
        var $barang = $(
            '<div class="select2-result-barang">' +
                '<div class="select2-result-barang__kode"><strong>' + barang.kd_barang + '</strong></div>' +
                '<div class="select2-result-barang__info">' +
                    (barang.nama ? barang.nama : '') +
                '</div>' +
            '</div>'
        );
        return $barang;
    }

    // ### Event listener saat item dipilih
    $('#select_barang_edit').on('select2:select', function(e) {
        var data = e.params.data; // Data yang dipilih
        $('#kd_barang').val(data.kd_barang);
        get_barang_satuan_edit(data.kd_barang);
    });

    // ### Clear Selected Barangs
    $('#clear_select_edit').on('click', function() {
        $('#select_barang_edit').val(null).trigger('change'); // Kosongkan pilihan
        $('#kd_barang').val("").trigger('change');
        $('#nama_barang_edit').text('-').trigger('change');
        $('#unit_barang_edit').text('-').trigger('change');
        $('#select_barang_satuan_edit').empty().trigger('change');
        $('#select_barang_satuan_edit').append('<option value="">Pilih Satuan</option>').trigger('change');
        $('#harga_barang_edit').text('-').trigger('change');
    });

    $(document).on('select2:open', () => {
        document.querySelector('.select2-search__field').focus();
    });

    // === fungsi enter next di form ===
    // ### Mencegah submit ketika Enter kecuali pada tombol submit (fungsi enter jadi next)
    $('form').on('keydown', 'input, select', function(e) {
        if (e.keyCode === 13) {
            e.preventDefault(); // Mencegah form langsung submit
            // Dapatkan semua elemen fokusable (yang terlihat, tidak disabled, tidak readonly)
            var focusable = $('form').find('input, select, button')
                .filter(':visible:not([disabled]):not([readonly])');

            var nextIndex = focusable.index(this) + 1;

            if (nextIndex < focusable.length) {
                focusable.eq(nextIndex).focus();
            } else {
                // Submit form kalau sudah sampai elemen terakhir
                $('form').submit();
            }
        }
    });

    // ### Tangani default enter di select2
    $('#select_barang_edit').on('select2:select', function(e) {
        // Pindahkan fokus ke input selanjutnya ketika item dipilih
        var focusable = $('form').find('input, select, button').filter(':visible');
        var nextIndex = focusable.index(this) + 1;
        if (nextIndex < focusable.length) {
            focusable.eq(nextIndex).focus();
        }
    });
        // Mencegah Enter pada select2 agar tidak trigger submit
    $('#select_barang_edit').on('keypress', function(e) {
        if (e.keyCode == 13) {
            e.preventDefault(); // Mencegah select2 dari trigger submit ketika tekan Enter
        }
    });
        // === end of fungsi enter next di form ===
// ================================= End Of Select Barang ===========================================
// ======================= Trigger Select Satuan Barang When kd_barang change =============================
    function get_barang_satuan_edit(kd_barang){
        // console.log('test :' + kd_barang)
        if (kd_barang) {
            $.ajax({
                url: '{{ route("get_barang_satuan") }}',
                type: 'GET',
                data: { kd_barang: kd_barang },
                success: function (response) {
                    // alert(response[0].satuan);
                    $('#select_barang_satuan_edit').empty();
                    response.forEach(function (item) {
                        console.log('Satuan:', item.satuan);
                        $('#select_barang_satuan_edit').append(
                            `<option value="${item.satuan}">${item.satuan}</option>`
                        );
                    });
                    $('#nama_barang_edit').text(response[0].NAMA_BRG);
                    let data_harga = response[0].hj1;
                    $('#harga_barang_edit').text(format_ribuan(data_harga));
                    // menghapus nilani decimal dari dbase
                    let isi = response[0].isi.replace(/,/g, '');
                    let formatted_value = isi.replace(/\./g, ''); // Menghapus titik
                    let final_value = parseInt(formatted_value / 1000);
                    $('#unit_barang_edit').text(final_value);
                },
                error: function (xhr) {
                    console.error('Error:', xhr.responseText);
                }
            });
        } else {
            // Kosongkan opsi jika input kosong
            $('#select_barang_satuan_edit').empty();
            $('#select_barang_satuan_edit').append('<option value="">Pilih Satuan</option>');
        }
    };
// =================== End of Trigger Select Satuan Barang When kd_barang change ==========================
// ==================== Trigger Select Satuan Barang When select_barang_satuan change ============================
    $('#select_barang_satuan_edit').on('change', function() {
        const satuan_barang = $(this).val();
        const kd_barang = $('#kd_barang').val();
        if (satuan_barang) {
            $.ajax({
                url: '{{ route("get_barang_selected") }}',
                type: 'GET',
                data: { satuan_barang: satuan_barang, kd_barang: kd_barang },
                success: function(response) {
                    let data_harga = response.hj1;
                    $('#harga_barang_edit').text(format_ribuan(data_harga));
                    let isi = response.isi.replace(/,/g, '');
                    let formatted_value = isi.replace(/\./g, ''); // Menghapus titik
                    let final_value = parseInt(formatted_value / 1000);
                    $('#unit_barang_edit').text(final_value);
                },
                error: function(xhr) {
                    console.error('Error:', xhr.responseText);
                }
            });
        } else {
            console.log('Tidak ada pilihan yang dipilih.');
        }
    });

// ================= End of Trigger Select Satuan Barang When select_barang_satuan change ========================
// =================== Pajak PPN ==========================
    loadInputPajak();
    function loadInputPajak(){
        $.ajax({
            url: '{{ route('get_pajak') }}',
            type: 'GET',
            success: function(response) {
                let nilai_ppn = response.data.ppn;
                $('#ppn_trans_edit').val(nilai_ppn);
            },
            error: function() {
                $('#ppn_trans_edit').val('Error Loading');
            }
        });
    }
// =================== End Of Pajak PPN ==========================
// ================================= Input Barang To Table ===========================================
    // let grandTotal = 0;
    let cardIndex = 0; // untuk penomoran unik tiap card

    $('form').on('submit', function(event) {
        event.preventDefault();

        let kdBarang = $('#kd_barang').val();
        let namaBarang = $('#nama_barang_edit').text();
        let hargaBarangText = $('#harga_barang_edit').text();
        let hargaBarang = parseFloat(hapus_format(hargaBarangText)) || 0;
        let unitBarang = $('#unit_barang_edit').text();
        let satuanBarang = $('#select_barang_satuan_edit').val();
        let jumlahTrans = parseFloat($('#jumlah_trans_edit').val()) || 0;
        let diskonBarang = parseFloat($('#diskon_barang_edit').val()) || 0;
        let diskonBarangRp = parseFloat($('#diskon_barang_rp_edit').val()) || 0;
        let ppnBarang = parseFloat($('#ppn_trans_edit').val()) || 0;

        if (!kdBarang || !namaBarang || hargaBarang === 0 || jumlahTrans === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Submit Failed',
                text: 'Mohon lengkapi semua data sebelum submit!',
                showConfirmButton: false,
                timer: 2000
            });
            return;
        }

        let diskon_dalam_uang = (diskonBarang / 100) * hargaBarang;
        let harga_setelah_diskon = hargaBarang - diskon_dalam_uang - diskonBarangRp;
        let total = harga_setelah_diskon * jumlahTrans;

        // PPN dihitung mundur (seperti di Excel)
        let dpp = Math.round(total / (1 + ppnBarang / 100));
        let total_ppn = total - dpp;

        let gtotal = total;

        let user_role_diskon = @json(Auth::user()->roles);

        let cardHtml = `
            <div class="card mb-3 shadow-sm transaksi-card-edit" data-total="${gtotal}" data-index="${cardIndex}">
                <div class="card-body position-relative">
                    <div><strong>No:</strong> <span class="card-number">${cardIndex + 1}</span></div>
                    <div><strong>KD Barang:</strong> <span class="kd-barang">${kdBarang}</span></div>
                    <div><strong>Nama:</strong> <span class="nama-barang">${namaBarang}</span></div>
                    <div><strong>Harga:</strong> <span class="harga-barang">${format_ribuan(hargaBarang)}</span></div>
                    <div><strong>Isi:</strong> <span class="isi-barang">${unitBarang}</span></div>
                    <div><strong>Satuan:</strong> <span class="satuan-barang">${satuanBarang}</span></div>
                    <div><strong>Jumlah:</strong>
                        <span class="editable-jumlah" contenteditable="true">${jumlahTrans}</span>
                    </div>
                    <div><strong>Diskon:</strong>
                        <span class="editable-diskon" ${user_role_diskon === 'customer' ? '' : 'contenteditable="true"'}>${diskonBarang}</span>
                    </div>
                    <div><strong>Diskon Rp:</strong>
                        <span class="editable-diskon-rp" ${user_role_diskon === 'customer' ? '' : 'contenteditable="true"'}>${diskonBarangRp}</span>
                    </div>
                    <div><strong>PPN:</strong>
                        <span class="editable-ppn" ${user_role_diskon === 'admin' ? 'contenteditable="true"' : ''}>${ppnBarang}</span>
                    </div>
                    <div><strong>Total:</strong> <span class="total-text">${format_ribuan(gtotal)}</span></div>
                    <button class="btn btn-sm btn-danger mt-2 delete-card-edit"><i class="fa fa-trash"></i> Hapus</button>
                </div>
            </div>
        `;


        $('#transaksi_card_container_edit').append(cardHtml);
        let currentGrandTotal = parseFloat(hapus_format($('#grand_total_edit').text())) || 0;
        let newGrandTotal = currentGrandTotal + gtotal;
        $('#grand_total_edit').text(format_ribuan(newGrandTotal));
        cardIndex++;

        this.reset();
        $('#select_barang_edit').val(null).trigger('change');
        $('#nama_barang_edit').text('-');
        $('#harga_barang_edit').text('-');
        $('#unit_barang_edit').text('-');
        $('#select_barang_satuan_edit').html('<option value="">Pilih Satuan</option>');
        loadInputPajak();

        setTimeout(() => {
            $('#select_barang_edit').select2('open');
            document.querySelector('.select2-search__field').focus();
        }, 0);
    });
    function hapus_format(angka) {
        if (!angka) return 0;
        return angka.toString().replace(/[^\d.-]/g, '');
    }

    function format_ribuan(angka) {
        return angka.toLocaleString('id-ID');
    }

    // Update total saat diskon/jumlah diubah
    $('#transaksi_card_container_edit').on('input', '.editable-jumlah, .editable-diskon, .editable-diskon-rp, .editable-ppn', function() {
        let card = $(this).closest('.transaksi-card-edit');

        let harga = parseFloat(hapus_format(card.find('.harga-barang').text())) || 0;
        let jumlah = parseFloat(card.find('.editable-jumlah').text()) || 0;
        let diskon = parseFloat(card.find('.editable-diskon').text()) || 0;
        let diskonrp = parseFloat(card.find('.editable-diskon-rp').text()) || 0;
        let ppn = parseFloat(card.find('.editable-ppn').text()) || 0;

        // Hitung ulang
        let diskon_uang = (diskon / 100) * harga;
        let harga_setelah_diskon = harga - diskon_uang - diskonrp;
        let total = harga_setelah_diskon * jumlah;

        // Hitung DPP dan PPN
        let dpp = Math.round(total / (1 + ppn / 100));
        let total_ppn = total - dpp;

        let gtotal = total;
        card.find('.total-text').text(format_ribuan(gtotal));
        card.attr('data-total', gtotal);

        // ===> ini perbaikan penting <===
        recalculateGrandTotal();
    });

    function recalculateGrandTotal() {
        let newGrandTotal = 0;
        $('.transaksi-card-edit').each(function() {
            let cardTotal = parseFloat($(this).attr('data-total')) || 0;
            newGrandTotal += cardTotal;
        });
        grandTotal = newGrandTotal;
        $('#grand_total_edit').text(format_ribuan(grandTotal));
    }

    // Trigger ulang saat contenteditable kehilangan fokus
    $('#transaksi_card_container_edit').on('blur', '.editable-jumlah, .editable-diskon, .editable-diskon-rp, .editable-ppn', function () {
        $(this).trigger('input');
    });

    // Hapus Card
    // Event hapus card
    $('#transaksi_card_container_edit').on('click', '.delete-card-edit', function () {
        let card = $(this).closest('.card');
        let total = parseFloat(hapus_format(card.find('.total-text').text())) || 0;

        // Kurangi grand total
        let currentGrandTotal = parseFloat(hapus_format($('#grand_total_edit').text())) || 0;
        let newGrandTotal = currentGrandTotal - total;

        // Hapus elemen card
        card.slideUp(300, function () {
        card.remove();
        // Update grand total
        $('#grand_total_edit').text(format_ribuan(newGrandTotal));
        // Update ulang penomoran card (No:)
        $('#transaksi_card_container_edit .card').each(function(index) {
            $(this).find('.card-number').text(index + 1);
            $(this).attr('data-index', index);
        });
        // Update cardIndex biar tetap konsisten jika user tambah lagi
        cardIndex = $('#transaksi_card_container_edit .card').length;
        });
    });
// =============================== End Of Input Barang To Table =========================================
// =================================== Update Barang To DB ==============================================
    $('#save_table_transaksi_edit').on('click', function () {
        const kode_user = $("#kode_user_trans_edit").val();
        const products = [];
        let value_invo = $(this).val();
        let is_valid = true; // Untuk memeriksa validasi secara keseluruhan

        // Loop melalui setiap baris di tabel
        $('.transaksi-card-edit').each(function () {
            const card = $(this);

            const kd_barang = card.find('.kd-barang').text().trim();
            const nama = card.find('.nama-barang').text().trim();
            const harga = hapus_format(card.find('.harga-barang').text().trim());
            const unit = card.find('.isi-barang').text().trim();
            const satuan = card.find('.satuan-barang').text().trim();
            const jumlah = card.find('.editable-jumlah').text().trim();
            const diskon = card.find('.editable-diskon').text().trim();
            const diskon_rp = card.find('.editable-diskon-rp').text().trim();
            const ppn_trans = card.find('.editable-ppn').text().trim();
            const total = hapus_format(card.find('.total-text').text().trim());

            if (!jumlah || isNaN(jumlah) || parseFloat(jumlah) <= 0) {
                is_valid = false;
                Swal.fire({
                    icon: 'warning',
                    title: 'Jumlah Tidak Valid',
                    text: 'Jumlah harus berupa angka dan lebih besar dari 0!',
                    showConfirmButton: false,
                    timer: 2000
                });
                return false;
            }

            if (diskon === "" || isNaN(diskon)) {
                is_valid = false;
                Swal.fire({
                    icon: 'warning',
                    title: 'Diskon Tidak Valid',
                    text: 'Diskon harus berupa angka, bisa 0!',
                    showConfirmButton: false,
                    timer: 2000
                });
                return false;
            }

            // Validasi diskon: harus angka (boleh 0)
            if (diskon_rp === "" || diskon_rp.trim() === "" || isNaN(diskon_rp)) {
                is_valid = false;
                Swal.fire({
                    icon: 'warning',
                    title: 'Diskon Tidak Valid',
                    text: 'Diskon harus berupa angka, bisa 0, dan tidak boleh kosong!',
                    showConfirmButton: false,
                    timer: 2000 // Durasi tampil dalam milidetik
                });
                return false; // Hentikan loop jika tidak valid
            }

            if (ppn_trans === "" || ppn_trans.trim() === "" || isNaN(ppn_trans)) {
                is_valid = false;
                Swal.fire({
                    icon: 'warning',
                    title: 'PPN Tidak Valid',
                    text: 'PPN harus berupa angka, bisa 0, dan tidak boleh kosong!',
                    showConfirmButton: false,
                    timer: 2000 // Durasi tampil dalam milidetik
                });
                return false; // Hentikan loop jika tidak valid
            }

            if (kode_user === "") {
                is_valid = false;
                Swal.fire({
                    icon: 'warning',
                    title: 'Kode User Tidak Valid',
                    text: 'Kode User tidak boleh kosong!',
                    showConfirmButton: false,
                    timer: 2000
                });
                return false;
            }

            if (kd_barang) {
                products.push({
                    kd_barang,
                    nama,
                    harga: parseFloat(harga),
                    unit,
                    satuan,
                    jumlah: parseFloat(jumlah),
                    diskon: parseFloat(diskon),
                    diskon_rp: parseFloat(diskon_rp),
                    ppn_trans: parseFloat(ppn_trans),
                    total: parseFloat(total)
                });
            }
        });

        if (!is_valid) return;

        if (products.length > 0) {
            save_to_database_edit(products,value_invo,kode_user);
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Save Gagal',
                text: 'Tidak Ada Data Disimpan',
                showConfirmButton: false,
                timer: 2000
            });
        }
    });

    function save_to_database_edit(products,value_invo,kode_user) {
        $('#loading_modal').modal('show');
        setTimeout(function () {
            $.ajax({
                url: '{{ route('update_products') }}', // Endpoint Laravel
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'), // CSRF Token
                    products: products,
                    value_invo: value_invo,
                    kode_user: kode_user
                },
                success: function (response) {
                    $('#loading_modal').modal('hide');
                    // Swal.fire({
                    //     icon: 'success',
                    //     title: 'Update Successul',
                    //     text: 'Data Berhasil Diupdate',
                    //     showConfirmButton: false,
                    //     timer: 2000 // Durasi tampil dalam milidetik
                    // });

                    Swal.fire({
                        icon: 'success',
                        title: 'Update Successul',
                        text: 'Data Berhasil Diupdate dengan Nomor Invoice: ' + value_invo,
                        showConfirmButton: true,
                        confirmButtonText: 'OK',
                    }).then(() => {
                        return_table_edit();
                    });
                    // $('#transaksi_table_edit tbody').empty();
                    // $('#grand_total_edit').text(0);
                },
                error: function (xhr, status, error) {
                    $('#loading_modal').modal('hide');
                    console.log("Status: " + status);  // Menampilkan status HTTP
                    console.log("Error: " + error);  // Menampilkan error message
                    console.log(xhr.responseText);
                    Swal.fire({
                        icon: 'warning',
                        title: 'Save Failed',
                        text: xhr.responseText,
                        showConfirmButton: false,
                        timer: 2000 // Durasi tampil dalam milidetik
                    });
                    // alert('Failed to save data.');
                }
            });
        }, 1200);
    }

// ================================= End Of Update Barang To DB =========================================
// ==================================== Reset Tabel PO ============================================
    $('#reset_table_transaksi_edit').on('click', function(){
        let value_invo = $(this).val();
        grandTotal = 0;
        ajax_click_edit_button(value_invo);
    });
// ================================= End Of Reset Tabel PO =========================================
// ================================= Return Tabel PO =========================================
    $('#return_table_transaksi_edit').on('click', function(){
        return_table_edit();
    });

    function return_table_edit(){
        grandTotal = 0;
        $('#transaksi_table_edit tbody').empty();
        $('.master_transaksi_field').hide();
        $('#transaksi_table_edit_field').DataTable().destroy();
        $('#transaksi_table_edit_field tbody').empty();
        $('#master_table_edit_field').show();
        if(user_role_select == 'customer'){
            $("#transaksi_table_edit_field").show();
            $("#transaksi_table_edit_field_admin").hide();
            show_table_po();
        }else{
            $("#transaksi_table_edit_field").hide();
            $("#transaksi_table_edit_field_admin").show();
            show_table_po_admin();
        }
    }
// ============================== End Of Return Tabel PO =====================================
// ============================== Number Formating =====================================
    function hapus_format(angka) {
        // Menghapus titik pemisah ribuan
        return parseFloat(angka.replace(/\./g, ""));
    }

    function format_ribuan(angka) {
        // Pastikan angka memiliki 2 digit desimal
        let fixed = parseFloat(angka).toFixed(2); // Contoh: "1000.00"
        // Pisahkan integer dan desimal
        let parts = fixed.split("."); // Contoh: ["1000", "00"]
        // Format bagian integer dengan titik ribuan
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        // Gabungkan kembali integer dan desimal
        return parts.join(",");
    }
// ============================== End Of Number Formating =====================================
});
</script>
