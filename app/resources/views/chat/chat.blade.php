@extends('layouts.app')

@section('title', 'WhatsApp Login')

@section('content')
<div class="container text-center mt-5">
    <h3 class="mb-3">Scan QR WhatsApp untuk Login</h3>

    <div id="qr-container" class="d-flex justify-content-center mb-3">
        <canvas id="qrcode" width="250" height="250" class="border rounded shadow-sm bg-light"></canvas>
    </div>

    <p id="status" class="mt-3 text-muted">⏳ Memuat QR Code...</p>
</div>

<script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
<script>
    // Ambil secret dari backend (disuntik lewat Blade agar aman)
    const SECRET_KEY = @json(env('WA_SECRET_KEY'));

    const canvas = document.getElementById('qrcode');
    const statusText = document.getElementById('status');
    let lastQR = null;
    let ready = false;

    async function loadQR() {
        try {
            const res = await fetch('/api/wa-qr', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-WA-SECRET': SECRET_KEY, // 🔥 wajib! untuk lolos dari validasi Laravel
                }
            });

            // Jika gagal ambil data
            if (!res.ok) {
                statusText.innerText = `⚠️ Gagal ambil QR (${res.status})`;
                console.error('Response error:', res.status);
                return;
            }

            const data = await res.json();

            // ✅ Jika sudah login (client WhatsApp siap)
            if (data.ready) {
                if (!ready) {
                    ready = true;
                    statusText.innerHTML = `<span class="text-success">✅ WhatsApp sudah terhubung!</span>`;
                    clearCanvas();
                }
                return;
            }

            // ✅ Jika QR tersedia dan berbeda dari sebelumnya
            if (data.qr && data.qr !== lastQR) {
                lastQR = data.qr;
                ready = false;
                statusText.innerText = "📱 Silakan scan QR dengan aplikasi WhatsApp Anda";

                clearCanvas();
                QRCode.toCanvas(canvas, data.qr.trim(), (error) => {
                    if (error) {
                        console.error("QR Error:", error);
                        statusText.innerText = "❌ Gagal generate QR";
                    }
                });
            } 
            // ⏳ Jika QR belum tersedia
            else if (!data.qr && !ready) {
                statusText.innerText = "⏳ QR belum tersedia, tunggu sebentar...";
                clearCanvas();
            }

        } catch (error) {
            console.error("Fetch Error:", error);
            statusText.innerText = "⚠️ Tidak dapat terhubung ke server Laravel / WA Service";
        }
    }

    function clearCanvas() {
        const ctx = canvas.getContext("2d");
        ctx.clearRect(0, 0, canvas.width, canvas.height);
    }

    // Jalankan load pertama kali
    loadQR();

    // Cek QR setiap 3 detik
    setInterval(loadQR, 3000);
</script>
@endsection
