<?php

namespace App\Http\Controllers;

use App\Models\Transactions;
use App\Models\HargaNotif;
use mPDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Jobs\ConvertPdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class PDFController extends Controller
{
    public function generate_pdf($invoice_number){
        $transaction = DB::table('po_userby as a')
            ->leftJoin('po_online as b', DB::raw('a.no_invoice COLLATE utf8mb4_unicode_ci'), '=', 'b.no_invoice')
            ->leftJoin('mcustomer as c', DB::raw('a.user_kode COLLATE utf8mb4_unicode_ci'), '=', 'c.CUSTOMER')
            ->leftJoin('users as d', DB::raw('a.user_id COLLATE utf8mb4_unicode_ci'), '=', 'd.user_id')
            ->select([
                'a.no_invoice',
                'a.user_id',
                DB::raw('DATE_FORMAT(a.created_at, "%d-%m-%Y") AS created_at'),
                'a.user_kode',
                'b.kd_brg',
                'b.nama_brg',
                'c.NAMACUST as nama_cust',
                'd.name',
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
            ])
            ->where('b.status_po', 0)
            ->where('a.no_invoice', $invoice_number)
            ->get();

        if (!$transaction) {
            return redirect()->route('error_page')->with('message', 'Invoice not found');
        }

        $html = view('pdf.po_online', compact('transaction'))->render();

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($html);
        $mpdf->Output('PO_online_' . $invoice_number . '.pdf', 'I');
    }
    public function generate_pdf_approved($invoice_number){
        $transaction = DB::table('po_userby as a')
            ->leftJoin('po_online as b', DB::raw('a.no_invoice COLLATE utf8mb4_unicode_ci'), '=', 'b.no_invoice')
            ->leftJoin('mcustomer as c', DB::raw('a.user_kode COLLATE utf8mb4_unicode_ci'), '=', 'c.CUSTOMER')
            ->leftJoin('users as d', DB::raw('a.user_id COLLATE utf8mb4_unicode_ci'), '=', 'd.user_id')
            ->select([
                'a.no_invoice',
                'a.user_id',
                DB::raw('DATE_FORMAT(a.created_at, "%d-%m-%Y") AS created_at'),
                'a.user_kode',
                'b.kd_brg',
                'b.nama_brg',
                'c.NAMACUST as nama_cust',
                'd.name',
                DB::raw('CAST(b.qty_order AS UNSIGNED) AS qty_order'),
                DB::raw('CAST(b.qty_unit AS UNSIGNED) AS qty_unit'),
                DB::raw('CAST(b.qty_sup AS UNSIGNED) AS qty_sup'),
                'b.satuan',
                DB::raw('CAST(b.harga AS UNSIGNED) AS harga'),
                DB::raw('CAST(b.disc AS UNSIGNED) AS disc'),
                DB::raw('CAST(b.ndisc AS UNSIGNED) AS ndisc'),
                DB::raw('CAST(b.ppn AS UNSIGNED) AS ppn'),
                DB::raw('CAST(b.rppn AS UNSIGNED) AS rppn'),
                DB::raw('CAST(b.dpp AS UNSIGNED) AS dpp'),
                DB::raw('CAST(b.total AS UNSIGNED) AS total'),
                'b.rcabang'
            ])
            ->where('b.status_po', 1)
            ->where('a.no_invoice', $invoice_number)
            ->get();

        if (!$transaction) {
            return redirect()->route('error_page')->with('message', 'Invoice not found');
        }

        $html = view('pdf.po_online_app', compact('transaction'))->render();

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($html);
        $mpdf->Output('PO_online_' . $invoice_number . '.pdf', 'I');
    }

    public function generate_list_harga_pdf(){
        $userId = Auth::user()->user_id;
        $idNotifikasi = HargaNotif::insertGetId([
            'user' => $userId,
            'status' => 'running',
        ]);

        ConvertPdf::dispatch($idNotifikasi);
        return response()->json(['message' => 'Proses sedang berjalan di background']);
        // return back();
    }

    public function generate_list_harga_pdf_node(Request $request) {
        $userId = Auth::user()->user_id;
        $userBroad = Auth::user()->id;
        $idNotifikasi = HargaNotif::insertGetId([
            'user' => $userId,
            'description' => 'Export PDF ' . $userId,
            'status' => 'running',
        ]);
        ConvertPdf::dispatch($userBroad, $userId, $idNotifikasi);

        return response()->json(['message' => 'Diproses'], 200);
    }


}
