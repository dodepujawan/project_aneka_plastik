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
        <h5>Faktur Online</h5>
        <div class="button-container" style="display: flex; justify-content: flex-start; gap: 10px;">
            <button type="button" class="btn mt-2 mb-2" id="po_table_refresh_faktur" style="background-color: rgba(0, 123, 255, 0.5); border-color: rgba(0, 123, 255, 0.5); color: white;"><i class="fas fa-undo"> Refresh</i></button>
        </div>
        <div class="row mb-3">
            <div class="col-md-3 mt-2">
                <input type="date" id="startDateAppFaktur" class="form-control" placeholder="Start Date">
            </div>
            <div class="col-md-3 mt-2">
                <input type="date" id="endDateAppFaktur" class="form-control" placeholder="End Date">
            </div>
            <div class="col-md-3 mt-2">
                <input type="text" id="searchBoxAppFaktur" class="form-control" placeholder="Search">
            </div>
            <div class="col-md-3 mt-2">
                <button id="filterBtnAppFaktur" class="btn btn-primary">Filter</button>
            </div>
        </div>
        <div class="table-responsive-set">
            <table id="transaksi_table_faktur_field" class="display table table-bordered mb-2 style-table" style="display: none;">
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
                        <th id="grand_total_appr_faktur">Rp 0</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
            <table id="transaksi_table_faktur_field_staff" class="display table table-bordered mb-2 style-table" style="display: none;">
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
                        <th id="grand_total_appr_faktur">Rp 0</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
            <table id="transaksi_table_faktur_field_admin" class="display table table-bordered mb-2 style-table" style="display: none;">
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
                        <th id="grand_total_appr_faktur">Rp 0</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="mt-3 table-container table-responsive" id="table_transaksi_po_app_faktur" style="display: none;">
        <h3>Detail Faktur Table</h3>
        {{-- Inputan No PO --}}
        <input type="text" class="form-control mt-3 mb-1 col-lg-3" name="no_faktur" id="no_faktur" readonly>
        <table id="table_transaksi_list_po_app_faktur" class="display table table-bordered mb-2">
            <thead>
                <tr>
                    <th>No</th>
                    <th>KD Barang</th>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th>Isi</th>
                    <th>Satuan</th>
                    <th>Jumlah</th>
                    <th>Diskon %</th>
                    <th>Diskon Rp</th>
                    <th>PPN</th>
                    <th style="background-color: rgba(255, 0, 0, 0.3); color: rgba(255, 0, 0, 0.8);">Total</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td colspan="10" class="text-right"><strong>Grand Total:</strong></td>
                    <td id="grand_total_faktur">0</td>
                    {{-- <td id="grand_total_edit_mirror">0</td> --}}
                </tr>
            </tfoot>
            <tbody>
                <!-- Data akan diisi oleh DataTables -->
            </tbody>
        </table>
        <div class="button-container" style="display: flex; justify-content: flex-end; gap: 10px;">
            <button type="button" class="btn btn-primary mt-2 mb-2" id="proses_table_faktur"><i class="fas fa-save"> Proses</i></button>
            <button type="button" class="btn btn-warning mt-2 mb-2" id="return_table_faktur"><i class="fas fa-undo"> List Menu</i></button>
        </div>
    </div>

