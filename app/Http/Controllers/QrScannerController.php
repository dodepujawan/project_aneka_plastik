<?php

namespace App\Http\Controllers;

use App\Models\Transactions;
use App\Models\Transusers;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class QrScannerController extends Controller
{
    public function index_qris(){
        return view('qris.index_qris');
    }

    public function cek_user_qr(Request $request){
        $kode = $request->input('kode');

        $user = User::where('user_id', $kode)->first();

        if ($user) {
            return response()->json([
                'status' => 'ok',
                'user_id' => $user->user_id,
                'name' => $user->name,
            ]);
        }

        return response()->json(['status' => 'not_found']);
    }
}
