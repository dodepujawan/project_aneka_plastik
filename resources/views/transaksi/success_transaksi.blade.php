<div class="container mt-5">
    <div id="formtable_po">
        <h5>PO Success Table</h5>
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
                    <th>Diskon %</th>
                    <th>Diskon Rp</th>
                    <th>PPN</th>
                    <th style="background-color: rgba(255, 0, 0, 0.3); color: rgba(255, 0, 0, 0.8);">Total</th>
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
