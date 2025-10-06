<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // List semua order
    public function index()
    {
        $orders = Order::with(['customer', 'user'])->get();
        return response()->json($orders);
    }

    // Detail order
    public function show($id)
    {
        $order = Order::with(['customer', 'user'])->findOrFail($id);
        return response()->json($order);
    }

    // Tambah order
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'user_id'     => 'required|exists:users,id',
            'order_at'    => 'required|date',
            'status'      => 'sometimes|in:diterima,diproses,selesai,diambil',
            'total_price' => 'sometimes|numeric|min:0',
        ]);

        $order = Order::create($validated);

        return response()->json([
            'message' => 'Order berhasil dibuat',
            'data'    => $order
        ], 201);
    }

    // Update order
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'customer_id' => 'sometimes|required|exists:customers,id',
            'user_id'     => 'sometimes|required|exists:users,id',
            'order_at'    => 'sometimes|required|date',
            'status'      => 'sometimes|required|in:diterima,diproses,selesai,diambil',
            'total_price' => 'sometimes|required|numeric|min:0',
        ]);

        $order = Order::findOrFail($id);
        $order->update($validated);

        return response()->json([
            'message' => 'Order berhasil diupdate',
            'data'    => $order
        ]);
    }

    // Hapus order
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return response()->json([
            'message' => 'Order berhasil dihapus'
        ]);
    }
}
