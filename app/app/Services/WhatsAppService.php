<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class WhatsAppService
{
    public function sendOrderMessage(Order $order)
    {
        $customer = $order->customer;
        $details = $order->orderDetails()->with('service')->get();

        if (!$customer || !$customer->phone) {
            return ['success' => false, 'message' => 'Nomor WhatsApp pelanggan tidak ditemukan'];
        }

        // Normalisasi nomor HP (ubah 08xxx ke 628xxx)
        $phone = preg_replace('/[^0-9]/', '', $customer->phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        // Format tanggal order
        $orderDate = $order->order_at
            ? Carbon::parse($order->order_at)->format('d/m/Y')
            : '-';

        // Format detail layanan
        $detailLines = '';
        foreach ($details as $d) {
            $namaLayanan = ucfirst($d->service->name ?? '-');
            $detailLines .= "• {$namaLayanan} x{$d->quantity} — Rp" .
                number_format($d->subtotal, 0, ',', '.') . "\n";
        }

        $message =
"✨ *Djakarta Laundry — Konfirmasi Pesanan* ✨

Halo *{$customer->name}*, terima kasih telah menggunakan layanan kami. Berikut detail pesanan Anda:

🧺 *Rincian Pesanan:*
{$detailLines}
💰 *Total:* Rp" . number_format($order->total_price, 0, ',', '.') . "
📅 *Tanggal:* {$orderDate}
📦 *Status:* {$order->status}

Terima kasih atas kepercayaan Anda 💚
Kami akan mengabari Anda saat pesanan sudah siap diambil.

_Berikan keharuman terbaik untuk pakaian Anda bersama Djakarta Laundry._";

        try {
            $status = Http::timeout(5)
                ->get(config('whatsapp.url') . '/status')
                ->json();

            if (empty($status['connected'])) {
                return ['success' => false, 'message' => 'WhatsApp gateway belum terhubung'];
            }

            $response = Http::timeout(10)
                ->asJson()
                ->post(config('whatsapp.url') . '/send', [
                    'to' => $phone,
                    'message' => $message,
                    'secret' => config('whatsapp.secret'),
                ]);

            return $response->json() ?? ['success' => false, 'message' => 'Tidak ada respon dari server'];
        } catch (\Throwable $e) {
            return ['success' => false, 'message' => 'Gagal mengirim pesan WhatsApp'];
        }
    }
}