</div>
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
            $("#transaksi_table_faktur_field").show();
            transaksi_table_faktur_field();
        }else if(user_role_select_app == 'staff'){
            $("#transaksi_table_faktur_field_staff").show();
            transaksi_table_faktur_field_staff();
        }else{
            $("#transaksi_table_faktur_field_admin").show();
            transaksi_table_faktur_field_admin();
        }
    }

    function transaksi_table_faktur_field() {
        if ($.fn.dataTable.isDataTable('#transaksi_table_faktur_field')) {
            $('#transaksi_table_faktur_field').DataTable().clear().destroy();
        }
        table = $('#transaksi_table_faktur_field').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("filter_no_faktur") }}',
                data: function(d) {
                    d.startDate = $('#startDateAppFaktur').val();
                    d.endDate = $('#endDateAppFaktur').val();
                    d.searchText = $('#searchBoxAppFaktur').val();
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
                                <button class="btn btn-sm btn-primary edit-btn show_po_app_success" data-no-invoice="${row.no_invoice}" style="margin-right: 0;">
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
            $('#filterBtnAppFaktur').on('click', function() {
                table.ajax.reload();
            });
    }
    function transaksi_table_faktur_field_staff() {
        if ($.fn.dataTable.isDataTable('#transaksi_table_faktur_field_staff')) {
            $('#transaksi_table_faktur_field_staff').DataTable().clear().destroy();
        }
        table = $('#transaksi_table_faktur_field_staff').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("filter_success_invoice") }}',
                data: function(d) {
                    d.startDate = $('#startDateAppFaktur').val();
                    d.endDate = $('#endDateAppFaktur').val();
                    d.searchText = $('#searchBoxAppFaktur').val();
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
                                <button class="btn btn-sm btn-primary edit-btn show_po_app_success" data-no-invoice="${row.no_invoice}" style="margin-right: 0;">
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
            $('#filterBtnAppFaktur').on('click', function() {
                table.ajax.reload();
            });
    }
    function transaksi_table_faktur_field_admin() {
        if ($.fn.dataTable.isDataTable('#transaksi_table_faktur_field_admin')) {
            $('#transaksi_table_faktur_field_admin').DataTable().clear().destroy();
        }
        table = $('#transaksi_table_faktur_field_admin').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("filter_success_invoice") }}',
                data: function(d) {
                    d.startDate = $('#startDateAppFaktur').val();
                    d.endDate = $('#endDateAppFaktur').val();
                    d.searchText = $('#searchBoxAppFaktur').val();
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
                                <button class="btn btn-sm btn-primary edit-btn show_po_app_success" data-no-invoice="${row.no_invoice}" style="margin-right: 0;">
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
            $('#filterBtnAppFaktur').on('click', function() {
                table.ajax.reload();
            });
    }
// ===================================== End Of Show Table Approved ==============================================
// ======================================== Refresh Table =================================================
$(document).on('click', '#po_table_refresh_faktur', function() {
    if (table) {
        table.state.clear();  // Hapus state
        // table.destroy();      // Hancurkan tabel
        user_select_po_app(); // Panggil ulang fungsi untuk memuat ulang tabel yang sesuai
    }
});
// ===================================== End Of Refresh Table ==============================================
// ===================================== Show PO Detail Approved ==============================================
    $(document).on('click', '.show_po_app_success', function() {
        let no_po = $(this).data('no-invoice');
        $.ajax({
            url: '{{ route("get_po_success_det") }}',
            type: 'GET',
            data: {
                no_invoice: no_po
            },
            success: function(response) {
                var tableBody = $('#table_transaksi_list_po_app_faktur tbody');
                tableBody.empty();

                var grandTotal = 0;
                $('#no_faktur').val(response.data[0].no_invoice);
                console.log('test :' + response.data[0].no_invoice);

                $.each(response.data, function(index, item) {
                    var row = $('<tr></tr>');

                    row.append('<td>' + (index + 1) + '</td>');
                    row.append('<td>' + item.kd_brg + '</td>');
                    row.append('<td>' + item.nama_brg + '</td>');
                    row.append('<td>' + format_ribuan(item.harga) + '</td>');
                    row.append('<td>' + item.qty_unit + '</td>');
                    row.append('<td>' + item.satuan + '</td>');
                    row.append('<td>' + item.qty_sup + '</td>');
                    row.append('<td>' + item.disc + '</td>');
                    row.append('<td>' + item.ndisc + '</td>');
                    row.append('<td>' + item.ppn + '</td>');
                    row.append('<td>' + format_ribuan(item.total) + '</td>');

                    tableBody.append(row);

                    // grandTotal += parseFloat(item.total);
                });

                $('#grand_total_faktur').text(format_ribuan(response.grand_total));
                $("#formtable_po").hide();
                $("#table_transaksi_po_app_faktur").show();
            },
            error: function(xhr, status, error) {
                console.error("Terjadi kesalahan:", error);
            }
        });
    });

// ================================== End Of Show PO Detail Approved ===========================================
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
    $('#return_table_faktur').on('click', function(){
        $("#formtable_po").show();
        // grandTotal = 0;
        $("#table_transaksi_po_app_faktur").hide();
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
