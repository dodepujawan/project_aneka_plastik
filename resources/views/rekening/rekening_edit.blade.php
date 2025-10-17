<style>
    .table-responsive {
        overflow: visible;
    }
    #userTable {
        width: 100% !important;
    }
</style>
<div class="container mt-5">
    <div id="rekeningFormtable">
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
                {{-- @if(session('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
                @endif --}}
                <h3 id="message"></h3>
                <form action="" id="editListRekeningForm" method="post">
                    @csrf
                    <div class="form-group">
                    <label><i class="fa fa-user"></i> Rekening Id</label>
                    <input type="text" name="id" id="id" class="form-control" value="" required="" readonly>
                    </div>
                    <div class="form-group">
                        <label><i class="fa fa-envelope"></i> Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="" required="">
                    </div>
                    <div class="form-group">
                        <label><i class="fa fa-user"></i> Username</label>
                        <input type="text" name="name" id="name" class="form-control" value="" required="">
                    </div>
                    <div class="form-group">
                        <label><i class="fa fa-key"></i> Password</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Kosongkan Jika Tidak Ingin Ubah Password !">
                    </div>
                    <div class="form-group">
                        <label><i class="fa fa-address-book"></i> Role</label>
                        <input type="hidden" name="roles_flag" id="roles_flag" class="form-control" value="" readonly>
                        <select name="roles_list_reg" id="roles_list_reg" class="form-control">
                            <option value="AD">Admin</option>
                            <option value="ST">Staff</option>
                            <option value="CS">Customer</option>
                       </select>
                    </div>
                    <div class="form-group" id="cabang_list_register_group">
                        <label><i class="fa fa-address-book"></i> Cabang</label>
                        <input type="hidden" name="cabang_flag" id="cabang_flag" class="form-control" value="" readonly>
                        <select name="cabang_list_reg" id="cabang_list_reg" class="form-control">
                        </select>
                    </div>
                    <div class="form-group" id="gudang_list_register_group">
                        <label><i class="fa fa-store"></i> Kode Gudang</label>
                        <input type="text" name="gudang_list_reg" id="gudang_list_reg" class="form-control" placeholder="Kode Gudang">
                    </div>
                    <div class="form-group">
                        <label><i class="fa fa-user"></i> Kode User</label>
                        <input type="text" name="kode_user_list" id="kode_user_list" class="form-control" placeholder="Kode User" required="">
                    </div>
                    <button type="submit" class="btn btn-primary btn-block" id="but_edit_list_register"><i class="fa fa-user"></i> Update</button>
                    <hr>
                    <p class="text-center">Kembali ke <a href="javascript:void(0);">Dashboard !</a></p>
                </form>
            </div>
        </div>
    </div>
</div>
