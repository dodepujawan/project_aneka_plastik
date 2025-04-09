<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transactions;
use App\Models\Transusers;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MobileController extends Controller
{
    public function index(){
        return view('mobile.mobile_transaksi');
    }

    public function index_edit_transaksi_mobile(){
        return view('mobile.mobile_transaksi_edit');
    }
}
