<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - RT/RW Net</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script> --}}
        <script 
    src="https://app.sandbox.midtrans.com/snap/snap.js" 
    data-client-key="{{ config('services.midtrans.client_key') }}">
</script>
</head>

<body class="bg-gradient-to-br from-sky-50 to-blue-100 min-h-screen">

    <div class="bg-gradient-to-r from-sky-500 to-blue-600 text-white py-8 px-4 text-center shadow-lg">
        <h1 class="text-2xl font-bold">🌐 RT/RW Net</h1>
        <p class="text-sky-100 text-sm mt-1">Selesaikan Pembayaran</p>
    </div>

    <div class="max-w-md mx-auto px-4 py-6 space-y-4">

        {{-- Info Transaksi --}}
        <div class="bg-white rounded-2xl shadow-sm p-5">
            <h2 class="font-semibold text-gray-800 mb-4">Detail Pesanan</h2>
            <div class="space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">No Invoice</span>
                    <span class="font-medium">{{ $transaction->invoice }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Nama</span>
                    <span class="font-medium">{{ $customer->name }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Paket</span>
                    <span class="font-medium">{{ $package->name }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Durasi</span>
                    <span class="font-medium">{{ $package->duration_days }} Hari</span>
                </div>
                <div class="border-t pt-3 flex justify-between">
                    <span class="font-semibold">Total Bayar</span>
                    <span class="font-bold text-sky-600 text-lg">
                        Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Tombol Bayar --}}
        <button onclick="payNow()"
            class="w-full bg-gradient-to-r from-sky-500 to-blue-600 text-white py-4 rounded-2xl font-bold text-base hover:from-sky-600 hover:to-blue-700 transition-all shadow-lg">
            💳 Bayar Sekarang
        </button>

        <a href="{{ route('portal.index') }}" class="block text-center text-sm text-gray-500 hover:text-gray-700">
            ← Kembali
        </a>

    </div>
    {{-- Error display --}}
<div id="error-box" style="display:none;background:#fee2e2;border:1px solid #fca5a5;border-radius:12px;padding:16px;margin-bottom:16px;font-size:13px;color:#dc2626;word-break:break-all;">
</div>

    <script>
        console.log('Snap Token:', '{{ $snapToken }}');
        console.log('Client Key:', '{{ config('services.midtrans.client_key') }}');

        function showError(msg) {
            var box = document.getElementById('error-box');
            box.style.display = 'block';
            box.innerHTML = '❌ <strong>Error:</strong><br>' + msg;
            var btn = document.querySelector('button[onclick="payNow()"]');
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '💳 Bayar Sekarang';
            }
        }

        function payNow() {
            var btn = document.querySelector('button[onclick="payNow()"]');
            btn.disabled = true;
            btn.innerHTML = '⏳ Memproses...';
            document.getElementById('error-box').style.display = 'none';

            if (typeof snap === 'undefined') {
                showError('Snap.js tidak load. Pastikan internet aktif lalu refresh halaman.');
                return;
            }

            try {
                snap.pay('{{ $snapToken }}', {
                    onSuccess: function(result) {
                        window.location.href = '{{ route('portal.success', $customer->id) }}';
                    },
                    onPending: function(result) {
                        showError('Pembayaran pending: ' + JSON.stringify(result));
                    },
                    onError: function(result) {
                        showError('Pembayaran gagal: ' + JSON.stringify(result));
                    },
                    onClose: function() {
                        btn.disabled = false;
                        btn.innerHTML = '💳 Bayar Sekarang';
                    }
                });
            } catch (e) {
                showError('Exception: ' + e.message);
            }
        }
    </script>

</body>

</html>
