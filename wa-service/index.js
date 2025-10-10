// index.js
const { Client, LocalAuth } = require('whatsapp-web.js');
const express = require('express');
const axios = require('axios');
const cors = require('cors');
require('dotenv').config();

const app = express();
app.use(express.json());
app.use(cors());

// simpan auth
const client = new Client({
    authStrategy: new LocalAuth(), 
    puppeteer: {
        headless: true,
        args: ['--no-sandbox', '--disable-setuid-sandbox'],
    },
});

let lastSentTime = 0;
let currentQR = null;
let isReady = false;


// Saat QR muncul 
client.on('qr', (qr) => {
    currentQR = qr;
    const now = Date.now();

    if (now - lastSentTime > 120000) { // interval 2m
        lastSentTime = now;
        console.log('QR RECEIVED (sending to Laravel)...');

        axios.post(`${process.env.LARAVEL_URL}/api/wa-qr`, { 
            qr, 
            secret: process.env.WA_SECRET_KEY 
        })
        .then(() => console.log('QR sent to Laravel'))
        .catch(err => console.error('Error sending QR:', err.message));
    } else {
        console.log('QR diterima tapi di-skip (masih dalam interval 2 menit)');
    }
});

// Saat sudah login & siap
client.on('ready', () => {
    isReady = true;
    console.log('WhatsApp Client Ready!');
});

// Saat autentikasi berhasil
client.on('authenticated', () => {
    console.log('WhatsApp authenticated (session tersimpan)');
});

// Saat koneksi putus
client.on('disconnected', (reason) => {
    isReady = false;
    console.log(`WhatsApp Disconnected (${reason}), mencoba reconnect dalam 5 detik...`);
    setTimeout(() => client.initialize(), 5000);
});


// API Routes

// Kirim pesan WhatsApp
app.post('/api/send-message', async (req, res) => {
    try {
        const { number, message } = req.body;

        if (!number || !message) {
            return res.status(400).json({ success: false, error: "Number dan message wajib diisi" });
        }

        if (!isReady) {
            return res.status(503).json({ success: false, error: "WhatsApp belum siap" });
        }

        const chatId = number.includes('@c.us') ? number : `${number}@c.us`;
        const response = await client.sendMessage(chatId, message);

        console.log(`Pesan terkirim ke ${number}: ${message}`);

        res.json({
            success: true,
            to: number,
            message: message,
            messageId: response.id.id,
        });
    } catch (err) {
        console.error("Gagal kirim pesan:", err);
        res.status(500).json({ success: false, error: err.message });
    }
});

//Ambil QR terbaru 
app.get('/api/qr', (req, res) => {
    res.json({
        ready: isReady,
        qr: currentQR,
    });
});

// Cek status koneksi WhatsApp
app.get('/api/status', (req, res) => {
    res.json({
        ready: isReady,
        connected: !!client.info,
        number: client.info ? client.info.wid.user : null,
    });
});

// Inisialisasi Client
client.initialize();

// status
const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
    console.log(`WA Service running on http://localhost:${PORT}`);
});

// eror umum
process.on('unhandledRejection', (reason, promise) => {
    console.error('Unhandled Rejection:', reason);
});
