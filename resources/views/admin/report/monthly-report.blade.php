<!DOCTYPE html>
<html>
<head>
    <title>Laporan Bulanan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        .header h1 {
            font-size: 24px;
            color: #1a2035;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 14px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            font-size: 14px;
        }
        th {
            background-color: #1a2035;
            color: white;
            text-transform: uppercase;
        }
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tbody tr:hover {
            background-color: #f1f1f1;
        }
        .summary {
            margin-top: 30px;
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .summary h3 {
            font-size: 18px;
            color: #1a2035;
            margin: 0;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #999;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Keuangan Bulanan</h1>
        <p>Periode: {{ $startDate->format('d F Y') }} - {{ $endDate->format('d F Y') }}</p>
    </div>

    <h3>Ringkasan Penjualan per Produk</h3>
    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th>Jumlah Terjual</th>
                <th>Total Penjualan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stockSummary as $stock)
            <tr>
                <td>{{ $stock['name'] }}</td>
                <td>{{ $stock['quantity'] }}</td>
                <td>Rp {{ number_format($stock['total_price'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Detail Transaksi</h3>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>ID Transaksi</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
            <tr>
                <td>{{ $transaction->created_at->format('d/m/Y') }}</td>
                <td>{{ $transaction->id }}</td>
                <td>Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <h3>Total Pendapatan: Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
    </div>

    <div class="footer">
        <p>Generated by KASIR SMKN 9 BANDUNG</p>
    </div>
</body>
</html>