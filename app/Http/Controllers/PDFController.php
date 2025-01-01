<?php

namespace App\Http\Controllers;

use App\Models\Transactions;
use mPDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
                'a.created_at',
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
                DB::raw('CAST(b.total AS UNSIGNED) AS total'),
                'b.rcabang'
            ])
            ->where('b.status_po', 0)
            ->where('a.no_invoice', $invoice_number)
            ->get();

        if (!$transaction) {
            return redirect()->route('error_page')->with('message', 'Invoice not found');
        }

        // Pass the data to the view
        $html = view('pdf.po_online', compact('transaction'))->render(); // Assuming your view is using 'transaction'

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($html);
        $mpdf->Output('PO_online_' . $invoice_number . '.pdf', 'I');
    }
}
