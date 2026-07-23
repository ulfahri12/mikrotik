<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout - Fahri.Net</title>
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
        .wave-ring {
            position: absolute;
            border-radius: 50%;
            border: 2px solid rgba(239,68,68,0.3);
            animation: wave 2s ease-out infinite;
        }
        .wave-ring:nth-child(2) { animation-delay: 0.5s; }
        .wave-ring:nth-child(3) { animation-delay: 1s; }
        @keyframes wave {
            0% { width: 60px; height: 60px; opacity: 0.8; top: 50%; left: 50%; transform: translate(-50%,-50%); }
            100% { width: 140px; height: 140px; opacity: 0; top: 50%; left: 50%; transform: translate(-50%,-50%); }
        }
        .btn-primary {
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            box-shadow: 0 4px 20px rgba(37,99,235,0.4);
            transition: all 0.3s;
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 25px rgba(37,99,235,0.5); }
        .btn-secondary {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            transition: all 0.3s;
        }
        .btn-secondary:hover { background: rgba(255,255,255,0.1); }
    </style>
</head>
<body class="min-h-screen text-white">

    <div id="stars"></div>
    <div class="fixed inset-0" style="background: radial-gradient(ellipse at 15% 20%, rgba(120,20,20,0.2) 0%, transparent 55%), radial-gradient(ellipse at 85% 80%, rgba(20,20,80,0.2) 0%, transparent 50%), linear-gradient(180deg, #050510 0%, #080820 50%, #050510 100%);"></div>

    <div class="relative z-10 max-w-sm mx-auto px-4 min-h-screen flex flex-col items-center justify-center py-8">

        {{-- Brand --}}
        <div class="text-center mb-8">
            <p class="text-xs tracking-widest text-blue-400 uppercase mb-1">Internet</p>
            <h1 class="text-3xl font-black tracking-widest shimmer">FAHRI.NET</h1>
        </div>

        {{-- Status Card --}}
        <div class="w-full bg-white/5 border border-white/10 rounded-3xl p-8 text-center mb-5 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-red-500/5 to-transparent"></div>

            {{-- Icon dengan wave --}}
            <div class="relative inline-flex items-center justify-center mb-5" style="width:80px;height:80px;">
                <div class="wave-ring"></div>
                <div class="wave-ring"></div>
                <div class="wave-ring"></div>
                <div class="relative w-16 h-16 rounded-full flex items-center justify-center z-10"
                    style="background:rgba(239,68,68,0.15);border:2px solid rgba(239,68,68,0.4)">
                    <span class="text-3xl">📡</span>
                </div>
            </div>

            <h2 class="text-xl font-black text-white mb-2">Koneksi Diputus</h2>
            <p class="text-white/50 text-sm leading-relaxed">
                Kamu telah logout dari jaringan<br>
                <span class="text-white/70 font-semibold">Fahri.Net</span>
            </p>

            {{-- Divider --}}
            <div class="border-t border-white/10 my-5"></div>

            {{-- Info --}}
            <div class="bg-white/5 rounded-2xl p-4 text-left space-y-2">
                <div class="flex items-center gap-2 text-xs text-white/50">
                    <span>ℹ️</span>
                    <span>Untuk terhubung kembali, sambungkan ke WiFi dan buka browser</span>
                </div>
                <div class="flex items-center gap-2 text-xs text-white/50">
                    <span>🛒</span>
                    <span>Beli paket untuk menikmati internet penuh tanpa batas</span>
                </div>
            </div>
        </div>

        {{-- Buttons --}}
        <div class="w-full">
            <p class="text-center text-white/40 text-xs mb-3 leading-relaxed">
                Ingin menyambungkan kembali?<br>
                Kembali ke portal dulu untuk login atau beli paket
            </p>
            <a href="http://192.168.88.1/login"
    class="btn-primary flex items-center justify-center gap-2 w-full py-4 rounded-2xl font-bold text-white text-sm">
    <span>🏠</span>
    <span>Kembali ke Portal</span>
</a>
        </div>

        <p class="text-center text-white/10 text-xs tracking-widest uppercase mt-8">
            © 2026 Fahri.Net · Internet Desa
        </p>

    </div>

    <script>
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