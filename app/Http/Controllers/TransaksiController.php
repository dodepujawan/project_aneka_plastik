<?php

namespace App\Http\Controllers;

use App\Models\Transactions;
use App\Models\Transusers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index(){
        return view('transaksi.index_transaksi');
    }

    // public function get_barangs(Request $request){
    //     $search = $request->input('q'); // Ambil query pencarian
    //     $query = DB::table('mbarang as a')
    //                 ->select(
    //                     'a.id',
    //                     'a.KD_STOK as kd_barang',
    //                     'a.NAMA_BRG as nama_barang',
    //                     'a.SATUAN as satuan',
    //                     'a.HJ1 as harga',
    //                     DB::raw("CAST(SUBSTRING_INDEX(b.isi, ',', 1) AS UNSIGNED) as q_unit"),
    //                     DB::raw("CAST(SUBSTRING_INDEX(a.STOKUNIT, ',', 1) AS UNSIGNED) as stok")
    //                 )
    //                 ->leftJoin('mharga as b', 'a.KD_STOK', '=', 'b.kd_stok');
    //     if ($search) {
    //         $query->where('a.KD_STOK', 'LIKE', "%{$search}%")
    //             ->orWhere('a.NAMA_BRG', 'LIKE', "%{$search}%");
    //     }
    //     $barangs = $query->get(); // Eksekusi query
    //     return response()->json($barangs);
    // }

    public function get_users(Request $request){
        $search = $request->input('q'); // Ambil query pencarian
        $query = DB::table('mcustomer as a')
                    ->select(
                        'id',
                        'CUSTOMER as kd_customer',
                        'NAMACUST as nama_cust',
                    );
        if ($search) {
            $query->where('CUSTOMER', 'LIKE', "%{$search}%")
                ->orWhere('NAMACUST', 'LIKE', "%{$search}%");
        }
        $users = $query->get(); // Eksekusi query
        return response()->json($users);
    }

    public function get_barangs(Request $request){
        $search = $request->input('q'); // Ambil query pencarian
        $query = DB::table('mbarang as a')
                    ->select(
                        'id',
                        'KD_STOK as kd_barang',
                        'NAMA_BRG as nama_barang',
                    );
        if ($search) {
            $query->where('KD_STOK', 'LIKE', "%{$search}%")
                ->orWhere('NAMA_BRG', 'LIKE', "%{$search}%");
        }
        $barangs = $query->get(); // Eksekusi query
        return response()->json($barangs);
    }

    public function save_products(Request $request){
        // Ambil data produk dari request
        $products = $request->input('products');
        $kode_user = $request->input('kode_user');

        // Ambil bulan dan tahun saat ini, format 'mY' misalnya '0225' untuk Februari 2025
        $currentMonthYear = Carbon::now()->format('m') . substr(Carbon::now()->format('Y'), 2, 2);

        // Ambil rcabang dari pengguna yang sedang login
        $rcabang = Auth::user()->rcabang;
        $user_id = Auth::user()->user_id;
        $user_name = Auth::user()->name;

        // Mulai transaksi
        DB::beginTransaction();

        try {
            // Ambil nomor urut terakhir untuk transaksi berdasarkan bulan dan tahun yang sama
            $lastInvoice = Transusers::where('no_invoice', 'like', '%'.$currentMonthYear.'%')
                                        ->orderBy('no_invoice', 'desc')
                                        ->lockForUpdate()
                                        ->first();

            // Tentukan nomor urut berdasarkan nomor invoice terakhir
            $nextInvoiceNumber = $lastInvoice ? (int) substr($lastInvoice->no_invoice, -5) + 1 : 1;

            // Format nomor invoice dengan menambahkan prefix dan nomor urut
            $invoiceNumber = 'POL-' . $currentMonthYear . str_pad($nextInvoiceNumber, 5, '0', STR_PAD_LEFT);

            Transusers::create([
                'no_invoice' => $invoiceNumber,
                'user_id' => $user_id,
                'nama_cust' => $user_name,
                'user_kode' => $kode_user,
            ]);

            // Proses menyimpan data produk
            foreach ($products as $product) {
                // menghilangkan titik di total
                // $cleaned_total = intval(str_replace('.', '', explode(',', $product['total'])[0]));
                $total_net = $product['total'];
                $ppn = $product['ppn_trans']; // contoh: 10
                $dpp = round($total_net / (1 + ($ppn / 100)));
                $ppn_rupiah = $total_net - $dpp;
                $diskon_rupiah = ($product['diskon'] / 100) * $product['harga'];
                Transactions::create([
                    'no_invoice' => $invoiceNumber,  // Menyimpan nomor invoice
                    'kd_brg' => $product['kd_barang'],
                    'nama_brg' => $product['nama'],
                    'harga' => $product['harga'],
                    'qty_unit' => $product['unit'],
                    'satuan' => $product['satuan'],
                    'qty_order' => $product['jumlah'],
                    'ppn' => $product['ppn_trans'],
                    'rppn' => $ppn_rupiah,
                    'dpp' => $total_net - $ppn_rupiah,
                    'disc' => $product['diskon'],
                    'rdisc' => $diskon_rupiah,
                    'ndisc' => $product['diskon_rp'],
                    'ttldisc' => ($diskon_rupiah + $product['diskon_rp']) * $product['jumlah'],
                    'ttl_gross' => $product['jumlah'] * $product['harga'],
                    'total' => $product['total'],
                    'rcabang' => $rcabang,  // Menyimpan rcabang dari pengguna yang login
                    'status_po' => 0,
                ]);
            }

            // Commit transaksi jika berhasil
            DB::commit();

            return response()->json(['message' => 'Products saved successfully!','invoice_number' => $invoiceNumber,], 200);
        } catch (\Exception $e) {
            // Rollback transaksi jika ada error
            DB::rollBack();

            // Kembalikan pesan error
            return response()->json(['error' => 'Failed to save products: ' . $e->getMessage()], 500);
        }
    }

    public function get_barang_satuan(Request $request)
    {
        $kd_barang = $request->input('kd_barang');

        $data = DB::table('mharga as a')
            ->leftJoin('mbarang as b', 'b.KD_STOK', '=', 'a.kd_stok')
            ->select('a.kd_stok', 'a.satuan', 'a.hj1', 'a.isi', 'b.NAMA_BRG')
            ->where('a.kd_stok', $kd_barang)
            ->orderBy('a.isi', 'ASC')
            ->get();

        return response()->json($data);
    }

    public function get_barang_selected(Request $request){
        $kd_barang = $request->input('kd_barang');
        $satuan_barang = $request->input('satuan_barang');

        $data = DB::table('mharga as a')
            ->leftJoin('mbarang as b', 'b.KD_STOK', '=', 'a.kd_stok')
            ->select('a.kd_stok', 'a.satuan', 'a.hj1', 'a.isi', 'b.NAMA_BRG')
            ->where('a.kd_stok', $kd_barang)
            ->where('a.satuan', $satuan_barang)
            ->first();

        return response()->json($data);
    }

    // ### Membuka Halaman Edit Transaksi
    public function index_edit(){
        return view('transaksi.transaksi_edit');
    }

    public function get_edit_transaksi_data(Request $request){
        $data = DB::table('po_userby as a')
            ->leftJoin('po_online as b', 'a.no_invoice', '=', 'b.no_invoice')
            ->select(
                'a.no_invoice',
                DB::raw('DATE(a.created_at) as created_at'), // Gunakan DATE untuk mengambil tanggal saja
                DB::raw('SUM(b.total) as total')
            )
            // ->where('a.user_id', Auth::user()->user_id)
            ->where('a.user_kode', Auth::user()->user_kode)
            ->where('b.status_po', 0)
            ->groupBy('a.no_invoice', 'a.created_at')
            ->orderBy('a.created_at', 'desc')
            ->get();

        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $data->count(),
            'recordsFiltered' => $data->count(),
            'data' => $data,
        ]);
    }

    public function get_edit_transaksi_data_admin(Request $request){
        $user = Auth::user();

        $query = DB::table('po_userby as a')
            ->leftJoin('po_online as b', 'a.no_invoice', '=', 'b.no_invoice')
            ->leftJoin('mcustomer as c', 'a.user_kode', '=', 'c.CUSTOMER')
            ->select(
                'a.no_invoice',
                DB::raw('DATE(a.created_at) as created_at'),
                'a.user_id',
                'a.user_kode',
                'c.NAMACUST as nama_cust',
                DB::raw('SUM(b.total) as total')
            )
            ->where('b.status_po', 0)
            ->groupBy('a.id','a.no_invoice', 'a.created_at', 'a.user_kode', 'c.NAMACUST', 'a.user_id')
            // ->orderBy('a.id', 'desc');
            ->orderBy('a.created_at', 'desc');

        if ($user->roles != 'admin') {
            $query->where('a.user_id', $user->user_id);
        }

        $data = $query->get();

        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $data->count(),
            'recordsFiltered' => $data->count(),
            'data' => $data,
        ]);
    }

    public function get_edit_transaksi_to_table(Request $request){
        $no_invoice = $request->no_invoice;

        $data = DB::table('po_userby as a')
        ->leftJoin('po_online as b', 'a.no_invoice', '=', 'b.no_invoice')
        ->leftJoin('mcustomer as c', 'a.user_kode', '=', 'c.CUSTOMER')
        ->select(
            'a.no_invoice',
            'a.created_at',
            'a.user_kode',
            'b.kd_brg',
            'b.nama_brg',
            'c.NAMACUST as nama_cust',
            DB::raw('CAST(b.qty_order AS UNSIGNED) AS qty_order'),
            DB::raw('CAST(b.qty_unit AS UNSIGNED) AS qty_unit'),
            'b.satuan',
            DB::raw('CAST(b.harga AS UNSIGNED) AS harga'),
            DB::raw('CAST(b.disc AS UNSIGNED) AS disc'),
            DB::raw('CAST(b.ndisc AS UNSIGNED) AS ndisc'),
            DB::raw('CAST(b.ppn AS UNSIGNED) AS ppn'),
            DB::raw('CAST(b.rppn AS UNSIGNED) AS rppn'),
            DB::raw('CAST(b.dpp AS UNSIGNED) AS dpp'),
            DB::raw('CAST(b.total AS UNSIGNED) AS total'),
            'b.rcabang'
        )
        // ->where('a.user_id', Auth::user()->user_id)
        ->where('b.status_po', 0)
        ->where('a.no_invoice', $no_invoice)
        ->get();

        return response()->json(['data' => $data]);
    }

    // ###  Update Transaksi
    public function update_products(Request $request){
        $products = $request->input('products');
        $noInvoice = $request->input('value_invo');
        $kodeUser = $request->input('kode_user');

        $rcabang = Auth::user()->rcabang;
        $roles_user = Auth::user()->roles;
        $user_kode = Auth::user()->user_kode;
        $user_id = Auth::user()->user_id;
        $user_name = Auth::user()->name;

        if ($roles_user == 'customer') {
            $statusRolesCheck = Transusers::where('no_invoice', $noInvoice)
                ->where('user_kode', $user_kode)
                ->exists();

            if (!$statusRolesCheck) {
                return response()->json([
                    'error' => 'Anda tidak dapat mengupdate produk karena status_po Anda tidak memenuhi syarat, Tolong Refresh.'
                ], 403);
            }
        }

        $statusPoCheck = Transactions::where('no_invoice', $noInvoice)
            ->where('status_po', '!=', 0)
            ->exists();
        if ($statusPoCheck) {
            return response()->json([
                'error' => 'Tidak dapat melanjutkan, terdapat transaksi dengan status_po selain 0'
            ], 400);
        }

        DB::beginTransaction();

        try {
            // Lock dulu data transusers biar tidak ada race condition
            $lockedTransUser = Transusers::where('no_invoice', $noInvoice)
                ->lockForUpdate()
                ->first();

            if (!$lockedTransUser) {
                DB::rollBack();
                return response()->json([
                    'error' => 'Data tidak ditemukan atau tidak bisa dikunci.'
                ], 404);
            }

            // Simpan data penting sebelum delete
            $user_id_prev = $lockedTransUser->user_id;
            $user_name_prev = $lockedTransUser->nama_cust;
            $created_at_transusers = $lockedTransUser->created_at;

            $transactionsCreatedAt = Transactions::where('no_invoice', $noInvoice)
                ->pluck('created_at')->first();

            // Hapus data lama
            Transusers::where('no_invoice', $noInvoice)->delete();
            Transactions::where('no_invoice', $noInvoice)->delete();

            // Simpan data baru
            Transusers::create([
                'no_invoice' => $noInvoice,
                'user_id' => $user_id_prev,
                'nama_cust' => $user_name_prev,
                'user_kode' => $kodeUser,
                'created_at' => $created_at_transusers,
                'updated_at' => Carbon::now(),
            ]);

            foreach ($products as $product) {
                $cleaned_total = str_replace(',', '.', str_replace('.', '', $product['total']));
                $total_net = (float) $cleaned_total;
                // Hitung DPP & PPN mundur (sesuai gaya Excel klien)
                $ppn = $product['ppn_trans']; // contoh: 10
                $dpp = round($total_net / (1 + ($ppn / 100)));
                $ppn_rupiah = $total_net - $dpp;

                $diskon_rupiah = ($product['diskon'] / 100) * $product['harga'];
                Transactions::create([
                    'no_invoice' => $noInvoice,
                    'kd_brg' => $product['kd_barang'],
                    'nama_brg' => $product['nama'],
                    'harga' => $product['harga'],
                    'qty_unit' => $product['unit'],
                    'satuan' => $product['satuan'],
                    'qty_order' => $product['jumlah'],
                    'ppn' => $product['ppn_trans'],
                    'rppn' => $ppn_rupiah,
                    'dpp' => $total_net - $ppn_rupiah,
                    'disc' => $product['diskon'],
                    'rdisc' => $diskon_rupiah,
                    'ndisc' => $product['diskon_rp'],
                    'ttldisc' => ($diskon_rupiah + $product['diskon_rp']) * $product['jumlah'],
                    'ttl_gross' => $product['jumlah'] * $product['harga'],
                    'total' => $cleaned_total,
                    'rcabang' => $rcabang,
                    'status_po' => 0,
                    'created_at' => $transactionsCreatedAt,
                    'updated_at' => Carbon::now(),
                ]);
            }

            DB::commit();

            return response()->json(['message' => 'Products updated successfully!'], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => 'Failed to update products: ' . $e->getMessage()], 500);
        }
    }

    public function delete_products(Request $request){
        // Validasi input
        $request->validate([
            'value_invo' => 'required|string'
        ]);

        $roles_user = Auth::user()->roles;
        $user_kode = Auth::user()->user_kode;
        $noInvoice = $request->input('value_invo'); // Ambil no_invoice dari request

        if ($roles_user == 'customer') {
            // Periksa apakah Transusers memiliki nilai sesuai kondisi
            $statusRolesCheck = Transusers::where('no_invoice', $noInvoice)
                ->where('user_kode', $user_kode)
                ->exists();

            if (!$statusRolesCheck) {
                return response()->json([
                    'error' => 'Anda tidak dapat menghapus produk karena status_po Anda tidak memenuhi syarat, Tolong Refresh.'], 403);
            }
        }

        // Memastikan Semua Nilai status_po = 0
        $statusPoCheck = Transactions::where('no_invoice', $noInvoice)
                                  ->where('status_po', '!=', 0)
                                  ->exists();
        if ($statusPoCheck) {
            return response()->json(['error' => 'Tidak dapat melanjutkan, terdapat transaksi dengan status_po selain 0'], 400);
        }

        // Mulai transaksi
        DB::beginTransaction();

        try {
            // Periksa apakah data ada di Transactions
            $exists = Transactions::where('no_invoice', $noInvoice)->exists();

            if (!$exists) {
                return response()->json(['error' => 'Invoice not found'], 404);
            }

            // Hapus data transaksi dan transuser berdasarkan no_invoice
            Transusers::where('no_invoice', $noInvoice)->delete();
            Transactions::where('no_invoice', $noInvoice)->delete();

            // Commit transaksi jika berhasil
            DB::commit();

            return response()->json(['message' => 'Products deleted successfully!'], 200);
        } catch (\Exception $e) {
            // Rollback transaksi jika ada error
            DB::rollBack();

            // Kembalikan pesan error
            return response()->json(['error' => 'Failed to delete products: ' . $e->getMessage()], 500);
        }
    }

    // ### Halaman Transaksi Approved
    public function approved(){
        return view('transaksi.approved_transaksi');
    }

    public function filter_approved_invoice(Request $request){
        if (!Auth::check()) {
            return response()->json(['message' => 'Silakan login terlebih dahulu'], 401);
        }

        $query = DB::table('po_userby as a')
            ->leftJoin('po_online as b', 'a.no_invoice', '=', 'b.no_invoice')
            ->select(
                'a.no_invoice',
                DB::raw('DATE(a.created_at) as created_at'),
                'a.user_id',
                'a.user_kode',
                DB::raw('SUM(b.total) as total')
            )
            // ->where('a.user_id', Auth::user()->user_id)
            // ->where('a.user_kode', Auth::user()->user_kode)
            ->where('b.status_po', '=', 1)
            ->groupBy('a.no_invoice', 'a.created_at', 'a.user_id', 'a.user_kode')
            ->orderBy('a.created_at', 'desc');

            $userRole = Auth::user()->roles;
            if ($userRole === 'customer') {
                $query->where('a.user_kode', Auth::user()->user_kode);
            } elseif ($userRole === 'staff') {
                $query->where('a.user_id', Auth::user()->user_id);
            } elseif ($userRole === 'admin') {
                // Tidak ada filter tambahan untuk admin, karena admin bisa melihat semua data
            } else {
                return response()->json(['message' => 'Anda tidak memiliki izin untuk mengakses data ini'], 403);
            }

            if ($request->has('startDate') && $request->startDate) {
                $query->where('a.created_at', '>=', $request->startDate);
            }

            if ($request->has('endDate') && $request->endDate) {
                $query->where('a.created_at', '<=', $request->endDate);
            }

            // if ($request->has('searchText') && $request->searchText) {
            //     $query->where(function($q) use ($request) {
            //         $q->where('a.no_invoice', 'like', '%' . $request->searchText . '%');
            //         $userRole = Auth::user()->roles;
            //         if ($userRole === 'staff') {
            //             $q->where('a.user_kode', Auth::user()->user_kode);
            //         } elseif ($userRole === 'admin') {
            //             $q->where('a.user_kode', Auth::user()->user_kode)
            //             ->orWhere('a.user_id', Auth::user()->user_id);
            //         }
            //     });
            // }
            if ($request->has('searchText') && $request->searchText) {
                $query->where(function($q) use ($request) {
                    $userRole = Auth::user()->roles;
                    $searchText = $request->searchText;

                    // Cari berdasarkan no_invoice
                    $q->where('a.no_invoice', 'like', '%' . $searchText . '%');

                    // Tambahkan filter berdasarkan role
                    if ($userRole === 'staff') {
                        // Staff hanya bisa melihat data dengan user_kode mereka
                        // $q->where('a.user_kode', Auth::user()->user_kode);
                        $q->orWhere('a.user_kode', 'like', '%' . $searchText . '%');
                    } elseif ($userRole === 'admin') {
                        // Admin bisa melihat data dengan user_kode atau user_id mereka
                        $q->orWhere('a.user_kode', 'like', '%' . $searchText . '%')
                          ->orWhere('a.user_id', 'like', '%' . $searchText . '%');
                    }
                });
            }

        $order = $query->get();

        return response()->json([
            'data' => $order
        ]);
    }

    public function get_po_approved_det(Request $request){
        $no_invoice = $request->input('no_invoice');
        $query = DB::table('po_online')
            ->select([
                'no_invoice',
                'kd_brg',
                'nama_brg',
                'harga',
                'qty_unit',
                'satuan',
                'qty_order',
                'qty_sup',
                'disc',
                'ndisc',
                'ndisc',
                'ppn',
                'total'
            ])
            ->where('status_po', 1)
            ->where('no_invoice', $no_invoice)
            ->get();

        $grandTotal = $query->sum('total');

        return response()->json([
            'data' => $query,
            'grand_total' => $grandTotal
        ]);
    }

    public function save_products_approved(Request $request){
        // Ambil data produk dari request
        $products = $request->input('products');
        $no_po_approve = $request->input('no_po_approve');

        // Ambil bulan dan tahun saat ini, format 'mY' misalnya '0225' untuk Februari 2025
        $currentMonthYear = Carbon::now()->format('m') . substr(Carbon::now()->format('Y'), 2, 2);

        // Ambil rcabang dari pengguna yang sedang login
        $kode_user = Transusers::where('no_invoice', $no_po_approve)->value('user_kode');
        $user_id = Transusers::where('no_invoice', $no_po_approve)->value('user_id');
        $user_name = Transusers::where('no_invoice', $no_po_approve)->value('nama_cust');
        $rcabang = Transactions::where('no_invoice', $no_po_approve)->pluck('rcabang')->first();

        // Mulai transaksi
        DB::beginTransaction();

        try {
            // Update Status PO ke 2 yang berarti tidak muncul lagi di aproved maupun di edit
            DB::table('po_online')
                ->where('no_invoice', $no_po_approve)
                ->update(['status_po' => 2]);

            // Ambil nomor urut terakhir untuk transaksi berdasarkan bulan dan tahun yang sama
            $lastInvoice = Transusers::where('no_invoice', 'like', '%'.$currentMonthYear.'%')
                                        ->orderBy('no_invoice', 'desc')
                                        ->lockForUpdate()
                                        ->first();

            // Tentukan nomor urut berdasarkan nomor invoice terakhir
            $nextInvoiceNumber = $lastInvoice ? (int) substr($lastInvoice->no_invoice, -5) + 1 : 1;

            // Format nomor invoice dengan menambahkan prefix dan nomor urut
            $invoiceNumber = 'POL-' . $currentMonthYear . str_pad($nextInvoiceNumber, 5, '0', STR_PAD_LEFT);

            Transusers::create([
                'no_invoice' => $invoiceNumber,
                'user_id' => $user_id,
                'nama_cust' => $user_name,
                'user_kode' => $kode_user,
            ]);

            // Proses menyimpan data produk
            foreach ($products as $product) {
                // menghilangkan titik di total
                // $cleaned_total = intval(str_replace('.', '', explode(',', $product['total'])[0]));
                $total_net = $product['total'];
                $ppn = $product['ppn_trans']; // contoh: 10
                $dpp = round($total_net / (1 + ($ppn / 100)));
                $ppn_rupiah = $total_net - $dpp;
                $diskon_rupiah = ($product['diskon'] / 100) * $product['harga'];
                Transactions::create([
                    'no_invoice' => $invoiceNumber,  // Menyimpan nomor invoice
                    'kd_brg' => $product['kd_barang'],
                    'nama_brg' => $product['nama'],
                    'harga' => $product['harga'],
                    'qty_unit' => $product['unit'],
                    'satuan' => $product['satuan'],
                    'qty_order' => $product['jumlah_order'] - $product['jumlah'],
                    'ppn' => $product['ppn_trans'],
                    'rppn' => $ppn_rupiah,
                    'dpp' => $total_net - $ppn_rupiah,
                    'disc' => $product['diskon'],
                    'rdisc' => $diskon_rupiah,
                    'ndisc' => $product['diskon_rp'],
                    'ttldisc' => round(($diskon_rupiah + $product['diskon_rp']) * $product['jumlah']),
                    'ttl_gross' => $product['jumlah'] * $product['harga'],
                    'total' => $product['total'],
                    'rcabang' => $rcabang,  // Menyimpan rcabang dari pengguna yang login
                    'status_po' => 0,
                    'history_inv' => $no_po_approve,
                ]);
            }

            // Commit transaksi jika berhasil
            DB::commit();

            return response()->json(['message' => 'Products saved successfully!','invoice_number' => $invoiceNumber,], 200);
        } catch (\Exception $e) {
            // Rollback transaksi jika ada error
            DB::rollBack();

            // Kembalikan pesan error
            return response()->json(['error' => 'Failed to save products: ' . $e->getMessage()], 500);
        }
    }

