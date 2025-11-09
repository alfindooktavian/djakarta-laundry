<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Service;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->input('year'); 

        // Totals
        $totalCustomers = Customer::count();
        $totalServices = Service::count();
        $totalOrders = Order::count();
        $activeOrders = Order::where('status','diterima','diproses')->count();
        $completedOrders = Order::where('status', 'selesai')->count();

        // Income per bulan
        $incomeQuery = Payment::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(amount) as total_income')
            )
            ->where('status', 'paid');

        if ($year) {
            $incomeQuery->whereYear('created_at', $year);
        }

        $incomePerMonth = $incomeQuery->groupBy('month')
                                      ->orderBy('month', 'asc')
                                      ->get()
                                      ->pluck('total_income', 'month')
                                      ->toArray();

        $incomeData = [];
        for ($m = 1; $m <= 12; $m++) {
            $incomeData[$m] = $incomePerMonth[$m] ?? 0;
        }

        // Income per year (untuk dropdown tahun)
        $incomePerYear = Payment::select(DB::raw('YEAR(created_at) as year'))
                                ->where('status', 'paid')
                                ->groupBy('year')
                                ->orderBy('year', 'desc')
                                ->pluck('year')
                                ->toArray();

        // Orders per year
        $ordersPerYear = Order::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->get();

        // Customers per year
        $customersPerYear = Customer::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->get();

        // Services per year
        $servicesPerYear = Service::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->get();

        // Filter totals berdasarkan tahun
        if ($year) {
            $totalCustomers = Customer::whereYear('created_at', $year)->count();
            $totalServices = Service::whereYear('created_at', $year)->count();
            $totalOrders = Order::whereYear('created_at', $year)->count();
            $activeOrders = Order::whereYear('created_at', $year)->where('status', 'diproses')->count();
            $completedOrders = Order::whereYear('created_at', $year)->where('status', 'selesai')->count();
        }

        $filtered = [
            'total_customers' => $totalCustomers,
            'total_services' => $totalServices,
            'total_orders' => $totalOrders,
            'active_orders' => $activeOrders,
            'completed_orders' => $completedOrders,
        ];

        return response()->json([
            'totals' => $filtered,
            'income_per_month' => $incomeData,
            'income_per_year' => $incomePerYear,
            'orders_per_year' => $ordersPerYear,
            'customers_per_year' => $customersPerYear,
            'services_per_year' => $servicesPerYear,
        ]);
    }
}
