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
     * Export laporan ke PDF
     */
    public function exportPDF(Request $request)
    {
        $orders = $this->filterOrders($request);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('report.pdf', [
            'orders' => $orders,
            'period' => $request->period,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

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
