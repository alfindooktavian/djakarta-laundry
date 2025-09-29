<?php

namespace App\Http\Controllers;

use App\Models\OrderDetail;
use Illuminate\Http\Request;

class OrderDetailController extends Controller
{
    // Ambil semua order detail
    public function index()
    {
        $orderDetails = OrderDetail::with(['order', 'service'])->get();
        return response()->json($orderDetails);
    }

    // Detail order detail
    public function show($id)
    {
        $orderDetail = OrderDetail::with(['order', 'service'])->findOrFail($id);
        return response()->json($orderDetail);
    }

    // Tambah order detail
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id'   => 'required|exists:orders,id',
            'service_id' => 'required|exists:services,id',
            'quantity'   => 'required|integer|min:1',
            'subtotal'   => 'required|numeric|min:0',
        ]);

        $orderDetail = OrderDetail::create($validated);

        return response()->json([
            'message' => 'Order detail created successfully',
            'data'    => $orderDetail
        ], 201);
    }

    // Update order detail
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'order_id'   => 'sometimes|required|exists:orders,id',
            'service_id' => 'sometimes|required|exists:services,id',
            'quantity'   => 'sometimes|required|integer|min:1',
            'subtotal'   => 'sometimes|required|numeric|min:0',
        ]);

        $orderDetail = OrderDetail::findOrFail($id);
        $orderDetail->update($validated);

        return response()->json([
            'message' => 'Order detail updated successfully',
            'data'    => $orderDetail
        ]);
    }

    // Hapus order detail
    public function destroy($id)
    {
        $orderDetail = OrderDetail::findOrFail($id);
        $orderDetail->delete();

        return response()->json([
            'message' => 'Order detail deleted successfully'
        ]);
    }
}
