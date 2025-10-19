<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        th { background-color: #f3f3f3; }
        h2 { text-align: center; margin-bottom: 10px; }
    </style>
</head>
<body>
    <h2>Laporan Transaksi</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Pelanggan</th>
                <th>Kasir</th>
                <th>Tanggal Order</th>
                <th>Status</th>
                <th>Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $i => $order)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $order->customer->name ?? '-' }}</td>
                <td>{{ $order->user->name ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($order->order_at)->format('d/m/Y') }}</td>
                <td>{{ $order->status }}</td>
                <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
