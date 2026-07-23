<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Internet - Fahri.Net</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: #050510; }
        .shimmer {
            background: linear-gradient(135deg, #63b3ed 0%, #fff 50%, #63b3ed 100%);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: shine 3s linear infinite;
        }
        @keyframes shine { 0%{background-position:0%;} 100%{background-position:200%;} }
        .star { position:fixed; background:white; border-radius:50%; animation:twinkle ease-in-out infinite; }
        @keyframes twinkle { 0%,100%{opacity:0.1;} 50%{opacity:0.8;} }
        .pulse-ring {
            animation: pulse-ring 2s ease-out infinite;
        }
        @keyframes pulse-ring {
            0% { transform: scale(0.8); opacity: 1; }
            100% { transform: scale(1.6); opacity: 0; }
        }
    </style>
</head>
<body class="min-h-screen text-white">

    <div id="stars"></div>
    <div class="fixed inset-0" style="background: radial-gradient(ellipse at 15% 20%, rgba(20,40,120,0.35) 0%, transparent 55%), radial-gradient(ellipse at 85% 80%, rgba(10,20,80,0.3) 0%, transparent 50%), linear-gradient(180deg, #050510 0%, #080820 50%, #050510 100%);"></div>

    <div class="relative z-10 max-w-sm mx-auto px-4 py-8 min-h-screen flex flex-col">

        {{-- Header --}}
        <div class="text-center mb-8">
            <p class="text-xs tracking-widest text-blue-400 uppercase mb-1">Internet</p>
            <h1 class="text-3xl font-black tracking-widest shimmer">FAHRI.NET</h1>
        </div>

        {{-- Status Card --}}
        <div class="flex-1 flex flex-col gap-4">

            {{-- Connected Status --}}
            <div class="relative bg-white/5 border border-white/10 rounded-3xl p-6 text-center overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-green-500/5 to-transparent"></div>

                {{-- Pulse animation --}}
                <div class="relative inline-flex items-center justify-center mb-4">
                    <div class="pulse-ring absolute w-20 h-20 rounded-full bg-green-500/20"></div>
                    <div class="relative w-16 h-16 bg-green-500/20 border-2 border-green-500/50 rounded-full flex items-center justify-center">
                        <span class="text-2xl">✅</span>
                    </div>
                </div>

                <h2 class="text-xl font-black text-white mb-1">Internet Aktif</h2>
                <p class="text-white/40 text-sm">
                    @if($customer && $customer->package)
                        Paket {{ $customer->package->name }}
                    @elseif(str_contains($loginBy ?? '', 'trial'))
                        Mode Trial
                    @else
                        Terhubung
                    @endif
                </p>
            </div>

            {{-- Info Grid --}}
            <div class="grid grid-cols-2 gap-3">

                {{-- Uptime --}}
                <div class="bg-white/5 border border-white/10 rounded-2xl p-4 text-center">
                    <div class="text-blue-400 text-2xl mb-1">⏱</div>
                    <div class="text-white font-bold text-sm" id="uptime-display">{{ $uptime ?? '0s' }}</div>
                    <div class="text-white/30 text-xs mt-0.5">Waktu Online</div>
                </div>

                {{-- Session Time Left --}}
                <div class="bg-white/5 border border-white/10 rounded-2xl p-4 text-center">
                    <div class="text-orange-400 text-2xl mb-1">⏳</div>
                    <div class="text-white font-bold text-sm">
                        @if($customer && $customer->expired_at)
                            {{ $customer->expired_at->diffForHumans() }}
                        @elseif($sessionTimeLeft)
                            {{ $sessionTimeLeft }}
                        @else
                            -
                        @endif
                    </div>
                    <div class="text-white/30 text-xs mt-0.5">Sisa Waktu</div>
                </div>

            </div>

            {{-- Customer Info --}}
            @if($customer)
            <div class="bg-white/5 border border-white/10 rounded-2xl p-4 space-y-3">
                <h3 class="text-xs font-bold text-white/40 uppercase tracking-wider">Info Pelanggan</h3>
                <div class="flex justify-between text-sm">
                    <span class="text-white/50">Nama</span>
                    <span class="font-semibold">{{ $customer->name }}</span>
                </div>
                @if($customer->package)
                <div class="flex justify-between text-sm">
                    <span class="text-white/50">Paket</span>
                    <span class="font-semibold text-blue-300">{{ $customer->package->name }}</span>
                </div>
                @if($customer->package->speed_download)
                <div class="flex justify-between text-sm">
                    <span class="text-white/50">Kecepatan</span>
                    <span class="font-semibold text-green-300">{{ $customer->package->speed_download }} Mbps</span>
                </div>
                @endif
                @endif
                @if($customer->expired_at)
                <div class="flex justify-between text-sm">
                    <span class="text-white/50">Expired</span>
                    <span class="font-semibold {{ $customer->expired_at->isPast() ? 'text-red-400' : 'text-white' }}">
                        {{ $customer->expired_at->format('d M Y H:i') }}
                    </span>
                </div>
                @endif
            </div>
            @endif

            {{-- Trial info --}}
            @if(!$customer || !$customer->package)
            <div class="bg-orange-500/10 border border-orange-500/20 rounded-2xl p-4">
                <div class="flex gap-3">
                    <span class="text-xl">⚡</span>
                    <div>
                        <p class="text-orange-300 font-bold text-sm">Mode Trial Aktif</p>
                        <p class="text-white/40 text-xs mt-0.5">Beli paket untuk kecepatan penuh tanpa batas waktu</p>
                    </div>
                </div>
            </div>
            @endif

            {{-- Action Buttons --}}
            <div class="space-y-3">
                @if(!$customer || !$customer->package || ($customer->expired_at && $customer->expired_at->isPast()))
                <a href="{{ route('portal.index') }}"
                    class="block w-full py-4 rounded-2xl font-bold text-center text-white text-sm"
                    style="background:linear-gradient(135deg,#2563eb,#4f46e5);box-shadow:0 4px 20px rgba(37,99,235,0.3)">
                    🛒 Beli Paket Internet →
                </a>
                @else
                <a href="{{ route('portal.index') }}"
                    class="block w-full py-4 rounded-2xl font-bold text-center text-sm bg-white/5 border border-white/10 text-white/70">
                    🔄 Perpanjang Paket
                </a>
                @endif

                {{-- Logout --}}
                <form action="http://192.168.88.1/logout" method="get" id="logoutForm" style="display:none"></form>
<button onclick="confirmLogout()"
    class="block w-full py-3 rounded-2xl font-bold text-center text-sm bg-red-500/10 border border-red-500/20 text-red-400">
    🔌 Putuskan Koneksi
</button>

<script>
function confirmLogout() {
    if(confirm('Yakin ingin memutuskan koneksi internet?')) {
        document.getElementById('logoutForm').submit();
    }
}
</script>
            </div>

        </div>

        <p class="text-center text-white/10 text-xs tracking-widest uppercase mt-6">
            © 2026 Fahri.Net · Internet Desa
        </p>

    </div>

    <script>
        // Stars
        (function(){
            for(var i=0;i<60;i++){
                var s=document.createElement('div');
                s.className='star';
                var sz=Math.random()*2+0.5;
                s.style.cssText='width:'+sz+'px;height:'+sz+'px;left:'+(Math.random()*100)+'%;top:'+(Math.random()*100)+'%;animation-duration:'+(2+Math.random()*5)+'s;animation-delay:'+(Math.random()*5)+'s;z-index:1';
                document.getElementById('stars').appendChild(s);
            }
        })();
    </script>

</body>
</html>