// =================================== Transaksi Success ======================================
    public function success_transaksi(){
        return view('transaksi.success_transaksi');
    }

    public function filter_success_invoice(Request $request){
        if (!Auth::check()) {
            return response()->json(['message' => 'Silakan login terlebih dahulu'], 401);
        }

        $start = $request->input('start');   // posisi awal data
        $length = $request->input('length'); // berapa baris per halaman
        $draw = $request->input('draw');     // digunakan untuk menjaga sinkronisasi DataTables

        $query = DB::table('po_userby as a')
            ->join('po_online as b', 'a.no_invoice', '=', 'b.no_invoice')
            ->select(
                'a.no_invoice',
                DB::raw('DATE(a.created_at) as created_at'),
                'a.user_id',
                'a.user_kode',
                DB::raw('SUM(b.total) as total')
            )
            ->where('b.status_po', '!=', 0)
            ->where('b.qty_sup', '!=', 0)
            // ->whereNotNull('b.no_invoice')
            ->groupBy('a.no_invoice', 'a.created_at', 'a.user_id', 'a.user_kode')
            ->orderBy('a.created_at', 'desc');

            $userRole = Auth::user()->roles;
            if ($userRole === 'customer') {
                $query->where('a.user_kode', Auth::user()->user_kode);
            } elseif ($userRole === 'staff') {
                $query->where('a.user_id', Auth::user()->user_id);
            } elseif ($userRole === 'admin') {
                // Tidak ada filter tambahan untuk admin, karena admin bisa melihat semua data
            } else {
                return response()->json(['message' => 'Anda tidak memiliki izin untuk mengakses data ini'], 403);
            }

            if ($request->has('startDate') && $request->startDate) {
                $query->where('a.created_at', '>=', $request->startDate);
            }

            if ($request->has('endDate') && $request->endDate) {
                $query->where('a.created_at', '<=', $request->endDate);
            }

            if ($request->has('searchText') && $request->searchText) {
                $query->where(function($q) use ($request) {
                    $userRole = Auth::user()->roles;
                    $searchText = $request->searchText;

                    // Cari berdasarkan no_invoice
                    $q->where('a.no_invoice', 'like', '%' . $searchText . '%');

                    // Tambahkan filter berdasarkan role
                    if ($userRole === 'staff') {
                        // Staff hanya bisa melihat data dengan user_kode mereka
                        // $q->where('a.user_kode', Auth::user()->user_kode);
                        $q->orWhere('a.user_kode', 'like', '%' . $searchText . '%');
                    } elseif ($userRole === 'admin') {
                        // Admin bisa melihat data dengan user_kode atau user_id mereka
                        $q->orWhere('a.user_kode', 'like', '%' . $searchText . '%')
                          ->orWhere('a.user_id', 'like', '%' . $searchText . '%');
                    }
                });
            }

        $totalFiltered = $query->count(); // sebelum paginate, total hasil filter

        $data = $query->offset($start)
                    ->limit($length)
                    ->get();

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalFiltered, // jumlah semua data yang difilter
            'recordsFiltered' => $totalFiltered,
            'data' => $data
        ]);

    }

    public function get_po_success_det(Request $request){
        $no_invoice = $request->input('no_invoice');
        $query = DB::table('po_online')
            ->select([
                'no_invoice',
                'kd_brg',
                'nama_brg',
                'harga',
                'qty_unit',
                'satuan',
                'qty_order',
                'qty_sup',
                'disc',
                'ndisc',
                'ndisc',
                'ppn',
                'total'
            ])
            ->where('no_invoice', $no_invoice)
            ->get();

        $grandTotal = $query->sum('total');

        return response()->json([
            'data' => $query,
            'grand_total' => $grandTotal
        ]);
    }
