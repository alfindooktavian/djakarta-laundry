<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WhatsAppController extends Controller
{
    protected $waServiceUrl;
    protected $secretKey;

    public function __construct()
    {
        $this->waServiceUrl = rtrim(env('WA_SERVICE_URL', 'http://127.0.0.1:3000'), '/');
        $this->secretKey = env('WA_SECRET_KEY', 'defaultsecret');
    }

    /**
     * Validasi secret key di header / body.
     */
    protected function checkSecret(Request $request)
    {
        $secret = $request->header('X-WA-SECRET') ?? $request->input('secret');

        if ($secret !== $this->secretKey) {
            abort(response()->json(['error' => 'Unauthorized'], 401));
        }
    }

    /**
     * Kirim pesan ke WA Service.
     */
    public function send(Request $request)
    {
        $this->checkSecret($request);

        $validated = $request->validate([
            'number' => 'required|string',
            'message' => 'required|string',
        ]);

        try {
            $response = Http::post("{$this->waServiceUrl}/api/send-message", [
                'number' => $validated['number'],
                'message' => $validated['message'],
            ]);

            return $response->json();
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal mengirim pesan ke server WhatsApp',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Node.js mengirim QR ke Laravel.
     */
    public function receiveQR(Request $request)
    {
        $this->checkSecret($request);

        $request->validate([
            'qr' => 'required|string',
        ]);

        Cache::put('wa-qr', $request->qr, now()->addMinutes(2));

        return response()->json(['success' => true]);
    }

    /**
     * Frontend ambil QR dan status dari Laravel.
     */
    public function getQR(Request $request)
    {
        $this->checkSecret($request);

        $qr = Cache::get('wa-qr');
        $status = ['ready' => false, 'connected' => false];

        try {
            $response = Http::get("{$this->waServiceUrl}/api/status");
            if ($response->ok()) {
                $status = $response->json();
            }
        } catch (\Exception $e) {
            $status['error'] = 'Tidak dapat terhubung ke WA Service';
        }

        return response()->json([
            'qr' => $qr,
            'ready' => $status['ready'] ?? false,
            'connected' => $status['connected'] ?? false,
            'number' => $status['number'] ?? null,
        ]);
    }

    /**
     * Ambil status WhatsApp (langsung dari WA Service).
     */
    public function status(Request $request)
    {
        $this->checkSecret($request);

        try {
            $response = Http::get("{$this->waServiceUrl}/api/status");
            return $response->json();
        } catch (\Exception $e) {
            return response()->json([
                'ready' => false,
                'connected' => false,
                'error' => 'Tidak dapat terhubung ke WA Service',
            ]);
        }
    }
}
