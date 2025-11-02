import express from 'express'
import pino from 'pino'
import qrcode from 'qrcode'
import cors from 'cors'
import bodyParser from 'body-parser'
import dotenv from 'dotenv'
import { Boom } from '@hapi/boom'
import makeWASocket, {
  useMultiFileAuthState,
  Browsers,
  DisconnectReason
} from '@whiskeysockets/baileys'
import fs from 'fs'

dotenv.config()

const app = express()
app.use(cors())
app.use(bodyParser.json())

const PORT = process.env.PORT || 3000
const SECRET_KEY = process.env.WA_SECRET_KEY || 'defaultsecret'
const LARAVEL_URL = process.env.LARAVEL_URL || ''
const BROWSER_NAME = process.env.BROWSER_NAME || 'Chrome'

let sock
let qrCodeData = null
let isConnected = false
let reconnecting = false

async function startSock() {
  const { state, saveCreds } = await useMultiFileAuthState('auth')

  sock = makeWASocket({
    auth: state,
    logger: pino({ level: 'silent' }),
    browser: Browsers.macOS(BROWSER_NAME),
    markOnlineOnConnect: false,
    syncFullHistory: false,
    getMessage: async () => ({})
  })

  sock.ev.on('creds.update', saveCreds)

  sock.ev.on('connection.update', async (update) => {
    const { connection, qr, lastDisconnect } = update

    if (qr) {
      qrCodeData = await qrcode.toDataURL(qr)

      if (LARAVEL_URL) {
        try {
          await fetch(`${LARAVEL_URL}/api/wa/receive-qr`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-WA-SECRET': SECRET_KEY
            },
            body: JSON.stringify({ qr: qrCodeData })
          })
        } catch {}
      }
    }

    if (connection === 'open') {
      isConnected = true
      reconnecting = false
      qrCodeData = null
    }

    if (connection === 'close') {
      isConnected = false
      const reason = new Boom(lastDisconnect?.error)?.output?.statusCode

      switch (reason) {
        case DisconnectReason.badSession:
          fs.rmSync('./auth', { recursive: true, force: true })
          return startSock()
        case DisconnectReason.connectionClosed:
        case DisconnectReason.connectionLost:
        case DisconnectReason.restartRequired:
          return reconnect()
        case DisconnectReason.loggedOut:
          fs.rmSync('./auth', { recursive: true, force: true })
          return startSock()
        default:
          reconnect()
      }
    }
  })
}

function reconnect() {
  if (!reconnecting) {
    reconnecting = true
    setTimeout(() => {
      reconnecting = false
      startSock()
    }, 5000)
  }
}

startSock()

app.get('/qr', (req, res) => {
  res.json({
    success: true,
    connected: isConnected,
    qr: qrCodeData,
    message: qrCodeData ? 'QR tersedia, silakan scan.' : 'Sudah login atau QR belum dibuat.'
  })
})

app.get('/refresh', async (req, res) => {
  try {
    if (fs.existsSync('./auth')) {
      fs.rmSync('./auth', { recursive: true, force: true })
    }
    await startSock()
    res.json({ success: true, message: 'QR baru dibuat.' })
  } catch (err) {
    res.status(500).json({ success: false, error: err.message })
  }
})

app.get('/status', (req, res) => {
  res.json({
    success: true,
    connected: isConnected
  })
})

app.post('/send', async (req, res) => {
  const { to, message, secret } = req.body
  try {
    if (secret !== SECRET_KEY) {
      return res.status(401).json({ success: false, error: 'Unauthorized' })
    }

    if (!isConnected) throw new Error('Belum terkoneksi ke WhatsApp')

    await sock.sendMessage(`${to}@s.whatsapp.net`, { text: message })
    res.json({ success: true, to, message })
  } catch (err) {
    res.status(500).json({ success: false, error: err.message })
  }
})

app.listen(PORT, () => {
  console.log(`WA Service running on port ${PORT}`)
})
