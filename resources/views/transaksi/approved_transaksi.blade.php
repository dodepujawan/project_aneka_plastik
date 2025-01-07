<style>
    .table-responsive {
        overflow: visible;
    }
    #transaksi_table_approve_field {
        width: 100% !important;
    }
</style>
<div class="container mt-5">
    <div id="formtable">
        <h5>User Table</h5>
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
        <div class="table-responsive">
            <table id="transaksi_table_approve_field" class="display table table-bordered mb-2 style-table">
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
        </div>
    </div>

</div>
{{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
<script>
$(document).ready(function() {
    // ========================== menapilkan list user ===============================
    transaksi_table_approve_field();
    function transaksi_table_approve_field() {
        let table = $('#transaksi_table_approve_field').DataTable({
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
                                <button class="btn btn-sm btn-primary edit-btn" data-no-invoice="${row.no_invoice}" style="margin-right: 0;">
                                    <i class="fa fa-search"></i>
                                </button>
                                <button class="btn btn-sm btn-success print-btn" id="print_edit_pdf_app" data-no-invoice="${row.no_invoice}">
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
                    .column(3, { page: 'current' })
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
            fixedHeader: {
                header: true,
                footer: false
            }
        });
            $('#filterBtnApp').on('click', function() {
                table.ajax.reload();
            });
    }

});
</script>
