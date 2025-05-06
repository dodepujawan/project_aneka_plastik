<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        /* Reset default margin dan padding */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            padding: 20px;
        }

        .container {
            width: 100%;
            margin: 0 auto;
        }

        /* Styling untuk table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }

        th {
            background-color: #f2f2f2;
        }

        /* Header styling */
        .header {
            width: 100%;
            margin-bottom: 20px;
        }

        .header table {
            width: 100%;
            border: none;
        }

        .header table td {
            border: none;
            vertical-align: top;
            padding: 0;
        }

        .logo {
            font-weight: bold;
            font-size: 24px;
            margin-bottom: 5px;
        }

        .right {
            text-align: right;
            font-size: 12px;
        }

        /* Row untuk Customer dan Dibuat */
        .info-row {
            width: 100%;
            margin-bottom: 20px;
        }

        .info-row table {
            width: 100%;
            margin: 0;
            border: none;
        }

        .info-row table td {
            border: none;
            padding: 0;
            width: 50%;
        }

        h3 {
            font-size: 12px;
            margin-bottom: 10px;
        }

        /* Footer styling */
        .footer {
            text-align: right;
            margin-top: 20px;
        }

        /* Untuk tabel transaksi */
        #transaksi_table_po tfoot td {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header dengan table layout -->
        <div class="header">
            <table>
                <tr>
                    <td>
                        {{-- ♥ --}}
                        <div class="logo">Aneka Plastik</div>
                        <p><b>Toko Plastik Terlengkap</b></p>
                    </td>
                    <td class="right">
                        <div>Jalan Singaraja Buleleng, Bali</div>
                        <div>Phone +62 361 123456</div>
                        <div>anekaplastik@gmail.com | www.anekaplastik.com</div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Info row dengan table layout -->
        <div class="info-row">
            <table>
                <tr>
                    <td>
                        <h3>Dibuat: {{ $transaction->first()->name }}</h3>
                    </td>
                    <td>
                        <h3>Customer: {{ $transaction->first()->nama_cust }}</h3>
                    </td>
                </tr>
            </table>
        </div>

        <div class="info-row">
            <table>
                <tr>
                    <td>
                        <h3>PO Nomor: {{ $transaction->first()->no_invoice }}</h3>
                    </td>
                    <td>
                        <h3>Tgl PO: {{ $transaction->first()->created_at }}</h3>
                    </td>
                </tr>
            </table>
        </div>



        <!-- Tabel Transaksi -->
        <table id="transaksi_table_po">
            <thead>
                <tr>
                    <th>No</th>
                    <th>KD Barang</th>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th>Isi</th>
                    <th>Satuan</th>
                    <th>Jumlah</th>
                    <th>Diskon %</th>
                    <th>Diskon Rp</th>
                    <th>PPN</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaction as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->kd_brg }}</td>
                    <td>{{ $item->nama_brg }}</td>
                    <td>{{ number_format($item->harga, 0, ',', '.') }}</td>
                    <td>{{ $item->qty_unit }}</td>
                    <td>{{ $item->satuan }}</td>
                    <td>{{ $item->qty_order }}</td>
                    <td>{{ number_format($item->disc, 0, ',', '.') }} %</td>
                    <td>{{ number_format($item->ndisc, 0, ',', '.') }}</td>
                    <td>{{ number_format($item->ppn, 0, ',', '.') }}</td>
                    <td>{{ number_format($item->total, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="10" style="text-align: right;"><strong>Grand Total:</strong></td>
                    <td>{{ number_format($transaction->sum('total'), 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="10" style="text-align: right;"><strong>DPP:</strong></td>
                    <td>{{ number_format($transaction->sum('dpp'), 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="10" style="text-align: right;"><strong>PPN:</strong></td>
                    <td>{{ number_format($transaction->sum('rppn'), 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>
</html>
