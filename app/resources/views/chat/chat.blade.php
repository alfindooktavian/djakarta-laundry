@extends('layouts.app')

@section('title', 'Manajemen WhatsApp')

@section('content')
<div class="container mx-auto py-6 space-y-6">

    <div class="flex justify-between items-center">
        <div>
            <h3 class="text-2xl font-semibold text-gray-800">Manajemen WhatsApp</h3>
            <p class="text-gray-500 text-sm">Pantau koneksi gateway WhatsApp dan jumlah pesan yang terkirim.</p>
        </div>

        <button id="refresh-qr-btn" 
            class="flex items-center gap-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition disabled:opacity-60 disabled:cursor-not-allowed">
            <svg id="refresh-icon" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9M20 20v-5h-.581m-15.357-2a8.003 8.003 0 0115.356-2"/>
            </svg>
            <span id="refresh-text">Reload Data</span>
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="p-6 rounded-lg shadow-sm bg-green-100 border border-green-200 text-center">
            <h5 class="text-lg font-bold text-gray-800 mb-2">Status Koneksi</h5>
            <p id="connection-status" class="font-semibold text-gray-700">Memeriksa koneksi...</p>
        </div>

        <div class="p-6 rounded-lg shadow-sm bg-blue-100 border border-blue-200 text-center">
            <h5 class="text-lg font-bold text-gray-800 mb-2">Pesan Terkirim</h5>
            <p id="message-count" class="font-semibold text-gray-700">0</p>
        </div>
    </div>

    <div class="p-6 border-2 border-dashed border-gray-300 rounded-lg shadow-sm bg-white">
        <div class="flex flex-col md:flex-row items-center md:items-start md:justify-between">
            
            <div class="flex justify-center md:justify-start md:w-1/3 mb-4 md:mb-0">
                <canvas id="qrcode" width="230" height="230" class="border rounded bg-gray-50 shadow-sm"></canvas>
            </div>

            <div class="md:w-2/3 md:pl-6 text-center md:text-left">
                <h5 class="text-lg font-bold text-gray-800 mb-2">QR Code Sesi WhatsApp</h5>
                <p class="text-gray-600 text-sm mb-2">
                    Pindai kode di samping menggunakan aplikasi <strong>WhatsApp</strong> Anda untuk menyambungkan perangkat.
                </p>
                <p class="text-gray-500 text-sm mb-4">
                    Pastikan koneksi gateway Anda aktif saat memindai QR ini.<br>
                    Setelah tersambung, klik tombol <strong>Reload Data</strong> untuk memperbarui status sesi.
                </p>
                <p id="qr-status" class="text-sm text-gray-500 italic mb-4">Memuat QR Code...</p>

                <button id="manual-refresh-btn" 
                    class="inline-flex items-center gap-2 px-4 py-2 border border-gray-700 text-gray-700 rounded-lg hover:bg-gray-700 hover:text-white transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9M20 20v-5h-.581m-15.357-2a8.003 8.003 0 0115.356-2"/>
                    </svg>
                    Minta QR Baru
                </button>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
<script>
const SECRET_KEY = "{{ env('WA_SECRET_KEY') }}";
const canvas = document.getElementById('qrcode');
const qrStatus = document.getElementById('qr-status');
const connectionStatus = document.getElementById('connection-status');
const messageCount = document.getElementById('message-count');
const refreshBtn = document.getElementById('refresh-qr-btn');
const refreshText = document.getElementById('refresh-text');
const refreshIcon = document.getElementById('refresh-icon');

let lastQR = null;
let connected = false;
let qrInterval = null;

// Ambil data koneksi & pesan
async function loadStatus() {
    try {
        const res = await fetch('/api/wa/status', { headers: { 'X-WA-SECRET': SECRET_KEY } });
        const data = await res.json();
        connected = data.connected ?? false;

        if (connected) {
            connectionStatus.textContent = 'Terhubung ke WhatsApp';
            connectionStatus.className = 'font-semibold text-green-800';
        } else {
            connectionStatus.textContent = 'Terputus dari WhatsApp';
            connectionStatus.className = 'font-semibold text-red-600';
        }
    } catch {
        connectionStatus.textContent = 'Gagal memeriksa koneksi.';
        connectionStatus.className = 'font-semibold text-red-600';
    }

    try {
        const countRes = await fetch('/api/wa/count', { headers: { 'X-WA-SECRET': SECRET_KEY } });
        const countData = await countRes.json();
        messageCount.textContent = countData.sent_messages ?? 0;
    } catch {
        messageCount.textContent = '0';
    }
}

// Ambil QR
async function loadQR() {
    try {
        const res = await fetch('/api/wa/qr', { headers: { 'X-WA-SECRET': SECRET_KEY } });
        const data = await res.json();
        connected = data.connected ?? false;

        if (connected) {
            clearCanvas();
            qrStatus.textContent = 'WhatsApp sudah terhubung.';
            if (qrInterval) clearInterval(qrInterval);
            return;
        }

        if (data.qr) {
            connected = false;
            connectionStatus.textContent = 'Terputus dari WhatsApp';
            connectionStatus.className = 'font-semibold text-red-800';
        }

        if (data.qr && data.qr !== lastQR) {
            lastQR = data.qr;
            qrStatus.textContent = "Silakan scan QR dengan aplikasi WhatsApp Anda.";
            clearCanvas();

            if (data.qr.startsWith('data:image')) {
                const ctx = canvas.getContext("2d");
                const img = new Image();
                img.onload = () => ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                img.src = data.qr;
            } else {
                QRCode.toCanvas(canvas, data.qr.trim(), (err) => {});
            }
        } else if (!data.qr && !connected) {
            qrStatus.textContent = "QR belum tersedia, tunggu sebentar...";
            clearCanvas();
        }
    } catch {
        qrStatus.textContent = "Tidak dapat terhubung ke server WhatsApp.";
    }
}

function clearCanvas() {
    const ctx = canvas.getContext("2d");
    ctx.clearRect(0, 0, canvas.width, canvas.height);
}

async function refreshQR() {
    try {
        await fetch('/api/wa/qr', { headers: { 'X-WA-SECRET': SECRET_KEY } });
        await loadQR();
    } catch {
        console.error('Gagal meminta QR baru');
    }
}

// Tombol interaktif Reload Data
async function reloadDataInteractive() {
    refreshBtn.disabled = true;
    refreshText.textContent = 'Memuat...';
    refreshIcon.classList.add('animate-spin');

    try {
        await Promise.all([loadStatus(), loadQR()]);
        refreshText.textContent = 'Berhasil Diperbarui';
        refreshBtn.classList.replace('bg-blue-500', 'bg-green-500');
    } catch {
        refreshText.textContent = 'Gagal Memuat';
        refreshBtn.classList.replace('bg-blue-500', 'bg-red-500');
    }

    refreshIcon.classList.remove('animate-spin');

    setTimeout(() => {
        refreshBtn.disabled = false;
        refreshText.textContent = 'Reload Data';
        refreshBtn.classList.remove('bg-green-500', 'bg-red-500');
        refreshBtn.classList.add('bg-blue-500');
    }, 2000);
}

document.getElementById('refresh-qr-btn').addEventListener('click', reloadDataInteractive);
document.getElementById('manual-refresh-btn').addEventListener('click', refreshQR);

// Load awal
loadStatus();
loadQR();

// Auto-refresh setiap 30 detik
qrInterval = setInterval(() => {
    if (!connected) loadQR();
    loadStatus();
}, 30000);
</script>
@endsection
