<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berhasil - RT/RW Net</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-sky-50 to-blue-100 min-h-screen">

    <div class="max-w-md mx-auto px-4 py-12">
        <div class="bg-white rounded-2xl shadow-sm p-8 text-center">

            {{-- Icon --}}
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="text-4xl">✅</span>
            </div>

            <h1 class="text-xl font-bold text-gray-800 mb-2">Internet Siap Digunakan!</h1>
            <p class="text-gray-500 text-sm mb-6">Paket kamu sudah aktif. Berikut kredensial login:</p>

            {{-- Kredensial --}}
            <div class="bg-sky-50 border border-sky-200 rounded-xl p-4 text-left space-y-3 mb-6">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Nama</span>
                    <span class="font-semibold">{{ $customer->name }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Username</span>
                    <span class="font-semibold font-mono">{{ $customer->username }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Password</span>
                    <span class="font-semibold font-mono">{{ $customer->password }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Paket</span>
                    <span class="font-semibold">{{ $customer->package->name ?? '-' }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Aktif Hingga</span>
                    <span class="font-semibold">{{ $customer->expired_at?->format('d M Y H:i') }}</span>
                </div>
            </div>

            {{-- Cara Pakai --}}
            <div class="bg-gray-50 rounded-xl p-4 text-left text-sm text-gray-600 mb-6">
                <p class="font-semibold mb-2">📱 Cara Menggunakan:</p>
                <ol class="list-decimal list-inside space-y-1">
                    <li>Buka browser di HP kamu</li>
                    <li>Akan muncul halaman login hotspot</li>
                    <li>Masukkan username dan password di atas</li>
                    <li>Selesai, internet siap digunakan!</li>
                </ol>
            </div>

            <a href="{{ route('portal.index') }}"
                class="block w-full bg-gradient-to-r from-sky-500 to-blue-600 text-white py-3 rounded-xl font-semibold text-sm">
                Kembali ke Portal
            </a>

        </div>
    </div>
{{-- Auto login ke MikroTik setelah bayar --}}
@if(session('hotspot_username'))
<form id="redirectLogin" action="http://192.168.88.1/login" method="post" style="display:none">
    <input type="hidden" name="username" value="{{ session('hotspot_username') }}">
    <input type="hidden" name="password" value="{{ session('hotspot_password') }}">
    <input type="hidden" name="dst" value="https://google.com">
</form>
<script>
    // Auto submit ke MikroTik setelah 2 detik
    setTimeout(function() {
        document.getElementById('redirectLogin').submit();
    }, 2000);
</script>
@endif
</body>
</html>