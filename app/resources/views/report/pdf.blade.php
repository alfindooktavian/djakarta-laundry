<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi - Djakarta Laundry</title>
    <style>
        @page {
            margin: 30px 25px;
        }

        body {
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }

        .header h1 {
            font-size: 20px;
            margin: 0;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .header p {
            font-size: 13px;
            margin: 2px 0;
            color: #555;
        }

        .periode {
            margin-top: 5px;
            font-size: 12.5px;
            font-weight: 500;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        th, td {
            border: 1px solid #999;
            padding: 6px 8px;
            vertical-align: middle;
        }

        th {
            background-color: #f3f3f3;
            text-align: center;
            font-weight: 600;
            font-size: 12px;
        }

        td {
            font-size: 11.5px;
        }

        tfoot td {
            font-weight: 600;
            background-color: #f9f9f9;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }

        .footer {
            text-align: right;
            font-size: 11px;
            margin-top: 30px;
            color: #555;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>Djakarta Laundry</h1>
        <p>Laporan Transaksi Pelanggan</p>

        <!-- Tambahan: Periode Laporan -->
        @if(isset($start_date) && isset($end_date))
            <p class="periode">
                Periode: {{ \Carbon\Carbon::parse($start_date)->translatedFormat('d F Y') }}
                &nbsp;–&nbsp;
                {{ \Carbon\Carbon::parse($end_date)->translatedFormat('d F Y') }}
            </p>
        @elseif(isset($period))
            <p class="periode">
                Periode: 
                @switch($period)
                    @case('weekly') Mingguan ({{ now()->startOfWeek()->format('d/m/Y') }} – {{ now()->endOfWeek()->format('d/m/Y') }}) @break
                    @case('monthly') Bulanan ({{ now()->startOfMonth()->format('F Y') }}) @break
                    @case('yearly') Tahunan ({{ now()->year }}) @break
                    @default Semua Waktu
                @endswitch
            </p>
        @endif

        <p><small>Dicetak pada {{ now()->format('d F Y, H:i') }}</small></p>
    </div>

    <!-- Tabel Laporan -->
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 20%;">Pelanggan</th>
                <th style="width: 15%;">Kasir</th>
                <th style="width: 15%;">Tanggal Order</th>
                <th style="width: 15%;">Status</th>
                <th style="width: 15%;" class="text-right">Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $i => $order)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $order->customer->name ?? '-' }}</td>
                    <td>{{ $order->user->name ?? '-' }}</td>
                    <td class="text-center">
                        {{ \Carbon\Carbon::parse($order->order_at)->format('d/m/Y') }}
                    </td>
                    <td class="text-center">{{ ucfirst($order->status ?? '-') }}</td>
                    <td class="text-right">
                        Rp {{ number_format($order->total_price ?? 0, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data transaksi</td>
                </tr>
            @endforelse
        </tbody>

        @if ($orders->count() > 0)
        <tfoot>
            <tr>
                <td colspan="5" class="text-right">Total Pendapatan:</td>
                <td class="text-right">
                    Rp {{ number_format($orders->sum('total_price'), 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>
        @endif
    </table>

    <!-- Footer -->
    <div class="footer">
        <p>Dibuat oleh sistem Djakarta Laundry &nbsp; | &nbsp; {{ now()->format('d M Y H:i') }}</p>
    </div>
</body>
</html>