// =================================== End Of Transaksi Success ======================================
}

// Jaga Jaga Vesi Update Lama Tanpa Lock
// public function update_products(Request $request){
//     // Ambil data dari request
//     $products = $request->input('products');
//     $noInvoice = $request->input('value_invo'); // Ambil no_invoice dari request
//     $kodeUser = $request->input('kode_user');

//     // Ambil rcabang dari pengguna yang sedang login
//     $rcabang = Auth::user()->rcabang;
//     $roles_user = Auth::user()->roles;
//     $user_kode = Auth::user()->user_kode;
//     // jika ingin mengisi Transusers dengan data sesuai yang login
//     $user_id = Auth::user()->user_id;
//     $user_name = Auth::user()->name;

//     // Memastikan jika user login customer user_kode harus sama mencegah update data yng diubah admin
//     if ($roles_user == 'customer') {
//         // Periksa apakah Transusers memiliki nilai sesuai kondisi
//         $statusRolesCheck = Transusers::where('no_invoice', $noInvoice)
//             ->where('user_kode', $user_kode)
//             ->exists();

//         if (!$statusRolesCheck) {
//             return response()->json([
//                 'error' => 'Anda tidak dapat mengupdate produk karena status_po Anda tidak memenuhi syarat, Tolong Refresh.'], 403);
//         }
//     }

