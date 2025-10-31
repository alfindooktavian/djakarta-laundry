<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // ✅ List semua order (termasuk payments)
    public function index(Request $request)
{
    if ($request->boolean('all')) {
        $orders = Order::with(['customer', 'user', 'orderDetails.service', 'payments'])
            ->orderBy('id', 'desc')
            ->get();
    } else {
        $orders = Order::with(['customer', 'user', 'orderDetails.service', 'payments'])
            ->orderBy('id', 'desc')
            ->paginate(5);
    }

    return response()->json($orders);
}



    // ✅ Detail order (termasuk payments)
    public function show($id)
    {
        $order = Order::with(['customer', 'user', 'orderDetails.service', 'payments'])->findOrFail($id);
        return response()->json($order);
    }

    // ✅ Tambah order + detail
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'order_at'    => 'required|date',
            'status'      => 'sometimes|in:diterima,diproses,selesai,diambil',
            'details'     => 'required|array|min:1',
            'details.*.service_id' => 'required|exists:services,id',
            'details.*.quantity'   => 'required|integer|min:1',
            'details.*.subtotal'   => 'required|numeric|min:0',
        ]);

        $userId = Auth::id();

        // Hitung total harga otomatis
        $totalPrice = collect($validated['details'])->sum('subtotal');

        // Simpan order utama
        $order = Order::create([
            'customer_id' => $validated['customer_id'],
            'user_id'     => $userId,
            'order_at'    => $validated['order_at'],
            'status'      => $validated['status'] ?? 'diterima',
            'total_price' => $totalPrice,
        ]);

        // Simpan semua detail order
        foreach ($validated['details'] as $detail) {
            $order->orderDetails()->create($detail);
        }

        // ✅ Buat data payment otomatis (status unpaid, method null)
        Payment::create([
            'order_id' => $order->id,
            'amount'   => $totalPrice,
            'method' => 'cash',
            'status'   => 'unpaid',
            'paid_at'  => null,
        ]);

        return response()->json([
            'message' => 'Order, detail, dan payment berhasil dibuat',
            'data'    => $order->load(['customer', 'user', 'orderDetails.service', 'payments']),
        ], 201);
    }

    // ✅ Update order + detail
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'customer_id' => 'sometimes|exists:customers,id',
            'order_at'    => 'sometimes|date',
            'status'      => 'sometimes|in:diterima,diproses,selesai,diambil',
            'details'     => 'nullable|array',
            'details.*.id'         => 'nullable|exists:order_details,id',
            'details.*.service_id' => 'required_with:details|exists:services,id',
            'details.*.quantity'   => 'required_with:details|integer|min:1',
            'details.*.subtotal'   => 'required_with:details|numeric|min:0',
            'deleted_details'      => 'nullable|array',
        ]);

        $order = Order::with('orderDetails')->findOrFail($id);

        // Update data utama
        $order->update([
            'customer_id' => $validated['customer_id'] ?? $order->customer_id,
            'order_at'    => $validated['order_at'] ?? $order->order_at,
            'status'      => $validated['status'] ?? $order->status,
        ]);

        // Update / Tambah detail
        if (!empty($validated['details'])) {
            foreach ($validated['details'] as $detail) {
                if (isset($detail['id'])) {
                    // Update detail lama
                    $order->orderDetails()->where('id', $detail['id'])->update([
                        'service_id' => $detail['service_id'],
                        'quantity'   => $detail['quantity'],
                        'subtotal'   => $detail['subtotal'],
                    ]);
                } else {
                    // Tambah detail baru
                    $order->orderDetails()->create([
                        'service_id' => $detail['service_id'],
                        'quantity'   => $detail['quantity'],
                        'subtotal'   => $detail['subtotal'],
                    ]);
                }
            }
        }

        // Hapus detail jika ada
        if (!empty($validated['deleted_details'])) {
            $order->orderDetails()->whereIn('id', $validated['deleted_details'])->delete();
        }

        // ✅ Hitung ulang total harga & update payment
        $totalPrice = $order->orderDetails()->sum('subtotal');
        $order->update(['total_price' => $totalPrice]);

        // Update amount di payment jika ada
        $payment = Payment::where('order_id', $order->id)->first();
        if ($payment) {
            $payment->update(['amount' => $totalPrice]);
        }

        return response()->json([
            'message' => 'Order berhasil diupdate',
            'data'    => $order->load(['customer', 'user', 'orderDetails.service', 'payments']),
        ]);
    }

    // ✅ Hapus order (beserta detail dan payments)
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->orderDetails()->delete();
        Payment::where('order_id', $id)->delete(); // hapus payment juga
        $order->delete();

        return response()->json([
            'message' => 'Order, detail, dan payment berhasil dihapus',
        ]);
    }
}
