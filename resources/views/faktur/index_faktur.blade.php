<style>
    .table-responsive-set {
        overflow: visible;
    }
    .style-table {
        width: 100% !important;
    }

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
                        <th>No Faktur</th>
                        <th>Tgl Faktur</th>
                        <th>No Invoice</th>
                        <th>Total PFakturs</th>
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
            <table id="transaksi_table_faktur_field_staff" class="display table table-bordered mb-2 style-table" style="display: none;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Faktur</th>
                        <th>Customer Kode</th>
                        <th>Tgl Faktur</th>
                        <th>No Invoice</th>
                        <th>Total Faktur</th>
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
            <table id="transaksi_table_faktur_field_admin" class="display table table-bordered mb-2 style-table" style="display: none;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Faktur</th>
                        <th>Customer Kode</th>
                        <th>Sales Kode</th>
                        <th>Tgl Faktur</th>
                        <th>No Invoice</th>
                        <th>Total Faktur</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="6" style="text-align:right">Grand Total:</th>
                        <th id="grand_total_appr_faktur">Rp 0</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="master_faktur_field" style="display: none">
        <div class="col-lg-12 alert alert-warning" id="mssakit_warning" role="alert" style="margin-top: 10px; opacity: 0.8;">
            <h5 style="font-weight: bold;">Mode Edit Data Faktur</h5>
        </div>
        <div class="container master_customer_select_faktur">
            <div class="row">
                <div class="form-group col-lg-4 col-md-12 col-sm-12 mb-3">
                    <input type="text" name="kode_user_trans_faktur" id="kode_user_trans_faktur" class="form-control" placeholder="Kode Customer" required="" readonly>
                </div>
                <div class="form-group col-lg-4 col-md-12 col-sm-12 mb-3">
                    <input type="text" name="nama_user_trans_faktur" id="nama_user_trans_faktur" class="form-control" placeholder="Nama Customer" required="" readonly>
                </div>
                <div class="form-group col-lg-4 col-md-12 col-sm-12 mb-3">
                    <select name="select_user_trans_faktur" id="select_user_trans_faktur" class="form-control">
                        <option value="">Pilih Customer</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="container master_transaksi_faktur">
            <div class="product-info">
                <input type="hidden" value="" id="kd_barang" readonly>

                <!-- Informasi Barang -->
                <div class="row">
                    <div class="col-md-3 info-item">
                        <h5>Nama: <span id="master_transaksi_faktur">-</span></h5>
                    </div>
                    <div class="col-md-3 info-item">
                        <h5>Harga: <span id="harga_barang_faktur">-</span></h5>
                    </div>
                    <div class="col-md-3 info-item">
                        <h5>Isi: <span id="unit_barang_faktur">-</span></h5>
                    </div>
                    <div class="col-md-3 info-item">
                        <h5>Stok: <span id="stok_barang_faktur">-</span></h5>
                    </div>
                </div>
            </div>
        </div>
        {{-- Inputan No PO --}}
        <div class="container">
            <input type="text" class="form-control mt-3 col-lg-3" name="no_po_faktur" id="no_po_faktur" readonly>
        </div>
        {{-- End Of Inputan No PO --}}
        <form action="" class="mt-3">
        <div class="row">
            <div class="col-lg-4 col-md-12 col-sm-12 mb-3">
                <div class="d-flex align-items-center">
                    <select name="select_gudang_edit" id="select_gudang_edit" class="form-control">
                        <option></option>
                        <!-- Options untuk select dropdown -->
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-12 mb-3">
                <div class="d-flex align-items-center">
                <button type="button" id="clear_select_faktur" class="btn btn-secondary btn-sm me-2 mr-2">
                    <i class="fa fa-eraser" aria-hidden="true"></i>
                </button>
                <select name="select_barang_faktur" id="select_barang_faktur" class="form-control">
                    <option></option>
                    <!-- Options untuk select dropdown -->
                </select>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                <select name="select_barang_satuan_faktur" id="select_barang_satuan_faktur" class="form-control">
                    <option value="">Pilih Satuan</option>
                </select>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                <input type="number" id="jumlah_trans_faktur" class="form-control" placeholder="Jumlah barang">
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                <div class="d-flex align-items-center">
                    @php
                        $user = Auth::user();
                        $allowed_roles = ['customer'];
                        $is_customer = in_array($user->roles, $allowed_roles);
                    @endphp
                <input type="number" id="diskon_barang_faktur" class="form-control" placeholder="Disc %" {{ $is_customer ? 'readonly' : '' }}>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                <div class="d-flex align-items-center">
                    @php
                        $user = Auth::user();
                        $allowed_roles = ['customer'];
                        $is_customer = in_array($user->roles, $allowed_roles);
                    @endphp
                <input type="number" id="diskon_barang_rp_faktur" class="form-control" placeholder="Diskon RP" {{ $is_customer ? 'readonly' : '' }}>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                <div class="d-flex align-items-center" style="gap: 10px;">
                    <label for="ppn_trans_faktur" class="mb-0">PPN</label>
                    <input type="number" id="ppn_trans_faktur" class="form-control form-control-md" style="width: 150px;" placeholder="0" disabled>
                    <button type="submit" class="btn btn-success btn-md">
                        {{-- <i class="fa fa-check" aria-hidden="true"></i> --}}Simpan
                    </button>
                </div>
            </div>
        </div>
        </form>
        <div class="mt-3 table-container table-responsive">
            <table id="transaksi_table_faktur" class="display table table-bordered mb-2">
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
                        <th>Total</th>
                        <th>del</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <td colspan="10" class="text-right"><strong>Grand Total:</strong></td>
                        <td id="grand_total_faktur">0</td>
                        <tr>
                            <td colspan="10" class="text-right"><strong>DPP :</strong></td>
                            <td id="grand_total_dpp_faktur">0</td>
                        </tr>
                        <tr>
                            <td colspan="10" class="text-right"><strong>PPN :</strong></td>
                            <td id="grand_total_ppn_faktur">0</td>
                        </tr>
                        {{-- <td id="grand_total_faktur_mirror">0</td> --}}
                    </tr>
                </tfoot>
                <tbody>
                    <!-- Data akan diisi oleh DataTables -->
                </tbody>
            </table>
        </div>
        <div class="button-container" style="display: flex; justify-content: flex-end; gap: 10px;">
            <button type="submit" class="btn btn-success mt-2 mb-2" id="save_table_transaksi_faktur"><i class="fas fa-print"> Struk</i></button>
            <button type="submit" class="btn btn-danger mt-2 mb-2" id="delete_table_transaksi_faktur"><i class="fas fa-trash"> Delete</i></button>
            <button type="submit" class="btn btn-info mt-2 mb-2" id="reset_table_transaksi_faktur"><i class="fas fa-sync-alt"> Reset</i></button>
        </div>
    </div>