//     // Memastikan Semua Nilai status_po = 0
//     $statusPoCheck = Transactions::where('no_invoice', $noInvoice)
//                               ->where('status_po', '!=', 0)
//                               ->exists();
//     if ($statusPoCheck) {
//         return response()->json(['error' => 'Tidak dapat melanjutkan, terdapat transaksi dengan status_po selain 0'], 400);
//     }

//     // Untuk Mengambil data user_id dan nama_cust dari Transusers berdasarkan no_invoice sebelumnya
//     $previousUserData = Transusers::where('no_invoice', $noInvoice)->first();

//     if ($previousUserData) {
//         $user_id_prev = $previousUserData->user_id;
//         $user_name_prev = $previousUserData->nama_cust;
//     }

//     // Simpan nilai created_at lama
//     $created_at_transusers = $previousUserData->created_at; // Untuk tabel Transusers
//     $transactionsCreatedAt = Transactions::where('no_invoice', $noInvoice)->pluck('created_at')->first();

//     // Mulai transaksi
//     DB::beginTransaction();

//     try {
//         Transusers::where('no_invoice', $noInvoice)->delete();
//         Transactions::where('no_invoice', $noInvoice)->delete();

//         // Simpan data baru ke tabel Transusers
//         Transusers::create([
//             'no_invoice' => $noInvoice,
//             'user_id' => $user_id_prev,
//             'nama_cust' => $user_name_prev,
//             'user_kode' => $kodeUser,
//             'created_at' => $created_at_transusers,
//             'updated_at' => Carbon::now(),
//         ]);

//         foreach ($products as $product) {
//             // menghilangkan titik di total
//             $cleaned_total = str_replace(',', '.', str_replace('.', '', $product['total']));
//             Transactions::create([
//                 'no_invoice' => $noInvoice, // Menggunakan nomor invoice lama
//                 'kd_brg' => $product['kd_barang'],
//                 'nama_brg' => $product['nama'],
//                 'harga' => $product['harga'],
//                 'qty_unit' => $product['unit'],
//                 'satuan' => $product['satuan'],
//                 'qty_order' => $product['jumlah'],
//                 'disc' => $product['diskon'],
//                 'total' => $cleaned_total,
//                 'rcabang' => $rcabang, // Menyimpan rcabang dari pengguna yang login
//                 'status_po' => 0,
//                 'created_at' => $transactionsCreatedAt,
//                 'updated_at' => Carbon::now(),
//             ]);
//         }

//         // Commit transaksi jika berhasil
//         DB::commit();

//         return response()->json(['message' => 'Products updated successfully!'], 200);
//     } catch (\Exception $e) {
//         // Rollback transaksi jika ada error
//         DB::rollBack();

//         // Kembalikan pesan error
//         return response()->json(['error' => 'Failed to update products: ' . $e->getMessage()], 500);
//     }
// }
