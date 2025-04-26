<style>
    .table-responsive-set {
        overflow: visible;
    }
    .style-table {
        width: 100% !important;
    }

</style>
<div class="container mt-5">
    <div id="formtable_po">
        <h5>PO Approved Table</h5>
        <div class="button-container" style="display: flex; justify-content: flex-start; gap: 10px;">
            <button type="button" class="btn mt-2 mb-2" id="po_table_refresh" style="background-color: rgba(0, 123, 255, 0.5); border-color: rgba(0, 123, 255, 0.5); color: white;"><i class="fas fa-undo"> Refresh</i></button>
        </div>
        <div class="row mb-3">
            <div class="col-md-3 mt-2">
                <input type="date" id="startDateApp" class="form-control" placeholder="Start Date">
            </div>
            <div class="col-md-3 mt-2">
                <input type="date" id="endDateApp" class="form-control" placeholder="End Date">
            </div>
            <div class="col-md-3 mt-2">
                <input type="text" id="searchBoxApp" class="form-control" placeholder="Search">
            </div>
            <div class="col-md-3 mt-2">
                <button id="filterBtnApp" class="btn btn-primary">Filter</button>
            </div>
        </div>
        <div class="table-responsive-set">
            <table id="transaksi_table_approve_field" class="display table table-bordered mb-2 style-table" style="display: none;">
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
                <tfoot>
                    <tr>
                        <th colspan="3" style="text-align:right">Grand Total:</th>
                        <th id="grand_total_appr">Rp 0</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
            <table id="transaksi_table_approve_field_staff" class="display table table-bordered mb-2 style-table" style="display: none;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No PO</th>
                        <th>Customer Kode</th>
                        <th>Tgl PO</th>
                        <th>Total PO</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" style="text-align:right">Grand Total:</th>
                        <th id="grand_total_appr">Rp 0</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
            <table id="transaksi_table_approve_field_admin" class="display table table-bordered mb-2 style-table" style="display: none;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No PO</th>
                        <th>Customer Kode</th>
                        <th>Sales Kode</th>
                        <th>Tgl PO</th>
                        <th>Total PO</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" style="text-align:right">Grand Total:</th>
                        <th id="grand_total_appr">Rp 0</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="mt-3 table-container table-responsive" id="table_transaksi_po_app" style="display: none;">
        <h3>Detail PO Approved Table</h3>
        {{-- Inputan No PO --}}
        <input type="text" class="form-control mt-3 mb-1 col-lg-3" name="no_po_approve" id="no_po_approve" readonly>
        <table id="table_transaksi_list_po_app" class="display table table-bordered mb-2">
            <thead>
                <tr>
                    <th>No</th>
                    <th>KD Barang</th>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th>Isi</th>
                    <th>Satuan</th>
                    <th>Jumlah</th>
                    <th style="background-color: rgba(255, 0, 0, 0.3); color: rgba(255, 0, 0, 0.8);">Disetujui</th>
                    <th>Diskon %</th>
                    <th>Diskon Rp</th>
                    <th>PPN</th>
                    <th style="background-color: rgba(255, 0, 0, 0.3); color: rgba(255, 0, 0, 0.8);">Total Disetujui</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td colspan="11" class="text-right"><strong>Grand Total:</strong></td>
                    <td id="grand_total_po_app">0</td>
                    {{-- <td id="grand_total_edit_mirror">0</td> --}}
                </tr>
            </tfoot>
            <tbody>
                <!-- Data akan diisi oleh DataTables -->
            </tbody>
        </table>
        <div class="button-container" style="display: flex; justify-content: flex-end; gap: 10px;">
            <button type="button" class="btn btn-primary mt-2 mb-2" id="proses_table_transaksi_approved"><i class="fas fa-save"> Proses</i></button>
            <button type="button" class="btn btn-warning mt-2 mb-2" id="return_table_transaksi_approved"><i class="fas fa-undo"> List Menu</i></button>
        </div>
    </div>

