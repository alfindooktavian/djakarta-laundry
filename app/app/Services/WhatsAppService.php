<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class WhatsAppService
{
    /**
     * Mengirim pesan konfirmasi pesanan ke pelanggan via WhatsApp.
     *
     * @param  \App\Models\Order  $order
     * @return array
     */
    public function sendOrderMessage(Order $order)
    {
        $customer = $order->customer;
        $details = $order->orderDetails()->with('service')->get();

        if (!$customer || !$customer->phone) {
            return [
                'success' => false,
                'message' => 'Nomor WhatsApp pelanggan tidak ditemukan'
            ];
        }

        // Normalisasi nomor telepon (ubah 08xxxx menjadi 628xxxx)
        $phone = preg_replace('/[^0-9]/', '', $customer->phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        // Format tanggal pemesanan
        $orderDate = $order->order_at
            ? Carbon::parse($order->order_at)->format('d/m/Y')
            : '-';

        // Format rincian layanan
        $detailLines = '';
        foreach ($details as $d) {
            $serviceName = ucfirst($d->service->name ?? '-');
            $detailLines .= "• {$serviceName} x{$d->quantity} — Rp" .
                number_format($d->subtotal, 0, ',', '.') . "\n";
        }

        // Susun template pesan
        $message = 
"✨ *Djakarta Laundry — Konfirmasi Pesanan* ✨

Halo *{$customer->name}*, terima kasih telah menggunakan layanan kami. Berikut detail pesanan Anda:

🧺 *Rincian Pesanan:*
{$detailLines}💰 *Total:* Rp" . number_format($order->total_price, 0, ',', '.') . "
📅 *Tanggal:* {$orderDate}
📦 *Status:* {$order->status}

Terima kasih atas kepercayaan Anda 💚
Kami akan mengabari Anda saat pesanan sudah siap diambil.

_Berikan keharuman terbaik untuk pakaian Anda bersama Djakarta Laundry._";

        try {
            // Periksa koneksi ke WhatsApp Gateway
            $statusResponse = Http::timeout(5)
                ->get(config('whatsapp.url') . '/status')
                ->json();

            if (empty($statusResponse['connected'])) {
                return [
                    'success' => false,
                    'message' => 'WhatsApp gateway belum terhubung'
                ];
            }

            // Kirim pesan
            $response = Http::timeout(10)
                ->asJson()
                ->post(config('whatsapp.url') . '/send', [
                    'to' => $phone,
                    'message' => $message,
                    'secret' => config('whatsapp.secret'),
                ]);

            $data = $response->json();

            // Tambahkan counter jika pengiriman berhasil
            if (!empty($data['success']) && $data['success'] === true) {
                $todayKey = 'wa_sent_messages_' . now()->format('Y-m-d');

                if (!Cache::has($todayKey)) {
                    Cache::put($todayKey, 0);
                }

                Cache::increment($todayKey);
                Cache::put('wa_sent_messages_last', now()->toDateTimeString());
            }

            return $data ?? [
                'success' => false,
                'message' => 'Tidak ada respon dari server WhatsApp'
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengirim pesan WhatsApp'
            ];
        }
    }
}
