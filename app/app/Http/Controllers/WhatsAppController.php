<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class WhatsAppController extends Controller
{
    protected string $waServiceUrl;
    protected string $secretKey;

    public function __construct()
    {
        $this->waServiceUrl = rtrim(env('WA_SERVICE_URL', 'http://127.0.0.1:3000'), '/');
        $this->secretKey = env('WA_SECRET_KEY', 'defaultsecret');
    }

    protected function checkSecret(Request $request): void
    {
        $secret = $request->header('X-WA-SECRET') ?? $request->input('secret');
        if ($secret !== $this->secretKey) {
            abort(response()->json(['error' => 'Unauthorized'], 401));
        }
    }

    public function send(Request $request)
    {
        $this->checkSecret($request);

        $validated = $request->validate([
            'number' => 'required|string',
            'message' => 'required|string',
        ]);

        try {
            $response = Http::asJson()->post("{$this->waServiceUrl}/send", [
                'to' => $validated['number'],
                'message' => $validated['message'],
                'secret' => $this->secretKey,
            ]);

            return $response->json();
        } catch (\Throwable $e) {
            Log::error('WA Send Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'Tidak dapat terhubung ke WA Service',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

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

    public function getQR(Request $request)
    {
        $this->checkSecret($request);

        $qr = Cache::get('wa-qr');
        $connected = false;
        $message = null;

        try {
            $response = Http::get("{$this->waServiceUrl}/status");
            if ($response->ok()) {
                $status = $response->json();
                $connected = $status['connected'] ?? false;
                $message = $status['message'] ?? null;
            }
        } catch (\Throwable $e) {
            $message = 'Tidak dapat terhubung ke WA Service';
        }

        return response()->json([
            'success' => true,
            'connected' => $connected,
            'qr' => $qr,
            'message' => $message,
        ]);
    }

    public function status(Request $request)
    {
        $this->checkSecret($request);

        try {
            $response = Http::get("{$this->waServiceUrl}/status");

            if ($response->ok()) {
                return $response->json();
            }

            return response()->json([
                'success' => false,
                'connected' => false,
                'error' => 'WA Service tidak merespons',
            ], $response->status());
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'connected' => false,
                'error' => 'Tidak dapat terhubung ke WA Service',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
}
