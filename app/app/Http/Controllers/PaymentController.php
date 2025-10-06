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
            'order_id' => 'required|exists:orders,id',
            'amount'   => 'required|numeric|min:0',
            'method'   => 'sometimes|in:cash,transfer,ewallet', // default cash
            'status'   => 'sometimes|in:paid,unpaid',           // default unpaid
            'paid_at'  => 'sometimes|date',
        ]);

        $payment = Payment::create($validated);

        return response()->json([
            'message' => 'Pembayaran berhasil dibuat',
            'data'    => $payment
        ], 201);
    }

    // Update pembayaran
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'order_id' => 'sometimes|required|exists:orders,id',
            'amount'   => 'sometimes|required|numeric|min:0',
            'method'   => 'sometimes|in:cash,transfer,ewallet',
            'status'   => 'sometimes|in:paid,unpaid',
            'paid_at'  => 'sometimes|date',
        ]);

        $payment = Payment::findOrFail($id);
        $payment->update($validated);

        return response()->json([
            'message' => 'Pembayaran berhasil diupdate',
            'data'    => $payment
        ]);
    }

    // Hapus pembayaran
    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();

        return response()->json([
            'message' => 'Pembayaran berhasil dihapus'
        ]);
    }
}

