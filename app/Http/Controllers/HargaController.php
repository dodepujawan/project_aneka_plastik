<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HargaController extends Controller
{
    public function list_harga(){
        if (Auth::check()) {
            if (Auth::user()->roles === 'admin' || Auth::user()->roles === 'staff') {
                return view('harga.list_harga');
            }
        }

        return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    }

    public function filter_list_harga(Request $request){
        // Cek apakah pengguna sudah login
        if (!Auth::check()) {
            return response()->json(['message' => 'Silakan login terlebih dahulu'], 401);
        }

        // Cek role pengguna
        $userRole = Auth::user()->roles;
        if ($userRole != 'admin' && $userRole != 'staff') {
            return response()->json(['message' => 'Anda tidak memiliki izin untuk mengakses data ini'], 403);
        }

        // Query dasar
        $query = DB::table('mharga as a')
            ->leftJoin('mbarang as b', 'b.KD_STOK', '=', 'a.kd_stok')
            ->select('a.kd_stok', 'a.satuan', 'a.hj1', 'a.isi', 'b.NAMA_BRG')
            ->orderBy('a.isi', 'ASC');

        // Filter berdasarkan searchText
        if ($request->has('searchText') && $request->searchText) {
            $query->where(function ($q) use ($request) {
                $q->where('a.kd_stok', 'like', '%' . $request->searchText . '%')
                ->orWhere('a.satuan', 'like', '%' . $request->searchText . '%')
                ->orWhere('b.NAMA_BRG', 'like', '%' . $request->searchText . '%');
            });
        }

        // Hitung total data tanpa filter
        $recordsTotal = $query->count();

        // Pagination
        $perPage = $request->input('length', 10); // Default 10 data per halaman
        $page = $request->input('start', 0) / $perPage + 1; // Hitung halaman berdasarkan `start`
        $order = $query->paginate($perPage, ['*'], 'page', $page);

        // Tambahkan nomor baris (no) ke setiap item
        $data = $order->items();
        $start = ($page - 1) * $perPage; // Hitung nomor awal
        foreach ($data as $key => $item) {
            $item->no = $start + $key + 1; // Nomor baris
        }

        // Format response untuk DataTables
        return response()->json([
            'draw' => $request->input('draw', 1), // Ambil nilai `draw` dari request
            'recordsTotal' => $recordsTotal, // Total data tanpa filter
            'recordsFiltered' => $order->total(), // Total data setelah filter
            'data' => $data, // Data untuk halaman saat ini
        ]);
    }
}
