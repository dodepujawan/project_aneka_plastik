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

        /* Untuk Title Po Disetujui */
        .title_bar{
            text-align: center;
            font-family: Arial, sans-serif;
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
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

        <div class="title_bar">
            <p style="margin: 0;">List Harga - {{ now()->format('d/m/Y') }}</p>
        </div>

        <!-- Tabel Transaksi -->
        <table id="transaksi_table_po">
            <thead>
                <tr>
                    <th>No</th>
                    <th>KD Barang</th>
                    <th>Nama</th>
                    <th>Satuan</th>
                    <th>Harga</th>
                    <th>Isi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaction as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->KD_STOK }}</td>
                    <td>{{ $item->NAMA_BRG }}</td>
                    <td>{{ $item->KEMASAN }}</td>
                    <td>{{ number_format($item->HJ1, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
