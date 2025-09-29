<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    // Ambil semua pembayaran
    public function index()
    {
        $payments = Payment::with('order')->get();
        return response()->json($payments);
    }

    // Detail pembayaran
    public function show($id)
    {
        $payment = Payment::with('order')->findOrFail($id);
        return response()->json($payment);
    }

    // Tambah pembayaran
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id'     => 'required|exists:orders,id',
            'amount'       => 'required|numeric|min:0',
            'method'       => 'required|in:cash,transfer,ewallet',
            'status'       => 'required|in:paid,unpaid',
            'payment_date' => 'date',
        ]);

        $payment = Payment::create($validated);

        return response()->json([
            'message' => 'Payment created successfully',
            'data'    => $payment
        ], 201);
    }

    // Update pembayaran
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'order_id'     => 'sometimes|required|exists:orders,id',
            'amount'       => 'sometimes|required|numeric|min:0',
            'method'       => 'sometimes|required|in:cash,transfer,ewallet',
            'status'       => 'sometimes|required|in:paid,unpaid',
            'payment_date' => 'sometimes|date',
        ]);

        $payment = Payment::findOrFail($id);
        $payment->update($validated);

        return response()->json([
            'message' => 'Payment updated successfully',
            'data'    => $payment
        ]);
    }

    // Hapus pembayaran
    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();

        return response()->json([
            'message' => 'Payment deleted successfully'
        ]);
    }
}
