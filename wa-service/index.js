const { Client, LocalAuth } = require('whatsapp-web.js');
const express = require('express');
const axios = require('axios');
const app = express();
app.use(express.json());

const client = new Client({
    authStrategy: new LocalAuth()
});

let lastSentTime = 0;

client.on('qr', (qr) => {
    const now = Date.now();
    if (now - lastSentTime > 120000) { 
        lastSentTime = now;
        console.log('QR RECEIVED (sending to Laravel)');
        axios.post('http://127.0.0.1:8000/api/wa-qr', { qr })
            .then(() => console.log('QR sent to Laravel'))
            .catch(err => console.error('Error sending QR:', err.message));
    } else {
        console.log('QR diterima tapi di-skip (masih dalam interval 2 menit)');
    }
});

client.on('ready', () => {
    console.log('WhatsApp Client Ready!');
});

app.post('/send-message', async (req, res) => {
    try {
        const { number, message } = req.body;
        const chatId = number + "@c.us"; 

        console.log("equest kirim pesan:", chatId, message);

        const response = await client.sendMessage(chatId, message);

        console.log("esan terkirim, ID:", response.id.id);

        res.json({
            success: true,
            to: number,
            message: message,
            messageId: response.id.id
        });
    } catch (err) {
        console.error("Gagal kirim pesan:", err);
        res.status(500).json({
            success: false,
            error: err.message
        });
    }
});

client.initialize();

app.listen(3000, () => {
    console.log('WA Service running on http://localhost:3000');
});
