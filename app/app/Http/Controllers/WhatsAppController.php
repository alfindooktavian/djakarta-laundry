<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WhatsAppController extends Controller
{
    protected string $waServiceUrl;
    protected string $secretKey;

    public function __construct()
    {
        $this->waServiceUrl = rtrim(env('WA_SERVICE_URL', 'http://127.0.0.1:3000'), '/');
        $this->secretKey = env('WA_SECRET_KEY', 'defaultsecret');
    }

    /**
     * Validasi secret agar API tidak bisa diakses publik.
     */
    protected function checkSecret(Request $request): void
    {
        $secret = $request->header('X-WA-SECRET') ?? $request->input('secret');
        if ($secret !== $this->secretKey) {
            abort(response()->json(['error' => 'Unauthorized'], 401));
        }
    }

    /**
     * Menerima QR code dari service Node.js (disimpan sementara di cache)
     */
    public function receiveQR(Request $request)
    {
        $this->checkSecret($request);

        $request->validate(['qr' => 'required|string']);
        Cache::put('wa-qr', $request->qr, now()->addMinutes(2));

        return response()->json([
            'success' => true,
            'message' => 'QR diterima dan disimpan di cache',
        ]);
    }

    /**
     * Mengambil QR code terakhir dan status koneksi WhatsApp.
     */
    public function getQR(Request $request)
    {
        $this->checkSecret($request);

        $qr = Cache::get('wa-qr');
        $connected = false;
        $message = null;

        try {
            $response = Http::timeout(5)->get("{$this->waServiceUrl}/status");
            if ($response->ok()) {
                $status = $response->json();
                $connected = $status['connected'] ?? false;
                $message = $status['message'] ?? null;
            }
        } catch (\Throwable $e) {
            $message = 'Tidak dapat terhubung ke WhatsApp Service';
        }

        return response()->json([
            'success' => true,
            'connected' => $connected,
            'qr' => $qr,
            'message' => $message,
        ]);
    }

    /**
     * Mengecek status koneksi WhatsApp Gateway.
     */
    public function status(Request $request)
    {
        $this->checkSecret($request);

        try {
            $response = Http::timeout(5)->get("{$this->waServiceUrl}/status");

            if ($response->ok()) {
                $status = $response->json();
                $connected = $status['connected'] ?? false;

                // Jika gateway terputus, reset counter otomatis
                if (!$connected) {
                    $todayKey = 'wa_sent_messages_' . now()->format('Y-m-d');
                    Cache::forget($todayKey);
                    Cache::forget('wa_sent_messages_last');
                }

                return response()->json([
                    'success' => true,
                    'connected' => $connected,
                ]);
            }

            return response()->json([
                'success' => false,
                'connected' => false,
                'error' => 'WA Service tidak merespons',
            ], $response->status());
        } catch (\Throwable $e) {
            // Reset counter juga kalau tidak bisa konek ke service
            $todayKey = 'wa_sent_messages_' . now()->format('Y-m-d');
            Cache::forget($todayKey);
            Cache::forget('wa_sent_messages_last');

            return response()->json([
                'success' => false,
                'connected' => false,
                'error' => 'Tidak dapat terhubung ke WA Service',
            ], 500);
        }
    }


    /**
     * Mengambil jumlah pesan terkirim hari ini.
     */
    public function count(Request $request)
    {
        $this->checkSecret($request);

        $todayKey = 'wa_sent_messages_' . now()->format('Y-m-d');
        $count = Cache::get($todayKey, 0);
        $lastSent = Cache::get('wa_sent_messages_last');

        return response()->json([
            'success' => true,
            'date' => now()->format('d/m/Y'),
            'sent_messages' => $count,
            'last_message_at' => $lastSent,
        ]);
    }
}
