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
}
