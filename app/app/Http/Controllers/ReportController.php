<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportController extends Controller
{
    /**
     * Ambil semua data laporan transaksi
     */
    public function index()
    {
        $orders = Order::with(['customer', 'user'])
            ->orderBy('order_at', 'desc')
            ->get();

        return response()->json($orders);
    }

    /**
     * Export laporan ke Excel
     */
    public function exportExcel(Request $request)
    {
        try {
            $orders = $this->filterOrders($request);

            if ($orders->isEmpty()) {
                return response()->json(['message' => 'Tidak ada data untuk diexport'], 404);
            }

            $data = $orders->map(function ($order, $i) {
                return [
                    'No' => $i + 1,
                    'Pelanggan' => $order->customer->name ?? '-',
                    'Kasir' => $order->user->name ?? '-',
                    'Tanggal Order' => $order->order_at
                        ? \Carbon\Carbon::parse($order->order_at)->format('d/m/Y')
                        : '-',
                    'Status' => $order->status ?? '-',
                    'Total Harga' => $order->total_price ?? 0,
                ];
            })->toArray();

            $export = new class($data) implements FromArray, WithHeadings {
                protected $data;
                public function __construct($data) { $this->data = $data; }
                public function array(): array { return $this->data; }
                public function headings(): array {
                    return ['No', 'Pelanggan', 'Kasir', 'Tanggal Order', 'Status', 'Total Harga'];
                }
            };

            $fileName = 'laporan-transaksi-' . now()->format('Ymd-His') . '.xlsx';
            return Excel::download($export, $fileName);

        } catch (\Throwable $e) {
            Log::error('Error exportExcel: ' . $e->getMessage());
            return response()->json([
                'message' => 'Gagal generate Excel',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export laporan ke PDF
     */
    public function exportPDF(Request $request)
    {
        $orders = $this->filterOrders($request);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('report.pdf', ['orders' => $orders]);
        $fileName = 'laporan-transaksi-' . now()->format('Ymd-His') . '.pdf';
        return $pdf->download($fileName);
    }


    /**
     * Filter data order berdasarkan periode
     */
    private function filterOrders(Request $request)
    {
        $query = Order::with(['customer', 'user']);

        if ($request->period === 'custom' && $request->start_date && $request->end_date) {
            $query->whereBetween('order_at', [$request->start_date, $request->end_date]);
        } elseif ($request->period === 'weekly') {
            $query->whereBetween('order_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($request->period === 'monthly') {
            $query->whereMonth('order_at', now()->month)
                  ->whereYear('order_at', now()->year);
        } elseif ($request->period === 'yearly') {
            $query->whereYear('order_at', now()->year);
        }

        return $query->orderBy('order_at', 'desc')->get();
    }
}
