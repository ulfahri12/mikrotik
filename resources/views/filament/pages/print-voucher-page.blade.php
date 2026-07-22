<x-filament-panels::page>
    <div class="flex gap-3 mb-6">
        <x-filament::button onclick="window.print()" icon="heroicon-o-printer">
            Print Voucher
        </x-filament::button>
        <x-filament::button color="gray" tag="a" href="{{ url()->previous() }}" icon="heroicon-o-arrow-left">
            Kembali
        </x-filament::button>
        <span class="text-sm text-gray-500 self-center">
            Total: {{ $vouchers->count() }} voucher
        </span>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 print:grid-cols-3">
        @foreach ($vouchers as $voucher)
            <div
                class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 overflow-hidden">
                {{-- Header --}}
                <div class="bg-gradient-to-r from-primary-500 to-primary-600 p-4 text-center text-white">
                    <div class="font-bold text-base">📡 RT/RW NET</div>
                    <div class="text-xs opacity-90 mt-1">{{ $voucher->package->name }}</div>
                </div>

                {{-- Body --}}
                <div class="p-4 text-center space-y-3">
                    {{-- Badge --}}
                    <span
                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                    {{ $voucher->is_used ? 'bg-danger-100 text-danger-700' : 'bg-success-100 text-success-700' }}">
                        {{ $voucher->is_used ? 'Sudah Dipakai' : 'Aktif' }}
                    </span>

                    {{-- QR Code --}}
                    <div class="flex justify-center">
                        {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(120)->generate(
                            'http://192.168.88.1/login?username=' . $voucher->code . '&password=' . $voucher->code,
                        ) !!}
                    </div>

                    {{-- Kode --}}
                    <div
                        class="font-mono text-xl font-bold tracking-widest bg-gray-100 dark:bg-gray-800 rounded-lg py-2 px-3">
                        {{ $voucher->code }}
                    </div>

                    {{-- Info --}}
                    <div class="text-xs text-gray-500 space-y-1">
                        <div>⏱ {{ $voucher->package->duration_days }} Hari</div>
                        @if ($voucher->package->speed_download)
                            <div>🚀 {{ $voucher->package->speed_download }} Mbps</div>
                        @endif
                        @if ($voucher->expired_at)
                            <div>📅 Berlaku hingga {{ $voucher->expired_at->format('d/m/Y') }}</div>
                        @endif
                    </div>
                </div>

                {{-- Footer --}}
                <div
                    class="border-t border-dashed border-gray-200 dark:border-gray-700 p-3 text-center text-xs text-gray-400">
                    wifi.ulfahri.online
                </div>
            </div>
        @endforeach
    </div>

    <style>
        @media print {

            nav,
            header,
            .fi-topbar,
            .fi-sidebar,
            .fi-breadcrumbs {
                display: none !important;
            }

            .fi-main {
                padding: 0 !important;
                margin: 0 !important;
            }

            button {
                display: none !important;
            }
        }
    </style>
</x-filament-panels::page>
