<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f4f8;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        .header {
            background: linear-gradient(135deg, #0ea5e9, #0284c7);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }
        .header p {
            margin: 8px 0 0;
            opacity: 0.9;
            font-size: 14px;
        }
        .badge {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            border: 2px solid rgba(255,255,255,0.5);
            color: white;
            padding: 6px 16px;
            border-radius: 999px;
            font-size: 13px;
            margin-top: 12px;
        }
        .content {
            padding: 32px 30px;
        }
        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
            color: #374151;
        }
        .info-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            color: #6b7280;
        }
        .info-value {
            font-weight: 600;
            color: #111827;
        }
        .voucher-box {
            background: linear-gradient(135deg, #0ea5e9, #0284c7);
            border-radius: 12px;
            padding: 24px;
            text-align: center;
            margin-bottom: 24px;
            color: white;
        }
        .voucher-box p {
            margin: 0 0 8px;
            font-size: 13px;
            opacity: 0.9;
        }
        .voucher-code {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: 4px;
            font-family: monospace;
        }
        .credentials-box {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
        }
        .credentials-box h3 {
            margin: 0 0 12px;
            font-size: 14px;
            color: #15803d;
        }
        .credentials-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 14px;
        }
        .note {
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 12px;
            padding: 16px;
            font-size: 13px;
            color: #92400e;
            margin-bottom: 24px;
        }
        .footer {
            background: #f8fafc;
            padding: 24px 30px;
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
            border-top: 1px solid #e2e8f0;
        }
        .status-badge {
            display: inline-block;
            background: #dcfce7;
            color: #16a34a;
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        {{-- Header --}}
        <div class="header">
            <h1>✅ Pembayaran Berhasil!</h1>
            <p>Terima kasih telah melakukan pembayaran</p>
            <span class="badge">{{ $transaction->invoice }}</span>
        </div>

        {{-- Content --}}
        <div class="content">
            <p class="greeting">
                Halo <strong>{{ $transaction->customer->name }}</strong>,<br>
                Pembayaran kamu telah kami terima dan paket internet sudah aktif!
            </p>

            {{-- Info Transaksi --}}
            <div class="info-box">
                <div class="info-row">
                    <span class="info-label">No Invoice</span>
                    <span class="info-value">{{ $transaction->invoice }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Paket</span>
                    <span class="info-value">{{ $transaction->package->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Durasi</span>
                    <span class="info-value">{{ $transaction->package->duration_days }} Hari</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Total Bayar</span>
                    <span class="info-value">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Waktu Bayar</span>
                    <span class="info-value">{{ $transaction->paid_at->format('d M Y H:i') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status</span>
                    <span class="status-badge">LUNAS</span>
                </div>
            </div>

            {{-- Voucher Code jika ada --}}
            @if($transaction->customer->voucher_code ?? null)
            <div class="voucher-box">
                <p>Kode Voucher Kamu</p>
                <div class="voucher-code">{{ $transaction->customer->voucher_code }}</div>
            </div>
            @endif

            {{-- Kredensial Login --}}
            <div class="credentials-box">
                <h3>🔐 Kredensial Internet Kamu</h3>
                <div class="credentials-row">
                    <span style="color:#6b7280">Username</span>
                    <strong>{{ $transaction->customer->username }}</strong>
                </div>
                <div class="credentials-row">
                    <span style="color:#6b7280">Password</span>
                    <strong>{{ $transaction->customer->password }}</strong>
                </div>
                <div class="credentials-row">
                    <span style="color:#6b7280">Aktif Hingga</span>
                    <strong>{{ $transaction->customer->expired_at?->format('d M Y H:i') }}</strong>
                </div>
            </div>

            {{-- Cara Pakai --}}
            <div class="note">
                <strong>📱 Cara Menggunakan:</strong><br>
                1. Sambungkan HP ke WiFi jaringan kami<br>
                2. Buka browser, akan otomatis muncul halaman login<br>
                3. Masukkan username dan password di atas<br>
                4. Selesai, internet siap digunakan!
            </div>
        </div>

        {{-- Footer --}}
        <div class="footer">
            <p>Email ini dikirim otomatis oleh sistem <strong>RT/RW Net</strong></p>
            <p>Jika ada pertanyaan, hubungi kami via WhatsApp</p>
        </div>
    </div>
</body>
</html>