<?php

namespace App\Http\Controllers;

use App\Models\Transactions;
use App\Models\Transusers;
use App\Models\Faktur;
use App\Models\FakturUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FakturController extends Controller
{
    public function index(){
        return view('faktur.index_faktur');
    }

    public function filter_no_faktur(Request $request){
        if (!Auth::check()) {
            return response()->json(['message' => 'Silakan login terlebih dahulu'], 401);
        }

        $start = $request->input('start');   // posisi awal data
        $length = $request->input('length'); // berapa baris per halaman
        $draw = $request->input('draw');     // digunakan untuk menjaga sinkronisasi DataTables

        $query = DB::table('faktur_userby as a')
            ->join('faktur_online as b', 'a.no_faktur', '=', 'b.no_faktur')
            ->select(
                'a.no_faktur',
                DB::raw('DATE(a.created_at) as created_at'),
                'a.user_id',
                'a.user_kode',
                DB::raw('SUM(b.total) as total')
            )
            // ->where('b.status_po', '!=', 0)
            // ->where('b.qty_sup', '!=', 0)
            // ->whereNotNull('b.no_invoice')
            ->groupBy('a.no_faktur', 'a.created_at', 'a.user_id', 'a.user_kode')
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
                    $q->where('a.no_faktur', 'like', '%' . $searchText . '%');

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

    public function get_faktur_det(Request $request){
        $no_faktur = $request->input('no_faktur');
        $role = auth()->user()->roles;
        $query = DB::table('faktur_online')
            ->select([
                'no_faktur',
                'kd_brg',
                'nama_brg',
                'harga',
                'qty_unit',
                'satuan',
                'qty_order',
                'disc',
                'ndisc',
                'ndisc',
                'ppn',
                'total',
                'created_at'
            ])
            ->where('no_faktur', $no_faktur)
            ->get();

        $grandTotal = $query->sum('total');

        // Default Hide Button
        $hidePrint = false; // default: tampilkan tombol

        if (strtolower($role) !== 'admin' && $query->isNotEmpty()) {
            $createdAt = Carbon::parse($query[0]->created_at);
            $now = Carbon::now();
            $hoursDiff = $createdAt->diffInHours($now);

            if ($hoursDiff > 24) {
                $hidePrint = true; // tombol di-hide kalau bukan admin & sudah 24 jam
            }
        }

        return response()->json([
            'data' => $query,
            'grand_total' => $grandTotal,
            'hide_print' => $hidePrint
        ]);
    }

    // ###  Update Faktur
    public function update_faktur(Request $request){
        $products = $request->input('products');
        $noFaktur = $request->input('value_invo');
        // $kodeUser = $request->input('kode_user');
        $noInvoice = Faktur::where('no_faktur', $noFaktur)->value('history_inv');

        $rcabang = Auth::user()->rcabang;
        $roles_user = Auth::user()->roles;
        $user_kode = Auth::user()->user_kode;
        $user_id = Auth::user()->user_id;
        $user_name = Auth::user()->name;

        if ($roles_user == 'customer') {
            $statusRolesCheck = FakturUser::where('no_faktur', $noFaktur)
                ->where('user_kode', $user_kode)
                ->exists();

            if (!$statusRolesCheck) {
                return response()->json([
                    'error' => 'Anda tidak dapat mengupdate produk karena status_po Anda tidak memenuhi syarat, Tolong Refresh.'
                ], 403);
            }
        }

        DB::beginTransaction();

        try {
            // Lock dulu data fakturUser biar tidak ada race condition
            $lockedFakturUser = FakturUser::where('no_faktur', $noFaktur)
                ->lockForUpdate()
                ->first();

            if (!$lockedFakturUser) {
                DB::rollBack();
                return response()->json([
                    'error' => 'Data tidak ditemukan atau tidak bisa dikunci.'
                ], 404);
            }

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
            $user_kode_prev = $lockedTransUser->user_kode;
            $user_name_prev = $lockedTransUser->nama_cust;
            $created_at_transusers = $lockedTransUser->created_at;

            $user_id_faktur = $lockedFakturUser->user_id;
            $user_kode_faktur = $lockedFakturUser->user_kode;
            $user_name_faktur = $lockedFakturUser->nama_cust;
            $created_at_faktur = $lockedFakturUser->created_at;

            $transCreatedAt = Transactions::where('no_invoice', $noInvoice)
                ->pluck('created_at')->first();

            $fakturCreatedAt = Faktur::where('no_faktur', $noFaktur)
                ->pluck('created_at')->first();

            // Hapus data lama
            FakturUser::where('no_faktur', $noFaktur)->delete();
            Faktur::where('no_faktur', $noFaktur)->delete();
            Transusers::where('no_invoice', $noInvoice)->delete();
            Transactions::where('no_invoice', $noInvoice)->delete();

            // Simpan data baru Transaksi
            Transusers::create([
                'no_invoice' => $noInvoice,
                'user_id' => $user_id_prev,
                'nama_cust' => $user_name_prev,
                'user_kode' => $user_kode_prev,
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
                    'status_faktur' => 1,
                    'created_at' => $transCreatedAt,
                    'updated_at' => Carbon::now(),
                ]);
            }

            // Simpan data baru Faktur
            FakturUser::create([
                'no_faktur' => $noFaktur,
                'user_id' => $user_id_prev,
                'nama_cust' => $user_name_prev,
                'user_kode' => $user_kode_faktur,
                'created_at' => $created_at_faktur,
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
                Faktur::create([
                    'no_faktur' => $noFaktur,
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
                    'history_inv' => $noInvoice,
                    'created_at' => $fakturCreatedAt,
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

}
