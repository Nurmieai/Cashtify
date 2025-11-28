<!DOCTYPE html>
<html>
<head>
    <title>Laporan Akuntansi</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 14px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }
        th { background: #eee; }
        h2, h4 { margin: 0; padding: 0; }
    </style>
</head>
<body>

<h2>Laporan Akuntansi</h2>
<h4>Periode: {{ strtoupper($range ?? 'SEMUA') }}</h4>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Periode</th>
            <th>Total Penjualan</th>
            <th>Item Terjual</th>
            <th>Jumlah Transaksi</th>
            <th>Total Ongkir</th>
            <th>Total Pemasukan</th>
            <th>Total Pengeluaran</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($accountings as $acc)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $acc->act_period_from }} s/d {{ $acc->act_period_to }}</td>

            <td>Rp {{ number_format($acc->act_total_sales, 0, ',', '.') }}</td>

            <td>{{ $acc->act_total_items_sold }}</td>

            <td>{{ $acc->act_total_transactions }} transaksi</td>

            <td>Rp {{ number_format($acc->act_total_shipping_cost, 0, ',', '.') }}</td>

            <td>Rp {{ number_format($acc->act_total_income, 0, ',', '.') }}</td>

            <td>Rp {{ number_format($acc->act_total_expense, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>

</table>

</body>
</html>