</div>
<!-- Modal Pembayaran Struk-->
<div class="modal fade" id="payment_modal" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Pembayaran</h5>
      </div>
      <div class="modal-body">

        <!-- Total Harga -->
        <div class="form-group">
          <label>Total Harga</label>
          <input type="text" id="total_harga_modal" class="form-control" readonly>
        </div>

        <!-- Metode Pembayaran -->
        <div class="form-group">
          <label>Metode Pembayaran</label>
          <select id="paymentMethod" class="form-control">
            <option value="">-- Pilih --</option>
            <option value="cash">Cash</option>
            <option value="transfer">Transfer</option>
            <option value="bon">Bon</option>
          </select>
        </div>

        <!-- Input jika Cash -->
        <div id="cashSection" class="d-none">
          <div class="form-group">
            <label>Nominal Uang</label>
            <input type="number" id="cashAmount" class="form-control">
          </div>
          <div class="form-group">
            <label>Kembalian</label>
            <input type="text" id="changeAmount" class="form-control" readonly>
          </div>
        </div>

        <!-- Input jika Transfer -->
        <div id="transferSection" class="d-none">
          <div class="form-group">
            <label>Nama Bank</label>
            <input type="text" id="bankName" class="form-control">
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" id="cetak_faktur" class="btn btn-success">Cetak</button>
        <button type="button" class="btn btn-secondary" id="cancel_btn_cetak" data-dismiss="modal">Batal</button>
      </div>
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
            select_user_list_edit();
        }else{
            $("#transaksi_table_faktur_field_admin").show();
            transaksi_table_faktur_field_admin();
            select_user_list_edit();
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
                { data: 'no_faktur', name: 'no_faktur' },
                { data: 'created_at', name: 'created_at' },
                { data: 'history_inv', name: 'history_inv' },
                {
                    data: 'total',
                    name: 'total',
                    render: $.fn.dataTable.render.number(',', '.', 2, 'Rp ') // Format angka jadi Rupiah
                },
                {
                    data: 'no_faktur',
                    orderable: false,
                    render: (data, type, row) => {
                        return `
                            <div style="display: flex; justify-content: center; gap: 0.5rem;">
                                <button class="btn btn-sm btn-primary edit-btn show_faktur_app_success" data-no-invoice="${row.no_faktur}" style="margin-right: 0;">
                                    <i class="fa fa-search"></i>
                                </button>
                                <button class="btn btn-sm btn-success print-btn" id="print_faktur_pdf_app" data-no-invoice="${row.no_faktur}">
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
    function transaksi_table_faktur_field_staff() {
        if ($.fn.dataTable.isDataTable('#transaksi_table_faktur_field_staff')) {
            $('#transaksi_table_faktur_field_staff').DataTable().clear().destroy();
        }
        table = $('#transaksi_table_faktur_field_staff').DataTable({
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
                { data: 'no_faktur', name: 'no_faktur' },
                { data: 'user_kode', name: 'user_kode' },
                { data: 'created_at', name: 'created_at' },
                { data: 'history_inv', name: 'history_inv' },
                {
                    data: 'total',
                    name: 'total',
                    render: $.fn.dataTable.render.number(',', '.', 2, 'Rp ') // Format angka jadi Rupiah
                },
                {
                    data: 'no_faktur',
                    orderable: false,
                    render: (data, type, row) => {
                        return `
                            <div style="display: flex; justify-content: center; gap: 0.5rem;">
                                <button class="btn btn-sm btn-primary edit-btn show_faktur_app_success" data-no-invoice="${row.no_faktur}" style="margin-right: 0;">
                                    <i class="fa fa-search"></i>
                                </button>
                                <button class="btn btn-sm btn-success print-btn" id="print_faktur_pdf_app" data-no-invoice="${row.no_faktur}">
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
    function transaksi_table_faktur_field_admin() {
        if ($.fn.dataTable.isDataTable('#transaksi_table_faktur_field_admin')) {
            $('#transaksi_table_faktur_field_admin').DataTable().clear().destroy();
        }
        table = $('#transaksi_table_faktur_field_admin').DataTable({
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
                { data: 'no_faktur', name: 'no_faktur' },
                { data: 'user_kode', name: 'user_kode' },
                { data: 'user_id', name: 'user_id' },
                { data: 'created_at', name: 'created_at' },
                { data: 'history_inv', name: 'history_inv' },
                {
                    data: 'total',
                    name: 'total',
                    render: $.fn.dataTable.render.number(',', '.', 2, 'Rp ') // Format angka jadi Rupiah
                },
                {
                    data: 'no_faktur',
                    orderable: false,
                    render: (data, type, row) => {
                        return `
                            <div style="display: flex; justify-content: center; gap: 0.5rem;">
                                <button class="btn btn-sm btn-primary edit-btn show_faktur_app_success" data-no-invoice="${row.no_faktur}" style="margin-right: 0;">
                                    <i class="fa fa-search"></i>
                                </button>
                                <button class="btn btn-sm btn-success print-btn" id="print_faktur_pdf_app" data-no-invoice="${row.no_faktur}">
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
                    .column(6)
                    .data()
                    .reduce((a, b) => parseFloat(a) + parseFloat(b), 0);

                // Update footer
                $(api.column(6).footer()).html(
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
    let grandTotal = 0;
    let grandTotalDpp = 0;
    let grandTotalPpn = 0;
    $(document).on('click', '.show_faktur_app_success', function() {
        let no_invoice = $(this).data('no-invoice');
        ajax_click_edit_button(no_invoice);
    });

    function ajax_click_edit_button(no_invoice){
            $.ajax({
                url: '{{ route('get_faktur_to_table') }}', // Ganti dengan route yang sesuai
                type: 'GET',
                data: {
                    no_invoice: no_invoice // Mengirimkan no_invoice ke server
                },
                success: function (response) {
                    $('#transaksi_table_faktur tbody').empty();
                    const get_no_nvoice = response.data[0].no_faktur;
                    $('#no_po_faktur').val('Faktur No : '+get_no_nvoice);
                    $('#save_table_transaksi_faktur').val(get_no_nvoice);
                    $('#reset_table_transaksi_faktur').val(get_no_nvoice);
                    $('#delete_table_transaksi_faktur').val(get_no_nvoice);
                    $("#kode_user_trans_faktur").val(response.data[0].user_kode);
                    $("#nama_user_trans_faktur").val(response.data[0].nama_cust);
                    let user_role_diskon = @json(Auth::user()->roles);
                    // let grandTotal = 0;

                    response.data.forEach((item, index) => {
                        let total = item.total; // Menggunakan nilai total yang sudah ada dari database
                        grandTotal += total;// Menambahkan ke grand total
                        let total_dpp = item.dpp;
                        grandTotalDpp += total_dpp;
                        let total_ppn = item.rppn;
                        grandTotalPpn += total_ppn;
                        // console.log('TEST: ' + item.kd_brg); // Cek item.kd_brg
                        let total_order = format_ribuan(item.total);
                        $('#transaksi_table_faktur tbody').append(`
                            <tr>
                                <td>${index + 1}</td>
                                <td>${item.kd_brg}</td>
                                <td>${item.nama_brg}</td>
                                <td>${item.harga}</td>
                                <td>${item.qty_unit}</td>
                                <td>${item.satuan}</td>
                                <td class="editable" contenteditable="true">${item.qty_order}</td>
                                <td class="editable" ${user_role_diskon === 'customer' ? '' : 'contenteditable="true"'}>${item.disc}</td>
                                <td class="editable" ${user_role_diskon === 'customer' ? '' : 'contenteditable="true"'}>${item.ndisc}</td>
                                <td>${item.ppn}</td>
                                <td>${total_order}</td>
                                <td class="d-none dpp-val">${item.dpp}</td>     <!-- DPP dari server -->
                                <td class="d-none ppn-val">${item.rppn}</td>
                                <td><button type="button" class="btn btn-danger btn-sm delete-row"><i class="fa fa-trash" aria-hidden="true"></i></button></td>
                            </tr>
                        `);
                    });
                    // d-none dpp-val dan ppn-val untuk mengakali akumulasi jumlah dpp dan ppn
                    $('#grand_total_faktur').text(format_ribuan(grandTotal));
                    $('#grand_total_dpp_faktur').text(format_ribuan(grandTotalDpp));
                    $('#grand_total_ppn_faktur').text(format_ribuan(grandTotalPpn));
                    $('#formtable_po').hide();
                    $('.master_faktur_field').show();
                    $('#select_user_trans_faktur').val(null).trigger('change');
                    // Fungsi Hide BUtton Within 24 hours
                    const userRole = @json(Auth::user()->roles);
                    let firstCreatedAt = response.data.length > 0 ? response.data[0].created_at : null;
                    if (firstCreatedAt) {
                        // pastikan format valid ISO + offset timezone
                        let created = new Date(firstCreatedAt.replace(' ', 'T') + "+08:00");
                        let now = new Date();
                        let diffHours = Math.abs(now - created) / 36e5;
                        console.log("Created:", created, "Now:", now, "Diff:", diffHours);
                        if (userRole.toLowerCase() !== 'admin' && diffHours > 24) {
                            $('#save_table_transaksi_faktur').hide();
                        } else {
                            $('#save_table_transaksi_faktur').show();
                        }
                    }
                    if (userRole.toLowerCase() !== 'admin') {
                            $('#delete_table_transaksi_faktur').hide();
                        }
                    select2_call();
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error: ', status, error);
                }
            });
        }
// ================================== End Of Show PO Detail Approved ===========================================
// ==================================== Select User ==============================================
    function select_user_list_edit(){
        $('#select_user_trans_faktur').select2({
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
        $('#select_user_trans_faktur').on('select2:select', function(e) {
            var data = e.params.data; // Data yang dipilih
            $('#kode_user_trans_faktur').val(data.kd_customer);
            $('#nama_user_trans_faktur').val(data.nama);
        });
    };
// ================================= End Of Select User ===========================================
// ================================= Select Barang ===========================================
    function select2_call(){
        $('#select_barang_faktur').select2({
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
    $('#select_barang_faktur').on('select2:select', function(e) {
        var data = e.params.data; // Data yang dipilih
        $('#kd_barang').val(data.kd_barang);
        get_barang_satuan_edit(data.kd_barang);
    });

    // ### Clear Selected Barangs
    $('#clear_select_faktur').on('click', function() {
        $('#select_barang_faktur').val(null).trigger('change'); // Kosongkan pilihan
        $('#kd_barang').val("").trigger('change');
        $('#master_transaksi_faktur').text('-').trigger('change');
        $('#unit_barang_faktur').text('-').trigger('change');
        $('#stok_barang_faktur').text('-').trigger('change');
        $('#select_barang_satuan_faktur').empty().trigger('change');
        $('#select_barang_satuan_faktur').append('<option value="">Pilih Satuan</option>').trigger('change');
        $('#harga_barang_faktur').text('-').trigger('change');
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
    $('#select_barang_faktur').on('select2:select', function(e) {
        // Pindahkan fokus ke input selanjutnya ketika item dipilih
        var focusable = $('form').find('input, select, button').filter(':visible');
        var nextIndex = focusable.index(this) + 1;
        if (nextIndex < focusable.length) {
            focusable.eq(nextIndex).focus();
        }
    });
        // Mencegah Enter pada select2 agar tidak trigger submit
    $('#select_barang_faktur').on('keypress', function(e) {
        if (e.keyCode == 13) {
            e.preventDefault(); // Mencegah select2 dari trigger submit ketika tekan Enter
        }
    });
        // === end of fungsi enter next di form ===
// ================================= End Of Select Barang ===========================================
// ===================================== Select Gudang =============================================
    $.ajax({
        url: "{{ route('get_kode_gudang') }}",
        type: "GET",
        success: function(data) {
            let select = $('#select_gudang_edit');
            select.empty();
            // select.append('<option value="">-- Pilih Gudang --</option>');
            $.each(data, function(index, value) {
                select.append('<option value="' + value + '">' + value + '</option>');
            });
        }
    });
// ================================= End of Select Gudang ===========================================
// ======================= Trigger Select Satuan Barang When kd_barang change =============================
function get_barang_satuan_edit(kd_barang){
        // console.log('test :' + kd_barang)
        let kode_gudang = $('#select_gudang_edit').val();
        if (kd_barang) {
            $.ajax({
                url: '{{ route("get_barang_satuan") }}',
                type: 'GET',
                data: {
                    kd_barang: kd_barang,
                    kode_gudang: kode_gudang
                 },
                success: function (response) {
                    // alert(response[0].satuan);
                    $('#select_barang_satuan_faktur').empty();
                    response.forEach(function (item) {
                        console.log('Satuan:', item.satuan);
                        $('#select_barang_satuan_faktur').append(
                            `<option value="${item.satuan}">${item.satuan}</option>`
                        );
                    });
                    $('#master_transaksi_faktur').text(response[0].NAMA_BRG);
                    let data_harga = response[0].hj1;
                    $('#harga_barang_faktur').text(format_ribuan(data_harga));
                    // menghapus nilani decimal dari dbase
                    let isi = response[0].isi.replace(/,/g, '');
                    let formatted_value = isi.replace(/\./g, ''); // Menghapus titik
                    let final_value = parseInt(formatted_value / 1000);
                    $('#unit_barang_faktur').text(final_value);
                    // menghapus nilani decimal dari dbase
                    let isi_gudang = response[0].stok_gudang.replace(/,/g, '');
                    let formatted_value_gudang = isi_gudang.replace(/\./g, ''); // Menghapus titik
                    let final_value_gudang = parseInt(formatted_value_gudang / 100);
                    $('#stok_barang_faktur').text(final_value_gudang);
                },
                error: function (xhr) {
                    console.error('Error:', xhr.responseText);
                }
            });
        } else {
            // Kosongkan opsi jika input kosong
            $('#select_barang_satuan_faktur').empty();
            $('#select_barang_satuan_faktur').append('<option value="">Pilih Satuan</option>');
        }
    };
// =================== End of Trigger Select Satuan Barang When kd_barang change ==========================
// ==================== Trigger Select Satuan Barang When select_barang_satuan change ============================
    $('#select_barang_satuan_faktur').on('change', function() {
        const satuan_barang = $(this).val();
        const kd_barang = $('#kd_barang').val();
        const kode_gudang = $('#select_gudang_edit').val();
        if (satuan_barang) {
            $.ajax({
                url: '{{ route("get_barang_selected") }}',
                type: 'GET',
                data: { satuan_barang: satuan_barang, kd_barang: kd_barang, kode_gudang: kode_gudang },
                success: function(response) {
                    let data_harga = response.hj1;
                    $('#harga_barang_faktur').text(format_ribuan(data_harga));
                    let isi = response.isi.replace(/,/g, '');
                    let formatted_value = isi.replace(/\./g, ''); // Menghapus titik
                    let final_value = parseInt(formatted_value / 1000);
                    $('#unit_barang_faktur').text(final_value);
                    // menghapus nilani decimal dari dbase
                    let isi_gudang = response[0].stok_gudang.replace(/,/g, '');
                    let formatted_value_gudang = isi_gudang.replace(/\./g, ''); // Menghapus titik
                    let final_value_gudang = parseInt(formatted_value_gudang / 100);
                    $('#stok_barang_faktur').text(final_value_gudang);
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
    loadInputPajakEdit();
    function loadInputPajakEdit(){
        $.ajax({
            url: '{{ route('get_pajak') }}',
            type: 'GET',
            success: function(response) {
                let nilai_ppn = response.data.ppn;
                $('#ppn_trans_faktur').val(nilai_ppn);
            },
            error: function() {
                $('#ppn_trans_faktur').val('Error Loading');
            }
        });
    }
// =================== End Of Pajak PPN ==========================
// ================================= Input Barang To Table ===========================================
    // let grandTotal = 0;
    $('form').on('submit', function(event) {
        event.preventDefault();

        let kdBarang = $('#kd_barang').val();
        let namaBarang = $('#master_transaksi_faktur').text();
        let hargaBarangText = $('#harga_barang_faktur').text();
        let hargaBarang = parseFloat(hapus_format(hargaBarangText)) || 0;
        let unitBarang = $('#unit_barang_faktur').text();
        let satuanBarang = $('#select_barang_satuan_faktur').val();
        let jumlahTrans = parseFloat($('#jumlah_trans_faktur').val()) || 0;
        let diskonBarang = parseFloat($('#diskon_barang_faktur').val()) || 0;
        let diskonBarangRp = parseFloat($('#diskon_barang_rp_faktur').val()) || 0;
        let ppnBarang = parseFloat($('#ppn_trans_faktur').val().replace(',', '.')) || 0;
            // Pengecekan untuk nilai kosong
        if (!kdBarang || !namaBarang || hargaBarang === 0 || jumlahTrans === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Submit Failed',
                text: 'Mohon lengkapi semua data sebelum submit!',
                showConfirmButton: false,
                timer: 2000 // Durasi tampil dalam milidetik
            });
            // alert('Mohon lengkapi semua data sebelum submit!');
            return; // Hentikan proses jika salah satu input kosong
        }
        // rumus diskon
        let diskon_dalam_uang = (diskonBarang / 100) * hargaBarang;
        let harga_setelah_diskon = hargaBarang - diskon_dalam_uang - diskonBarangRp;
        let total = harga_setelah_diskon * jumlahTrans;

        // PPN dihitung mundur (seperti di Excel)
        let dpp = Math.round(total / (1 + ppnBarang / 100));
        let total_ppn = total - dpp;

        let gtotal = total; // karena total sudah termasuk PPN
        // let formatted_total = total.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });-> jika butuh pembulatan .00
        // let formatted_total = total;

        // Validasi user untuk menonaktifkan diskon edit
        let user_role_diskon = @json(Auth::user()->roles);
        let newRow = `
            <tr>
                <td class="row-number"></td>
                <td>${kdBarang}</td>
                <td>${namaBarang}</td>
                <td>${hargaBarang}</td>
                <td>${unitBarang}</td>
                <td>${satuanBarang}</td>
                <td class="editable" contenteditable="true">${jumlahTrans}</td>
                <td class="editable" ${user_role_diskon === 'customer' ? '' : 'contenteditable="true"'}>${diskonBarang}</td>
                <td class="editable" ${user_role_diskon === 'customer' ? '' : 'contenteditable="true"'}>${diskonBarangRp}</td>
                <td class="editable" ${user_role_diskon !== 'customer' && user_role_diskon !== 'staff' ? 'contenteditable="true"' : ''}>${ppnBarang}</td>
                <td>${format_ribuan(gtotal)}</td>
                <td class="d-none dpp-val">${dpp}</td>
                <td class="d-none ppn-val">${total_ppn}</td>
                <td><button type="button" class="btn btn-danger btn-sm delete-row"><i class="fa fa-trash" aria-hidden="true"></i></button></td>
            </tr>
        `;
        // d-none dpp-val dan ppn-val untuk mengakali akumulasi jumlah dpp dan ppn
        $('#transaksi_table_faktur tbody').append(newRow);
        updateRowNumbers();

        grandTotal += gtotal;
        $('#grand_total_faktur').text(format_ribuan(grandTotal));
        grandTotalDpp += dpp;
        $('#grand_total_dpp_faktur').text(format_ribuan(grandTotalDpp));
        grandTotalPpn += total_ppn;
        $('#grand_total_ppn_faktur').text(format_ribuan(grandTotalPpn));

        this.reset();
        $('#select_barang_faktur').val(null).trigger('change');
        $('#master_transaksi_faktur').text('-');
        $('#harga_barang_faktur').text('-');
        $('#unit_barang_faktur').text('-');
        $('#stok_barang_faktur').text('-');
        $('#select_barang_satuan_faktur').empty();
        $('#select_barang_satuan_faktur').append('<option value="">Pilih Satuan</option>');
        loadInputPajakEdit();

        setTimeout(function() {
            $('#select_barang_faktur').select2('open');
            document.querySelector('.select2-search__field').focus();
        }, 0);

    });

    // ### Detele Table
    $('#transaksi_table_faktur').on('click', '.delete-row', function() {
        let row = $(this).closest('tr');
        let total_text = row.find('td:eq(10)').text();
        let total = parseFloat(hapus_format(total_text)) || 0;

        let dpp = parseFloat(row.find('.dpp-val').text()) || 0;
        let total_ppn = parseFloat(row.find('.ppn-val').text()) || 0;

        grandTotal -= total;
        grandTotalDpp -= dpp;
        grandTotalPpn -= total_ppn;

        row.remove();
        updateRowNumbers();

        $('#grand_total_faktur').text(format_ribuan(grandTotal));
        $('#grand_total_dpp_faktur').text(format_ribuan(grandTotalDpp));
        $('#grand_total_ppn_faktur').text(format_ribuan(grandTotalPpn));
    });

    function updateRowNumbers() {
        $('#transaksi_table_faktur tbody tr').each(function(index) {
            $(this).find('.row-number').text(index + 1);
        });
    }

    // ### Editing Baris Table
    $('#transaksi_table_faktur').on('input', '.editable', function() {
        let row = $(this).closest('tr');
        let hargaBarang = parseFloat(row.find('td:eq(3)').text()) || 0;
        let newJumlah = parseFloat(row.find('td:eq(6)').text()) || 0;
        let diskonBarang = parseFloat(row.find('td:eq(7)').text()) || 0;
        let diskonBarangRp = parseFloat(row.find('td:eq(8)').text()) || 0;
        let ppnBarang = parseFloat(row.find('td:eq(9)').text()) || 0;

        let oldTotal = parseFloat(hapus_format(row.find('td:eq(10)').text())) || 0;
        let oldDpp = parseFloat(row.find('.dpp-val').text()) || 0;
        let oldPpn = parseFloat(row.find('.ppn-val').text()) || 0;

        let diskon_dalam_uang = (diskonBarang / 100) * hargaBarang;
        let harga_setelah_diskon = hargaBarang - diskon_dalam_uang - diskonBarangRp;
        let total = harga_setelah_diskon * newJumlah;

        let dpp = Math.round(total / (1 + ppnBarang / 100));
        let total_ppn = total - dpp;
        let gtotal = total;

        row.find('td:eq(10)').text(format_ribuan(gtotal));
        row.find('.dpp-val').text(dpp);        // update nilai DPP
        row.find('.ppn-val').text(total_ppn);  // update nilai PPN

        grandTotal = grandTotal - oldTotal + gtotal;
        grandTotalDpp = grandTotalDpp - oldDpp + dpp;
        grandTotalPpn = grandTotalPpn - oldPpn + total_ppn;

        $('#grand_total_faktur').text(format_ribuan(grandTotal));
        $('#grand_total_dpp_faktur').text(format_ribuan(grandTotalDpp));
        $('#grand_total_ppn_faktur').text(format_ribuan(grandTotalPpn));
    });
// =============================== End Of Input Barang To Table =========================================
// =================================== Struk on Click ==============================================
    $('#save_table_transaksi_faktur').on('click', function () {
        let value_invo = $(this).val();
        console.log('coba =' + value_invo);
        $.ajax({
                url: '{{ route('get_faktur_to_table') }}', // Ganti dengan route yang sesuai
                type: 'GET',
                data: {
                    no_invoice: value_invo // Mengirimkan no_invoice ke server
                },
                success: function (response) {
                    const payup = response.data[0];
                    $("#paymentMethod").val(payup.pembayaran ?? 'bon');
                    $("#cashAmount").val(payup.nominal_bayar ?? '');
                    $("#changeAmount").val(payup.kembalian ?? '');
                    $("#bankName").val(payup.nama_bank ?? '');
                    if (payup.pembayaran === 'transfer') {
                        // contoh: tampilkan input bank
                        $("#transferSection").removeClass("d-none");
                    }
                    if (payup.pembayaran === 'cash') {
                        // contoh: tampilkan input bank
                        $("#cashSection").removeClass("d-none");
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error: ', status, error);
                }
            });
        let nilai_total_modal = $('#grand_total_faktur').text().trim();
        let nilaiBersih = unformatRupiah(nilai_total_modal);
        $("#total_harga_modal").val(nilaiBersih);
        function unformatRupiah(str) {
            if(!str) return 0;
            // hapus semua titik (ribuan), ganti koma dengan titik
            let cleaned = str.replace(/\./g, '').replace(',', '.');
            return parseFloat(cleaned);
        }
        $('#payment_modal').data('invoice', value_invo);
        $('#payment_modal').modal('show');
    });
// ================================= End Of Struk on Click =========================================
// ==================================== Model Transaksi Cetak ============================================
    // Ganti tampilan sesuai metode pembayaran
    $("#paymentMethod").on("change", function(){
        let method = $(this).val();
        if(method === "cash"){
        $("#bankName").val('');
        $("#cashAmount").val('');
        $("#changeAmount").val('');
        $("#cashSection").removeClass("d-none");
        $("#transferSection").addClass("d-none");
        } else if(method === "transfer"){
        $("#bankName").val('');
        $("#cashAmount").val('');
        $("#changeAmount").val('');
        $("#transferSection").removeClass("d-none");
        $("#cashSection").addClass("d-none");
        } else if(method === "bon"){
        $("#bankName").val('');
        $("#cashAmount").val('');
        $("#changeAmount").val('');
        $("#transferSection").addClass("d-none");
        $("#cashSection").addClass("d-none");
        } else {
        $("#cashSection, #transferSection").addClass("d-none");
        }
    });

    // Hitung kembalian otomatis
    $("#cashAmount").on("input", function(){
        let total = parseFloat($("#total_harga_modal").val());
        let bayar = parseFloat($(this).val());
        let kembali = bayar - total;
        $("#changeAmount").val(kembali >= 0 ? kembali : 0);
    });

    // Validasi saat cetak
    $("#cetak_faktur").on("click", function(){
        let method = $("#paymentMethod").val();
        let total = parseFloat($("#total_harga_modal").val());
        let nama_bank = $("#bankName").val();
        let jumlah_bayar = $("#cashAmount").val();
        let jumlah_kembalian = $("#changeAmount").val();

        if(method === "cash"){
        let bayar = parseFloat($("#cashAmount").val());
        if(!bayar || bayar < total){
            alert("Bayaran kurang! Harap isi nominal dengan benar.");
            return;
        }
        }
        else if(method === "transfer"){
        let bank = $("#bankName").val().trim();
        if(bank === ""){
            alert("Harap isi nama bank untuk transfer.");
            return;
        }
        }
        else if (method === "bon"){

        }
        else {
        alert("Pilih metode pembayaran dulu.");
        return;
        }
        $('#payment_modal').modal('hide');
        $('body').removeClass('modal-open').css('overflow', 'auto').css('padding-right', '');
        $('.modal-backdrop').remove();
        // Panggil fungsi simpan
        proses_cetak_faktur();
    });

    $("#cancel_btn_cetak").on("click", function(){
        $('#payment_modal').modal('hide');
        $('body').removeClass('modal-open').css('overflow', 'auto').css('padding-right', '');
        $('.modal-backdrop').remove();
    });
// ============================== End of Model Transaksi Cetak ========================================
// ==================================== CETAK FAKTUR TO DB ============================================
    function proses_cetak_faktur(){
        const kode_user = $("#kode_user_trans_faktur").val();
        let method = $("#paymentMethod").val();
        let total = parseFloat($("#total_harga_modal").val());
        let nama_bank = $("#bankName").val();
        let jumlah_bayar = $("#cashAmount").val();
        let jumlah_kembalian = $("#changeAmount").val();

        const products = [];
        let value_invo = $("#payment_modal").data("invoice");
        let is_valid = true; // Untuk memeriksa validasi secara keseluruhan
        $('#transaksi_table_faktur tbody tr').each(function () {
            const kd_barang = $(this).find('td:eq(1)').text(); // KD Barang
            const nama = $(this).find('td:eq(2)').text();      // Nama Barang
            const harga = $(this).find('td:eq(3)').text();     // Harga Barang
            const unit = $(this).find('td:eq(4)').text();      // Unit Barang
            const satuan = $(this).find('td:eq(5)').text();    // Satuan Barang
            const jumlah = $(this).find('td:eq(6)').text();    // Jumlah (editable)
            const diskon = $(this).find('td:eq(7)').text();    // Diskon (editable)
            const diskon_rp = $(this).find('td:eq(8)').text();
            const ppn_trans = $(this).find('td:eq(9)').text();
            const total_text = $(this).find('td:eq(10)').text();     // Total
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

            if (kode_user === "") {
                is_valid = false;
                Swal.fire({
                    icon: 'warning',
                    title: 'Kode User Tidak Valid',
                    text: 'Kode User tidak boleh kosong!',
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
                    harga,
                    unit,
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
            save_to_database_edit(products,value_invo,kode_user,method,nama_bank,jumlah_bayar,jumlah_kembalian);
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Save Failed',
                text: 'Tidak Ada Data Disimpan',
                showConfirmButton: false,
                timer: 2000 // Durasi tampil dalam milidetik
            });
        }
    }

    function save_to_database_edit(products,value_invo,kode_user,method,nama_bank,jumlah_bayar,jumlah_kembalian) {
        $('#loading_modal').modal('show');
        setTimeout(function () {
            $.ajax({
                url: '{{ route('update_faktur') }}', // Endpoint Laravel
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'), // CSRF Token
                    products: products,
                    value_invo: value_invo,
                    kode_user: kode_user,
                    method: method,
                    nama_bank: nama_bank,
                    jumlah_bayar: jumlah_bayar,
                    jumlah_kembalian: jumlah_kembalian
                },
                success: function (response) {
                    let encodedStruk = encodeURIComponent(response.struk_text);
                    window.location.href = "rawbt://print?text=" + encodedStruk;
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
                        $.ajax({
                            url: '{{ route('index_faktur') }}',
                            type: 'GET',
                            success: function(response) {
                                $('.master-page').html(response);
                            },
                            error: function() {
                                $('.master-page').html('<p>Error loading form.</p>');
                            }
                        });
                    });
                    // $('#transaksi_table_faktur tbody').empty();
                    // $('#grand_total_faktur').text(0);
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
// ================================= END OF CETAK FAKTUR TO DB =========================================
// =================================== Delete Faktur ==============================================
    $('#delete_table_transaksi_faktur').on('click', function () {
        let value_invo = $(this).val();

        Swal.fire({
            title: 'Yakin hapus?',
            text: "Data faktur dan transaksi terkait akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("delete_faktur", ":no_faktur") }}'.replace(':no_faktur', value_invo),
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if(response.status === 'success'){
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                 $.ajax({
                                    url: '{{ route('index_faktur') }}',
                                    type: 'GET',
                                    success: function(response) {
                                        $('.master-page').html(response);
                                    },
                                    error: function() {
                                        $('.master-page').html('<p>Error loading form.</p>');
                                    }
                                });
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: response.message
                            });
                        }
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Terjadi kesalahan: ' + xhr.responseJSON.message
                        });
                    }
                });
            }
        });
    });
// =================================== End Of Delete Faktur ==============================================
// ==================================== Reset Tabel PO ============================================
    $('#reset_table_transaksi_faktur').on('click', function(){

        let value_invo = $(this).val();
        console.log('faktur yo: '+value_invo);
        grandTotal = 0;
        grandTotalDpp = 0;
        grandTotalPpn = 0;
        ajax_click_edit_button(value_invo);
    });
// ================================= End Of Reset Tabel PO =========================================
// ==================================== Click Print PDF Button ==============================================
    $(document).on('click', '#print_faktur_pdf_app', function() {
        var faktur_number = $(this).data("no-invoice");

        // Show SweetAlert confirmation
        Swal.fire({
            title: 'Are you sure?',
            text: `Apakah Ingin Print Faktur: ${faktur_number} ?`,
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
                window.open('{{ route("generate_faktur_pdf", ":faktur_number") }}'.replace(':faktur_number', faktur_number), '_blank');
            }
        });
    });
// ================================= End Of Click Print PDF Button ===========================================
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
