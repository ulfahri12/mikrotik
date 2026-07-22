<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fahri.Net - Pilih Paket</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        body {
            background: linear-gradient(135deg, #f0f4ff 0%, #e8f0fe 50%, #f5f0ff 100%);
            min-height: 100vh;
        }

        .hero-gradient {
            background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 40%, #4f46e5 100%);
        }

        .card-base {
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 20px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .card-base:hover {
            border-color: #93c5fd;
            box-shadow: 0 20px 60px rgba(37,99,235,0.12);
            transform: translateY(-4px);
        }

        .card-popular {
            border-color: #2563eb !important;
            box-shadow: 0 20px 60px rgba(37,99,235,0.2) !important;
        }

        .card-popular::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 4px;
            background: linear-gradient(90deg, #2563eb, #4f46e5);
        }

        .popular-badge {
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            color: white;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.5px;
            padding: 4px 12px;
            border-radius: 999px;
        }

        .price-tag {
            font-size: 38px;
            font-weight: 900;
            color: #111827;
            line-height: 1;
        }

        .btn-select {
            width: 100%;
            padding: 13px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            border: 2px solid #2563eb;
            color: #2563eb;
            background: white;
            transition: all 0.3s;
            letter-spacing: 0.3px;
        }

        .btn-select:hover {
            background: #2563eb;
            color: white;
        }

        .card-popular .btn-select {
            background: #2563eb;
            color: white;
        }

        .card-popular .btn-select:hover {
            background: #1d4ed8;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #374151;
            padding: 4px 0;
        }

        .feature-check {
            width: 18px; height: 18px;
            background: #dbeafe;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            color: #2563eb;
            font-size: 10px;
            font-weight: 800;
        }

        .speed-meter {
            background: #f1f5f9;
            border-radius: 999px;
            height: 6px;
            overflow: hidden;
        }

        .speed-fill {
            height: 100%;
            border-radius: 999px;
            background: linear-gradient(90deg, #2563eb, #4f46e5);
        }

        .btn-checkout {
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            color: white;
            border: none;
            border-radius: 16px;
            padding: 16px;
            font-weight: 800;
            font-size: 16px;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 20px rgba(37,99,235,0.3);
            letter-spacing: 0.3px;
        }

        .btn-checkout:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(37,99,235,0.4);
        }

        .btn-checkout:active { transform: translateY(0); }

        .input-field {
            width: 100%;
            border: 1.5px solid #e5e7eb;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 14px;
            color: #111827;
            outline: none;
            transition: border-color 0.2s;
            background: white;
        }

        .input-field:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.08); }

        .trust-badge {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: #6b7280;
        }

        .shimmer {
            background: linear-gradient(135deg, #1d4ed8 0%, #4f46e5 50%, #1d4ed8 100%);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: shine 3s linear infinite;
        }
        @keyframes shine { 0%{background-position:0%;} 100%{background-position:200%;} }

        .selected-card {
            border-color: #2563eb !important;
            background: #eff6ff !important;
        }
    </style>
</head>
<body>

    {{-- Hero Header --}}
    <div class="hero-gradient text-white py-10 px-4 text-center relative overflow-hidden">
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 20% 50%, white 1px, transparent 1px), radial-gradient(circle at 80% 20%, white 1px, transparent 1px); background-size: 40px 40px;"></div>
        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 bg-white/15 border border-white/20 rounded-full px-4 py-1.5 text-xs font-semibold mb-4 tracking-wider">
                <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                JARINGAN AKTIF 24/7
            </div>
            <h1 class="text-3xl font-black tracking-wider mb-1">FAHRI.NET</h1>
            <p class="text-blue-200 text-sm">Internet Desa Cepat & Terjangkau</p>

            {{-- Trust badges --}}
            <div class="flex justify-center gap-6 mt-5">
                <div class="trust-badge text-blue-100">
                    <span>🛡️</span> Tanpa Kontrak
                </div>
                <div class="trust-badge text-blue-100">
                    <span>⚡</span> Aktif Instan
                </div>
                <div class="trust-badge text-blue-100">
                    <span>💳</span> Bayar QRIS
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-2xl mx-auto px-4 py-8">

        @if(session('warning'))
        <div class="mb-5 bg-yellow-50 border border-yellow-200 rounded-2xl p-4 text-sm text-yellow-700 flex gap-3">
            <span class="text-lg">⚠️</span>
            <span>{{ session('warning') }}</span>
        </div>
        @endif

        @if($errors->has('error'))
        <div class="mb-5 bg-red-50 border border-red-200 rounded-2xl p-4 text-sm text-red-600 flex gap-3">
            <span class="text-lg">❌</span>
            <span>{{ $errors->first('error') }}</span>
        </div>
        @endif

        {{-- Section title --}}
        <div class="text-center mb-6">
            <h2 class="text-2xl font-black text-gray-900 mb-1">Pilih Paket Internet</h2>
            <p class="text-gray-500 text-sm">Semua paket unlimited tanpa batas kuota</p>
        </div>

        {{-- Package Cards Grid --}}
        <div class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-2">
            @foreach($packages as $index => $package)
            @php
                $isPopular = $index === 1;
                $speedPercent = min(100, (($package->speed_download ?? 1) / 10) * 100);
                $perDay = $package->duration_days > 1 ? round($package->price / $package->duration_days) : null;
                $emoji = $package->duration_days <= 1 ? '🌅' : ($package->duration_days <= 7 ? '📅' : '🗓️');
            @endphp

            <label class="block cursor-pointer">
                <input type="radio" name="selected_package" value="{{ $package->id }}"
                    class="hidden peer"
                    onchange="selectPackage({{ $package->id }}, {{ $package->price }}, '{{ addslashes($package->name) }}')">

                <div class="card-base peer-checked:selected-card {{ $isPopular ? 'card-popular' : '' }} h-full">

                    {{-- Popular badge --}}
                    @if($isPopular)
                    <div class="absolute top-4 right-4">
                        <span class="popular-badge">⭐ TERPOPULER</span>
                    </div>
                    @endif

                    <div class="p-5">
                        {{-- Icon + Name --}}
                        <div class="mb-3">
                            <span class="text-2xl">{{ $emoji }}</span>
                            <h3 class="font-bold text-gray-900 text-base mt-1">{{ $package->name }}</h3>
                            <p class="text-gray-400 text-xs mt-0.5">
                                Internet unlimited tanpa batas waktu
                            </p>
                        </div>

                        {{-- Price --}}
                        <div class="mb-4">
                            <div class="price-tag">
                                Rp {{ number_format($package->price, 0, ',', '.') }}
                            </div>
                            <div class="text-gray-400 text-sm mt-0.5">/{{ $package->duration_days }} hari</div>
                            @if($perDay)
                            <div class="text-green-600 text-xs font-semibold mt-1">
                                ≈ Rp {{ number_format($perDay, 0, ',', '.') }} per hari
                            </div>
                            @endif
                        </div>

                        {{-- CTA Button --}}
                        <button type="button" class="btn-select mb-4">
                            Pilih Paket
                        </button>

                        {{-- Divider --}}
                        <div class="border-t border-gray-100 pt-4 space-y-2">

                            {{-- Duration --}}
                            <div class="feature-item">
                                <div class="feature-check">✓</div>
                                <span>Aktif selama <strong>{{ $package->duration_days }} hari</strong></span>
                            </div>

                            {{-- Speed --}}
                            @if($package->speed_download)
                            <div class="feature-item">
                                <div class="feature-check">✓</div>
                                <span>Kecepatan hingga <strong>{{ $package->speed_download }} Mbps</strong></span>
                            </div>
                            @endif

                            {{-- Upload --}}
                            @if($package->speed_upload)
                            <div class="feature-item">
                                <div class="feature-check">✓</div>
                                <span>Upload <strong>{{ $package->speed_upload }} Mbps</strong></span>
                            </div>
                            @endif

                            {{-- Unlimited --}}
                            <div class="feature-item">
                                <div class="feature-check">✓</div>
                                <span>Kuota <strong>unlimited</strong></span>
                            </div>

                            {{-- All devices --}}
                            <div class="feature-item">
                                <div class="feature-check">✓</div>
                                <span>Semua perangkat</span>
                            </div>

                            {{-- Speed bar --}}
                            @if($package->speed_download)
                            <div class="mt-3">
                                <div class="flex justify-between text-xs text-gray-400 mb-1">
                                    <span>Kecepatan</span>
                                    <span>{{ $package->speed_download }} Mbps</span>
                                </div>
                                <div class="speed-meter">
                                    <div class="speed-fill" style="width: {{ $speedPercent }}%"></div>
                                </div>
                            </div>
                            @endif

                        </div>
                    </div>
                </div>
            </label>
            @endforeach
        </div>

        {{-- Selected summary --}}
        <div id="summary" class="hidden mb-5 bg-blue-50 border-2 border-blue-200 rounded-2xl p-4">
            <div class="flex items-center gap-2 mb-2">
                <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                <span class="font-bold text-blue-800 text-sm">Paket Dipilih</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-700 text-sm" id="summary-name"></span>
                <span class="font-black text-blue-700 text-lg" id="summary-price"></span>
            </div>
        </div>

        {{-- Error --}}
        <div id="error-package" class="hidden mb-4 bg-red-50 border border-red-200 rounded-2xl p-3 text-sm text-red-600 flex gap-2">
            <span>⚠️</span> Silakan pilih paket terlebih dahulu!
        </div>

        {{-- Form --}}
        <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
            <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                <span class="w-7 h-7 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-black">2</span>
                Isi Data Diri
            </h3>

            <form action="{{ route('portal.buy') }}" method="POST" id="form-beli" onsubmit="return validateForm()">
                @csrf
                <input type="hidden" name="package_id" id="selected_package_id">

                <div class="space-y-3">
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide block mb-1.5">Nama Lengkap</label>
                        <input type="text" name="name" required
                            class="input-field" placeholder="Masukkan nama lengkap">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide block mb-1.5">No WhatsApp</label>
                        <input type="text" name="phone" required
                            class="input-field" placeholder="08xxxxxxxxxx">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide block mb-1.5">
                            Email <span class="text-gray-300 normal-case font-normal">(opsional, untuk kirim bukti)</span>
                        </label>
                        <input type="email" name="email"
                            class="input-field" placeholder="email@gmail.com">
                    </div>
                </div>

                <button type="submit" class="btn-checkout mt-5">
                    💳 Lanjut ke Pembayaran →
                </button>

                <p class="text-center text-gray-400 text-xs mt-3">
                    🔒 Pembayaran aman via Midtrans · QRIS · Transfer · E-Wallet
                </p>
            </form>
        </div>

        {{-- Footer --}}
        <div class="text-center mt-6 space-y-1">
            <p class="text-gray-400 text-xs">© 2026 Fahri.Net · Internet Desa</p>
            <p class="text-gray-300 text-xs">Butuh bantuan? Hubungi admin via WhatsApp</p>
        </div>

    </div>

    <script>
        function selectPackage(id, price, name) {
            document.getElementById('selected_package_id').value = id;
            document.getElementById('summary').classList.remove('hidden');
            document.getElementById('summary-name').textContent = name;
            document.getElementById('summary-price').textContent = 'Rp ' + price.toLocaleString('id-ID');
            document.getElementById('error-package').classList.add('hidden');

            // Scroll ke form
            setTimeout(function(){
                document.getElementById('form-beli').scrollIntoView({behavior:'smooth', block:'start'});
            }, 200);
        }

        function validateForm() {
            if (!document.getElementById('selected_package_id').value) {
                var err = document.getElementById('error-package');
                err.classList.remove('hidden');
                err.scrollIntoView({behavior:'smooth'});
                return false;
            }
            return true;
        }
    </script>

</body>
</html>