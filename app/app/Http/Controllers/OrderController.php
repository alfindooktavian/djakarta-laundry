<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // List semua order
    public function index()
    {
        $orders = Order::with(['customer', 'user', 'orderDetails.service'])->get();
        return response()->json($orders);
    }

    // Detail order
    public function show($id)
    {
        $order = Order::with(['customer', 'user', 'orderDetails.service'])->findOrFail($id);
        return response()->json($order);
    }

    // Tambah order + detail
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

        // Ambil user yang sedang login
        $userId = Auth::id();

        // Hitung total harga otomatis dari subtotal semua detail
        $totalPrice = collect($validated['details'])->sum('subtotal');

        // Simpan order utama
        $order = Order::create([
            'customer_id' => $validated['customer_id'],
            'user_id'     => $userId,
            'order_at'    => $validated['order_at'],
            'status'      => $validated['status'] ?? 'diterima',
            'total_price' => $totalPrice,
        ]);

        // Simpan semua detail
        foreach ($validated['details'] as $detail) {
            $order->orderDetails()->create($detail);
        }

        return response()->json([
            'message' => 'Order dan detail berhasil dibuat',
            'data'    => $order->load(['customer', 'user', 'orderDetails.service'])
        ], 201);
    }

    // Update order
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
    ]);

    $order = Order::with('orderDetails')->findOrFail($id);

    // Update data utama
    $order->update([
        'customer_id' => $validated['customer_id'] ?? $order->customer_id,
        'order_at'    => $validated['order_at'] ?? $order->order_at,
        'status'      => $validated['status'] ?? $order->status,
    ]);

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
                $order->orderDetails()->create($detail);
            }
        }
    }

    // Hapus detail yang dikirim sebagai _hapus_ di frontend (opsional)
    if ($request->filled('deleted_details')) {
        $order->orderDetails()->whereIn('id', $request->deleted_details)->delete();
    }

    // Hitung ulang total harga
    $totalPrice = $order->orderDetails()->sum('subtotal');
    $order->update(['total_price' => $totalPrice]);

    return response()->json([
        'message' => 'Order berhasil diupdate',
        'data'    => $order->load(['customer', 'user', 'orderDetails.service'])
    ]);
}

    // Hapus order
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->orderDetails()->delete(); // hapus detail dulu
        $order->delete();

        return response()->json([
            'message' => 'Order dan detail berhasil dihapus'
        ]);
    }
}
