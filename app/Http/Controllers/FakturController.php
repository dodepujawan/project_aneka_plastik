<?php

namespace App\Http\Controllers;

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

}
