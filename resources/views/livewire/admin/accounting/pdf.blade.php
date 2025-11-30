<!DOCTYPE html>
<html>
<head>
    <title>Laporan Accounting</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>

<h2>Laporan Accounting ({{ strtoupper($range) }})</h2>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Periode</th>
            <th>Total Penjualan</th>
            <th>Total Item</th>
            <th>Midtrans</th>
            <th>Pengiriman</th>
            <th>Income</th>
            <th>Expense</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($accountings as $acc)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $acc->act_period_from }} → {{ $acc->act_period_to }}</td>
            <td>Rp{{ number_format($acc->act_total_sales) }}</td>
            <td>{{ $acc->act_total_items_sold }}</td>
            <td>{{ $acc->act_midtrans_total_transactions }} trx</td>
            <td>{{ $acc->act_total_shipments }}</td>
            <td>Rp{{ number_format($acc->act_total_income) }}</td>
            <td>Rp{{ number_format($acc->act_total_expense) }}</td>
        </tr>
        @endforeach
    </tbody>

</table>

</body>
</html>