</div>
{{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
<script>
$(document).ready(function() {
// ======================================== Show Table Approved ===============================================
    let user_role_select_app = @json(Auth::user()->roles);
    let user_kode_select_app = @json(Auth::user()->user_kode);
    let user_nama_select_app = @json(Auth::user()->name);
    let table; // ### Untuk fungsi refresh Pindahkan ke datatble ini siasi jika error
    // console.log('kode:' + user_role_select);
    // console.log('nama:' + user_nama_select);
    user_select_po_app();
    if (table) {
        table.state.clear();
        user_select_po_app();
    }

    function user_select_po_app(){
        if(user_role_select_app == 'customer'){
            $("#transaksi_table_approve_field").show();
            transaksi_table_approve_field();
        }else if(user_role_select_app == 'staff'){
            $("#transaksi_table_approve_field_staff").show();
            transaksi_table_approve_field_staff();
        }else{
            $("#transaksi_table_approve_field_admin").show();
            transaksi_table_approve_field_admin();
        }
    }

    function transaksi_table_approve_field() {
        if ($.fn.dataTable.isDataTable('#transaksi_table_approve_field')) {
            $('#transaksi_table_approve_field').DataTable().clear().destroy();
        }
        table = $('#transaksi_table_approve_field').DataTable({
            ajax: {
                url: '{{ route("filter_approved_invoice") }}',
                data: function(d) {
                    d.startDate = $('#startDateApp').val();
                    d.endDate = $('#endDateApp').val();
                    d.searchText = $('#searchBoxApp').val();
                },
                dataSrc: function(json) {
                    console.log('Server Response:', json);
                    return json.data;
                }
            },
            columns:[
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
                                <button class="btn btn-sm btn-primary edit-btn show_po_app" data-no-invoice="${row.no_invoice}" style="margin-right: 0;">
                                    <i class="fa fa-search"></i>
                                </button>
                                <button class="btn btn-sm btn-success print-btn" id="print_po_pdf_app" data-no-invoice="${row.no_invoice}">
                                    <i class="fa fa-print"></i>
                                </button>
                            </div>
                        `;
                    },
                },
            ],
            footerCallback: function (row, data, start, end, display) {
                let api = this.api();

                // Calculate the total for current page
                let pageTotal = api
                    .column(3)
                    .data()
                    .reduce((a, b) => parseFloat(a) + parseFloat(b), 0);

                // Update footer
                $(api.column(3).footer()).html(
                    'Rp ' + $.fn.dataTable.render.number(',', '.', 2, '').display(pageTotal)
                );
            },
            searching: false,
            paging: true,
            info: false,
            scrollY: '100vh',  // Menambahkan scrolling vertikal
            scrollCollapse: true,
            scrollX: true,
            stateSave: true, // untuk kembali ke halaman sebelumnya
            fixedHeader: {
                header: true,
                footer: false
            }
        });
            $('#filterBtnApp').on('click', function() {
                table.ajax.reload();
            });
    }
    function transaksi_table_approve_field_staff() {
        if ($.fn.dataTable.isDataTable('#transaksi_table_approve_field_staff')) {
            $('#transaksi_table_approve_field_staff').DataTable().clear().destroy();
        }
        table = $('#transaksi_table_approve_field_staff').DataTable({
            ajax: {
                url: '{{ route("filter_approved_invoice") }}',
                data: function(d) {
                    d.startDate = $('#startDateApp').val();
                    d.endDate = $('#endDateApp').val();
                    d.searchText = $('#searchBoxApp').val();
                },
                dataSrc: function(json) {
                    console.log('Server Response:', json);
                    return json.data;
                }
            },
            columns:[
                {
                    data: null,
                    name: 'no',
                    render: (data, type, row, meta) => meta.row + 1, // Nomor otomatis
                },
                { data: 'no_invoice', name: 'no_invoice' },
                { data: 'user_kode', name: 'user_kode' },
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
                                <button class="btn btn-sm btn-primary edit-btn show_po_app" data-no-invoice="${row.no_invoice}" style="margin-right: 0;">
                                    <i class="fa fa-search"></i>
                                </button>
                                <button class="btn btn-sm btn-success print-btn" id="print_po_pdf_app" data-no-invoice="${row.no_invoice}">
                                    <i class="fa fa-print"></i>
                                </button>
                            </div>
                        `;
                    },
                },
            ],
            footerCallback: function (row, data, start, end, display) {
                let api = this.api();

                // Calculate the total for current page
                let pageTotal = api
                    .column(4)
                    .data()
                    .reduce((a, b) => parseFloat(a) + parseFloat(b), 0);

                // Update footer
                $(api.column(4).footer()).html(
                    'Rp ' + $.fn.dataTable.render.number(',', '.', 2, '').display(pageTotal)
                );
            },
            searching: false,
            paging: true,
            info: false,
            scrollY: '100vh',  // Menambahkan scrolling vertikal
            scrollCollapse: true,
            scrollX: true,
            stateSave: true, // untuk kembali ke halaman sebelumnya
            fixedHeader: {
                header: true,
                footer: false
            }
        });
            $('#filterBtnApp').on('click', function() {
                table.ajax.reload();
            });
    }
    function transaksi_table_approve_field_admin() {
        if ($.fn.dataTable.isDataTable('#transaksi_table_approve_field_admin')) {
            $('#transaksi_table_approve_field_admin').DataTable().clear().destroy();
        }
        table = $('#transaksi_table_approve_field_admin').DataTable({
            ajax: {
                url: '{{ route("filter_approved_invoice") }}',
                data: function(d) {
                    d.startDate = $('#startDateApp').val();
                    d.endDate = $('#endDateApp').val();
                    d.searchText = $('#searchBoxApp').val();
                },
                dataSrc: function(json) {
                    console.log('Server Response:', json);
                    return json.data;
                }
            },
            columns:[
                {
                    data: null,
                    name: 'no',
                    render: (data, type, row, meta) => meta.row + 1, // Nomor otomatis
                },
                { data: 'no_invoice', name: 'no_invoice' },
                { data: 'user_kode', name: 'user_kode' },
                { data: 'user_id', name: 'user_id' },
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
                                <button class="btn btn-sm btn-primary edit-btn show_po_app" data-no-invoice="${row.no_invoice}" style="margin-right: 0;">
                                    <i class="fa fa-search"></i>
                                </button>
                                <button class="btn btn-sm btn-success print-btn" id="print_po_pdf_app" data-no-invoice="${row.no_invoice}">
                                    <i class="fa fa-print"></i>
                                </button>
                            </div>
                        `;
                    },
                },
            ],
            footerCallback: function (row, data, start, end, display) {
                let api = this.api();

                // Calculate the total for current page
                let pageTotal = api
                    .column(5)
                    .data()
                    .reduce((a, b) => parseFloat(a) + parseFloat(b), 0);

                // Update footer
                $(api.column(5).footer()).html(
                    'Rp ' + $.fn.dataTable.render.number(',', '.', 2, '').display(pageTotal)
                );
            },
            searching: false,
            paging: true,
            info: false,
            scrollY: '100vh',  // Menambahkan scrolling vertikal
            scrollCollapse: true,
            scrollX: true,
            stateSave: true, // untuk kembali ke halaman sebelumnya
            fixedHeader: {
                header: true,
                footer: false
            }
        });
            $('#filterBtnApp').on('click', function() {
                table.ajax.reload();
            });
    }
// ===================================== End Of Show Table Approved ==============================================
// ======================================== Refresh Table =================================================
$(document).on('click', '#po_table_refresh', function() {
    if (table) {
        table.state.clear();  // Hapus state
        // table.destroy();      // Hancurkan tabel
        user_select_po_app(); // Panggil ulang fungsi untuk memuat ulang tabel yang sesuai
    }
});
// ===================================== End Of Refresh Table ==============================================
// ===================================== Show PO Detail Approved ==============================================
    $(document).on('click', '.show_po_app', function() {
        let no_po = $(this).data('no-invoice');
        $.ajax({
            url: '{{ route("get_po_approved_det") }}',
            type: 'GET',
            data: {
                no_invoice: no_po
            },
            success: function(response) {
                var tableBody = $('#table_transaksi_list_po_app tbody');
                tableBody.empty();

                var grandTotal = 0;
                $('#no_po_approve').val(response.data[0].no_invoice);
                console.log('test :' + response.data[0].no_invoice);

                $.each(response.data, function(index, item) {
                    var row = $('<tr></tr>');

                    row.append('<td>' + (index + 1) + '</td>');
                    row.append('<td>' + item.kd_brg + '</td>');
                    row.append('<td>' + item.nama_brg + '</td>');
                    row.append('<td>' + format_ribuan(item.harga) + '</td>');
                    row.append('<td>' + item.qty_unit + '</td>');
                    row.append('<td>' + item.satuan + '</td>');
                    row.append('<td>' + item.qty_order + '</td>');
                    row.append('<td>' + item.qty_sup + '</td>');
                    row.append('<td>' + item.disc + '</td>');
                    row.append('<td>' + item.ndisc + '</td>');
                    row.append('<td>' + item.ppn + '</td>');
                    row.append('<td>' + format_ribuan(item.total) + '</td>');

                    tableBody.append(row);

                    // grandTotal += parseFloat(item.total);
                });

                $('#grand_total_po_app').text(format_ribuan(response.grand_total));
                $("#formtable_po").hide();
                $("#table_transaksi_po_app").show();
            },
            error: function(xhr, status, error) {
                console.error("Terjadi kesalahan:", error);
            }
        });
    });

// ================================== End Of Show PO Detail Approved ===========================================
// ================================= Submit Barang To DB =========================================
$('#proses_table_transaksi_approved').on('click', function () {
        const no_po_approve = $("#no_po_approve").val();
        const products = [];
        let is_valid = true; // Untuk memeriksa validasi secara keseluruhan

        // Loop melalui setiap baris di tabel
        $('#table_transaksi_list_po_app tbody tr').each(function () {
            const kd_barang = $(this).find('td:eq(1)').text(); // KD Barang
            const nama = $(this).find('td:eq(2)').text();      // Nama Barang
            const harga = $(this).find('td:eq(3)').text();     // Harga Barang
            const unit = $(this).find('td:eq(4)').text();      // Unit Barang
            const satuan = $(this).find('td:eq(5)').text();    // Satuan Barang
            const jumlah = $(this).find('td:eq(7)').text();    // Jumlah (editable)
            const diskon = $(this).find('td:eq(8)').text();    // Diskon (editable)
            const diskon_rp = $(this).find('td:eq(9)').text();
            const ppn_trans = $(this).find('td:eq(10)').text();
            const total_text = $(this).find('td:eq(11)').text();     // Total
            const total = hapus_format(total_text);
            // Validasi jumlah: tidak boleh kosong, harus angka, dan lebih besar dari 0
            if (!jumlah || isNaN(jumlah) || parseFloat(jumlah) <= 0) {
                is_valid = false;
                Swal.fire({
                    icon: 'warning',
                    title: 'Jumlah Tidak Valid',
                    text: 'Jumlah harus berupa angka dan lebih besar dari 0 di salah satu baris!',
                    showConfirmButton: false,
                    timer: 2000 // Durasi tampil dalam milidetik
                });
                return false; // Hentikan loop jika tidak valid
            }

            // Validasi diskon: harus angka (boleh 0)
            if (diskon === "" || diskon.trim() === "" || isNaN(diskon)) {
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

            // Masukkan ke array hanya jika KD Barang ada
            if (kd_barang) {
                products.push({
                    kd_barang,
                    nama,
                    harga: parseFloat(harga),
                    unit: parseFloat(unit),
                    satuan,
                    jumlah: parseFloat(jumlah), // Pastikan formatnya angka
                    diskon: parseFloat(diskon), // Pastikan formatnya angka
                    diskon_rp: parseFloat(diskon_rp),
                    ppn_trans: parseFloat(ppn_trans),
                    total: parseFloat(total) // Bersihkan format jika ada titik
                });
            }
        });

        // Pastikan validasi lolos sebelum mengirim data ke server
        if (!is_valid) {
            return; // Hentikan eksekusi jika validasi gagal
        }

        // Kirim data ke server jika ada produk
        if (products.length > 0) {
            save_to_database(products,no_po_approve);
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Save Failed',
                text: 'Tidak Ada Data Disimpan',
                showConfirmButton: false,
                timer: 2000 // Durasi tampil dalam milidetik
            });
        }
    });

    function save_to_database(products,no_po_approve) {
        $('#loading_modal').modal('show');
        setTimeout(function () {
            $.ajax({
                url: '{{ route('save_products_approved') }}',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    products: products,
                    no_po_approve:no_po_approve
                },
                success: function (response) {
                    $('#loading_modal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Save Successful',
                        text: 'Data Berhasil Disimpan dengan Nomor Invoice: ' + response.invoice_number,
                        showConfirmButton: true, // Tampilkan tombol OK
                        confirmButtonText: 'OK', // Ubah teks tombol jika diperlukan
                        showDenyButton: true, // Show Deny button for Print PDF
                        denyButtonText: 'Print PDF',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Callback setelah tombol OK ditekan
                            success_call();
                        }   else if (result.isDenied) {
                            window.open('{{ route("generate_pdf", ":invoice_number") }}'.replace(':invoice_number', response.invoice_number), '_blank');
                                success_call();
                            }
                    });
                },
                error: function (xhr, status, error) {
                    console.log("Status: " + status);  // Menampilkan status HTTP
                    console.log("Error: " + error);  // Menampilkan error message
                    console.log(xhr.responseText);
                    $('#loading_modal').modal('hide');
                    alert('Failed to save data.');
                }
            });
        }, 1200);
    }
    function success_call(){
        $('#table_transaksi_list_po_app tbody').empty();
        // ### Redirect Hal Edit
        $.ajax({
            url: '{{ route('index_edit_transaksi') }}',
            type: 'GET',
            success: function(response) {
                $('.master-page').html(response);
            },
            error: function() {
                $('.master-page').html('<p>Error loading form.</p>');
            }
        });
    }
// ================================= End Of Submit Barang To DB =========================================
// ==================================== Click Print Button ==============================================
$(document).on('click', '#print_po_pdf_app', function() {
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
                window.open('{{ route("generate_pdf_approved", ":invoice_number") }}'.replace(':invoice_number', invoice_number), '_blank');
            }
        });
    });
// ================================= End Of Click Print Button ===========================================
// ================================= Return Tabel PO =========================================
    $('#return_table_transaksi_approved').on('click', function(){
        $("#formtable_po").show();
        // grandTotal = 0;
        $("#table_transaksi_po_app").hide();
        user_select_po_app();
    });
// ================================= End Of Return Tabel PO =========================================
// ================================== Format Angka ===========================================
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
// ================================== End Of Format Angka ===========================================
});
</script>
