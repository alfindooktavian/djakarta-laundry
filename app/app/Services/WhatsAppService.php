<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class WhatsAppService
{
    public function sendOrderMessage(Order $order): array
    {
        $customer = $order->customer;
        $details = $order->orderDetails()->with('service')->get();

        if (!$customer || !$customer->phone) {
            return ['success' => false, 'message' => 'Nomor WhatsApp pelanggan tidak ditemukan'];
        }

        $phone = preg_replace('/[^0-9]/', '', $customer->phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        $orderDate = $order->order_at ? Carbon::parse($order->order_at)->format('d/m/Y') : '-';

        $detailLines = '';
        foreach ($details as $d) {
            $serviceName = ucfirst($d->service->name ?? '-');
            $detailLines .= sprintf(
                "%s x%d — Rp%s\n",
                $serviceName,
                $d->quantity,
                number_format($d->subtotal, 0, ',', '.')
            );
        }

        $message = "DJAKARTA LAUNDRY — KONFIRMASI PESANAN\n\n";
        $message .= "Pelanggan : {$customer->name}\n";
        $message .= "No. Order : #{$order->id}\n";
        $message .= "Tanggal    : {$orderDate}\n\n";
        $message .= "RINCIAN PESANAN:\n";
        $message .= $detailLines . "\n";
        $message .= "TOTAL      : Rp" . number_format($order->total_price, 0, ',', '.') . "\n";
        $message .= "STATUS     : {$order->status}\n\n";
        $message .= "Terima kasih telah mempercayakan cucian Anda kepada Djakarta Laundry.\n";
        $message .= "Jika ada perubahan atau pertanyaan, balas pesan ini atau hubungi: 0812-XXXX-XXXX.\n";
        $message .= "Harap simpan nomor order untuk keperluan penjemputan/pengambilan.";

        try {
            $statusResponse = Http::timeout(5)->get(config('whatsapp.url') . '/status')->json();

            if (empty($statusResponse['connected'])) {
                return ['success' => false, 'message' => 'WhatsApp gateway belum terhubung'];
            }

            $response = Http::timeout(10)
                ->asJson()
                ->post(config('whatsapp.url') . '/send', [
                    'to' => $phone,
                    'message' => $message,
                    'secret' => config('whatsapp.secret'),
                ]);

            $data = $response->json();

            if (!empty($data['success']) && $data['success'] === true) {
                $todayKey = 'wa_sent_messages_' . now()->format('Y-m-d');
                if (!Cache::has($todayKey)) {
                    Cache::put($todayKey, 0);
                }
                Cache::increment($todayKey);
                Cache::put('wa_sent_messages_last', now()->toDateTimeString());
            }

            return $data ?? ['success' => false, 'message' => 'Tidak ada respon dari server WhatsApp'];
        } catch (\Throwable $e) {
            Log::warning('Gagal mengirim pesan WhatsApp', ['order_id' => $order->id, 'error' => $e->getMessage()]);
            return ['success' => false, 'message' => 'Gagal mengirim pesan WhatsApp'];
        }
    }

    public function sendOrderCompletedMessage(Order $order): array
    {
        $customer = $order->customer;

        if (!$customer || !$customer->phone) {
            return ['success' => false, 'message' => 'Nomor WhatsApp pelanggan tidak ditemukan'];
        }

        $phone = preg_replace('/[^0-9]/', '', $customer->phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        $completedAt = $order->updated_at ? Carbon::parse($order->updated_at)->format('d/m/Y H:i') : now()->format('d/m/Y H:i');

        $message = "DJAKARTA LAUNDRY — PESANAN SIAP DIAMBIL\n\n";
        $message .= "Pelanggan : {$customer->name}\n";
        $message .= "No. Order : #{$order->id}\n";
        $message .= "Selesai    : {$completedAt}\n\n";
        $message .= "RINCIAN PESANAN:\n";

        foreach ($order->orderDetails()->with('service')->get() as $d) {
            $serviceName = ucfirst($d->service->name ?? '-');
            $message .= sprintf(
                "%s x%d — Rp%s\n",
                $serviceName,
                $d->quantity,
                number_format($d->subtotal, 0, ',', '.')
            );
        }

        $message .= "\nTOTAL      : Rp" . number_format($order->total_price, 0, ',', '.') . "\n\n";
        $message .= "Pesanan Anda sudah selesai dan dapat diambil di lokasi kami.\n";
        $message .= "Bawa bukti order atau sebutkan No. Order saat pengambilan.\n";
        $message .= "Jika ingin layanan antar, hubungi: 0812-XXXX-XXXX.\n";
        $message .= "Terima kasih atas kepercayaan Anda.";

        try {
            $response = Http::timeout(10)
                ->asJson()
                ->post(config('whatsapp.url') . '/send', [
                    'to' => $phone,
                    'message' => $message,
                    'secret' => config('whatsapp.secret'),
                ]);

            $data = $response->json();

            if (!empty($data['success']) && $data['success'] === true) {
                $todayKey = 'wa_sent_messages_' . now()->format('Y-m-d');
                if (!Cache::has($todayKey)) {
                    Cache::put($todayKey, 0);
                }
                Cache::increment($todayKey);
                Cache::put('wa_sent_messages_last', now()->toDateTimeString());
            }

            return $data ?? ['success' => false, 'message' => 'Tidak ada respon dari server WhatsApp'];
        } catch (\Throwable $e) {
            Log::warning('Gagal mengirim pesan WhatsApp selesai', ['order_id' => $order->id, 'error' => $e->getMessage()]);
            return ['success' => false, 'message' => 'Gagal mengirim pesan WhatsApp'];
        }
    }
}
